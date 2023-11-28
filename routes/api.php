<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/admin/auth/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('images/{type}/{prefix}/{filename}', [FileController::class,'showImage']);
Route::get('audio/{type}/{prefix}/{filename}', [FileController::class,'showAudio']);
Route::get('templates/getAllTemplates', [TemplateController::class,'getAllTemplates']);

Route::group(['prefix' => 'admin', 'middleware' => 'auth:api'], function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::resource('users',UserController::class);
    Route::put('users/reset-password/{id}',[UserController::class,'resetPassword']);
    // Route::post('users', [UserController::class,'store']);

    Route::resource('templates',TemplateController::class);

    Route::put('exams/questions/{id}',[ExamController::class,'updateQuestion']);
    Route::put('exams/groups/{id}',[ExamController::class,'updateGroup']);
    Route::resource('exams', ExamController::class);
    Route::resource('blogs', BlogController::class);
    Route::post('scores/delete', [ScoreController::class,'destroy']);
    Route::resource('scores', ScoreController::class)->except('destroy');
});
