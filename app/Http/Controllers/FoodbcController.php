<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FoodbcController extends Controller
{
    public function getMenuByCategory(Request $request)
    {
        $category = $request->query('kategori');

        if (empty($category)) {
            return response()->json([
                'error' => 'Category parameter is required'
            ], 400);
        }

        $validCategories = ['makanan_berat', 'dessert', 'minuman_sehat'];

        if (!in_array($category, $validCategories)) {
            return response()->json([
                'error' => 'Invalid category. Valid categories are: makanan_berat, dessert, minuman_sehat'
            ], 400);
        }

        $menus = DB::table('menu')
            ->select('id_menu', 'nama_menu', 'kalori', 'gambar', 'resep')
            ->where('kategori_menu', $category)
            ->get();

            foreach ($menus as $item) {
                $item->gambar = url('storage/image/' . basename($item->gambar));
            }
        if ($menus->isEmpty()) {
            return response()->json([
                'message' => 'No items found for category: ' . $category
            ], 404);
        }

        $data = [];
        foreach ($menus as $menu) {
            $data[] = [
                'id_menu' => trim($menu->id_menu),
                'nama_menu' => trim($menu->nama_menu),
                'kalori' => trim($menu->kalori),
                'gambar' => trim($menu->gambar),
                'resep' => trim($menu->resep),
            ];
        }

        return response()->json([
            'data' => $data
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }
}