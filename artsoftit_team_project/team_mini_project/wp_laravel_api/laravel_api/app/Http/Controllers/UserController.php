<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    // Function to handle user signup process
    public function UserSignup(Request $request): JsonResponse {
        try{
            /**
             * Input validation process for backend
             */
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'nullable|unique:users,mail',
                'phone' => 'required|string|min:8|max:15',
            ]);

            /**
             * New user create in the users table
             */
            User::create($validatedData);

            /**
             * Return a JSON response indicating success
             * This will return a JSON response with a status of 'success' and a message
             * indicating that the signup was successful.
             */
            return response()->json([
                'status' => 'success',
                'message' => 'ধন্যবাদ, আপনার রেজিস্ট্রেশন সম্পন্ন হয়েছে'
            ]);
        } catch (ValidationException $error) {
            return response()->json([
                'status' => 'fail',
                'message' => 'Validation error.',
                'errors' => $error->errors()
            ]);
        } catch (Exception $error) {
            /**
             * If an exception occurs, return a JSON response indicating failure
             */
            return response()->json([
                'status' => 'fail',
                'message' => 'দুঃখিত! দয়া করে আবার চেষ্টা করুন। অথবা সাপোর্টে যোগাযোগ করুন'. $error->getMessage()
            ]);
        }
    }

    /**
     * Function to handle user signin process.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function UserSignin(Request $request): JsonResponse {
        try{
            /**
             * Input validation process for backend
             */
            $validatedData = $request->validate([
                'phone' => ['required', 'string', 'max:255'],
                'password' => ['required', 'string'],
            ]);

            /* Get user object by user provided mobile number */
            $user = User::where('phone', $validatedData['phone'])->first();

            // Check if user exists AND password is correct
            if(!$user || !Hash::check($validatedData['password'], $user->password)) {
                return response()->json(['status' => 'failed', 'message' => 'দুঃখিত! আপনার তথ্য ম্যাচ করছে না, আবার চেষ্টা করুন']);
            }

            /**
             * Create a new token for the authenticated user and pass to cookie and frontend
             */
            $token = JWTToken::CreateToken($user->id, $user->name, $user->phone);
            return response()->json(['status' => 'success', 'message' => 'আপনি সফলভাবে লগইন করেছেন', 'signin_token' => $token, 'redirect' => 'dashboard'])->cookie(
                'signin_token', // 1. Token name
                $token,       // 2. Token value
                time()+60*60, // 3. Expiration date/time
                '/',          // 4. Path - where the cookie is available ("/" = entire site)
                '',           // 5. Domain - blank means current domain
                true,         // 6. Secure - true = only send over HTTPS
                true,         // 7. HttpOnly - true = JavaScript can't access this cookie
                false,        // 8. Raw - false = URL encode the cookie value
                'Strict'      // 9. SameSite - prevents cross-site requests ("Strict" is safest)
            );

        } catch (ValidationException $error) {
            // Laravel's automatic handling for ValidationException usually returns 422
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation error.'.$error->errors(),
                'validation_error' => $error->errors()
            ]);
        } catch(Exception $error) {
             /* If an unexpected exception occurs, return a JSON response indicating failure */
            return response()->json([
                'status' => 'failed',
                'message' => 'দুঃখিত! দয়া করে আবার চেষ্টা করুন। অথবা সাপোর্টে যোগাযোগ করুন'. $error->getMessage(),
                'exception_error' => $error->getMessage()
            ]);
        }
    }
    
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
