<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;
use Illuminate\Support\Facades\Log;

class GetPenggunaData extends Controller
{
    public function getPengguna(Request $request)
    {
        $response = ['success' => false, 'message' => 'Unknown error'];

        if (!$request->isMethod('post')) {
            return response()->json(['success' => false, 'message' => 'Only POST method is allowed'], 405);
        }

        $request->validate([
            'id_user' => 'required|string'
        ]);

        try {
            $id_user = $request->input('id_user');
            $pengguna = Pengguna::select(
                'id_user',
                'nama_lengkap', 
                'email', 
                'berat_badan', 
                'tinggi_badan', 
                'tanggal_lahir', 
                'tipe_diet', 
                'gender',
                'gambar', 
                'aktifitas'
            )
            ->where('id_user', $id_user)
            ->first();

            if ($pengguna) {
                // Normalize activity value
                if ($pengguna->aktifitas === '*NULL' || $pengguna->aktifitas === null || empty($pengguna->aktifitas)) {
                    $pengguna->aktifitas = 'Sedang'; // Set default activity level
                } elseif (strpos($pengguna->aktifitas, '*') === 0) {
                    // Remove asterisk if present
                    $pengguna->aktifitas = substr($pengguna->aktifitas, 1);
                }
                
                // Convert image path to URL if image exists
                if ($pengguna->gambar) {
                    $pengguna->gambar = asset($pengguna->gambar);
                }

                $response = [
                    'success' => true,
                    'user_data' => $pengguna
                ];
                
                // Log successful retrieval for debugging
                Log::info('User data retrieved successfully', [
                    'id_user' => $id_user,
                    'activity' => $pengguna->aktifitas
                ]);
            } else {
                $response = ['success' => false, 'message' => 'User not found'];
                Log::warning('User not found', ['id_user' => $id_user]);
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
            Log::error('Error retrieving user data', [
                'id_user' => $request->input('id_user'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }

        return response()->json($response);
    }
}