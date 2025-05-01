<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST");
        header("Access-Control-Allow-Headers: Content-Type");

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Ambil data dari request
        $email = $request->email;
        $password = $request->password;

        // Cari pengguna berdasarkan email
        $user = Pengguna::where('email', $email)->first();

        if ($user) {
            // Verifikasi password
            if (Hash::check($password, $user->password)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Login Berhasil',
                    'user' => [
                        'id_user' => $user->id_user,
                        'nama_lengkap' => $user->nama_lengkap,
                        'email' => $user->email,
                        'aktifitas' => $user->aktifitas
                    ]
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kesalahan di Email atau Password'
                ], 401);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Kesalahan di Email atau Password'
            ], 401);
        }
    }
}
