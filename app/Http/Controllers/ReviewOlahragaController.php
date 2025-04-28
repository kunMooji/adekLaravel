<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReviewOlahragaController extends Controller
{
    public function getOlahragaReviews(Request $request)
    {
        $olahragaId = $request->query('id_olahraga') ?? $request->input('id_olahraga');
        
        if (empty($olahragaId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Olahraga ID is required',
            ], 400);
        }
        
        $reviews = DB::table('review_olahraga')
                    ->join('data_pengguna', 'review_olahraga.id_user', '=', 'data_pengguna.id_user')
                    ->select(
                        'review_olahraga.*',
                        'data_pengguna.nama_lengkap as nama_user'
                    )
                    ->where('review_olahraga.id_olahraga', $olahragaId)
                    ->orderBy('review_olahraga.created_at', 'desc')
                    ->get();
                    
        return response()->json([
            'status' => 'success',
            'data' => $reviews,
        ]);
    }
    
    public function getUserReview(Request $request)
    {
        $userId = $request->query('id_user') ?? $request->input('id_user');
        $olahragaId = $request->query('id_olahraga') ?? $request->input('id_olahraga');
        
        if (empty($userId) || empty($olahragaId)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID and Olahraga ID are required',
            ], 400);
        }
        
        $review = DB::table('review_olahraga')
                   ->where('id_user', $userId)
                   ->where('id_olahraga', $olahragaId)
                   ->get();
                   
        return response()->json([
            'status' => 'success',
            'data' => $review,
        ]);
    }
    
    public function addReview(Request $request)
    {
        $userId = $request->input('id_user');
        $olahragaId = $request->input('id_olahraga');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($olahragaId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Olahraga ID, and Rating are required',
            ], 400);
        }
        
        // Check if already reviewed
        $existing = DB::table('review_olahraga')
                     ->where('id_user', $userId)
                     ->where('id_olahraga', $olahragaId)
                     ->first();
                     
        if ($existing) {
            // Instead of returning error, update the existing review
            DB::table('review_olahraga')
              ->where('id_user', $userId)
              ->where('id_olahraga', $olahragaId)
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
        DB::table('review_olahraga')->insert([
            'id_user' => $userId,
            'id_olahraga' => $olahragaId,
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
        $olahragaId = $request->input('id_olahraga');
        $rating = $request->input('rating');
        $komentar = $request->input('komentar');
        
        if (empty($userId) || empty($olahragaId) || empty($rating)) {
            return response()->json([
                'status' => 'error',
                'message' => 'User ID, Olahraga ID, and Rating are required',
            ], 400);
        }
        
        // Check if review exists
        $existing = DB::table('review_olahraga')
                     ->where('id_user', $userId)
                     ->where('id_olahraga', $olahragaId)
                     ->first();
                     
        if (!$existing) {
            // If no existing review, create a new one instead
            DB::table('review_olahraga')->insert([
                'id_user' => $userId,
                'id_olahraga' => $olahragaId,
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
        DB::table('review_olahraga')
          ->where('id_user', $userId)
          ->where('id_olahraga', $olahragaId)
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
