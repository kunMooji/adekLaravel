<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class KaloriController extends Controller
{
    public function getDetailKalori(Request $request)
    {
        // Mengambil id_user dari parameter query
        $id_user = $request->query('id_user');

        // Validasi id_user
        if (!$id_user) {
            return response()->json([
                "success" => false,
                "message" => "ID User tidak ditemukan"
            ], 400);
        }

        try {
            // Mengambil data dari database
            $data = DB::table('detail_kalori as dk')
                ->join('menu as m', 'dk.id_menu', '=', 'm.id_menu')
                ->select('m.nama_menu', 'dk.jumlah', 'dk.total_kalori')
                ->where('dk.id_user', $id_user)
                ->get();

            // Mengubah data menjadi array
            $result = $data->map(function ($item) {
                return [
                    "nama_menu" => $item->nama_menu,
                    "jumlah" => (int)$item->jumlah,
                    "total_kalori" => (int)$item->total_kalori
                ];
            });

            return response()->json([
                "success" => true,
                "data" => $result
            ]);
        } catch (QueryException $e) {
            return response()->json([
                "success" => false,
                "message" => "Server error: " . $e->getMessage()
            ], 500);
        }
    }
}
