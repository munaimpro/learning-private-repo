<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Models\ApiUser;
use App\Helper\JWTToken;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            $token = JWTToken::CreateToken($user->id, $user->name, $user->phone, $user->email);
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
        $users = ApiUser::all();

        // Return user data to frontend
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    
    // Function to insert user
    function insertUser (Request $request): JsonResponse {
        try {
            // Validation process
            $validatedData = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|max:100|unique:api_users,email',
                'phone' => 'required|string|max:50',
                'image' => 'nullable|image|mimes:jpg,jpeg,png,jfif,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('uploads', 'public');
            }

            // Insert user data
            $result = ApiUser::create($validatedData);

            return response()->json([
                'status' => 'success',
                'message' => 'User Data Inserted',
                'data' => $result
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


    // Function to get single user
    function getSingleUser ($id): JsonResponse {
        try {
            // Fetch single user by id
            $user = ApiUser::find($id);

            // Return user data to frontend
            return response()->json([
                'status' => 'success',
                'data' => $user,
            ]);
        } catch (Exception $error) {
            /* If an unexpected exception occurs, return a JSON response indicating failure */
            return response()->json([
                'status' => 'failed',
                'message' => 'দুঃখিত! দয়া করে আবার চেষ্টা করুন। অথবা সাপোর্টে যোগাযোগ করুন'. $error->getMessage(),
                'exception_error' => $error->getMessage()
            ]);
        }
    }


    // Function to update single user
    function updateSingleUser (Request $request, $id) {
        try {
            $user = ApiUser::findOrFail($id);
            
            // Backend validation process
            $validatedData = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:50',
                'image' => 'image|mimes:jpg,jpeg,png,jfif,gif|max:2048',
            ]);

            // Existing image delete process

            // Upload image handling
            if ($request->hasFile('image')) {
                $validatedData['image'] = $request->file('image')->store('uploads', 'public');
            }

            // Data update process
            $result = $user->update($validatedData);

            // Return response for the frontend
            return response()->json([
                'status' => 'success',
                'message' => 'User details updated',
                'data' => $user
            ]);
        
        }   catch(Exception $error) {
            /* If an unexpected exception occurs, return a JSON response indicating failure */
            return response()->json([
                'status' => 'failed',
                'message' => 'দুঃখিত! দয়া করে আবার চেষ্টা করুন। অথবা সাপোর্টে যোগাযোগ করুন'. $error->getMessage(),
                'exception_error' => $error->getMessage()
            ]);
        }
    }


    // Function to delete single user
    function deleteSingleUser (Request $request, $id) {
        try {
            $user = ApiUser::findOrFail($id);

            // Delete existing image if has
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Data delete process
            $result = $user->delete();

            // Return response for the frontend
            return response()->json([
                'status' => 'success',
                'message' => 'User successfully deleted',
                'data' => $user
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
}
