<?php

use App\Http\Controllers\Member\CatalogController;
use App\Http\Controllers\Member\DashboardController;
use App\Http\Controllers\Member\LoanController;
use App\Http\Controllers\Member\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Member Routes
|--------------------------------------------------------------------------
| Di-require dari routes/web.php.
| Dilindungi middleware 'auth' dan 'role:member'.
*/

Route::middleware(['auth', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
        Route::get('/catalog/{book}', [CatalogController::class, 'show'])->name('catalog.show');

        Route::get('/loans', [LoanController::class, 'history'])->name('loans.history');
        Route::post('/loans/{book}', [LoanController::class, 'store'])->name('loans.store');
        Route::delete('/loans/{loan}', [LoanController::class, 'cancel'])->name('loans.cancel');
        Route::post('/loans/{loan}/return', [LoanController::class, 'returnBook'])->name('loans.return');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    });
