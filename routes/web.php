<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;

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

    // Admin authenticate
    Route::get('/login', [AuthController::class, 'get_login'])->name('screen_admin_login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin_login');
    Route::get('/register', [AuthController::class, 'get_register'])->name('screen_admin_register');
    Route::post('/register', [AuthController::class, 'register'])->name('admin_register');

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        // Admin authenticate
        Route::get('/home', [UserController::class, 'index'])->name('screen_home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        // Category
        Route::get('/categories', [CategoryController::class, 'create'])->name('screen_list_categories');
        Route::get('/add-categories', [CategoryController::class, 'index'])->name('screen_add_categories');
        Route::post('/add-categories', [CategoryController::class, 'store'])->name('add_categories');

        // Post
        Route::get('/posts', [PostController::class, 'index'])->name('screen_list_posts');

    });
});
