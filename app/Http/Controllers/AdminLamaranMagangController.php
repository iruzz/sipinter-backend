<?php

namespace App\Http\Controllers;

use App\Models\LamaranMagang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminLamaranMagangController extends Controller
{
    // Get all lamaran
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status', '');

        $query = LamaranMagang::with(['siswa.user', 'lowongan.perusahaan']);

        // Filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('siswa', function($q) use ($search) {
                    $q->where('nisn', 'like', "%{$search}%")
                      ->orWhereHas('user', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                })
                ->orWhereHas('lowongan', function($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                });
            });
        }

        // Filter status
        if ($status) {
            $query->where('status', $status);
        }

        $lamaran = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $lamaran
        ]);
    }

    // Get lamaran by ID
    public function show($id)
    {
        $lamaran = LamaranMagang::with(['siswa.user', 'lowongan.perusahaan'])->find($id);

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
    }

    // Update lamaran (untuk admin)
    public function update(Request $request, $id)
    {
        $lamaran = LamaranMagang::find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        $rules = [
            'status' => 'sometimes|required|in:pending,diterima,ditolak,interview,proses',
            'catatan_perusahaan' => 'nullable|string',
            'tanggal_interview' => 'nullable|date',
        ];

        $validated = $request->validate($rules);
        $lamaran->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil diupdate',
            'data' => $lamaran->load(['siswa.user', 'lowongan.perusahaan'])
        ]);
    }

    // Delete lamaran
    public function destroy($id)
    {
        $lamaran = LamaranMagang::find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        // Hapus files
        if ($lamaran->surat_lamaran) {
            Storage::disk('public')->delete($lamaran->surat_lamaran);
        }
        if ($lamaran->cv_file) {
            Storage::disk('public')->delete($lamaran->cv_file);
        }
        if ($lamaran->portofolio_file) {
            Storage::disk('public')->delete($lamaran->portofolio_file);
        }

        $lamaran->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lamaran berhasil dihapus'
        ]);
    }

    // Set status interview
    public function setInterview(Request $request, $id)
    {
        $request->validate([
            'tanggal_interview' => 'required|date',
            'catatan_perusahaan' => 'nullable|string'
        ]);

        $lamaran = LamaranMagang::find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        $lamaran->update([
            'status' => 'interview',
            'tanggal_interview' => $request->tanggal_interview,
            'catatan_perusahaan' => $request->catatan_perusahaan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Jadwal interview berhasil diset',
            'data' => $lamaran->load(['siswa.user', 'lowongan.perusahaan'])
        ]);
    }

    // Terima lamaran
    public function terima(Request $request, $id)
    {
        $lamaran = LamaranMagang::find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        $lamaran->update([
            'status' => 'diterima',
            'catatan_perusahaan' => $request->input('catatan_perusahaan', null)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lamaran diterima',
            'data' => $lamaran->load(['siswa.user', 'lowongan.perusahaan'])
        ]);
    }

    // Tolak lamaran
    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_perusahaan' => 'required|string'
        ]);

        $lamaran = LamaranMagang::find($id);

        if (!$lamaran) {
            return response()->json([
                'success' => false,
                'message' => 'Lamaran tidak ditemukan'
            ], 404);
        }

        $lamaran->update([
            'status' => 'ditolak',
            'catatan_perusahaan' => $request->catatan_perusahaan
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lamaran ditolak',
            'data' => $lamaran->load(['siswa.user', 'lowongan.perusahaan'])
        ]);
    }

    // Get statistics
    public function statistics()
    {
        $stats = [
            'total' => LamaranMagang::count(),
            'pending' => LamaranMagang::where('status', 'pending')->count(),
            'interview' => LamaranMagang::where('status', 'interview')->count(),
            'proses' => LamaranMagang::where('status', 'proses')->count(),
            'diterima' => LamaranMagang::where('status', 'diterima')->count(),
            'ditolak' => LamaranMagang::where('status', 'ditolak')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}