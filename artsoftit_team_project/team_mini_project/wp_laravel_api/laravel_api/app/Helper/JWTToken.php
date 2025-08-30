<?php

namespace App\Helper;

use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{
    
    /* Method for create token */

    static function CreateToken($name, $email, $phone, $password):string{
        
        $key = base64_decode(env('JWT_SECRET'));

        $payload = [
            'iss' => 'laravel',
            'iat' => time(),
            /**
            * User email and user id passed to token to find after token decode 
            */
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => $password,
        ];

        return JWT::encode($payload, $key, 'HS512');
    }


    /* Method for create token for reset password */

    static function createResetPasswordToken($userMobile, $userId, $userFullName):string{
         
        $key = base64_decode(env('JWT_SECRET'));

        $payload = [
            'iss' => 'laravel',
            'iat' => time(),
            'exp' => time()+60*5,
            /**
            * User email and user id passed to token to find after token decode 
            */
            'userFullName' => $userFullName,
            'userMobile' => $userMobile,
            'userId' => $userId
        ];

        return JWT::encode($payload, $key, 'HS512');
    }

    
    /* Method for verify token */
    static function VerifyToken($token){
        try {
            if ($token) {
                $key = base64_decode(env('JWT_SECRET'));
                $decodedData = JWT::decode($token, new Key($key, 'HS512'));

                // DB user check
                $user = User::where('api_token', $token)
                        ->where('phone', $decodedData->phone)
                        ->where('email', $decodedData->email)
                        ->first();

                if ($user) {
                    return $decodedData; // Valid token & user exists
                } else {
                    return "Unauthorized"; // Token decoded but user not found in DB
                }
            } else {
                return "Unauthorized";
            }
        } catch (Exception $error) {
            return "Unauthorized";
        }
    }


}

