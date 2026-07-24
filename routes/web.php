<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\FptkApprovalController;

use App\Http\Controllers\Pelamar\DashboardController as PelamarDashboardController;
use App\Http\Controllers\Pelamar\ProfileController;

use App\Http\Controllers\Hod\DashboardController as HodDashboardController;
use App\Http\Controllers\Hod\FptkController;
use App\Http\Controllers\Hrd\ApprovalPelamarController;
use App\Http\Controllers\Hrd\DashboardController as HrdDashboardController;
use App\Http\Controllers\Hrd\KualifikasiController;
use App\Http\Controllers\Hrd\LowonganController;
use App\Http\Controllers\Hrd\HodController;
use App\Http\Controllers\Hrd\DepartmentController;
use App\Http\Controllers\Hrd\PelamarController;
use App\Http\Controllers\LamaranApprovalController;
use App\Http\Controllers\Pelamar\LamaranController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/careers', [CareerController::class, 'index'])->name('career');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {

    Route::middleware('role:pelamar')->prefix('pelamar')->name('pelamar.')->group(function () {
        Route::get('/dashboard', [PelamarDashboardController::class, 'index'])->name('dashboard');
        Route::post('/lamaran/{lowonganId}', [PelamarDashboardController::class, 'lamar'])->name('lamaran.store');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/lamaran', [LamaranController::class, 'index'])->name('lamaran');
        Route::delete('/lamaran/{id}', [LamaranController::class, 'destroy'])->name('lamaran.destroy');
    });

    Route::middleware('role:gm')->prefix('gm')->name('gm.')->group(function () {

        Route::prefix('approval')->name('approval.')->group(function () {
            Route::get('/',               [FptkApprovalController::class, 'indexGm']) ->name('index');
            Route::patch('/{id}/approve', [FptkApprovalController::class, 'approveGm'])->name('approve');
            Route::patch('/{id}/revisi',  [FptkApprovalController::class, 'revisiGm']) ->name('revisi');
            Route::patch('/{id}/tolak',   [FptkApprovalController::class, 'tolakGm'])  ->name('tolak');
        });

    });

    Route::middleware('role:hrd')->prefix('hrd')->name('hrd.')->group(function () {

        Route::get('/dashboard', [HrdDashboardController::class, 'index'])->name('dashboard');

        Route::prefix('department')->name('department.')->group(function () {
            Route::get('/',       [DepartmentController::class, 'index'])->name('index');
            Route::post('/',      [DepartmentController::class, 'store'])->name('store');
            Route::put('/{id}',   [DepartmentController::class, 'update'])->name('update');
            Route::delete('/{id}',[DepartmentController::class, 'destroy'])->name('delete');
        });

        Route::prefix('approval')->name('approval.')->group(function () {
            Route::get('/',              [FptkApprovalController::class, 'indexHrd'])->name('index');
            Route::get('/{id}',          [FptkApprovalController::class, 'showHrd'])->name('show');
            Route::patch('/{id}/approve',[FptkApprovalController::class, 'approveHrd'])->name('approve');
            Route::patch('/{id}/revisi', [FptkApprovalController::class, 'revisiHrd'])->name('revisi');
            Route::patch('/{id}/batalkan',[FptkApprovalController::class, 'batalkanHrd'])->name('batalkan');
        });

        Route::prefix('approval-pelamar')->name('approval-pelamar.')->group(function () {
            Route::get('/',                 [ApprovalPelamarController::class, 'index'])->name('index');
            Route::post('/{id}/setujui',    [ApprovalPelamarController::class, 'setujui'])->name('setujui');
            Route::post('/{id}/tolak',      [ApprovalPelamarController::class, 'tolak'])->name('tolak');
            Route::post('/{id}/interview', [ApprovalPelamarController::class, 'simpanInterview'])->name('interview');
        });

        Route::prefix('lamaran-approval')->name('lamaran-approval.')->group(function () {
            Route::post('/{id}/terima', [LamaranApprovalController::class, 'terima'])->name('terima');
            Route::post('/{id}/tolak',  [LamaranApprovalController::class, 'tolak'])->name('tolak');
        });

        Route::prefix('akun-hod')->name('hod.')->group(function () {
            Route::get('/',         [HodController::class, 'index'])->name('index');
            Route::post('/',        [HodController::class, 'store'])->name('store');
            Route::put('/{hod}',    [HodController::class, 'update'])->name('update');
            Route::delete('/{hod}', [HodController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('akun-pelamar')->name('pelamar.')->group(function () {
            Route::get('/',            [PelamarController::class, 'index'])->name('index');
            Route::put('/{id}',        [PelamarController::class, 'update'])->name('update');
            Route::delete('/{id}',     [PelamarController::class, 'destroy'])->name('destroy');
            Route::patch('/{id}/status',[PelamarController::class, 'updateStatus'])->name('updateStatus');
        });

        Route::prefix('lowongan')->name('lowongan.')->group(function () {
            Route::get('/',        [LowonganController::class, 'index'])->name('index');
            Route::get('/create',  [LowonganController::class, 'create'])->name('create');
            Route::post('/',       [LowonganController::class, 'store'])->name('store');
            Route::put('/{id}',    [LowonganController::class, 'update'])->name('update');
            Route::delete('/{id}', [LowonganController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('kualifikasi')->name('kualifikasi.')->group(function () {
            Route::get('/',        [KualifikasiController::class, 'index'])->name('index');
            Route::post('/',       [KualifikasiController::class, 'store'])->name('store');
            Route::put('/{id}',    [KualifikasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [KualifikasiController::class, 'destroy'])->name('destroy');
        });
    });

    Route::middleware('role:hod')->prefix('hod')->name('hod.')->group(function () {

        Route::get('/dashboard', [HodDashboardController::class,'index',])->name('dashboard');
        
        Route::prefix('kandidat')->name('kandidat.')->controller(HodDashboardController::class)->group(function () {
            Route::post('/{id}/setujui', 'setujui')->whereNumber('id')->name('setujui');
            Route::post('/{id}/tolak', 'tolak')->whereNumber('id')->name('tolak');
            Route::post('/{id}/kirim-hrd', 'kirimKeHrd')->whereNumber('id')->name('kirimHrd');
        });

        Route::prefix('kandidat')->name('kandidat.')->group(function () {
            Route::post('/{id}/setujui',   [HodDashboardController::class, 'setujui'])->name('setujui');
            Route::post('/{id}/tolak',     [HodDashboardController::class, 'tolak'])->name('tolak');
            Route::post('/{id}/kirim-hrd', [HodDashboardController::class, 'kirimKeHrd'])->name('kirimHrd');
        });

        Route::prefix('fptk')->name('fptk.')->group(function () {
            Route::get('/',       [FptkController::class, 'index'])->name('index');
            Route::post('/',      [FptkController::class, 'store'])->name('store');
            Route::put('/{id}',   [FptkController::class, 'update'])->name('update');
            Route::delete('/{id}',[FptkController::class, 'destroy'])->name('destroy');
        });
    });
});