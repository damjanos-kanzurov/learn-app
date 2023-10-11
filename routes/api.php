<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\UserController;
use App\Models\User;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/users', [UserController::class, 'index']);

Route::post('/forgot-password', [ResetPasswordController::class, 'store'])->name('password.forgot');
Route::put('/reset-password', [ResetPasswordController::class, 'update'])->name('password.reset');

Route::group(['middleware' => ['auth:sanctum']], function () {
  Route::get('/dashboard', function () {
    return 'DOG';
  });
});
