<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // 1. Fitur Registrasi (Sesuai KF-01)
    public function register(Request $request)
    {
        // Validasi input data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'in:kandidat,hrd,admin', // Membatasi peran sesuai proposal[cite: 9]
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Membuat user baru dengan password yang dienkripsi (Sesuai KNF-02)[cite: 9]
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Enkripsi password aman
            'role' => $request->role ?? 'kandidat', // Default sebagai kandidat jika tidak diisi
        ]);

        // Generate token akses Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registrasi akun berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }

    // 2. Fitur Login
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Cek kecocokan kredensial
        if (! auth()->attempt($credentials)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email atau password salah',
            ], 401);
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Buat token baru saat login berhasil
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login berhasil',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ]);
    }

    // 3. Fitur Logout (Menghapus token aktif)
    public function logout(Request $request)
    {
        // Menghapus token yang sedang digunakan saat request
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil, token telah dicabut',
        ]);
    }
}
