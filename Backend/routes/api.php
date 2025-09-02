<?php

use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/hi', function () {
    return view('welcome');
})->name('home');
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

    //company
    Route::get('/companies',[CompanyController::class,'index']);
    Route::post('/companies',[CompanyController::class,'apply']);
    Route::get('/companies/{id}',[CompanyController::class,'show']);
    Route::patch('/companies/{id}',[CompanyController::class,'update']);
    Route::patch('/companies/{id}/change-status',[CompanyController::class,'ChangeStatus']);
    // Route::delete('/companies/{id}',[CompanyController::class,'destroy']);

    //Category
    Route::get('/categories',[CategoryController::class,'index']);
    Route::post('/categories',[CategoryController::class,'store']);
    Route::get('/categories/{id}',[CategoryController::class,'show']);
    Route::patch('/categories/{id}',[CategoryController::class,'update']);
    Route::delete('/categories/{id}',[CategoryController::class,'destroy']);
});

// Protected + verified route
Route::middleware(['auth:api','verified'])->get('/dashboard',function(){
    return response()->json((['message'=>'Welcome to your dashboard']));
});


