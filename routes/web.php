<?php

use Illuminate\Auth\Events\Login;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/hello', function(){
    return "hello,world";
});

Route::get('/users/{id}', function($id){
    return "id user yg masuk : " . $id;
});

Route::get('/dashboard', function () {
    return view('dashboard');
    })->name('dashboard');

Route::get('/login', function() {
    return view('login');

})->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
    return view('dashboard');
    });
    Route::get('/users', function () {
    return "Admin Users";
    });
    });
    
    Route::get('/layout', function () {
        return view('layout');
        })->name('layout');

Route::get('/gambar/{filename}', function ($filename) {
    $path = storage_path("app/public/$filename");

    if (!file_exists($path)) {
        abort(404); 
    }

    return response()->file($path);
});