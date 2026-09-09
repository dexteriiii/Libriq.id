<?php

use App\Http\Controllers\Member\CatalogController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\LoanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
| Tambahkan require ini di routes/web.php:
|   require __DIR__.'/member.php';
|
| Middleware 'role:member' mengasumsikan kamu sudah punya middleware/gate
| yang memverifikasi Auth::user()->role === 'member'. Jika belum, ganti
| dengan Gate::allows() manual di controller, atau pakai package
| spatie/laravel-permission sesuai rekomendasi PRD Bagian 4.3.
*/

Route::middleware(['auth', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');

        Route::get('/loans', [LoanController::class, 'history'])->name('loans.history');
        Route::post('/loans/{book}', [LoanController::class, 'store'])->name('loans.store');
        Route::delete('/loans/{loan}', [LoanController::class, 'cancel'])->name('loans.cancel');
    });
