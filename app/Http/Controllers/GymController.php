<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GymController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Ambil query params
            $search = $request->query('search');
            $minRating = $request->query('min_rating');
            $limit = $request->query('limit', 10);
            $page = $request->query('page', 1);

            // Query builder
            $query = DB::table('gym');

            // Filter by search (name)
            if (!empty($search)) {
                $query->where('name', 'LIKE', '%' . $search . '%');
            }

            // Filter by minimum rating
            if (!empty($minRating) && is_numeric($minRating)) {
                $query->where('rating', '>=', (float) $minRating);
            }

            // Get total count for pagination
            $total = $query->count();

            // Fetch gyms with limit & offset
            $gyms = $query->orderBy('name', 'asc')
                        ->offset(($page - 1) * $limit)
                        ->limit($limit)
                        ->get();

            // Process facilities JSON field
            $gyms->transform(function ($gym) {
                if (!empty($gym->facilities)) {
                    $decoded = json_decode($gym->facilities, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $gym->facilities = $decoded;
                    }
                }
                return $gym;
            });

            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => count($gyms) . ' gyms found',
                'data' => $gyms,
                'pagination' => [
                    'total' => $total,
                    'page' => (int) $page,
                    'limit' => (int) $limit,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            // Error handling
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
                'data' => []
            ], 500);
        }
    }
}
