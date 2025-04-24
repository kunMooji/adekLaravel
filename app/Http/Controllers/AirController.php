<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailKalori;

class AirController extends Controller
{
    public function update(Request $request)
{
    $validatedData = $request->validate([
        'id_user' => 'required|string',
        'tanggal' => 'required|date',
        'total_minum' => 'required|numeric',
    ]);


    $detailKalori = DetailKalori::where('id_user', $validatedData['id_user'])
        ->where('tanggal', $validatedData['tanggal'])
        ->first();

    if ($detailKalori) {

        $detailKalori->total_minum += $validatedData['total_minum'];
        $detailKalori->save();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah minum berhasil ditambahkan',
            'total_minum' => $detailKalori->total_minum,
        ]);
    } else {

        $newData = new DetailKalori();
        $newData->id_user = $validatedData['id_user'];
        $newData->tanggal = $validatedData['tanggal'];
        $newData->total_minum = $validatedData['total_minum'];
        $newData->save();

        return response()->json([
            'success' => true,
            'message' => 'Data baru dibuat dan total minum ditambahkan',
            'total_minum' => $newData->total_minum,
        ]);
    }
}
}
