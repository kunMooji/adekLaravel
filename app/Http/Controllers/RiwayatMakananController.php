<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RiwayatMakananController extends Controller
{
    public function getTerakhirDimakan(Request $request)
    {
        $id_user = $request->query('id_user');

        if (!$id_user) {
            return response()->json([
                'success' => false,
                'message' => 'ID User tidak ditemukan',
            ], 400);
        }

        try {
            $data = DB::table('riwayat_makanan as rm')
                ->selectRaw('DATE(rm.tanggal) as tanggal, COUNT(*) as jumlah, COALESCE(SUM(rm.total_kalori), 0) as total_kalori, rm.nama_menu')
                ->where('rm.id_user', $id_user)
                ->groupBy(DB::raw('DATE(rm.tanggal)'), 'rm.nama_menu')
                ->orderByDesc(DB::raw('DATE(rm.tanggal)')) // ✅ fix untuk ONLY_FULL_GROUP_BY
                ->get();


            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
