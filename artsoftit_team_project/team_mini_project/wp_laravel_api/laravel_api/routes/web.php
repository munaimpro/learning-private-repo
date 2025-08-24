<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


// Route for get API
Route::get('/users/get', ['getUsers', UserController::class]);

// Route for post API
Route::post('users/post', ['insertUser', UserController::class]);

// Route for view user list
Route::view('/', 'home');