<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
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
Route::prefix('api')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('refresh-token', [AuthController::class, 'refreshToken']);

    Route::middleware('jwt.verify')->group(function() {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::resource('movies', MovieController::class);
        Route::post('toggle-movie/{id}', [MovieController::class, 'toggleMovieFollow']);
    });
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found'], 404);
});