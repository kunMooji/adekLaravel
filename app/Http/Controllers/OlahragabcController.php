<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Olahraga;

class OlahragabcController extends Controller
{
    public function getOlahragaByJenis(Request $request)
    {
        $jenis = $request->query('jenis');

        if (empty($jenis)) {
            return response()->json([
                'error' => 'Jenis olahraga parameter is required'
            ], 400);
        }

        $validJenis = ['kardio', 'kekuatan', 'interval'];

        if (!in_array($jenis, $validJenis)) {
            return response()->json([
                'error' => 'Invalid jenis olahraga. Valid values are: kardio, kekuatan, interval'
            ], 400);
        }

        $olahraga = DB::table('olahraga')
            ->select('id_olahraga', 'nama_olahraga', 'deskripsi', 'cara_olahraga', 'gambar', 'kalori')
            ->where('jenis_olahraga', $jenis)
            ->get();

        foreach ($olahraga as $item) {
            $item->gambar = url('storage/image/' . basename($item->gambar));
        }

        if ($olahraga->isEmpty()) {
            return response()->json([
                'message' => 'No items found for jenis olahraga: ' . $jenis
            ], 404);
        }

        $data = [];
        foreach ($olahraga as $olahraga) {
            $data[] = [
                'id_olahraga' => trim($olahraga->id_olahraga),
                'nama_olahraga' => trim($olahraga->nama_olahraga),
                'deskripsi' => trim($olahraga->deskripsi),
                'cara_olahraga' => trim($olahraga->cara_olahraga),
                'gambar' => trim($olahraga->gambar),
                'kalori' => trim($olahraga->kalori),
            ];
        }

        return response()->json([
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
