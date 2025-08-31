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
    // Function to handle user generate token process
    public function userGenerateToken(Request $request): JsonResponse {
        try{
            /**
             * Input validation process for backend
             */
            $validatedData = $request->validate([
                'name' => 'required|string|max:100',
                'email' => 'nullable',
                'phone' => 'required|string|min:8|max:15|unique:users,phone',
                'password' => 'required|string',
            ]);

            /**
             * Create a new token
             */
            $token = JWTToken::CreateToken($validatedData['name'], $validatedData['email'], $validatedData['phone']);

            // Attach token to the input data array
            $validatedData['api_token'] = $token;

            /**
             * New user create in the users table
             */
            User::updateOrCreate(
                // Look user by phone
                ['phone' => $validatedData['phone']],

                // Update or insert new user data and generate API token
                [
                    'name' => $validatedData['name'],
                    'email' => $validatedData['email'],
                    'phone' => $validatedData['phone'],
                    'password' => $validatedData['password'],
                    'api_token' => $validatedData['api_token'],
                ]
            );

            /**
             * Return a JSON response indicating success
             * This will return a JSON response with a status of 'success' and a message
             * indicating that the signup was successful.
             */
            return response()->json([
                'status' => 'success',
                'message' => 'New token generated',
                'api_token' => $token
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

    // Function to handle update token process
    public function userUpdateToken (Request $request, $id) {
        try {
            $user = User::findOrFail($id);
            
            // Backend validation process
            $validatedData = $request->validate([
                'name'  => 'required|string|max:255',
                'email' => 'required|email|max:100',
                'phone' => 'required|string|max:50',
            ]);

            /**
             * Create a new token
             */
            $token = JWTToken::CreateToken($validatedData['name'], $validatedData['email'], $validatedData['phone']);

            // Attach token to the input data array
            $validatedData['api_token'] = $token;

            // Data update process
            $user->update($validatedData);

            // Return response for the frontend
            return response()->json([
                'status' => 'success',
                'message' => 'Token updated success',
                'api_token' => $token
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

    /**
     * Function to handle user signin process.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function userGetAuthDataWithToken($token): JsonResponse {
        try{
            /* Get user object by supplied token from wp */
            $user = User::where('api_token', $token)->first();

            // Check token validity
            $token_verification_status = JWTToken::VerifyToken($token);

            /* Check user exist with this token and fetch data by this token */
            if ($user) {
                return response()->json([
                    'status' => 'success',
                    'data' => $user,
                    'token_status' => $token_verification_status,
                ]);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'No user found with this token',
                ]);
            }

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
                'image' => 'image|mimes:jpg,jpeg,png,jfif,gif|max:2048',
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
