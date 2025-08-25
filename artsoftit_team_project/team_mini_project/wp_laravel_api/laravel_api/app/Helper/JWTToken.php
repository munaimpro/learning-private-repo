<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken{
    
    /* Method for create token */

    static function CreateToken($userId, $name, $phone):string{
        
        $key = base64_decode(env('JWT_SECRET'));

        $payload = [
            'iss' => 'laravel',
            'iat' => time(),
            'exp' => time()+60*60,
            /**
            * User email and user id passed to token to find after token decode 
            */
            'name' => $name,
            'phone' => $phone,
            'userId' => $userId,
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
        try{
            if($token){
                $key = base64_decode(env('JWT_SECRET'));
                $decodedData = JWT::decode($token, new Key($key, 'HS512'));
                return $decodedData;
            } else{
                return "Unauthorized";
            }
        } catch(Exception $error){
            return "Unauthorized";
        }
    }
}