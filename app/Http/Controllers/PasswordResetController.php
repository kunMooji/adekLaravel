<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class PasswordResetController extends Controller
{

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email tidak ditemukan dalam sistem kami.'
            ], 404);
        }


        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();


        $token = sprintf('%06d', rand(0, 999999));
        $tokenHash = Hash::make($token);


        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $tokenHash,
            'created_at' => Carbon::now()
        ]);

        try {

            Mail::send('emails.reset_password', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Kode Reset Password');
            });

            return response()->json([
                'success' => true,
                'message' => 'Kode reset password telah dikirim ke email Anda.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);


        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau sudah kadaluarsa.'
            ], 400);
        }


        if (!Hash::check($request->token, $tokenData->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid.'
            ], 400);
        }


        $created = Carbon::parse($tokenData->created_at);
        if (Carbon::now()->diffInMinutes($created) > 60) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return response()->json([
                'success' => false,
                'message' => 'Token sudah kadaluarsa. Silakan request token baru.'
            ], 400);
        }


        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();


        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah!'
        ]);
    }
}
