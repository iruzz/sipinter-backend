<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LowonganMagang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminLowonganMagangController extends Controller
{
    /**
     * Display a listing of lowongan magang (dengan pagination, search, filter).
     */
    public function index(Request $request)
    {
        $query = LowonganMagang::with('perusahaan')
            ->when($request->search, function ($q) use ($request) {
                $q->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhereHas('perusahaan', function ($sub) use ($request) {
                      $sub->where('nama_perusahaan', 'like', '%' . $request->search . '%');
                  });
            })
            ->when($request->status_approval, function ($q) use ($request) {
                $q->where('status_approval', $request->status_approval);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            });

        $lowongan = $query->paginate(10)->appends($request->query());

        return response()->json([
            'success' => true,
            'data' => $lowongan,
        ]);
    }

    /**
     * Store a newly created lowongan magang (admin jarang buat, tapi untuk completeness).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'perusahaan_id' => 'required|exists:perusahaan_profiles,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'persyaratan' => 'required|string',
            'jumlah_posisi' => 'required|integer|min:1',
            'lokasi' => 'required|string|max:255',
            'durasi_magang' => 'required|integer|min:1|max:12',
            'gaji' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'status' => ['required', Rule::in(['draft', 'aktif', 'nonaktif', 'ditutup'])],
            'status_approval' => ['required', Rule::in(['pending', 'approved', 'rejected'])],
            'catatan_admin' => 'nullable|string',
        ]);

        $lowongan = LowonganMagang::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan magang berhasil dibuat.',
            'data' => $lowongan->load('perusahaan'),
        ], 201);
    }

    /**
     * Display the specified lowongan magang.
     */
    public function show(LowonganMagang $lowongan)
    {
        $lowongan->load('perusahaan');

        return response()->json([
            'success' => true,
            'data' => $lowongan,
        ]);
    }

    /**
     * Update the specified lowongan magang (untuk update catatan admin atau status).
     */
    public function update(Request $request, LowonganMagang $lowongan)
    {
        $validated = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|required|string',
            'persyaratan' => 'sometimes|required|string',
            'jumlah_posisi' => 'sometimes|required|integer|min:1',
            'lokasi' => 'sometimes|required|string|max:255',
            'durasi_magang' => 'sometimes|required|integer|min:1|max:12',
            'gaji' => 'nullable|numeric|min:0',
            'tanggal_mulai' => 'sometimes|required|date|after_or_equal:today',
            'tanggal_selesai' => 'sometimes|required|date|after:tanggal_mulai',
            'status' => ['sometimes', Rule::in(['draft', 'aktif', 'nonaktif', 'ditutup'])],
            'status_approval' => ['sometimes', Rule::in(['pending', 'approved', 'rejected'])],
            'catatan_admin' => 'nullable|string',
        ]);

        $lowongan->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan magang berhasil diupdate.',
            'data' => $lowongan->load('perusahaan'),
        ]);
    }

    /**
     * Remove the specified lowongan magang.
     */
    public function destroy(LowonganMagang $lowongan)
    {
        $lowongan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lowongan magang berhasil dihapus.',
        ]);
    }

    /**
     * Approve lowongan magang.
     */
    public function approve(Request $request, LowonganMagang $lowongan)
    {
        $validated = $request->validate([
            'catatan_admin' => 'nullable|string',
        ]);

        $lowongan->update([
            'status_approval' => 'approved',
            'catatan_admin' => $validated['catatan_admin'] ?? $lowongan->catatan_admin,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan magang berhasil disetujui.',
            'data' => $lowongan->load('perusahaan'),
        ]);
    }

    /**
     * Reject lowongan magang.
     */
    public function reject(Request $request, LowonganMagang $lowongan)
    {
        $validated = $request->validate([
            'catatan_admin' => 'required|string',
        ]);

        $lowongan->update([
            'status_approval' => 'rejected',
            'catatan_admin' => $validated['catatan_admin'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lowongan magang berhasil ditolak.',
            'data' => $lowongan->load('perusahaan'),
        ]);
    }
}