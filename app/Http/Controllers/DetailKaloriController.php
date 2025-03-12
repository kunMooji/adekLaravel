<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailKalori;
use Illuminate\Support\Facades\Log;

class DetailKaloriController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
            'id_menu' => 'required',
            'tanggal' => 'required|date',
            'jumlah' => 'required|integer',
            'total_kalori' => 'required|numeric',
            'total_protein' => 'required|numeric',
            'total_karbohidrat' => 'required|numeric',
            'total_lemak' => 'required|numeric',
            'total_gula' => 'required|numeric',
        ]);

        try {
            // Simpan data ke tabel
            $detailKalori = DetailKalori::create($request->all());

            // Log informasi
            Log::info('Data saved successfully', $detailKalori->toArray());

            // Response sukses
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'data' => $detailKalori
            ], 201);
        } catch (\Exception $e) {
            // Log error
            Log::error('Failed to save data: ' . $e->getMessage());

            // Response error
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }
}