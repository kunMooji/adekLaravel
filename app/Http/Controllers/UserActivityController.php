<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class UserActivityController extends Controller
{
    public function updateActivity(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_user' => 'required|string|exists:data_pengguna,id_user',
            'aktifitas' => 'required|string',
            'adjusted_calories' => 'required|numeric',
        ]);

        try {
            // Ambil pengguna
            $pengguna = Pengguna::findOrFail($request->id_user);

            // Update data aktivitas
            $pengguna->aktifitas = $request->aktifitas;

            // Jika Anda ingin menyimpan adjusted_calories, pastikan kolomnya ada di DB
            // $pengguna->adjusted_calories = $request->adjusted_calories;

            $pengguna->save();

            return response()->json([
                'success' => true,
                'message' => 'Aktivitas berhasil diperbarui.',
                'data' => [
                    'id_user' => $pengguna->id_user,
                    'aktifitas' => $pengguna->aktifitas,
                    // 'adjusted_calories' => $pengguna->adjusted_calories,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui aktivitas.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
