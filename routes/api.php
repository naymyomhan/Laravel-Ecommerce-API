<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Common\BrandController;
use App\Http\Controllers\Common\CategoryController;
use App\Http\Controllers\Common\ProductController;
use App\Http\Controllers\Seller\SellerAuthController;
use App\Http\Controllers\Seller\SellerProductController;
use App\Http\Controllers\Seller\SellerProfileController;
use App\Http\Controllers\User\UserAuthController;
use App\Http\Controllers\User\UserProductController;
use App\Http\Controllers\User\VerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


//Get Categories, SubCategories, Brands
Route::get('/categories', [CategoryController::class, 'getCategories']);
Route::get('/brands', [BrandController::class, 'getBrands']);
Route::get('/products', [ProductController::class, 'getAllProducts']);







// User
Route::post('/user/register', [UserAuthController::class, 'register']);
Route::post('/user/login', [UserAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', 'auth:user']], function () {
    Route::post('/user/verification_token/request', [UserAuthController::class, 'requestVerificationToken']);
    Route::post('/user/email/verify', [UserAuthController::class, 'verifyEmail']);
});

Route::group(['middleware' => ['auth:sanctum', 'email.verified', 'auth:user']], function () {
    Route::get('/user/products', [UserProductController::class, 'getAllProducts']);
});






//Seller
Route::post('/seller/register', [SellerAuthController::class, 'register']);
Route::post('/seller/login', [SellerAuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', 'auth:seller']], function () {
    Route::post('/seller/verification_token/request', [SellerAuthController::class, 'requestVerificationToken']);
    Route::post('/seller/email/verify', [SellerAuthController::class, 'verifyEmail']);
});

Route::group(['middleware' => ['auth:sanctum', 'email.verified', 'auth:seller']], function () {
    //Profile
    Route::get('/seller/profile', [SellerProfileController::class, 'getProfile']);

    //Upload Products
    Route::post('/seller/product/create', [SellerProductController::class, 'createProduct']);
    Route::get('/seller/products', [SellerProductController::class, 'getAllProducts']);
});






//Admin
Route::post('/admin/login', [AdminAuthController::class, 'login']);
