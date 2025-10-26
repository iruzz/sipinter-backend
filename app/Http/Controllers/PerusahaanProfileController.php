<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PerusahaanProfileController extends Controller
{
    // Get profile perusahaan yang login
    public function show()
    {
        $user = auth()->user();
        $profile = PerusahaanProfile::with('user')->where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile perusahaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // Get profile perusahaan by ID (untuk admin)
    public function showById($id)
    {
        $profile = PerusahaanProfile::with('user')->find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile perusahaan tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // Get all perusahaan profiles (untuk admin)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status_verifikasi', '');

        $query = PerusahaanProfile::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha', 'like', "%{$search}%")
                  ->orWhere('kota', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($status) {
            $query->where('status_verifikasi', $status);
        }

        $profiles = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $profiles
        ]);
    }

    // Create or Update profile perusahaan
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah perusahaan sudah punya profile
        $profile = PerusahaanProfile::where('user_id', $user->id)->first();

        $rules = [
            'nama_perusahaan' => 'required|string|max:255',
            'bidang_usaha' => 'required|string|max:100',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'provinsi' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'website' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pic_name' => 'required|string|max:100',
            'pic_jabatan' => 'required|string|max:100',
            'pic_telepon' => 'required|string|max:15',
            'pic_email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('perusahaan_profiles')->ignore($profile?->id)
            ],
        ];

        $request->validate($rules);

        $data = $request->only([
            'nama_perusahaan',
            'bidang_usaha',
            'alamat',
            'kota',
            'provinsi',
            'telepon',
            'website',
            'deskripsi',
            'pic_name',
            'pic_jabatan',
            'pic_telepon',
            'pic_email',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($profile && $profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }

            $logo = $request->file('logo');
            $logoPath = $logo->store('perusahaan/logo', 'public');
            $data['logo'] = $logoPath;
        }

        if ($profile) {
            // Update existing profile
            $profile->update($data);
            $message = 'Profile berhasil diupdate';
        } else {
            // Create new profile
            $data['user_id'] = $user->id;
            $data['status_verifikasi'] = 'pending';
            $profile = PerusahaanProfile::create($data);
            $message = 'Profile berhasil dibuat';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $profile
        ], $profile->wasRecentlyCreated ? 201 : 200);
    }

    // Update profile (untuk admin)
    public function update(Request $request, $id)
    {
        $profile = PerusahaanProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $rules = [
            'nama_perusahaan' => 'sometimes|required|string|max:255',
            'bidang_usaha' => 'sometimes|required|string|max:100',
            'alamat' => 'sometimes|required|string',
            'kota' => 'sometimes|required|string|max:100',
            'provinsi' => 'sometimes|required|string|max:100',
            'telepon' => 'sometimes|required|string|max:15',
            'website' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pic_name' => 'sometimes|required|string|max:100',
            'pic_jabatan' => 'sometimes|required|string|max:100',
            'pic_telepon' => 'sometimes|required|string|max:15',
            'pic_email' => [
                'sometimes',
                'required',
                'email',
                'max:100',
                Rule::unique('perusahaan_profiles')->ignore($id)
            ],
            'status_verifikasi' => 'sometimes|required|in:pending,approved,rejected',
        ];

        $request->validate($rules);

        $data = $request->only([
            'nama_perusahaan',
            'bidang_usaha',
            'alamat',
            'kota',
            'provinsi',
            'telepon',
            'website',
            'deskripsi',
            'pic_name',
            'pic_jabatan',
            'pic_telepon',
            'pic_email',
            'status_verifikasi',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($profile->logo) {
                Storage::disk('public')->delete($profile->logo);
            }
            $logoPath = $request->file('logo')->store('perusahaan/logo', 'public');
            $data['logo'] = $logoPath;
        }

        $profile->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil diupdate',
            'data' => $profile
        ]);
    }

    // Delete profile (untuk admin)
    public function destroy($id)
    {
        $profile = PerusahaanProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        // Hapus logo
        if ($profile->logo) {
            Storage::disk('public')->delete($profile->logo);
        }

        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil dihapus'
        ]);
    }

    // Verify profile perusahaan (untuk admin)
    public function verify($id)
    {
        $profile = PerusahaanProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $profile->update(['status_verifikasi' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Profile perusahaan berhasil diverifikasi',
            'data' => $profile
        ]);
    }

    // Reject profile perusahaan (untuk admin)
    public function reject($id)
    {
        $profile = PerusahaanProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $profile->update(['status_verifikasi' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Profile perusahaan ditolak',
            'data' => $profile
        ]);
    }
}