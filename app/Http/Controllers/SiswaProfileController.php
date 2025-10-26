<?php

namespace App\Http\Controllers;

use App\Models\SiswaProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SiswaProfileController extends Controller
{
    // Get profile siswa yang login
    public function show()
    {
        $user = auth()->user();
        $profile = SiswaProfile::with('user')->where('user_id', $user->id)->first();

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // Get profile siswa by ID (untuk admin)
    public function showById($id)
    {
        $profile = SiswaProfile::with('user')->find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile siswa tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $profile
        ]);
    }

    // Get all siswa profiles (untuk admin)
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $status = $request->get('status_verifikasi', '');

        $query = SiswaProfile::with('user');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nisn', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%")
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

    // Create or Update profile siswa
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cek apakah siswa sudah punya profile
        $profile = SiswaProfile::where('user_id', $user->id)->first();

        $rules = [
            'nisn' => [
                'required',
                'string',
                'max:10',
                Rule::unique('siswa_profiles')->ignore($profile?->id)
            ],
            'nis' => [
                'required',
                'string',
                'max:20',
                Rule::unique('siswa_profiles')->ignore($profile?->id)
            ],
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'alamat' => 'required|string',
            'telepon' => 'required|string|max:15',
            'jurusan' => 'required|string|max:100',
            'kelas' => 'required|string|max:20',
            'tahun_lulus' => 'required|integer|min:2000|max:2100',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv_file' => 'nullable|mimes:pdf|max:5120',
        ];

        $request->validate($rules);

        $data = $request->only([
            'nisn',
            'nis',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'telepon',
            'jurusan',
            'kelas',
            'tahun_lulus',
        ]);

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($profile && $profile->foto_profil) {
                Storage::disk('public')->delete($profile->foto_profil);
            }

            $foto = $request->file('foto_profil');
            $fotoPath = $foto->store('siswa/foto', 'public');
            $data['foto_profil'] = $fotoPath;
        }

        // Upload CV
        if ($request->hasFile('cv_file')) {
            // Hapus CV lama jika ada
            if ($profile && $profile->cv_file) {
                Storage::disk('public')->delete($profile->cv_file);
            }

            $cv = $request->file('cv_file');
            $cvPath = $cv->store('siswa/cv', 'public');
            $data['cv_file'] = $cvPath;
        }

        if ($profile) {
            // Update existing profile
            $profile->update($data);
            $message = 'Profile berhasil diupdate';
        } else {
            // Create new profile
            $data['user_id'] = $user->id;
            $data['status_verifikasi'] = 'pending';
            $profile = SiswaProfile::create($data);
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
        $profile = SiswaProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $rules = [
            'nisn' => [
                'sometimes',
                'required',
                'string',
                'max:10',
                Rule::unique('siswa_profiles')->ignore($id)
            ],
            'nis' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('siswa_profiles')->ignore($id)
            ],
            'tanggal_lahir' => 'sometimes|required|date',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'alamat' => 'sometimes|required|string',
            'telepon' => 'sometimes|required|string|max:15',
            'jurusan' => 'sometimes|required|string|max:100',
            'kelas' => 'sometimes|required|string|max:20',
            'tahun_lulus' => 'sometimes|required|integer|min:2000|max:2100',
            'status_verifikasi' => 'sometimes|required|in:pending,approved,rejected',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'cv_file' => 'nullable|mimes:pdf|max:5120',
        ];

        $request->validate($rules);

        $data = $request->only([
            'nisn',
            'nis',
            'tanggal_lahir',
            'jenis_kelamin',
            'alamat',
            'telepon',
            'jurusan',
            'kelas',
            'tahun_lulus',
            'status_verifikasi',
        ]);

        // Upload foto profil
        if ($request->hasFile('foto_profil')) {
            if ($profile->foto_profil) {
                Storage::disk('public')->delete($profile->foto_profil);
            }
            $fotoPath = $request->file('foto_profil')->store('siswa/foto', 'public');
            $data['foto_profil'] = $fotoPath;
        }

        // Upload CV
        if ($request->hasFile('cv_file')) {
            if ($profile->cv_file) {
                Storage::disk('public')->delete($profile->cv_file);
            }
            $cvPath = $request->file('cv_file')->store('siswa/cv', 'public');
            $data['cv_file'] = $cvPath;
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
        $profile = SiswaProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        // Hapus file
        if ($profile->foto_profil) {
            Storage::disk('public')->delete($profile->foto_profil);
        }
        if ($profile->cv_file) {
            Storage::disk('public')->delete($profile->cv_file);
        }

        $profile->delete();

        return response()->json([
            'success' => true,
            'message' => 'Profile berhasil dihapus'
        ]);
    }

    // Verify profile siswa (untuk admin)
    public function verify($id)
    {
        $profile = SiswaProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $profile->update(['status_verifikasi' => 'approved']);

        return response()->json([
            'success' => true,
            'message' => 'Profile siswa berhasil diverifikasi',
            'data' => $profile
        ]);
    }

    // Reject profile siswa (untuk admin)
    public function reject($id)
    {
        $profile = SiswaProfile::find($id);

        if (!$profile) {
            return response()->json([
                'success' => false,
                'message' => 'Profile tidak ditemukan'
            ], 404);
        }

        $profile->update(['status_verifikasi' => 'rejected']);

        return response()->json([
            'success' => true,
            'message' => 'Profile siswa ditolak',
            'data' => $profile
        ]);
    }
}