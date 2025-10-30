<?php
// app/Http/Controllers/Siswa/SiswaLowonganController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Lowongan;
use Illuminate\Http\Request;

class SiswaLowonganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $search = $request->get('search', '');

            $query = Lowongan::with(['perusahaan'])
                ->where('status', 'aktif')  // ← UBAH JADI 'aktif'
                ->where('status_approval', 'approved')
                ->where('tanggal_selesai', '>=', now());

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%")
                      ->orWhere('lokasi', 'like', "%{$search}%")
                      ->orWhereHas('perusahaan', function($q) use ($search) {
                          $q->where('nama_perusahaan', 'like', "%{$search}%");
                      });
                });
            }

            $lowongan = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $lowongan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat lowongan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $lowongan = Lowongan::with(['perusahaan'])
                        ->where('status', 'aktif')  // ← UBAH JADI 'aktif'
                        ->where('status_approval', 'approved')
                        ->find($id);

            if (!$lowongan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lowongan tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $lowongan
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail lowongan'
            ], 500);
        }
    }
}