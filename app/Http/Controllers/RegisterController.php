<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Pengguna;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // Set header untuk akses API
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        // Handle preflight OPTIONS request
        if ($request->isMethod('options')) {
            return response()->json([], 200);
        }

        // Validasi input
        $validatedData = $request->validate([
            'nama_lengkap' => 'required|string|max:70',
            'email' => 'required|email|unique:data_pengguna,email',
            'password' => 'required|string|min:6',
            'no_hp' => 'required|string|max:15',
            'berat_badan' => 'required|string|max:5',
            'tinggi_badan' => 'required|string|max:4',
            'tanggal_lahir' => 'required|date',
            'tipe_diet' => 'required|in:Menambah berat badan,Mengurangi berat badan,Mempertahankan berat badan,',
            'gender' => 'required|in:Laki-laki,Perempuan',
        ]);

        // Generate ID User
        $id_user = substr(hash('sha256', uniqid(mt_rand(), true)), 0, 5);

        // Simpan ke database
        $user = Pengguna::create([
            'id_user' => $id_user,
            'nama_lengkap' => $validatedData['nama_lengkap'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'no_hp' => $validatedData['no_hp'],
            'berat_badan' => $validatedData['berat_badan'],
            'tinggi_badan' => $validatedData['tinggi_badan'],
            'tanggal_lahir' => $validatedData['tanggal_lahir'],
            'tipe_diet' => $validatedData['tipe_diet'],
            'gender' => $validatedData['gender'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user_id' => $user->id_user
        ], 201);
    }
}
