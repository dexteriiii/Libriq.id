<?php

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LoanController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Semua route di sini dilindungi middleware 'auth' dan 'role:admin'.
| Di-require dari routes/web.php.
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ── Dashboard ─────────────────────────────────────────────────────
        Route::get('/dashboard', DashboardController::class)->name('dashboard');

        // ── Profile ───────────────────────────────────────────────────────
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // ── Books ─────────────────────────────────────────────────────────
        Route::prefix('books')->name('books.')->group(function () {
            Route::get('/',              [BookController::class, 'index'])->name('index');
            Route::get('/create',        [BookController::class, 'create'])->name('create');
            Route::post('/fetch-api',    [BookController::class, 'fetchFromApi'])->name('fetch-api');
            Route::post('/',             [BookController::class, 'store'])->name('store');
            Route::get('/{book}/edit',   [BookController::class, 'edit'])->name('edit');
            Route::put('/{book}',        [BookController::class, 'update'])->name('update');
            Route::delete('/{book}',     [BookController::class, 'destroy'])->name('destroy');
            Route::post('/{book}/stock', [BookController::class, 'addStock'])->name('add-stock');
        });

        // ── Loans / Circulation ───────────────────────────────────────────
        Route::prefix('loans')->name('loans.')->group(function () {
            Route::get('/',                    [LoanController::class, 'index'])->name('index');
            Route::get('/pending',             [LoanController::class, 'pending'])->name('pending');
            Route::get('/{loan}',              [LoanController::class, 'show'])->name('show');
            Route::post('/{loan}/approve',     [LoanController::class, 'approve'])->name('approve');
            Route::post('/{loan}/reject',      [LoanController::class, 'reject'])->name('reject');
            Route::post('/{loan}/return',      [LoanController::class, 'processReturn'])->name('return');
            Route::post('/{loan}/pay-fine',    [LoanController::class, 'markFinePaid'])->name('pay-fine');
        });

        // ── Users / Members ───────────────────────────────────────────────
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/',            [UserController::class, 'index'])->name('index');
            Route::get('/create',      [UserController::class, 'create'])->name('create');
            Route::post('/',           [UserController::class, 'store'])->name('store');
            Route::get('/{user}',      [UserController::class, 'show'])->name('show');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::put('/{user}',      [UserController::class, 'update'])->name('update');
            Route::delete('/{user}',   [UserController::class, 'destroy'])->name('destroy');
        });

        // ── Settings ──────────────────────────────────────────────────────
        Route::get('/settings',  [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings',  [SettingController::class, 'update'])->name('settings.update');
    });
