<?php

namespace App\Http\Controllers;

use App\Models\PenempatanMagang;
use App\Models\LamaranMagang;
use App\Models\GuruProfile;
use Illuminate\Http\Request;

class PenempatanMagangController extends Controller
{
    // Get all penempatan (untuk admin)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = PenempatanMagang::with([
            'siswa.user',
            'perusahaan',
            'guruPembimbing.user',
            'lamaran.lowongan'
        ]);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa.user', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('perusahaan', function($q) use ($search) {
                    $q->where('nama_perusahaan', 'like', "%{$search}%");
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        $penempatan = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Get single penempatan
    public function show($id)
    {
        $penempatan = PenempatanMagang::with([
            'siswa.user',
            'perusahaan',
            'guruPembimbing.user',
            'lamaran.lowongan'
        ])->find($id);

        if (!$penempatan) {
            return response()->json([
                'success' => false,
                'message' => 'Penempatan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Get lamaran yang sudah diterima tapi belum ditempatkan
    public function lamaranDiterima(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $lamaran = LamaranMagang::with([
            'siswa.user',
            'lowongan.perusahaan'
        ])
        ->where('status', 'diterima')
        ->doesntHave('penempatan') // Belum ada penempatan
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $lamaran
        ]);
    }

    // Get list guru pembimbing
    public function getGuruPembimbing()
    {
        $guru = GuruProfile::with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $guru
        ]);
    }

    // Create penempatan (untuk admin)
    public function store(Request $request)
    {
        $request->validate([
            'lamaran_id' => 'required|exists:lamaran_magang,id',
            'guru_pembimbing_id' => 'required|exists:guru_profiles,id',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
        ]);

        // Ambil data lamaran
        $lamaran = LamaranMagang::with('lowongan')->find($request->lamaran_id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        // Cek apakah sudah ada penempatan
        if ($lamaran->penempatan) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa ini sudah ditempatkan'
            ], 400);
        }

        $penempatan = PenempatanMagang::create([
            'lamaran_id' => $lamaran->id,
            'siswa_id' => $lamaran->siswa_id,
            'perusahaan_id' => $lamaran->lowongan->perusahaan_id,
            'guru_pembimbing_id' => $request->guru_pembimbing_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'aktif',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penempatan magang berhasil dibuat',
            'data' => $penempatan->load([
                'siswa.user',
                'perusahaan',
                'guruPembimbing.user'
            ])
        ], 201);
    }

    // Update penempatan
    public function update(Request $request, $id)
    {
        $penempatan = PenempatanMagang::find($id);

        if (!$penempatan) {
            return response()->json([
                'success' => false,
                'message' => 'Penempatan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'guru_pembimbing_id' => 'sometimes|required|exists:guru_profiles,id',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after:tanggal_mulai',
            'status' => 'sometimes|required|in:aktif,selesai,dibatalkan',
        ]);

        $penempatan->update($request->only([
            'guru_pembimbing_id',
            'tanggal_mulai',
            'tanggal_selesai',
            'status',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Penempatan berhasil diupdate',
            'data' => $penempatan
        ]);
    }

    // Delete penempatan
    public function destroy($id)
    {
        $penempatan = PenempatanMagang::find($id);

        if (!$penempatan) {
            return response()->json([
                'success' => false,
                'message' => 'Penempatan tidak ditemukan'
            ], 404);
        }

        $penempatan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penempatan berhasil dihapus'
        ]);
    }

    // Get penempatan by siswa (untuk siswa yang login)
    public function myPenempatan()
    {
        $user = auth()->user();
        $siswa = \App\Models\SiswaProfile::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Profile siswa tidak ditemukan'
            ], 404);
        }

        $penempatan = PenempatanMagang::with([
            'perusahaan',
            'guruPembimbing.user',
            'lamaran.lowongan'
        ])
        ->where('siswa_id', $siswa->id)
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Get siswa bimbingan (untuk guru yang login)
    public function siswaBimbingan()
    {
        $user = auth()->user();
        $guru = GuruProfile::where('user_id', $user->id)->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Profile guru tidak ditemukan'
            ], 404);
        }

        $penempatan = PenempatanMagang::with([
            'siswa.user',
            'perusahaan',
            'lamaran.lowongan'
        ])
        ->where('guru_pembimbing_id', $guru->id)
        ->where('status', 'aktif')
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }
}