<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatHistoryController extends Controller
{
    public function getByUser($userId)
    {
        try {
            $chatHistory = ChatHistory::where('id_user', $userId)
                ->orderBy('timestamp', 'asc')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => $chatHistory
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve chat history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_user' => 'required|string',
                'sender' => 'required|in:user,bot',
                'message' => 'required|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
    
            $chatMessage = ChatHistory::create([
                'id_user' => $request->id_user,
                'sender' => $request->sender,
                'message' => $request->message,
                'timestamp' => now(),
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Chat message saved successfully',
                'data' => $chatMessage
            ], 201);
    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save chat message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteByUser($userId)
    {
        try {
            $deleted = ChatHistory::where('id_user', $userId)->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Chat history deleted successfully',
                'count' => $deleted
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete chat history',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}