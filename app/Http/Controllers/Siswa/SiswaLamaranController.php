<?php
// app/Http/Controllers/Siswa/SiswaLamaranController.php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\LamaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SiswaLamaranController extends Controller
{
    public function index()
    {
        try {
            $siswaId = auth()->user()->siswaProfile->id;
            
            $lamaran = LamaranMagang::with(['lowongan.perusahaan'])
                        ->where('siswa_id', $siswaId)
                        ->orderBy('created_at', 'desc')
                        ->get();

            return response()->json([
                'success' => true,
                'data' => $lamaran
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data lamaran'
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lowongan_id' => 'required|exists:lowongan,id',
            'surat_lamaran' => 'required|file|mimes:pdf|max:2048',
            'cv_file' => 'required|file|mimes:pdf|max:2048',
            'portofolio_file' => 'nullable|file|mimes:pdf,zip|max:5120',
            'catatan_siswa' => 'nullable|string|max:500',
             'nomor_wa' => 'required|string|max:20', //tambahan
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswaId = auth()->user()->siswaProfile->id;

            // Cek apakah sudah pernah apply
            $existingLamaran = LamaranMagang::where('siswa_id', $siswaId)
                                ->where('lowongan_id', $request->lowongan_id)
                                ->first();

            if ($existingLamaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah melamar lowongan ini sebelumnya'
                ], 400);
            }

            // Upload files
            $suratLamaranPath = $request->file('surat_lamaran')->store('lamaran/surat_lamaran', 'public');
            $cvPath = $request->file('cv_file')->store('lamaran/cv', 'public');
            $portofolioPath = null;

            if ($request->hasFile('portofolio_file')) {
                $portofolioPath = $request->file('portofolio_file')->store('lamaran/portofolio', 'public');
            }

            $lamaran = LamaranMagang::create([
                'siswa_id' => $siswaId,
                'lowongan_id' => $request->lowongan_id,
                'surat_lamaran' => $suratLamaranPath,
                'cv_file' => $cvPath,
                'portofolio_file' => $portofolioPath,
                'catatan_siswa' => $request->catatan_siswa,
                 'nomor_wa' => $request->nomor_wa, // ✅ SIMPAN KE DATABASE
                'tanggal_apply' => now(),
                'status' => 'pending'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil dikirim',
                'data' => $lamaran->load(['lowongan.perusahaan'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim lamaran',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $siswaId = auth()->user()->siswaProfile->id;
            
            $lamaran = LamaranMagang::with(['lowongan.perusahaan'])
                        ->where('siswa_id', $siswaId)
                        ->find($id);

            if (!$lamaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $lamaran
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail lamaran'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $siswaId = auth()->user()->siswaProfile->id;
            
            $lamaran = LamaranMagang::where('siswa_id', $siswaId)
                        ->where('status', 'pending')
                        ->find($id);

            if (!$lamaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lamaran tidak ditemukan atau tidak dapat dihapus'
                ], 404);
            }

            // Delete files
            Storage::disk('public')->delete([
                $lamaran->surat_lamaran,
                $lamaran->cv_file,
                $lamaran->portofolio_file
            ]);

            $lamaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Lamaran berhasil dibatalkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan lamaran'
            ], 500);
        }
    }
}