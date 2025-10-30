<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PerusahaanProfileController;
use App\Http\Controllers\AdminLowonganMagangController;
use App\Http\Controllers\AdminLamaranMagangController;
use App\Http\Controllers\PenempatanMagangController;
use App\Http\Controllers\PenilaianMagangController;
use App\Http\Controllers\SiswaProfileController; 
use App\Http\Controllers\Siswa\SiswaLowonganController;
use App\Http\Controllers\Siswa\SiswaLamaranController;
use App\Http\Controllers\Siswa\SiswaMagangController;


// ← Tambah import ini

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
            // Perusahaan
             Route::get('/perusahaan-profiles', [PerusahaanProfileController::class, 'index']);
            Route::get('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'showById']);
            Route::put('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'update']);
            Route::delete('/perusahaan-profiles/{id}', [PerusahaanProfileController::class, 'destroy']);
            Route::post('/perusahaan-profiles/{id}/verify', [PerusahaanProfileController::class, 'verify']);
            Route::post('/perusahaan-profiles/{id}/reject', [PerusahaanProfileController::class, 'reject']);
            // Lowongan
             Route::get('/lowongan', [AdminLowonganMagangController::class, 'index']);
            Route::get('/lowongan/statistics', [AdminLowonganMagangController::class, 'statistics']);
            Route::get('/lowongan/{id}', [AdminLowonganMagangController::class, 'show']);
            Route::put('/lowongan/{id}', [AdminLowonganMagangController::class, 'update']);
            Route::delete('/lowongan/{id}', [AdminLowonganMagangController::class, 'destroy']);
            Route::post('/lowongan/{id}/approve', [AdminLowonganMagangController::class, 'approve']);
            Route::post('/lowongan/{id}/reject', [AdminLowonganMagangController::class, 'reject']);
            // Lamaran
             Route::get('/lamaran', [AdminLamaranMagangController::class, 'index']);
            Route::get('/lamaran/statistics', [AdminLamaranMagangController::class, 'statistics']);
            Route::get('/lamaran/{id}', [AdminLamaranMagangController::class, 'show']);
            Route::put('/lamaran/{id}', [AdminLamaranMagangController::class, 'update']);
            Route::delete('/lamaran/{id}', [AdminLamaranMagangController::class, 'destroy']);
            Route::post('/lamaran/{id}/set-interview', [AdminLamaranMagangController::class, 'setInterview']);
            Route::post('/lamaran/{id}/terima', [AdminLamaranMagangController::class, 'terima']);
            Route::post('/lamaran/{id}/tolak', [AdminLamaranMagangController::class, 'tolak']);
        
            Route::get('/penempatan', [PenempatanMagangController::class, 'index']);
            Route::get('/penempatan/lamaran-diterima', [PenempatanMagangController::class, 'lamaranDiterima']);
            Route::get('/penempatan/guru-pembimbing', [PenempatanMagangController::class, 'getGuruPembimbing']);
            Route::post('/penempatan', [PenempatanMagangController::class, 'store']);
            Route::get('/penempatan/{id}', [PenempatanMagangController::class, 'show']);
            Route::put('/penempatan/{id}', [PenempatanMagangController::class, 'update']);
            Route::delete('/penempatan/{id}', [PenempatanMagangController::class, 'destroy']);

             Route::get('/penilaian', [PenilaianMagangController::class, 'index']);
        Route::get('/penilaian/penempatan/{id}', [PenilaianMagangController::class, 'getByPenempatan']);
        Route::delete('/penilaian/{id}', [PenilaianMagangController::class, 'destroy']);
        });

    // === SISWA ===
    Route::middleware('check.role:siswa')->prefix('siswa')->group(function () {
        Route::get('/dashboard', fn() => response()->json(['message' => 'Dashboard Siswa']));
        
        // Siswa Profile
        Route::get('/profile', [SiswaProfileController::class, 'show']);
        Route::post('/profile', [SiswaProfileController::class, 'store']);

         // ✅ TAMBAHKAN ROUTE INI:
    Route::get('/lowongan', [App\Http\Controllers\Siswa\SiswaLowonganController::class, 'index']);
    Route::get('/lowongan/{id}', [App\Http\Controllers\Siswa\SiswaLowonganController::class, 'show']);
    Route::post('/lamaran', [App\Http\Controllers\Siswa\SiswaLamaranController::class, 'store']);
    Route::get('/lamaran', [App\Http\Controllers\Siswa\SiswaLamaranController::class, 'index']);
    Route::get('/lamaran/{id}', [App\Http\Controllers\Siswa\SiswaLamaranController::class, 'show']);
    Route::delete('/lamaran/{id}', [App\Http\Controllers\Siswa\SiswaLamaranController::class, 'destroy']);
    Route::get('/magang', [App\Http\Controllers\Siswa\SiswaMagangController::class, 'index']);
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