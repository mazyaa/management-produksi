<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Shift::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_shift', 'like', "%{$search}%");
        }

        $limit = $request->get('limit', 10);
        $shifts = $query->orderBy('nama_shift')->paginate($limit)->withQueryString();
        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShiftRequest $request)
    {
        Shift::create($request->validated());

        return redirect()->route('master.shifts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Shift baru berhasil dibuat.',
            'icon' => 'success'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shift $shift)
    {
        return view('shifts.edit', compact('shift'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateShiftRequest $request, Shift $shift)
    {
        $shift->update($request->validated());

        return redirect()->route('master.shifts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Data shift berhasil diperbarui.',
            'icon' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shift $shift)
    {
        if ($shift->produksis()->exists()) {
            return back()->with('swal_msg', [
                'title' => 'Gagal!',
                'text' => 'Shift tidak dapat dihapus karena sudah memiliki data produksi.',
                'icon' => 'error'
            ]);
        }

        $shift->delete();

        return redirect()->route('master.shifts.index')->with('swal_msg', [
            'title' => 'Berhasil!',
            'text' => 'Shift berhasil dihapus.',
            'icon' => 'success'
        ]);
    }
}
