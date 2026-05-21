<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\PengajuanPklController as AdminPengajuanPklController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\TempatPklController;
use App\Http\Controllers\Guru\DashboardController as GuruDashboardController;
use App\Http\Controllers\Guru\JurnalPklController as GuruJurnalPklController;
use App\Http\Controllers\Guru\LaporanPklController as GuruLaporanPklController;
use App\Http\Controllers\Guru\PenilaianPklController;
use App\Http\Controllers\Guru\PengajuanPklController as GuruPengajuanPklController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\JurnalPklController as SiswaJurnalPklController;
use App\Http\Controllers\Siswa\LaporanPklController as SiswaLaporanPklController;
use App\Http\Controllers\Siswa\PengajuanPklController as SiswaPengajuanPklController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/guide', function () {
    return view('guide');
})->name('guide');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('siswa', SiswaController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('tempat-pkl', TempatPklController::class);
        Route::get('pengajuan', [AdminPengajuanPklController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/{pengajuanPkl}', [AdminPengajuanPklController::class, 'show'])->name('pengajuan.show');
        Route::put('pengajuan/{pengajuanPkl}/guru', [AdminPengajuanPklController::class, 'assignGuru'])->name('pengajuan.assign-guru');
        
        // User approvals
        Route::get('users/unapproved', [\App\Http\Controllers\Admin\UserController::class, 'unapproved'])->name('users.unapproved');
        Route::put('users/{user}/approve', [\App\Http\Controllers\Admin\UserController::class, 'approve'])->name('users.approve');
        Route::delete('users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('users.destroy');
    });

// Guru
Route::middleware(['auth', 'role:guru'])
    ->prefix('guru')
    ->name('guru.')
    ->group(function () {
        Route::get('/dashboard', [GuruDashboardController::class, 'index'])->name('dashboard');
        Route::get('pengajuan', [GuruPengajuanPklController::class, 'index'])->name('pengajuan.index');
        Route::get('pengajuan/{pengajuanPkl}', [GuruPengajuanPklController::class, 'show'])->name('pengajuan.show');
        Route::put('pengajuan/{pengajuanPkl}/setujui', [GuruPengajuanPklController::class, 'setujui'])->name('pengajuan.setujui');
        Route::put('pengajuan/{pengajuanPkl}/tolak', [GuruPengajuanPklController::class, 'tolak'])->name('pengajuan.tolak');
        Route::put('pengajuan/{pengajuanPkl}/revisi', [GuruPengajuanPklController::class, 'mintaRevisi'])->name('pengajuan.revisi');
        Route::get('jurnal', [GuruJurnalPklController::class, 'index'])->name('jurnal.index');
        Route::get('jurnal/{jurnalPkl}', [GuruJurnalPklController::class, 'show'])->name('jurnal.show');
        Route::put('jurnal/{jurnalPkl}/valid', [GuruJurnalPklController::class, 'valid'])->name('jurnal.valid');
        Route::put('jurnal/{jurnalPkl}/revisi', [GuruJurnalPklController::class, 'mintaRevisi'])->name('jurnal.revisi');
        Route::get('laporan', [GuruLaporanPklController::class, 'index'])->name('laporan.index');
        Route::get('laporan/{laporanPkl}', [GuruLaporanPklController::class, 'show'])->name('laporan.show');
        Route::put('laporan/{laporanPkl}/terima', [GuruLaporanPklController::class, 'terima'])->name('laporan.terima');
        Route::put('laporan/{laporanPkl}/revisi', [GuruLaporanPklController::class, 'mintaRevisi'])->name('laporan.revisi');
        Route::get('penilaian', [PenilaianPklController::class, 'index'])->name('penilaian.index');
        Route::get('penilaian/{pengajuanPkl}/create', [PenilaianPklController::class, 'create'])->name('penilaian.create');
        Route::post('penilaian/{pengajuanPkl}', [PenilaianPklController::class, 'store'])->name('penilaian.store');
    });

// Siswa
Route::middleware(['auth', 'role:siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
        Route::resource('pengajuan', SiswaPengajuanPklController::class);
        Route::put('pengajuan/{pengajuan}/ajukan', [SiswaPengajuanPklController::class, 'ajukan'])->name('pengajuan.ajukan');
        Route::get('jurnal', [SiswaJurnalPklController::class, 'index'])->name('jurnal.index');
        Route::get('jurnal/create', [SiswaJurnalPklController::class, 'create'])->name('jurnal.create');
        Route::post('jurnal', [SiswaJurnalPklController::class, 'store'])->name('jurnal.store');
        Route::get('jurnal/{jurnalPkl}/edit', [SiswaJurnalPklController::class, 'edit'])->name('jurnal.edit');
        Route::put('jurnal/{jurnalPkl}', [SiswaJurnalPklController::class, 'update'])->name('jurnal.update');
        Route::get('laporan', [SiswaLaporanPklController::class, 'index'])->name('laporan.index');
        Route::get('laporan/create', [SiswaLaporanPklController::class, 'create'])->name('laporan.create');
        Route::post('laporan', [SiswaLaporanPklController::class, 'store'])->name('laporan.store');
        Route::get('pengajuan/{pengajuan}/sertifikat', [SiswaPengajuanPklController::class, 'cetakSertifikat'])->name('pengajuan.sertifikat');
    });

require __DIR__.'/auth.php';
