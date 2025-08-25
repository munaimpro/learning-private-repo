<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route for get API
Route::get('users/get', [UserController::class, 'getUsers'])->middleware('TokenVerificationMiddleware');

// Route for post API
Route::post('users/post', [UserController::class, 'insertUser'])->middleware('TokenVerificationMiddleware');

// Route for view user list
Route::view('/', 'home');

// Auth Routes
Route::post('users/signup', [UserController::class, 'userSignup']);
Route::post('users/signin', [UserController::class, 'userSignin']);

