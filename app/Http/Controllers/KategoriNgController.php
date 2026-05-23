<?php

namespace App\Http\Controllers;

use App\Models\KategoriNg;
use App\Enums\Severity;
use App\Http\Requests\StoreKategoriNgRequest;
use App\Http\Requests\UpdateKategoriNgRequest;
use Illuminate\Http\Request;

class KategoriNgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = KategoriNg::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_ng', 'like', "%{$search}%")
                  ->orWhere('nama_ng', 'like', "%{$search}%");
            });
        }

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $kategoriNgs = $query->orderBy('kode_ng')->paginate(10)->withQueryString();
        $severities = Severity::cases();

        return view('kategori-ngs.index', compact('kategoriNgs', 'severities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $severities = Severity::cases();
        return view('kategori-ngs.create', compact('severities'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreKategoriNgRequest $request)
    {
        KategoriNg::create($request->validated());

        return redirect()->route('master.kategori-ngs.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Kategori NG baru berhasil ditambahkan.',
            'icon' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(KategoriNg $kategoriNg)
    {
        $severities = Severity::cases();
        return view('kategori-ngs.edit', compact('kategoriNg', 'severities'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateKategoriNgRequest $request, KategoriNg $kategoriNg)
    {
        $kategoriNg->update($request->validated());

        return redirect()->route('master.kategori-ngs.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data Kategori NG berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(KategoriNg $kategoriNg)
    {
        if ($kategoriNg->detailNgProduksis()->exists()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Kategori NG tidak dapat dihapus karena sudah digunakan dalam pencatatan produksi.',
                'icon' => 'error'
            ]);
        }

        $kategoriNg->delete();

        return redirect()->route('master.kategori-ngs.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Kategori NG berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}
