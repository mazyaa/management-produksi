<?php

namespace App\Http\Controllers;

use App\Models\Produksi;
use App\Models\Shift;
use App\Models\Mesin;
use App\Enums\Role;
use App\Enums\StatusProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $today = Carbon::today();

        // Base query for today
        $queryToday = Produksi::whereDate('tanggal_produksi', $today);
        // Base query for all
        $queryAll = Produksi::query();

        // Scope data for operator
        if ($user->isOperator()) {
            $queryToday->where('operator_id', $user->id);
            $queryAll->where('operator_id', $user->id);
        }

        // 1. Total Produksi Hari Ini
        $totalGoodToday = (clone $queryToday)->sum('good_qty');
        $totalNgToday = (clone $queryToday)->sum('total_ng_qty');
        $totalTargetToday = (clone $queryToday)->sum('target_qty');

        // 2. Verified vs Submitted vs Rejected (Today)
        $todayVerified = (clone $queryToday)->where('status', StatusProduksi::VERIFIED)->count();
        $todaySubmitted = (clone $queryToday)->where('status', StatusProduksi::SUBMITTED)->count();
        $todayRejected = (clone $queryToday)->where('status', StatusProduksi::REJECTED)->count();
        $todayDraft = (clone $queryToday)->where('status', StatusProduksi::DRAFT)->count();

        // 3. Recent Activity (Latest 5 records)
        $recentActivities = (clone $queryAll)
            ->with(['shift', 'mesin', 'part', 'operator'])
            ->latest()
            ->limit(5)
            ->get();

        // 4. Data for Charts (Produksi per Shift hari ini)
        $shifts = Shift::all();
        $shiftLabels = [];
        $shiftGoodData = [];
        $shiftNgData = [];

        foreach ($shifts as $shift) {
            $shiftLabels[] = $shift->nama_shift;
            $shiftGoodData[] = (clone $queryToday)->where('shift_id', $shift->id)->sum('good_qty');
            $shiftNgData[] = (clone $queryToday)->where('shift_id', $shift->id)->sum('total_ng_qty');
        }

        // 5. Data for Charts (Produksi per Mesin hari ini)
        $mesins = Mesin::active()->get();
        $mesinLabels = [];
        $mesinGoodData = [];

        foreach ($mesins as $mesin) {
            $mesinLabels[] = $mesin->kode_mesin;
            $mesinGoodData[] = (clone $queryToday)->where('mesin_id', $mesin->id)->sum('good_qty');
        }

        // 6. NG by Category (Overall today)
        $ngByCategories = DB::table('detail_ng_produksis')
            ->join('produksis', 'detail_ng_produksis.produksi_id', '=', 'produksis.id')
            ->join('kategori_ngs', 'detail_ng_produksis.kategori_ng_id', '=', 'kategori_ngs.id')
            ->whereDate('produksis.tanggal_produksi', $today);

        if ($user->isOperator()) {
            $ngByCategories->where('produksis.operator_id', $user->id);
        }

        $ngByCategories = $ngByCategories
            ->select('kategori_ngs.nama_ng', DB::raw('SUM(detail_ng_produksis.qty) as total_qty'))
            ->groupBy('kategori_ngs.nama_ng')
            ->orderByDesc('total_qty')
            ->get();

        $ngCategoryLabels = $ngByCategories->pluck('nama_ng')->toArray();
        $ngCategoryData = $ngByCategories->pluck('total_qty')->toArray();

        return view('dashboard', compact(
            'totalGoodToday',
            'totalNgToday',
            'totalTargetToday',
            'todayVerified',
            'todaySubmitted',
            'todayRejected',
            'todayDraft',
            'recentActivities',
            'shiftLabels',
            'shiftGoodData',
            'shiftNgData',
            'mesinLabels',
            'mesinGoodData',
            'ngCategoryLabels',
            'ngCategoryData'
        ));
    }
}
