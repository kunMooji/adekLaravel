<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GetAsupanController;
use App\Http\Controllers\DetailKaloriController;
use App\Http\Controllers\BotSheetController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\FoodbcController;
use App\Http\Controllers\OlahragabcController;
use App\Http\Controllers\ArtikelController;
use App\Http\Controllers\AirController;
use App\Http\Controllers\KaloriController;
use App\Http\Controllers\GetPenggunaData;
use App\Http\Controllers\DailyIntakeController;
use App\Http\Controllers\RiwayatMakananController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Controllers\ChatHistoryController;
use App\Http\Controllers\GetInfoCalories;
use App\Http\Controllers\LikeArtikelController;
use App\Http\Controllers\LikeMenuController;
use App\Http\Controllers\ReviewMenuController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\LikeOlahragaController;
use App\Http\Controllers\ReviewArtikelController;
use App\Http\Controllers\ReviewOlahragaController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\UserWorkoutController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/get-asupan', [GetAsupanController::class, 'getAsupan']);
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/detail-kalori', [DetailKaloriController::class, 'store']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/bot-sheet', [BotSheetController::class, 'getMenuByName']);
Route::get('/foodbc', [FoodbcController::class, 'getMenuByCategory']);
Route::get('/olahragabc', [OlahragabcController::class, 'getOlahragaByJenis']);
Route::get('/artikel', [ArtikelController::class, 'getArtikel']);
Route::post('/update-water-consumption', [AirController::class, 'update']);
Route::get('/get-water-consumption', [AirController::class, 'getWaterConsumption']);
Route::get('/detail-kalori', [KaloriController::class, 'getDetailKalori']);
Route::post('/get-pengguna', [GetPenggunaData::class, 'getPengguna']);
Route::post('/update-profile-picture', [UserController::class, 'updateProfilePicture']);
Route::post('/user/reset-daily-intake', [DailyIntakeController::class, 'resetDailyIntake']);
Route::get('/riwayat-makanan', [RiwayatMakananController::class, 'getTerakhirDimakan']);
Route::post('/update-profile', [UpdateProfileController::class, 'updateProfile']);
Route::get('review-menu', [ReviewMenuController::class, 'getMenuReviews']);
Route::get('review-menu/user', [ReviewMenuController::class, 'getUserReview']);
Route::post('review-menu', [ReviewMenuController::class, 'addReview']);
Route::put('review-menu', [ReviewMenuController::class, 'updateReview']);
Route::get('review-artikel', [ReviewArtikelController::class, 'getArtikelReviews']);
Route::get('review-artikel/user', [ReviewArtikelController::class, 'getUserReview']);
Route::post('review-artikel', [ReviewArtikelController::class, 'addReview']);
Route::put('review-artikel', [ReviewArtikelController::class, 'updateReview']);
Route::get('review-olahraga', [ReviewOlahragaController::class, 'getOlahragaReviews']);
Route::get('review-olahraga/user', [ReviewOlahragaController::class, 'getUserReview']);
Route::post('review-olahraga', [ReviewOlahragaController::class, 'addReview']);
Route::put('review-olahraga', [ReviewOlahragaController::class, 'updateReview']);
Route::post('/lupa-password', [PasswordResetController::class, 'forgotPassword']);
Route::post('/ganti-password', [PasswordResetController::class, 'resetPassword']);
Route::post('/user/update-activity', [UserActivityController::class, 'updateActivity']);
Route::get('/info-kalori', [GetInfoCalories::class, 'getKalori']);

Route::prefix('user-workouts')->group(function () {
    Route::post('/store', [UserWorkoutController::class, 'store']);
    Route::get('/{userId}', [UserWorkoutController::class, 'getUserWorkouts']);
    Route::get('/stats/{userId}', [UserWorkoutController::class, 'getUserStats']);
    Route::get('/duration/{olahragaId}', [UserWorkoutController::class, 'getOlahragaDuration']);
});
Route::prefix('chat-history')->group(function () {
    Route::get('/{userId}', [ChatHistoryController::class, 'getByUser']);
    Route::post('/', [ChatHistoryController::class, 'store']);
    Route::delete('/{userId}', [ChatHistoryController::class, 'deleteByUser']);
});
Route::prefix('like-menu')->group(function () {
    Route::post('/get', [LikeMenuController::class, 'getUserLikes']);
    Route::post('/add', [LikeMenuController::class, 'addLike']);
    Route::post('/remove', [LikeMenuController::class, 'removeLike']);
});
Route::prefix('like-olahraga')->group(function () {
    Route::post('/get', [LikeOlahragaController::class, 'getUserLikes']);
    Route::post('/add', [LikeOlahragaController::class, 'addLike']);
    Route::post('/remove', [LikeOlahragaController::class, 'removeLike']);
});
Route::prefix('like-artikel')->group(function () {
    Route::post('/get', [LikeArtikelController::class, 'getUserLikes']);
    Route::post('/add', [LikeArtikelController::class, 'addLike']);
    Route::post('/remove', [LikeArtikelController::class, 'removeLike']);
});