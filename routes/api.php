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
use App\Http\Controllers\GetPenggunaData;

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
Route::post('/get-pengguna', [GetPenggunaData::class, 'getPengguna']);