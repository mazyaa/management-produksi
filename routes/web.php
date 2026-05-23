<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriNgController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MesinController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Produksi Harian (all authenticated users can access index/show; create/edit/delete scoped by policy)
    Route::resource('produksis', ProduksiController::class);
    Route::patch('/produksis/{produksi}/submit', [ProduksiController::class, 'submit'])->name('produksis.submit');
    Route::patch('/produksis/{produksi}/verify', [ProduksiController::class, 'verify'])->name('produksis.verify');
    Route::patch('/produksis/{produksi}/reject', [ProduksiController::class, 'reject'])->name('produksis.reject');

    // Laporan (leader, assistant_manager, admin)
    Route::get('/laporans', [LaporanController::class, 'index'])
        ->middleware('role:admin,leader,assistant_manager')
        ->name('laporans.index');

    // User Management (admin only)
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');

    // Master Data (admin only)
    Route::prefix('master')->name('master.')->middleware('role:admin')->group(function () {
        Route::resource('shifts', ShiftController::class);
        Route::resource('mesins', MesinController::class);
        Route::resource('parts', PartController::class);
        Route::resource('kategori-ngs', KategoriNgController::class);
    });
});

require __DIR__.'/auth.php';
