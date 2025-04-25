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
        $rawData = DB::table('riwayat_makanan as rm')
            ->selectRaw('DATE(rm.tanggal) as tanggal, rm.nama_menu, COUNT(*) as jumlah, COALESCE(SUM(rm.total_kalori), 0) as total_kalori')
            ->where('rm.id_user', $id_user)
            ->groupBy(DB::raw('DATE(rm.tanggal)'), 'rm.nama_menu')
            ->orderByDesc(DB::raw('DATE(rm.tanggal)'))
            ->get();


        $grouped = [];
        foreach ($rawData as $item) {
            $tanggal = $item->tanggal;
            if (!isset($grouped[$tanggal])) {
                $grouped[$tanggal] = [];
            }
            $grouped[$tanggal][] = [
                'nama_menu' => $item->nama_menu,
                'jumlah' => (int) $item->jumlah,
                'total_kalori' => (int) $item->total_kalori,
            ];
        }


        $result = [];
        foreach ($grouped as $tanggal => $makananList) {
            $result[] = [
                'tanggal' => $tanggal,
                'makanan' => $makananList,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Server error: ' . $e->getMessage(),
        ], 500);
    }
}

}
