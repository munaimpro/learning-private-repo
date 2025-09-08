<?php

use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('users/get', [UserController::class, 'getUsers']);
Route::get('user/get/{id}', [UserController::class, 'getSingleUser']);
Route::post('user/put/{id}', [UserController::class, 'updateSingleUser']);
Route::delete('user/delete/{id}', [UserController::class, 'deleteSingleUser']);

// TokenVerification middleware group
Route::middleware([TokenVerificationMiddleware::class])->group(function () {
    // Route for get API
    // Route::get('users/get', [UserController::class, 'getUsers']); // GET API: http://127.0.0.1:8000/users/get

    // Route for post API
    Route::post('user/post', [UserController::class, 'insertUser']); // POST API: http://127.0.0.1:8000/user/post

    // Route for fetch single API
    // Route::get('user/get/{id}', [UserController::class, 'getSingleUser']); // SINGLE GET API: http://127.0.0.1:8000/user/get/id

    // Route for update(put) API
    // Route::post('user/put/{id}', [UserController::class, 'updateSingleUser']); // PUT API: http://127.0.0.1:8000/user/put/id

    // Route for delete API
    // Route::delete('user/delete/{id}', [UserController::class, 'deleteSingleUser']); // DELETE API: http://127.0.0.1:8000/user/delete/id
});

// Auth Routes
Route::post('user/generate_token', [UserController::class, 'userGenerateToken']); // REGISTER API: http://127.0.0.1:8000/user/generate_token
Route::get('user/get_auth_data/{token}', [UserController::class, 'userGetAuthDataWithToken']); // LOGIN API: http://127.0.0.1:8000/user/get_auth_data/token
Route::post('user/update_token/{id}', [UserController::class, 'userUpdateToken']); // TOKEN UPDATE API: http://127.0.0.1:8000/user/update_token/id

// Page Routes
Route::view('/signin', 'signin');
Route::view('/', 'home');

