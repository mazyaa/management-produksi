<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Http\Requests\StorePartRequest;
use App\Http\Requests\UpdatePartRequest;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Part::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_part', 'like', "%{$search}%")
                  ->orWhere('nama_part', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $parts = $query->orderBy('nomor_part')->paginate(10)->withQueryString();
        return view('parts.index', compact('parts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('parts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePartRequest $request)
    {
        Part::create($request->validated());

        return redirect()->route('master.parts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Part baru berhasil ditambahkan.',
            'icon' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Part $part)
    {
        return view('parts.edit', compact('part'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePartRequest $request, Part $part)
    {
        $part->update($request->validated());

        return redirect()->route('master.parts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data part berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Part $part)
    {
        if ($part->produksis()->exists()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Part tidak dapat dihapus karena memiliki data produksi terkait.',
                'icon' => 'error'
            ]);
        }

        $part->delete();

        return redirect()->route('master.parts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Part berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}
