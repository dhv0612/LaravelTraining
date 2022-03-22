<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SendMailController;

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

// Route admin
Route::prefix('admin')->group(function () {

    // Admin authenticate
    Route::get('/login', [AuthController::class, 'getLogin'])->name('screen_admin_login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin_login');
    Route::get('/register', [AuthController::class, 'getRegister'])->name('screen_admin_register');
    Route::post('/register', [AuthController::class, 'register'])->name('admin_register');

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        // Admin authenticate
        Route::get('/home', [AuthController::class, 'index'])->name('screen_admin_home');
        Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

        // Category
        Route::get('/categories', [CategoryController::class, 'create'])->name('screen_list_categories');
        Route::get('/add-categories', [CategoryController::class, 'index'])->name('screen_add_categories');
        Route::post('/add-categories', [CategoryController::class, 'store'])->name('add_categories');

        // Action Post
        Route::get('/posts', [PostController::class, 'index'])->name('screen_list_posts');
        Route::get('/add-posts', [PostController::class, 'create'])->name('screen_add_posts');
        Route::post('/add-posts', [PostController::class, 'store'])->name('add_posts');
        Route::get('/edit-posts/{id}', [PostController::class, 'edit'])->name('screen_edit_posts');
        Route::post('/edit-posts/{id}', [PostController::class, 'update'])->name('edit_posts');
        Route::get('/delete-posts/{id}', [PostController::class, 'delete'])->name('delete_posts');

    });
});

// Route user
Route::prefix('user')->group(function () {

    // User authenticate
    Route::get('/login', [UserController::class, 'getLogin'])->name('screen_user_login');
    Route::post('/login', [UserController::class, 'login'])->name('user_login');

    Route::get('/home', [UserController::class, 'index'])->name('screen_user_home');
    Route::get('/posts', [UserController::class, 'posts'])->name('screen_user_list_posts');
    Route::get('/view-posts/{id}', [UserController::class, 'viewPost'])->name('screen_user_view_posts');

    Route::group([
        'middleware' => 'auth:sanctum'
    ], function () {
        Route::get('/logout', [UserController::class, 'logout'])->name('user_logout');
        Route::get('/get-voucher/{id}', [UserController::class, 'getVoucher'])->name('user_get_voucher');
    });
});

Route::get('/send-mail', [SendMailController::class, 'index']);

