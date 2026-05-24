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

        // Clone query for totals (before pagination)
        $totalRecords = (clone $query)->count();
        $totalTarget = (clone $query)->sum('target_qty');
        $totalGood = (clone $query)->sum('good_qty');
        $totalNg = (clone $query)->sum('total_ng_qty');

        $shifts = Shift::all();
        $mesins = Mesin::all();
        $parts = Part::all();
        $statuses = StatusProduksi::cases();

        // Print mode: get all records, no pagination
        if ($request->input('print') === 'true') {
            $allRecords = (clone $query)->orderBy('tanggal_produksi', 'desc')->get();
            return view('laporans.print', compact(
                'allRecords', 'totalTarget', 'totalGood', 'totalNg', 'totalRecords',
                'startDate', 'endDate', 'shifts', 'mesins', 'parts'
            ));
        }

        // Execute query with pagination
        $limit = $request->get('limit', 20);
        $produksis = $query->orderBy('tanggal_produksi', 'desc')->paginate($limit)->withQueryString();

        return view('laporans.index', compact(
            'produksis', 'totalTarget', 'totalGood', 'totalNg', 'totalRecords',
            'startDate', 'endDate', 'shifts', 'mesins', 'parts', 'statuses'
        ));
    }
}
