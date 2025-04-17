<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends Controller
{
    public function getArtikel()
    {
        // Ambil semua data dari tabel artikel
        $artikel = DB::table('artikel')
            ->select('id_buku', 'judul', 'kategori', 'isi_buku', 'gambar')
            ->get();

        // Jika tidak ada data, kembalikan response 404
        if ($artikel->isEmpty()) {
            return response()->json([
                'message' => 'No articles found' // Fixed missing closing quote
            ], 404);
        }

        // Ubah path gambar agar bisa diakses melalui URL
        foreach ($artikel as $item) {
            $item->gambar = url('storage/image/' . $item->gambar);
        }

        // Kembalikan response JSON
        return response()->json([
            'data' => $artikel
        ], 200);
    }
}