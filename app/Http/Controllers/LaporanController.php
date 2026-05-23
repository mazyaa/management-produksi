<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Shift;
use App\Models\Mesin;
use App\Models\Part;
use App\Enums\StatusProduksi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Display a listing of reports.
     */
    public function index(Request $request)
    {
        $query = Produksi::with(['shift', 'mesin', 'part', 'operator', 'latestVerifikasi']);

        // Default date range: today
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date) : Carbon::today();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date) : Carbon::today();

        $query->whereBetween('tanggal_produksi', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);

        // Filters
        if ($request->filled('shift_id')) {
            $query->where('shift_id', $request->shift_id);
        }

        if ($request->filled('mesin_id')) {
            $query->where('mesin_id', $request->mesin_id);
        }

        if ($request->filled('part_id')) {
            $query->where('part_id', $request->part_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // By default, exclude 'draft' in reports, showing verified, submitted, rejected, revised
            $query->where('status', '!=', StatusProduksi::DRAFT);
        }

        // Clone query for totals
        $totalTarget = (clone $query)->sum('target_qty');
        $totalGood = (clone $query)->sum('good_qty');
        $totalNg = (clone $query)->sum('total_ng_qty');

        // Execute query
        $produksis = $query->orderBy('tanggal_produksi', 'desc')->get();

        $shifts = Shift::all();
        $mesins = Mesin::all();
        $parts = Part::all();
        $statuses = StatusProduksi::cases();

        // Print mode check
        if ($request->input('print') === 'true') {
            return view('laporans.print', compact(
                'produksis', 'totalTarget', 'totalGood', 'totalNg', 
                'startDate', 'endDate', 'shifts', 'mesins', 'parts'
            ));
        }

        return view('laporans.index', compact(
            'produksis', 'totalTarget', 'totalGood', 'totalNg',
            'startDate', 'endDate', 'shifts', 'mesins', 'parts', 'statuses'
        ));
    }
}
