<?php

namespace App\Http\Controllers;

use App\Models\Lowongan;
use App\Models\PerusahaanProfile;
use Illuminate\Http\Request;

class AdminLowonganMagangController extends Controller
{
    // Get all lowongan
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $tipe = $request->get('tipe_lowongan', '');
        $statusApproval = $request->get('status_approval', '');
        $status = $request->get('status', '');

        $query = Lowongan::with('perusahaan.user');

        // Filter pencarian
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhereHas('perusahaan', function($q) use ($search) {
                      $q->where('nama_perusahaan', 'like', "%{$search}%");
                  });
            });
        }

        // Filter tipe lowongan
        if ($tipe) {
            $query->where('tipe_lowongan', $tipe);
        }

        // Filter status approval
        if ($statusApproval) {
            $query->where('status_approval', $statusApproval);
        }

        // Filter status
        if ($status) {
            $query->where('status', $status);
        }

        $lowongan = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $lowongan
        ]);
    }

    // Get lowongan by ID
    public function show($id)
    {
        $lowongan = Lowongan::with('perusahaan.user')->find($id);

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
    }

    // Update lowongan
    public function update(Request $request, $id)
    {
        $lowongan = Lowongan::find($id);

        if (!$lowongan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan'
            ], 404);
        }

        $rules = [
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'persyaratan' => 'sometimes|required|string',
            'jumlah_posisi' => 'sometimes|required|integer|min:1',
            'lokasi' => 'sometimes|required|string|max:255',
            'durasi_magang' => 'nullable|integer|min:1',
            'gaji' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_selesai' => 'sometimes|required|date|after:tanggal_mulai',
            'status' => 'sometimes|required|in:draft,aktif,nonaktif,ditutup',
            'status_approval' => 'sometimes|required|in:pending,approved,rejected',
            'catatan_admin' => 'nullable|string',
        ];

        $validated = $request->validate($rules);
        $lowongan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil diupdate',
            'data' => $lowongan->load('perusahaan.user')
        ]);
    }

    // Delete lowongan
    public function destroy($id)
    {
        $lowongan = Lowongan::find($id);

        if (!$lowongan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan'
            ], 404);
        }

        $lowongan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil dihapus'
        ]);
    }

    // Approve lowongan
    public function approve(Request $request, $id)
    {
        $lowongan = Lowongan::find($id);

        if (!$lowongan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan'
            ], 404);
        }

        $lowongan->update([
            'status_approval' => 'approved',
            'status' => 'aktif',
            'catatan_admin' => $request->input('catatan_admin', null)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan berhasil disetujui',
            'data' => $lowongan->load('perusahaan.user')
        ]);
    }

    // Reject lowongan
    public function reject(Request $request, $id)
    {
        $request->validate([
            'catatan_admin' => 'required|string'
        ]);

        $lowongan = Lowongan::find($id);

        if (!$lowongan) {
            return response()->json([
                'success' => false,
                'message' => 'Lowongan tidak ditemukan'
            ], 404);
        }

        $lowongan->update([
            'status_approval' => 'rejected',
            'status' => 'nonaktif',
            'catatan_admin' => $request->input('catatan_admin')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan ditolak',
            'data' => $lowongan->load('perusahaan.user')
        ]);
    }

    // Get statistics
    public function statistics()
    {
        $stats = [
            'total' => Lowongan::count(),
            'pending' => Lowongan::where('status_approval', 'pending')->count(),
            'approved' => Lowongan::where('status_approval', 'approved')->count(),
            'rejected' => Lowongan::where('status_approval', 'rejected')->count(),
            'aktif' => Lowongan::where('status', 'aktif')->count(),
            'magang' => Lowongan::where('tipe_lowongan', 'magang')->count(),
            'kerja' => Lowongan::where('tipe_lowongan', 'kerja')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}