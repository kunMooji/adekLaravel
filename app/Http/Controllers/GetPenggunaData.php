<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengguna;

class GetPenggunaData extends Controller
{
    public function getPengguna(Request $request)
    {
        // Set response default
        $response = ['success' => false, 'message' => 'Unknown error'];
        
        // Pastikan hanya menerima metode POST
        if (!$request->isMethod('post')) {
            return response()->json(['success' => false, 'message' => 'Only POST method is allowed'], 405);
        }
        
        // Validasi request
        $request->validate([
            'id_user' => 'required|string'
        ]);
        
        try {
            $id_user = $request->input('id_user');
            $pengguna = Pengguna::select('nama_lengkap', 'email', 'berat_badan', 'tinggi_badan', 'tanggal_lahir', 'gambar')
                ->where('id_user', $id_user)
                ->first();
            
            if ($pengguna) {
                $response = [
                    'success' => true,
                    'user_data' => $pengguna
                ];
            } else {
                $response = ['success' => false, 'message' => 'User not found'];
            }
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => 'Server error: ' . $e->getMessage()];
        }
        
        return response()->json($response);
    }
}