<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends Controller
{
    public function getAllArtikel()
    {
        $artikel = DB::table('artikel')
            ->select('id_buku', 'judul', 'isi_buku', 'gambar', 'kategori')
            ->orderBy('judul', 'desc')
            ->get();

        // Ubah path gambar menjadi full URL
        foreach ($artikel as $item) {
            $item->gambar = url('storage/artikel_image/' . basename($item->gambar));
        }

        if ($artikel->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada data artikel yang ditemukan.'
            ], 404);
        }

        // Format data akhir
        $data = [];
        foreach ($artikel as $item) {
            $data[] = [
                'id_buku' => $item->id_buku,
                'judul' => $item->judul,
                'isi_buku' => $item->isi_buku,
                'gambar' => $item->gambar,
                'kategori' => $item->kategori,
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}