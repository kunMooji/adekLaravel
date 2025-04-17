<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyIntakeController extends Controller
{
    /**
     * Reset daily intake for a user and transfer records to history
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetDailyIntake(Request $request)
    {
        $request->validate([
            'id_user' => 'required',
        ]);

        $id_user = $request->id_user;
        $tanggal = Carbon::now()->toDateString(); // Format YYYY-MM-DD
        $now = Carbon::now()->toDateTimeString(); // Untuk created_at

        try {
            DB::beginTransaction();

            // 1. Get all intake data for the user
            // Juga mengambil nama menu dari tabel menu (asumsi ada relasi)
            $details = DB::table('detail_kalori')
                ->join('menu', 'detail_kalori.id_menu', '=', 'menu.id_menu')
                ->where('detail_kalori.id_user', $id_user)
                ->select(
                    'detail_kalori.*',
                    'menu.nama_menu' // Mengambil nama menu
                )
                ->get();

            // 2. Move data to food history dengan struktur kolom yang benar
            foreach ($details as $detail) {
                DB::table('riwayat_makanan')->insert([
                    'id_user' => $id_user,
                    'nama_menu' => $detail->nama_menu,
                    'tanggal' => $tanggal,
                    'jumlah' => $detail->jumlah,
                    'total_kalori' => $detail->total_kalori,
                    'created_at' => $now
                ]);
            }

            // 3. Delete intake data
            DB::table('detail_kalori')
                ->where('id_user', $id_user)
                ->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Daily intake reset successful'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
