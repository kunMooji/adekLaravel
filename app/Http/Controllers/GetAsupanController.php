<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class GetAsupanController extends Controller
{
    public function getAsupan(Request $request)
    {
        try {
            $kategori_menu = $request->query('kategori_menu');

            if ($kategori_menu) {
                $menus = Menu::where('kategori_menu', $kategori_menu)->select('nama_menu')->get();
            } else {
                $menus = Menu::select('nama_menu')->get();
            }

            if ($menus->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Menu tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $menus
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
