<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('member')->group(function () {
        Route::get('/katalog', [UnitController::class, 'katalog'])->name('katalog');
        Route::post('/pinjam/{unit}', [LoanController::class, 'pinjam'])->name('loans.store');
        Route::get('/pinjaman-saya', [LoanController::class, 'index'])->name('pinjaman.index');
        Route::get('/pinjaman-saya/riwayat', [LoanController::class, 'riwayat'])->name('pinjaman.riwayat');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('admin')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'adminIndex'])->name('dashboard');

            Route::resource('units', UnitController::class)->except(['show'])->names('units');
            Route::resource('categories', CategoryController::class)->except(['show'])->names('categories');
            Route::resource('members', MemberController::class)
                ->except(['show'])
                ->parameters(['members' => 'member'])
                ->names('members');

            Route::get('/returns/riwayat', [ReturnController::class, 'riwayat'])->name('returns.riwayat');
            Route::get('/returns/cetak/{member}', [ReturnController::class, 'cetakLaporan'])->name('returns.print');
            Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
            Route::post('/returns/{peminjaman}/kembalikan', [ReturnController::class, 'kembalikan'])->name('returns.process');
        });
});
