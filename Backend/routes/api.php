<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);


// verify Email
Route::get('/email/verify/{id}/{hash}',[VerificationController::class,'verify'])
    ->name('verification.verify');

Route::post('/email/resend',[VerificationController::class,'resend'])
    ->middleware('auth:api')
    ->name('verification.resend');

// Protected routes
Route::middleware(('auth:api'))->group(function(){
    Route::get('me',[AuthController::class,'me']);
    Route::post('logout',[AuthController::class,'logout']);
    //user
    Route::get('/users',[UserController::class,'index']);
    Route::get('/users/{id}',[UserController::class,'show']);
    Route::put('/users/{id}',[UserController::class,'update']);
    Route::delete('/users/{id}',[UserController::class,'destroy']);
    Route::post('/users/{id}/upload-image',[UserController::class,'uploadImage']);
});

// Protected + verified route
Route::middleware(['auth:api','verified'])->get('/dashboard',function(){
    return response()->json((['message'=>'Welcome to your dashboard']));
});