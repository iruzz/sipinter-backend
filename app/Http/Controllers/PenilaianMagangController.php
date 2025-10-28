<?php

namespace App\Http\Controllers;

use App\Models\PenilaianMagang;
use App\Models\PenempatanMagang;
use App\Models\PerusahaanProfile;
use App\Models\GuruProfile;
use Illuminate\Http\Request;

class PenilaianMagangController extends Controller
{
    // Get all penilaian (untuk admin)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $penilaiType = $request->get('penilai_type', '');

        $query = PenilaianMagang::with([
            'penempatan.siswa.user',
            'penempatan.perusahaan',
            'penilai'
        ]);

        if ($search) {
            $query->whereHas('penempatan.siswa.user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($penilaiType) {
            $query->where('penilai_type', $penilaiType);
        }

        $penilaian = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $penilaian
        ]);
    }

    // Get penilaian by penempatan_id
    public function getByPenempatan($penempatanId)
    {
        $penilaian = PenilaianMagang::with('penilai')
            ->where('penempatan_id', $penempatanId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $penilaian
        ]);
    }

    // Get penempatan yang bisa dinilai oleh perusahaan
    public function penempatanForPerusahaan()
    {
        $user = auth()->user();
        $perusahaan = PerusahaanProfile::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Profile perusahaan tidak ditemukan'
            ], 404);
        }

        $penempatan = PenempatanMagang::with('siswa.user', 'lamaran.lowongan')
            ->where('perusahaan_id', $perusahaan->id)
            ->where('status', 'aktif')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Get penempatan yang bisa dinilai oleh guru
    public function penempatanForGuru()
    {
        $user = auth()->user();
        $guru = GuruProfile::where('user_id', $user->id)->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Profile guru tidak ditemukan'
            ], 404);
        }

        $penempatan = PenempatanMagang::with('siswa.user', 'perusahaan', 'lamaran.lowongan')
            ->where('guru_pembimbing_id', $guru->id)
            ->where('status', 'aktif')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Create/Update penilaian oleh Perusahaan
    public function storeByPerusahaan(Request $request)
    {
        $user = auth()->user();
        $perusahaan = PerusahaanProfile::where('user_id', $user->id)->first();

        if (!$perusahaan) {
            return response()->json([
                'success' => false,
                'message' => 'Profile perusahaan tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'penempatan_id' => 'required|exists:penempatan_magang,id',
            'nilai_disiplin' => 'required|integer|min:0|max:100',
            'nilai_kerjasama' => 'required|integer|min:0|max:100',
            'nilai_inisiatif' => 'required|integer|min:0|max:100',
            'nilai_teknis' => 'required|integer|min:0|max:100',
            'nilai_komunikasi' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        // Cek apakah penempatan milik perusahaan ini
        $penempatan = PenempatanMagang::find($request->penempatan_id);
        if ($penempatan->perusahaan_id !== $perusahaan->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menilai penempatan ini'
            ], 403);
        }

        // Cek apakah sudah pernah dinilai
        $existing = PenilaianMagang::where('penempatan_id', $request->penempatan_id)
            ->where('penilai_type', 'perusahaan')
            ->first();

        if ($existing) {
            // Update
            $existing->update([
                'nilai_disiplin' => $request->nilai_disiplin,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_inisiatif' => $request->nilai_inisiatif,
                'nilai_teknis' => $request->nilai_teknis,
                'nilai_komunikasi' => $request->nilai_komunikasi,
                'komentar' => $request->komentar,
            ]);

            $penilaian = $existing;
            $message = 'Penilaian berhasil diupdate';
        } else {
            // Create
            $penilaian = PenilaianMagang::create([
                'penempatan_id' => $request->penempatan_id,
                'penilai_type' => 'perusahaan',
                'penilai_id' => $user->id,
                'nilai_disiplin' => $request->nilai_disiplin,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_inisiatif' => $request->nilai_inisiatif,
                'nilai_teknis' => $request->nilai_teknis,
                'nilai_komunikasi' => $request->nilai_komunikasi,
                'komentar' => $request->komentar,
            ]);

            $message = 'Penilaian berhasil disimpan';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $penilaian
        ], 201);
    }

    // Create/Update penilaian oleh Guru
    public function storeByGuru(Request $request)
    {
        $user = auth()->user();
        $guru = GuruProfile::where('user_id', $user->id)->first();

        if (!$guru) {
            return response()->json([
                'success' => false,
                'message' => 'Profile guru tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'penempatan_id' => 'required|exists:penempatan_magang,id',
            'nilai_disiplin' => 'required|integer|min:0|max:100',
            'nilai_kerjasama' => 'required|integer|min:0|max:100',
            'nilai_inisiatif' => 'required|integer|min:0|max:100',
            'nilai_teknis' => 'required|integer|min:0|max:100',
            'nilai_komunikasi' => 'required|integer|min:0|max:100',
            'komentar' => 'nullable|string',
        ]);

        // Cek apakah penempatan dibimbing oleh guru ini
        $penempatan = PenempatanMagang::find($request->penempatan_id);
        if ($penempatan->guru_pembimbing_id !== $guru->id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk menilai penempatan ini'
            ], 403);
        }

        // Cek apakah sudah pernah dinilai
        $existing = PenilaianMagang::where('penempatan_id', $request->penempatan_id)
            ->where('penilai_type', 'guru')
            ->first();

        if ($existing) {
            // Update
            $existing->update([
                'nilai_disiplin' => $request->nilai_disiplin,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_inisiatif' => $request->nilai_inisiatif,
                'nilai_teknis' => $request->nilai_teknis,
                'nilai_komunikasi' => $request->nilai_komunikasi,
                'komentar' => $request->komentar,
            ]);

            $penilaian = $existing;
            $message = 'Penilaian berhasil diupdate';
        } else {
            // Create
            $penilaian = PenilaianMagang::create([
                'penempatan_id' => $request->penempatan_id,
                'penilai_type' => 'guru',
                'penilai_id' => $user->id,
                'nilai_disiplin' => $request->nilai_disiplin,
                'nilai_kerjasama' => $request->nilai_kerjasama,
                'nilai_inisiatif' => $request->nilai_inisiatif,
                'nilai_teknis' => $request->nilai_teknis,
                'nilai_komunikasi' => $request->nilai_komunikasi,
                'komentar' => $request->komentar,
            ]);

            $message = 'Penilaian berhasil disimpan';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $penilaian
        ], 201);
    }

    // Get penilaian siswa (untuk siswa yang login)
    public function myPenilaian()
    {
        $user = auth()->user();
        $siswa = \App\Models\SiswaProfile::where('user_id', $user->id)->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Profile siswa tidak ditemukan'
            ], 404);
        }

        $penempatan = PenempatanMagang::where('siswa_id', $siswa->id)
            ->with(['penilaianMagang.penilai', 'perusahaan', 'guruPembimbing.user'])
            ->get();

        return response()->json([
            'success' => true,
            'data' => $penempatan
        ]);
    }

    // Delete penilaian (untuk admin)
    public function destroy($id)
    {
        $penilaian = PenilaianMagang::find($id);

        if (!$penilaian) {
            return response()->json([
                'success' => false,
                'message' => 'Penilaian tidak ditemukan'
            ], 404);
        }

        $penilaian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Penilaian berhasil dihapus'
        ]);
    }
}