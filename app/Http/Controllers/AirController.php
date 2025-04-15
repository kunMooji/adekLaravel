<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailKalori; // Pastikan model ini sudah ada

class AirController extends Controller
{
    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'id_user' => 'required|string',
            'tanggal' => 'required|date',
            'total_minum' => 'required|numeric',
        ]);

        // Mencari data detail_kalori berdasarkan id_user dan tanggal
        $detailKalori = DetailKalori::where('id_user', $validatedData['id_user'])
            ->where('tanggal', $validatedData['tanggal'])
            ->first();

        if ($detailKalori) {
            // Memperbarui total_minum
            $detailKalori->total_minum = $validatedData['total_minum'];
            $detailKalori->save(); // Menyimpan perubahan ke database

            return response()->json([
                'success' => true,
                'message' => 'Data Minuman Berhasil Diperbarui',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }
    }

    public function getWaterConsumption(Request $request)
{
    $validated = $request->validate([
        'id_user' => 'required|string',
        'tanggal' => 'required|date',
    ]);

    $data = DetailKalori::where('id_user', $validated['id_user'])
        ->where('tanggal', $validated['tanggal'])
        ->first();

    if ($data) {
        return response()->json([
            'success' => true,
            'total_minum' => $data->total_minum,
        ]);
    } else {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan',
        ], 404);
    }
}
}
