<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;


// Route for get API
Route::get('users/get', [UserController::class, 'getUsers'])->middleware(TokenVerificationMiddleware::class); // GET API: http://127.0.0.1:8000/users/get

// Route for post API
Route::post('user/post', [UserController::class, 'insertUser'])->middleware(TokenVerificationMiddleware::class); // POST API: http://127.0.0.1:8000/user/post

// Route for fetch single API
Route::get('user/get/{id}', [UserController::class, 'getSingleUser'])->middleware(TokenVerificationMiddleware::class); // SINGLE GET API: http://127.0.0.1:8000/user/get/id

// Route for update(put) API
Route::post('user/put/{id}', [UserController::class, 'updateSingleUser'])->middleware(TokenVerificationMiddleware::class); // PUT API: http://127.0.0.1:8000/user/put/id

// Route for delete API
Route::delete('user/delete/{id}', [UserController::class, 'deleteSingleUser'])->middleware(TokenVerificationMiddleware::class); // DELETE API: http://127.0.0.1:8000/user/delete/id

// Auth Routes
Route::post('user/generate_token', [UserController::class, 'userGenerateToken']); // REGISTER API: http://127.0.0.1:8000/user/generate_token
Route::get('user/get_auth_data/{token}', [UserController::class, 'userGetAuthDataWithToken']); // LOGIN API: http://127.0.0.1:8000/user/get_auth_data/token

// Page Routes
Route::view('/signin', 'signin');
Route::view('/', 'home');

