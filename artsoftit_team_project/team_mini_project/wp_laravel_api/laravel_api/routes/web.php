<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route for get API
Route::get('/users/get', ['getUsers', UserController::class])->middleware('TokenVerificationMiddleware');

// Route for post API
Route::post('users/post', ['insertUser', UserController::class])->middleware('TokenVerificationMiddleware');

// Route for view user list
Route::view('/', 'home');

// Auth Routes
Route::post('signup', ['userSignup', UserController::class]);
Route::post('signin', ['userSignin', UserController::class]);

