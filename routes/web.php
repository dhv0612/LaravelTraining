<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\AuthController::class, 'getLogin']);
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

    Route::get('/register', [\App\Http\Controllers\AuthController::class, 'getRegister']);
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        Route::get('/home', [\App\Http\Controllers\UserController::class, 'index']);
        Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::get('/categories', [\App\Http\Controllers\CategoryController::class, 'create']);
        Route::get('/add-categories', [\App\Http\Controllers\CategoryController::class, 'index']);
        Route::post('/add-categories', [\App\Http\Controllers\CategoryController::class, 'store']);
    });
});


