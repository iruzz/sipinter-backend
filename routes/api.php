<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerusahaanProfileController;
use App\Http\Controllers\AdminLowonganMagangController;
use App\Http\Controllers\SiswaProfileController; // ← Tambah import ini

// === PUBLIC ROUTES ===
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])
    ->middleware('auth:sanctum');

// === PROTECTED ROUTES ===
Route::middleware(['auth:sanctum'])->group(function () { // ← Hapus 'verified' dari sini
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // === ADMIN ===
    Route::middleware('check.role:admin')
        ->prefix('admin')
        ->group(function () {
            Route::get('/stats', [AdminController::class, 'stats']);
            Route::get('/users', [AdminController::class, 'getUsers']);
            Route::get('/users/pending', [AdminController::class, 'pendingUsers']);
            Route::post('/users', [AdminController::class, 'storeUser']);
            Route::put('/users/{id}', [AdminController::class, 'updateUser']);
            Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
            Route::post('/users/verify/{id}', [AdminController::class, 'verifyUser']);
            
            // Siswa Profile Management
            Route::get('/siswa-profiles', [SiswaProfileController::class, 'index']);
            Route::get('/siswa-profiles/{id}', [SiswaProfileController::class, 'showById']);
            Route::put('/siswa-profiles/{id}', [SiswaProfileController::class, 'update']);
            Route::delete('/siswa-profiles/{id}', [SiswaProfileController::class, 'destroy']);
            Route::post('/siswa-profiles/{id}/verify', [SiswaProfileController::class, 'verify']);
            Route::post('/siswa-profiles/{id}/reject', [SiswaProfileController::class, 'reject']);

             Route::get('/perusahaan-profiles', [PerusahaanProfileController::class, 'index']);
            Route::get('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'showById']);
            Route::put('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'update']);
            Route::delete('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'destroy']);
            Route::post('/perusahaan-profiles/{id}/verify', [PerusahaanProfileController::class, 'verify']);
            Route::post('/perusahaan-profiles/{id}/reject', [PerusahaanProfileController::class, 'reject']);
            
             Route::get('/lowongan', [AdminLowonganMagangController::class, 'index']);
            Route::get('/lowongan/statistics', [AdminLowonganMagangController::class, 'statistics']);
            Route::get('/lowongan/{id}', [AdminLowonganMagangController::class, 'show']);
            Route::put('/lowongan/{id}', [AdminLowonganMagangController::class, 'update']);
            Route::delete('/lowongan/{id}', [AdminLowonganMagangController::class, 'destroy']);
            Route::post('/lowongan/{id}/approve', [AdminLowonganMagangController::class, 'approve']);
            Route::post('/lowongan/{id}/reject', [AdminLowonganMagangController::class, 'reject']);
        
        });

    // === SISWA ===
    Route::middleware('check.role:siswa')->prefix('siswa')->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'Dashboard Siswa']));
        
        // Siswa Profile
        Route::get('/profile', [SiswaProfileController::class, 'show']);
        Route::post('/profile', [SiswaProfileController::class, 'store']);
    });

    // === GURU ===
    Route::middleware('check.role:guru')->prefix('guru')->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'Dashboard Guru']));
    });

    // === PERUSAHAAN ===
    Route::middleware('check.role:perusahaan')->prefix('perusahaan')->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'Dashboard Perusahaan']));

           Route::get('/profile', [PerusahaanProfileController::class, 'show']);
        Route::post('/profile', [PerusahaanProfileController::class, 'store']);
    });

    Route::get('/test-lowongan', function() {
    try {
        $lowongan = \App\Models\LowonganMagang::with('perusahaan')->get();
        return response()->json([
            'success' => true,
            'count' => $lowongan->count(),
            'data' => $lowongan
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ], 500);
    }
});
});