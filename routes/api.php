<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



// User
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/verification_token/request', [UserAuthController::class, 'requestVerificationToken']);
    Route::post('/user/email/verify', [UserAuthController::class, 'verifyEmail']);
});

Route::group(['middleware' => ['auth:sanctum', 'email.verified']], function () {
    Route::get('/products', [UserProductController::class, 'getAllProducts']);
});






//Seller
Route::post('/seller/register', [SellerAuthController::class, 'register']);
Route::post('/seller/login', [SellerAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/seller/verification_token/request', [SellerAuthController::class, 'requestVerificationToken']);
    Route::post('/seller/email/verify', [SellerAuthController::class, 'verifyEmail']);
});

Route::group(['middleware' => ['auth:sanctum', 'email.verified']], function () {
    Route::get('/products', [SellerProductController::class, 'getAllProducts']);
});




//Admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);
