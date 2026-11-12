<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Landing Page (home)
Route::get('/', function () {
    return view('landing');
})->name('home');

// Guest routes (unauthenticated users only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    // Aktivasi akun siswa baru (pertama kali login)
    Route::get('/activate', [LoginController::class, 'showActivateForm'])->name('activate');
    Route::post('/activate/verify', [LoginController::class, 'verifyForActivation'])->name('activate.verify');
    Route::post('/activate/complete', [LoginController::class, 'completeActivation'])->name('activate.complete');
    Route::post('/activate/cancel', [LoginController::class, 'cancelActivation'])->name('activate.cancel');

    // Registrasi publik dinonaktifkan (hanya admin yang dapat mendaftarkan siswa)
    Route::get('/register', [LoginController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [LoginController::class, 'register']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard redirect based on role
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('member.dashboard');
    })->name('dashboard');

    // Load Member Routes
    require __DIR__.'/member.php';
});

// Admin Routes (terpisah — middleware diatur di dalam admin.php)
require __DIR__.'/admin.php';

// Route bantuan untuk menjalankan migrasi & seed otomatis dari browser di Vercel
Route::get('/deploy-migrate-init', function () {
    try {
        // Jalankan migrasi fresh (bersihkan dan buat ulang sesuai struktur Laravel persis)
        Artisan::call('migrate:fresh', [
            '--force' => true,
            '--seed' => true,
        ]);
        $output = Artisan::output();

        return response()->json([
            'status' => 'success',
            'message' => 'Database Supabase berhasil di-sinkronkan 100% otomatis!',
            'details' => $output,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Fallback route untuk file storage jika symlink Windows tidak aktif / belum di-link
Route::get('/storage/{path}', function (string $path) {
    if (Storage::disk('public')->exists($path)) {
        return Storage::disk('public')->response($path);
    }
    abort(404);
})->where('path', '.*');