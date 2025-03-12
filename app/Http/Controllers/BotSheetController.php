<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class BotSheetController extends Controller
{
    public function getMenuByName(Request $request)
    {
        try {
            $nama_menu = trim($request->query('nama_menu'));

            if (empty($nama_menu)) {
                return response()->json(['error' => 'Nama menu tidak boleh kosong'], 400);
            }

            $menu = Menu::where('nama_menu', $nama_menu)
                ->select('id_menu', 'nama_menu', 'kalori', 'protein', 'karbohidrat', 'lemak', 'gula', 'satuan')
                ->first();

            if (!$menu) {
                return response()->json(['error' => 'Menu tidak ditemukan'], 404);
            }

            return response()->json(['data' => $menu], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
