<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\UserPreferenceController;
// use App\Http\Controllers\Api\ForgetPaswordController;
// use App\Http\Controllers\Api\ResetPaswordController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);
// Route::post('/auth/logout', [AuthController::class, 'logoutUser'])->middleware('auth:sanctum');
// Route::post('/password/email', [ForgetPaswordController::class, 'sendPasswordResetLink']);
// Route::post('/password/reset', [ResetPaswordController::class, 'resetPassword'])->name('password.reset');
Route::post('/auth/email', [AuthController::class, 'sendPasswordResetLink']);
Route::post('/auth/reset', [AuthController::class, 'resetPassword'])->name('password.reset');


Route::get('/articles', [ArticleController::class, 'index']);
Route::get('/articles/search', [ArticleController::class, 'search']);
Route::get('/articles/{id}', [ArticleController::class, 'show']);


Route::get('/fetch-newsapi', [ArticleController::class, 'fetchAndStoreNewsAPI']);
Route::get('/fetch-guardian', [ArticleController::class, 'fetchAndStoreGuardianAPI']);
Route::get('/fetch-nytimes', [ArticleController::class, 'fetchAndStoreNYTimesAPI']);


 Route::middleware(['auth:sanctum'])->group(function () {

        Route::post('logout', [AuthController::class, 'logoutUser']);
        Route::get('/preferences', [UserPreferenceController::class, 'getPreferences']);
        Route::post('/preferences', [UserPreferenceController::class, 'setPreferences']);
        Route::get('/personalized-feed', [UserPreferenceController::class, 'getPersonalizedFeed']);
    
 });