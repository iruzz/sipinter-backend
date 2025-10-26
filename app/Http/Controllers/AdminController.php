<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Dashboard stats
    public function stats()
    {
        $totalUsers = User::count();
        $verifiedCompanies = User::where('role', 'perusahaan')
                                  ->where('is_verified', true)
                                  ->count();
        $registeredStudents = User::where('role', 'siswa')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'totalUsers' => $totalUsers,
                'verifiedCompanies' => $verifiedCompanies,
                'registeredStudents' => $registeredStudents,
            ]
        ]);
    }

    // Get all users with pagination & filter
    public function getUsers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $role = $request->get('role', '');

        $query = User::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // Get pending users (belum verified)
    public function pendingUsers()
    {
        $users = User::where('is_verified', false)
                     ->orderBy('created_at', 'desc')
                     ->get();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    // ✅ Create new user (auto verified if created by admin)
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:admin,guru,perusahaan,siswa',
            'is_verified' => 'boolean',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->role = $validated['role'];
        $user->is_verified = $request->boolean('is_verified', false);

        // ✅ Auto verify email karena dibuat oleh admin
        $user->email_verified_at = now();

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dibuat dan diverifikasi otomatis.',
            'data' => $user
        ], 201);
    }

    // Update user
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes', 'required', 'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'sometimes|nullable|string|min:8',
            'role' => 'sometimes|required|in:siswa,admin,perusahaan,guru',
            'is_verified' => 'sometimes|boolean',
        ]);

        $data = $request->only(['name', 'email', 'role', 'is_verified']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diupdate.',
            'data' => $user
        ]);
    }

    // Delete user
    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        // Prevent self-deletion
        if (auth()->check() && $user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus akun sendiri.'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus.'
        ]);
    }

    // Verify user
    public function verifyUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        }

        $user->update([
            'is_verified' => true,
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diverifikasi.',
            'data' => $user
        ]);
    }
}
