<?php

use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserProductController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// User
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);
Route::post('/user/email/verify', [UserAuthController::class, 'verifyEmail'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum', 'email.verified']], function () {
    Route::get('/products', [UserProductController::class, 'getAllProducts']);
});
