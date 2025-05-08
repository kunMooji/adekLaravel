<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtikelController extends Controller
{
    public function getArtikel()
    {
        // Ambil semua data dari tabel artikel
        $artikels = DB::table('artikel')
            ->select('id_buku', 'judul', 'kategori', 'isi_buku', 'gambar')
            ->orderBy('judul', 'desc')
            ->get();

        // Jika tidak ada data, kembalikan response 404
        if ($artikels->isEmpty()) {
            return response()->json([
                'message' => 'No articles found'
            ], 404);
        }

        // Base URL gambar (ubah sesuai kebutuhan)
        $base_url = url('storage/image');

        // Transformasi data
        $data = $artikels->map(function ($item) use ($base_url) {
            return [
                'id_buku'       => $item->id_buku,
                'judulArtikel'  => $item->judul,
                'kategori'      => $item->kategori,
                'isi_buku'      => $item->isi_buku,
                'gambar'        => !empty($item->gambar) 
                    ? str_replace('127.0.0.1', '10.0.2.2', $base_url . '/' . trim($item->gambar)) 
                    : null,
            ];
        });

        // Kembalikan response JSON
        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}
