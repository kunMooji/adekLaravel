<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LikeMenuController extends Controller
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
        
        $likes = DB::table('like_menu')
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
        $menuId = $request->input('id_menu');
        
        if (empty($userId) || empty($menuId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Menu ID are required',
            ], 400);
        }
        
        // Check if already liked
        $existing = DB::table('like_menu')
                     ->where('id_user', $userId)
                     ->where('id_menu', $menuId)
                     ->first();
                     
        if ($existing) {
            return response()->json([
                'status' => 'error',
                'message' => 'Already liked',
            ], 400);
        }
        
        // Create new like
        DB::table('like_menu')->insert([
            'id_user' => $userId,
            'id_menu' => $menuId,
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
        $menuId = $request->input('id_menu');
        
        if (empty($userId) || empty($menuId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Menu ID are required',
            ], 400);
        }
        
        // Delete like
        DB::table('like_menu')
          ->where('id_user', $userId)
          ->where('id_menu', $menuId)
          ->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Like removed successfully',
        ]);
    }
}