<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Pengguna;

class UpdateProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        // Validate request using Laravel's built-in validation
        try {
            $validated = $request->validate([
                'id_user' => 'required|exists:data_pengguna,id_user',
                'nama_lengkap' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'berat_badan' => 'required|numeric|min:1',
                'tinggi_badan' => 'required|numeric|min:1',
                'tanggal_lahir' => 'required|date_format:Y-m-d',
                'no_hp' => 'nullable|string|max:20', 
                'password' => 'nullable|string|min:6',
                'tipe_diet' => 'nullable|string',
                'gender' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            // Find user
            $user = Pengguna::where('id_user', $request->id_user)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Update fields
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->berat_badan = $request->berat_badan;
            $user->tinggi_badan = $request->tinggi_badan;
            $user->tanggal_lahir = date('Y-m-d', strtotime($request->tanggal_lahir));
            
            // Check if no_telp is provided (might be called no_hp in the database)
            if ($request->filled('no_hp')) {
                $user->no_hp = $request->no_hp; 
            }
            
            // Optional fields
            if ($request->has('tipe_diet')) {
                $user->tipe_diet = $request->tipe_diet; // Changed from diet_type to tipe_diet
            }
            
            if ($request->has('gender')) {
                $user->gender = $request->gender;
            }
            
            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            
            // Save changes
            $user->save();
            
            // Get updated user data to return
            $updatedUser = Pengguna::select(
                'id_user', 'nama_lengkap', 'email', 'berat_badan', 
                'tinggi_badan', 'tanggal_lahir', 'gambar', 'no_hp', 
                'tipe_diet', 'gender'
            )
            ->where('id_user', $request->id_user)
            ->first();
            
            // Add base URL to image path
            if ($updatedUser->gambar) {
                $updatedUser->gambar = asset($updatedUser->gambar);
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user_data' => $updatedUser
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
}