<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MenuUserController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_user' => 'required|string',
            'nama_menu' => 'required|string',
            'kategori_menu' => 'required|string',
            'kalori' => 'required|integer',
            'protein' => 'required|numeric',
            'karbohidrat' => 'required|numeric',
            'lemak' => 'required|numeric',
            'gula' => 'required|integer',
            'resep' => 'required|string',
            'satuan' => 'required|string',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $lastId = DB::table('menu_by_user')->max('id_menu');
        if ($lastId) {
            $idNumber = intval(substr($lastId, 2)) + 1;
            $id_menu = 'MN' . str_pad($idNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $id_menu = 'MN001';
        }

        $gambar = 'default.jpg';
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/menu', $filename, 'public');
            $gambar = $filename;
        }
        try {
            DB::table('menu_by_user')->insert([
                'id_menu' => $id_menu,
                'id_user' => $validated['id_user'],
                'nama_menu' => $validated['nama_menu'],
                'kategori_menu' => $validated['kategori_menu'],
                'kalori' => $validated['kalori'],
                'protein' => $validated['protein'],
                'karbohidrat' => $validated['karbohidrat'],
                'lemak' => $validated['lemak'],
                'gula' => $validated['gula'],
                'resep' => $validated['resep'],
                'gambar' => $gambar,
                'satuan' => $validated['satuan'],
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Menu berhasil ditambahkan',
                'id_menu' => $id_menu
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
