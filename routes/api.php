<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AuthController::class,'login_api']);

Route::group([
    'middleware' => 'auth:sanctum'
], function () {
    // Admin authenticate
    Route::post('/events/{event_id}/editable/me',[PostController::class,'editable']);
    Route::get('/events/{event_id}/editable/maintain',[PostController::class, 'maintain']);
    Route::post('/events/{event_id}/editable/release',[PostController::class, 'release']);
});
