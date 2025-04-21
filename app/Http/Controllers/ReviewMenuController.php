<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewMenuController extends Controller
{
    public function getMenuReviews(Request $request)
    {
        $menuId = $request->query('id_menu') ?? $request->input('id_menu');
        
        if (empty($menuId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Menu ID is required',
            ], 400);
        }
        
        $reviews = DB::table('review_menu')
                    ->join('data_pengguna', 'review_menu.id_user', '=', 'data_pengguna.id_user')
                    ->select(
                        'review_menu.*',
                        'data_pengguna.nama_lengkap as nama_user'
                    )
                    ->where('review_menu.id_menu', $menuId)
                    ->orderBy('review_menu.created_at', 'desc')
                    ->get();
                    
        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ]);
    }
    
    public function getUserReview(Request $request)
    {
        $userId = $request->query('id_user') ?? $request->input('id_user');
        $menuId = $request->query('id_menu') ?? $request->input('id_menu');
        
        if (empty($userId) || empty($menuId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Menu ID are required',
            ], 400);
        }
        
        $review = DB::table('review_menu')
                   ->where('id_user', $userId)
                   ->where('id_menu', $menuId)
                   ->get();
                   
        return response()->json([
            'status' => 'success',
            'data' => $review,
        ]);
    }
    
    public function addReview(Request $request)
    {
        $userId = $request->input('id_user');
        $menuId = $request->input('id_menu');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($menuId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Menu ID, and Rating are required',
            ], 400);
        }
        
        // Check if already reviewed
        $existing = DB::table('review_menu')
                     ->where('id_user', $userId)
                     ->where('id_menu', $menuId)
                     ->first();
                     
        if ($existing) {
            // Instead of returning error, update the existing review
            DB::table('review_menu')
              ->where('id_user', $userId)
              ->where('id_menu', $menuId)
              ->update([
                  'rating' => $rating,
                  'komentar' => $komentar,
                  'created_at' => Carbon::now(), // Using created_at as updated_at since you don't have updated_at column
              ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Review updated successfully',
            ]);
        }
        
        // Create new review
        DB::table('review_menu')->insert([
            'id_user' => $userId,
            'id_menu' => $menuId,
            'rating' => $rating,
            'komentar' => $komentar,
            'created_at' => Carbon::now(),
        ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Review added successfully',
        ]);
    }
    
    public function updateReview(Request $request)
    {
        $userId = $request->input('id_user');
        $menuId = $request->input('id_menu');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($menuId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Menu ID, and Rating are required',
            ], 400);
        }
        
        // Check if review exists
        $existing = DB::table('review_menu')
                     ->where('id_user', $userId)
                     ->where('id_menu', $menuId)
                     ->first();
                     
        if (!$existing) {
            // If no existing review, create a new one instead
            DB::table('review_menu')->insert([
                'id_user' => $userId,
                'id_menu' => $menuId,
                'rating' => $rating,
                'komentar' => $komentar,
                'created_at' => Carbon::now(),
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Review added successfully',
            ]);
        }
        
        // Update review
        DB::table('review_menu')
          ->where('id_user', $userId)
          ->where('id_menu', $menuId)
          ->update([
              'rating' => $rating,
              'komentar' => $komentar,
              'created_at' => Carbon::now(), // Using created_at for update time
          ]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Review updated successfully',
        ]);
    }
}