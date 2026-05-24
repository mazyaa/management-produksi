<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\DetailNgProduksi;
use App\Models\VerifikasiProduksi;
use App\Models\Shift;
use App\Models\Mesin;
use App\Models\Part;
use App\Models\KategoriNg;
use App\Enums\Role;
use App\Enums\StatusProduksi;
use App\Http\Requests\StoreProduksiRequest;
use App\Http\Requests\UpdateProduksiRequest;
use App\Http\Requests\RejectProduksiRequest;
use App\Http\Requests\SubmitProduksiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProduksiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query with eager loading to prevent N+1 queries
        $query = Produksi::with(['shift', 'mesin', 'part', 'operator', 'latestVerifikasi', 'detailNgProduksis.kategoriNg', 'verifikasiProduksis.verifier']);

        // Scope to operator's own records if role is operator
        if ($user->isOperator()) {
            $query->where('operator_id', $user->id);
        }

        // Apply filters
        if ($request->filled('tanggal_produksi')) {
            $query->whereDate('tanggal_produksi', $request->tanggal_produksi);
        }

        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        if ($request->filled('mesin_id')) {
            $query->where('mesin_id', $request->mesin_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('operator_search') && !$user->isOperator()) {
            $search = $request->operator_search;
            $query->whereHas('operator', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $limit = $request->get('limit', 10);
        $produksis = $query->latest('tanggal_produksi')
            ->latest('created_at')
            ->paginate($limit)
            ->withQueryString();

        $shifts = Shift::all();
        $mesins = Mesin::active()->get();
        $parts = Part::all();
        $kategoriNgs = KategoriNg::all();
        $statuses = StatusProduksi::cases();

        return view('produksis.index', compact('produksis', 'shifts', 'mesins', 'parts', 'kategoriNgs', 'statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Produksi::class);

        $shifts = Shift::all();
        $mesins = Mesin::active()->get();
        $parts = Part::all();
        $kategoriNgs = KategoriNg::all();

        return view('produksis.create', compact('shifts', 'mesins', 'parts', 'kategoriNgs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProduksiRequest $request)
    {
        $this->authorize('create', Produksi::class);

        $data = $request->validated();
        $user = auth()->user();

        // Determine action button
        $isSubmit = $request->input('action') === 'submit';
        
        DB::beginTransaction();
        try {
            // Calculate total NG Qty
            $totalNgQty = 0;
            $ngDetails = [];
            
            if (isset($data['ng']) && is_array($data['ng'])) {
                foreach ($data['ng'] as $ngItem) {
                    if (filled($ngItem['kategori_ng_id'] ?? null) && filled($ngItem['qty'] ?? null)) {
                        $totalNgQty += intval($ngItem['qty']);
                        $ngDetails[] = $ngItem;
                    }
                }
            }

            // Create produksi record
            $produksi = Produksi::create([
                'tanggal_produksi' => $data['tanggal_produksi'],
                'shift_id' => $data['shift_id'],
                'mesin_id' => $data['mesin_id'],
                'part_id' => $data['part_id'],
                'operator_id' => $user->id,
                'target_qty' => $data['target_qty'],
                'good_qty' => $data['good_qty'],
                'total_ng_qty' => $totalNgQty,
                'status' => $isSubmit ? StatusProduksi::SUBMITTED : StatusProduksi::DRAFT,
                'catatan' => $data['catatan'],
            ]);

            // Save NG details
            foreach ($ngDetails as $ngDetail) {
                DetailNgProduksi::create([
                    'produksi_id' => $produksi->id,
                    'kategori_ng_id' => $ngDetail['kategori_ng_id'],
                    'qty' => $ngDetail['qty'],
                    'catatan' => $ngDetail['catatan'] ?? null,
                ]);
            }

            DB::commit();

            $statusText = $isSubmit ? 'disubmit untuk verifikasi.' : 'disimpan sebagai draft.';
            return redirect()->route('produksis.index')->with('swal_msg', [
                'title' => 'Berhasil!',
                'text' => 'Data produksi harian berhasil ' . $statusText,
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Produksi $produksi)
    {
        $this->authorize('update', $produksi);

        $shifts = Shift::all();
        $mesins = Mesin::active()->get();
        // Include currently used inactive machines in edit dropdown just in case
        if (!$produksi->mesin->is_active) {
            $mesins->push($produksi->mesin);
        }
        
        $parts = Part::all();
        $kategoriNgs = KategoriNg::all();
        
        // Eager load details
        $produksi->load('detailNgProduksis');

        return view('produksis.edit', compact('produksi', 'shifts', 'mesins', 'parts', 'kategoriNgs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProduksiRequest $request, Produksi $produksi)
    {
        $this->authorize('update', $produksi);

        $data = $request->validated();
        $isSubmit = $request->input('action') === 'submit';

        DB::beginTransaction();
        try {
            // Calculate total NG Qty
            $totalNgQty = 0;
            $ngDetails = [];
            
            if (isset($data['ng']) && is_array($data['ng'])) {
                foreach ($data['ng'] as $ngItem) {
                    if (filled($ngItem['kategori_ng_id'] ?? null) && filled($ngItem['qty'] ?? null)) {
                        $totalNgQty += intval($ngItem['qty']);
                        $ngDetails[] = $ngItem;
                    }
                }
            }

            // Determine status
            $newStatus = $produksi->status;
            if ($produksi->isDraft() || $produksi->isRevised() || $produksi->isRejected()) {
                $newStatus = $isSubmit ? StatusProduksi::SUBMITTED : ($produksi->isRejected() ? StatusProduksi::REVISED : $produksi->status);
            }

            // Update produksi record
            $produksi->update([
                'tanggal_produksi' => $data['tanggal_produksi'],
                'shift_id' => $data['shift_id'],
                'mesin_id' => $data['mesin_id'],
                'part_id' => $data['part_id'],
                'target_qty' => $data['target_qty'],
                'good_qty' => $data['good_qty'],
                'total_ng_qty' => $totalNgQty,
                'status' => $newStatus,
                'catatan' => $data['catatan'],
            ]);

            // Re-sync NG details: Delete existing and write new
            $produksi->detailNgProduksis()->delete();
            
            foreach ($ngDetails as $ngDetail) {
                DetailNgProduksi::create([
                    'produksi_id' => $produksi->id,
                    'kategori_ng_id' => $ngDetail['kategori_ng_id'],
                    'qty' => $ngDetail['qty'],
                    'catatan' => $ngDetail['catatan'] ?? null,
                ]);
            }

            DB::commit();

            $statusText = $isSubmit ? 'disubmit untuk verifikasi.' : 'diperbarui.';
            return redirect()->route('produksis.index')->with('swal_msg', [
                'title' => 'Berhasil!',
                'text' => 'Data produksi berhasil ' . $statusText,
                'icon' => 'success'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produksi $produksi)
    {
        $this->authorize('delete', $produksi);

        $produksi->delete(); // Cascading delete will handle detail_ng_produksis & verifikasis

        return redirect()->route('produksis.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data produksi berhasil dihapus.',
            'icon' => 'success'
        ]);
    }

    /**
     * Submit a draft/revised produksi record.
     */
    public function submit(SubmitProduksiRequest $request, Produksi $produksi)
    {
        $this->authorize('submit', $produksi);

        $produksi->update(['status' => StatusProduksi::SUBMITTED]);

        return redirect()->route('produksis.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data produksi berhasil disubmit ke Leader untuk verifikasi.',
            'icon' => 'success'
        ]);
    }

    /**
     * Verify a submitted produksi record.
     */
    public function verify(Request $request, Produksi $produksi)
    {
        $this->authorize('verify', $produksi);

        DB::beginTransaction();
        try {
            $produksi->update(['status' => StatusProduksi::VERIFIED]);

            VerifikasiProduksi::create([
                'produksi_id' => $produksi->id,
                'verified_by' => auth()->id(),
                'status' => 'verified',
                'catatan' => $request->input('catatan'),
                'verified_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('produksis.index')->with('swal_msg', [
                'title' => 'Berhasil!',
                'text' => 'Data produksi berhasil diverifikasi.',
                'icon' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Gagal melakukan verifikasi: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }

    /**
     * Reject a submitted produksi record.
     */
    public function reject(RejectProduksiRequest $request, Produksi $produksi)
    {
        $this->authorize('reject', $produksi);

        DB::beginTransaction();
        try {
            $produksi->update(['status' => StatusProduksi::REJECTED]);

            VerifikasiProduksi::create([
                'produksi_id' => $produksi->id,
                'verified_by' => auth()->id(),
                'status' => 'rejected',
                'catatan' => $request->input('catatan_reject'),
                'verified_at' => Carbon::now(),
            ]);

            DB::commit();

            return redirect()->route('produksis.index')->with('swal_msg', [
                'title' => 'Rejection Berhasil!',
                'text' => 'Data produksi ditolak dengan catatan revisi.',
                'icon' => 'warning'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Gagal melakukan rejection: ' . $e->getMessage(),
                'icon' => 'error'
            ]);
        }
    }
}
