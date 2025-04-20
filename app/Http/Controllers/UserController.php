<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Pengguna;

class UserController extends Controller
{
    public function getUserData(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:data_pengguna,id_user'
        ]);

        try {
            $user = Pengguna::find($request->id_user);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'user_data' => $user
            ]);
            

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get user data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProfilePicture(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:data_pengguna,id_user',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $user = Pengguna::find($request->id_user);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Delete old image if exists
            if ($user->gambar) {
                $oldImagePath = str_replace('/storage', 'public', parse_url($user->gambar, PHP_URL_PATH));
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }

            // Store new image
            $imagePath = $request->file('profile_picture')->store('profile_pictures', 'public');
            
            // Update user
            $user->gambar = Storage::url($imagePath);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'user_data' => $user
                
            ]);
            
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile picture: ' . $e->getMessage()
            ], 500);
        }
        
    }
}