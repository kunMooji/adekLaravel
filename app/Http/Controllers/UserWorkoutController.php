<?php

namespace App\Http\Controllers;

use App\Models\UserWorkout;
use App\Models\Olahraga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserWorkoutController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_user' => 'required',
            'id_olahraga' => 'required',
            'durasi' => 'required|string',
            'kalori' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $workout = UserWorkout::create([
                'id_user' => $request->id_user,
                'id_olahraga' => $request->id_olahraga,
                'durasi' => $request->durasi,
                'kalori' => $request->kalori,
                'tanggal' => $request->tanggal,
            ]);

            return response()->json([
                'message' => 'Workout record created successfully',
                'data' => $workout
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating workout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getUserWorkouts($userId)
    {
        $workouts = UserWorkout::with('olahraga')
            ->where('id_user', $userId)
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'data' => $workouts
        ]);
    }

    public function getUserStats($userId)
    {
        $totalKalori = UserWorkout::where('id_user', $userId)->sum('kalori');

        $totalDurasiSeconds = 0;
        $workouts = UserWorkout::where('id_user', $userId)->get();

        foreach ($workouts as $workout) {
            list($hours, $minutes, $seconds) = explode(':', $workout->durasi);
            $totalDurasiSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
        }

        $hours = floor($totalDurasiSeconds / 3600);
        $minutes = floor(($totalDurasiSeconds % 3600) / 60);
        $seconds = $totalDurasiSeconds % 60;
        $totalDurasi = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

        $totalWorkouts = $workouts->count();

        return response()->json([
            'total_workouts' => $totalWorkouts,
            'total_kalori' => round($totalKalori, 2),
            'total_durasi' => $totalDurasi
        ]);
    }

    public function getOlahragaDuration($olahragaId)
    {
        $olahraga = Olahraga::findOrFail($olahragaId);

        $durasiMenit = $olahraga->durasi ?? 30;

        return response()->json([
            'durasi_menit' => $durasiMenit,
            'mets' => $olahraga->kalori
        ]);
    }
}
