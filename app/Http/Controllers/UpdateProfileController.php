<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\Pengguna;

class UpdateProfileController extends Controller
{
    public function updateProfile(Request $request)
    {
        Log::info('Update profile called', $request->all());

        // Validate request
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
                'diet_type' => 'nullable|in:Menambah berat badan,Mengurangi berat badan,Mempertahankan berat badan,',
                'gender' => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Validation failed', $e->errors());

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        }

        try {
            $user = Pengguna::where('id_user', $request->id_user)->first();

            if (!$user) {
                Log::error('User not found: ' . $request->id_user);

                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Log the received diet type for debugging
            Log::info('Received diet type:', ['diet_type' => $request->diet_type]);

            // Assign values
            $user->nama_lengkap = $request->nama_lengkap;
            $user->email = $request->email;
            $user->berat_badan = $request->berat_badan;
            $user->tinggi_badan = $request->tinggi_badan;
            $user->tanggal_lahir = date('Y-m-d', strtotime($request->tanggal_lahir));

            if ($request->filled('no_hp')) {
                $user->no_hp = $request->no_hp;
            }

            if ($request->has('diet_type')) {
                // Ensure diet type is one of the valid values
                if (!in_array($request->diet_type, ['Menambah berat badan', 'Mengurangi berat badan', 'Mempertahankan berat badan', ''])) {
                    Log::error('Invalid diet type provided: ' . $request->diet_type);

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid diet type'
                    ], 400);
                }

                $user->tipe_diet = $request->diet_type; // Set 'diet_type' instead of 'tipe_diet'
            }

            if ($request->has('gender')) {
                $user->gender = $request->gender;
            }

            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            $user->save();

            $updatedUser = Pengguna::select(
                'id_user', 'nama_lengkap', 'email', 'berat_badan',
                'tinggi_badan', 'tanggal_lahir', 'gambar', 'no_hp',
                'tipe_diet', 'gender'
            )
            ->where('id_user', $request->id_user)
            ->first();

            if ($updatedUser->gambar) {
                $updatedUser->gambar = asset($updatedUser->gambar);
            }

            Log::info('Profile updated successfully', ['id_user' => $request->id_user]);

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user_data' => $updatedUser
            ]);
        } catch (\Exception $e) {
            Log::error('Update profile failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }
}
