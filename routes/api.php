<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ScoreController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\UserController;

use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Http\Controllers\User\ExamController as UserExamController;
use App\Http\Controllers\User\CommentController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\UserController as UserUserController;
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
Route::post('/auth/login', [UserAuthController::class, 'login']);
Route::post('/auth/register', [UserAuthController::class, 'register']);

Route::get('/exams', [UserExamController::class, 'index']);
Route::get('/users/{id}/history', [UserExamController::class, 'getHistoryList']);
Route::get('/exams/{id}', [UserExamController::class, 'getExamDetail']);
Route::post('/exams/{id}/getExamForTest', [UserExamController::class, 'getExamForTest']);
Route::get('/home', [HomeController::class, 'index']);

Route::get('/comments/{id}/{type}', [CommentController::class, 'index']);

Route::group(["middleware" => "auth:api"], function () {
    Route::put('/users/reset-password/{id}', [UserUserController::class, 'resetPassword']);
    Route::get('/users/{id}', [UserUserController::class, 'show']);
    Route::put('/users/{id}', [UserUserController::class, 'update']);
    Route::post('/exams/{id}/submit', [UserExamController::class, 'submit']);
    Route::get('/exams/{exam_id}/history/{history_id}', [UserExamController::class, 'getHistory']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);
});

Route::post('/admin/auth/login', [AuthController::class, 'login'])->name('admin.login');
Route::get('images/{type}/{prefix}/{filename}', [FileController::class, 'showImage']);
Route::get('audio/{type}/{prefix}/{filename}', [FileController::class, 'showAudio']);
Route::get('templates/getAllTemplates', [TemplateController::class, 'getAllTemplates']);
Route::get('blogs', [BlogController::class,'index']);
Route::get('blogs/{id}', [BlogController::class,'show']);

Route::group(['prefix' => 'admin', 'middleware' => ['auth:api', 'isAdmin']], function () {
    Route::post('auth/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::resource('users', UserController::class);
    Route::put('users/reset-password/{id}', [UserController::class, 'resetPassword']);
    // Route::post('users', [UserController::class,'store']);

    Route::resource('templates', TemplateController::class);

    Route::put('exams/questions/{id}', [ExamController::class, 'updateQuestion']);
    Route::put('exams/groups/{id}', [ExamController::class, 'updateGroup']);
    Route::resource('exams', ExamController::class);
    Route::resource('blogs', BlogController::class);
    Route::post('scores/delete', [ScoreController::class, 'destroy']);
    Route::resource('scores', ScoreController::class)->except('destroy');
});
