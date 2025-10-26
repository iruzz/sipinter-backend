<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);

        // Kirim email verifikasi
        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan cek email untuk verifikasi.',
        ]);
    }

   public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['Email atau password salah.'],
        ]);
    }

    // Cek email verified
    if (!$user->hasVerifiedEmail()) {
        return response()->json([
            'success' => false,
            'message' => 'Email belum diverifikasi. Silakan cek email Anda.'
        ], 403);
    }

    // Cek approval admin (kecuali admin sendiri)
    if ($user->role !== 'admin' && !$user->is_verified) {
        return response()->json([
            'success' => false,
            'message' => 'Akun Anda belum disetujui admin.'
        ], 403);
    }

    // Create token
    $token = $user->createToken('auth-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'message' => 'Login berhasil',
        'data' => [
            'user' => $user,
            'token' => $token,
        ]
    ]);
}

    // Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    // Get current user
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user(),
        ]);
    }

        public function verifyEmail(Request $request, $id, $hash)
    {
        // Ambil user
        $user = User::findOrFail($id);

        // Validasi hash sesuai implementasi Laravel (sha1 of email)
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'success' => false,
                'message' => 'Verification link is invalid or has been tampered with.'
            ], 400);
        }

        // Jika sudah diverifikasi sebelumnya
        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'success' => true,
                'message' => 'Email already verified.'
            ], 200);
        }

        // Tandai sebagai verified dan dispatch event
        $user->markEmailAsVerified();
        event(new Verified($user));

        return response()->json([
            'success' => true,
            'message' => 'Email successfully verified. Please wait for admin approval (if required).'
        ], 200);
    }

    /**
     * (Optional) resend verification for authenticated user
     */
    public function resendVerification(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        if ($user->hasVerifiedEmail()) {
            return response()->json(['success' => false, 'message' => 'Email already verified.'], 400);
        }
        $user->sendEmailVerificationNotification();
        return response()->json(['success' => true, 'message' => 'Verification email resent.']);
    }
}
