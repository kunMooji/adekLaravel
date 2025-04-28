<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewArtikelController extends Controller
{
    public function getArtikelReviews(Request $request)
    {
        $artikelId = $request->query('id_artikel') ?? $request->input('id_artikel');
        
        if (empty($artikelId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Aritkel ID is required',
            ], 400);
        }
        
        $reviews = DB::table('review_artikel')
                    ->join('data_pengguna', 'review_artikel.id_user', '=', 'data_pengguna.id_user')
                    ->select(
                        'review_artikel.*',
                        'data_pengguna.nama_lengkap as nama_user'
                    )
                    ->where('review_artikel.id_artikel', $artikelId)
                    ->orderBy('review_artikel.created_at', 'desc')
                    ->get();
                    
        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ]);
    }
    
    public function getUserReview(Request $request)
    {
        $userId = $request->query('id_user') ?? $request->input('id_user');
        $artikelId = $request->query('id_artikel') ?? $request->input('id_artikel');
        
        if (empty($userId) || empty($artikelId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Artikel ID are required',
            ], 400);
        }
        
        $review = DB::table('review_artikel')
                   ->where('id_user', $userId)
                   ->where('id_artikel', $artikelId)
                   ->get();
                   
        return response()->json([
            'status' => 'success',
            'data' => $review,
        ]);
    }
    
    public function addReview(Request $request)
    {
        $userId = $request->input('id_user');
        $artikelId = $request->input('id_artikel');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($artikelId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Artikel ID, and Rating are required',
            ], 400);
        }
        
        // Check if already reviewed
        $existing = DB::table('review_artikel')
                     ->where('id_user', $userId)
                     ->where('id_artikel', $artikelId)
                     ->first();
                     
        if ($existing) {
            // Instead of returning error, update the existing review
            DB::table('review_artikel')
              ->where('id_user', $userId)
              ->where('id_artikel', $artikelId)
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
        DB::table('review_artikel')->insert([
            'id_user' => $userId,
            'id_artikel' => $artikelId,
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
        $artikelId = $request->input('id_artikel');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($artikelId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Artikel ID, and Rating are required',
            ], 400);
        }
        
        // Check if review exists
        $existing = DB::table('review_artikel')
                     ->where('id_user', $userId)
                     ->where('id_artikel', $artikelId)
                     ->first();
                     
        if (!$existing) {
            // If no existing review, create a new one instead
            DB::table('review_artikel')->insert([
                'id_user' => $userId,
                'id_artikel' => $artikelId,
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
        DB::table('review_artikel')
          ->where('id_user', $userId)
          ->where('id_artikel', $artikelId)
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