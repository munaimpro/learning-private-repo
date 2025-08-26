<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route for get API
Route::get('users/get', [UserController::class, 'getUsers'])->middleware('TokenVerificationMiddleware');

// Route for post API
Route::post('user/post', [UserController::class, 'insertUser'])->middleware('TokenVerificationMiddleware');

// Route for fetch single API
Route::get('user/get/{id}', [UserController::class, 'getSingleUser']);

// Route for update(put) API
Route::post('user/put/{id}', [UserController::class, 'updateSingleUser'])->middleware('TokenVerificationMiddleware');

// Route for delete API
Route::delete('user/delete/{id}', [UserController::class, 'deleteSingleUser'])->middleware('TokenVerificationMiddleware');

// Auth Routes
Route::post('users/signup', [UserController::class, 'userSignup']);
Route::post('users/signin', [UserController::class, 'userSignin']);

// Page Routes
Route::view('/signin', 'signin');
Route::view('/', 'home');
 
