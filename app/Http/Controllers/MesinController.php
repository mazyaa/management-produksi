<?php

namespace App\Http\Controllers;

use App\Models\Mesin;
use App\Http\Requests\StoreMesinRequest;
use App\Http\Requests\UpdateMesinRequest;
use Illuminate\Http\Request;

class MesinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Mesin::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_mesin', 'like', "%{$search}%")
                  ->orWhere('nama_mesin', 'like', "%{$search}%")
                  ->orWhere('line', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $limit = $request->get('limit', 10);
        $mesins = $query->orderBy('kode_mesin')->paginate($limit)->withQueryString();
        return view('mesins.index', compact('mesins'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mesins.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMesinRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        Mesin::create($data);

        return redirect()->route('master.mesins.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Mesin baru berhasil didaftarkan.',
            'icon' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mesin $mesin)
    {
        return view('mesins.edit', compact('mesin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMesinRequest $request, Mesin $mesin)
    {
        $data = $request->validated();
        $data['is_active'] = $request->has('is_active');

        $mesin->update($data);

        return redirect()->route('master.mesins.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data mesin berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mesin $mesin)
    {
        if ($mesin->produksis()->exists()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Mesin tidak dapat dihapus karena memiliki data produksi terkait.',
                'icon' => 'error'
            ]);
        }

        $mesin->delete();

        return redirect()->route('master.mesins.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Mesin berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}
