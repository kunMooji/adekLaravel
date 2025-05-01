<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPengguna;
use Illuminate\Support\Facades\DB;

class GetInfoCalories extends Controller
{
    public function getKalori(Request $request)
    {
        $id_user = $request->query('id_user');

        if (!$id_user) {
            return response()->json([
                'success' => false,
                'message' => 'ID User tidak ditemukan'
            ], 400);
        }

        try {
            $data = DB::table('detail_kalori as dk')
                ->join('data_pengguna as dp', 'dk.id_user', '=', 'dp.id_user')
                ->selectRaw('
                    COALESCE(SUM(dk.total_minum), 0) as total_minum,
                    COALESCE(SUM(dk.total_protein), 0) as total_protein,
                    COALESCE(SUM(dk.total_karbohidrat), 0) as total_karbohidrat,
                    COALESCE(SUM(dk.total_lemak), 0) as total_lemak,
                    COALESCE(SUM(dk.total_gula), 0) as total_gula,
                    dp.tipe_diet,
                    dp.berat_badan,
                    dp.tinggi_badan,
                    dp.tanggal_lahir,
                    dp.gender,
                    dp.aktifitas
                ')
                ->where('dk.id_user', $id_user)
                ->groupBy('dp.tipe_diet', 'dp.berat_badan', 'dp.tinggi_badan', 'dp.tanggal_lahir', 'dp.gender', 'dp.aktifitas')
                ->first();

            if ($data) {
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data ditemukan untuk user ini'
                ]);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
}
