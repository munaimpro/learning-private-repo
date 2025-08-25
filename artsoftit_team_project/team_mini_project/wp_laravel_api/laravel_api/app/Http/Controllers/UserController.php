<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    // Function to get all users
    function getUsers (): JsonResponse {
        // Get all users
        $users = User::all();

        // Return user data to frontend
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    
    // Function to insert user API
    function insertUser (): void {

    }
}
