<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LikeArtikelController extends Controller
{
    public function getUserLikes(Request $request)
    {
        $userId = $request->input('id_user');

        if (empty($userId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID is required',
            ], 400);
        }

        $likes = DB::table('like_artikel')
                    ->where('id_user', $userId)
                    ->get();

        return response()->json([
            'status' => 'success',
            'data' => $likes,
        ]);
    }

    public function addLike(Request $request)
    {
        $userId = $request->input('id_user');
        $artikelId = $request->input('id_artikel');

        if (empty($userId) || empty($artikelId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Artikel ID are required',
            ], 400);
        }

        // Check if already liked
        $existing = DB::table('like_artikel')
                        ->where('id_user', $userId)
                        ->where('id_artikel', $artikelId)
                        ->first();

        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already liked',
            ], 400);
        }

        // Create new like
        DB::table('like_artikel')->insert([
            'id_user' => $userId,
            'id_artikel' => $artikelId,
            'created_at' => Carbon::now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Like added successfully',
        ]);
    }

    public function removeLike(Request $request)
    {
        $userId = $request->input('id_user');
        $artikelId = $request->input('id_artikel');

        if (empty($userId) || empty($artikelId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Artikel ID are required',
            ], 400);
        }

        // Delete like
        DB::table('like_artikel')
            ->where('id_user', $userId)
            ->where('id_artikel', $artikelId)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Like removed successfully',
        ]);
    }
}
