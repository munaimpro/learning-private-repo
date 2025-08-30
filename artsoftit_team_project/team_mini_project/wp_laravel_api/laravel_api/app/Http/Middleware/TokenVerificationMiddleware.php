<?php

namespace App\Http\Middleware;

use Closure;
use App\Helper\JWTToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function Ramsey\Uuid\v1;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next) {   
        // $signin_token = $request->cookie('signin_token');

        // If signin_token not present in cookie
        if ($request->hasHeader('Authorization')) {
            $authHeader = $request->header('Authorization');
            logger()->info("Authorization Header: ".$authHeader); // DEBUG
            if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
                $api_token = $matches[1];
            }
        }

        // Try to verify api_token
        if ($api_token) {
            $result = JWTToken::VerifyToken($api_token);

            if ($result !== "Unauthorized") {
                // Set headers for later use
                $request->headers->set('name', $result->name);
                $request->headers->set('phone', $result->phone);
                $request->headers->set('email', $result->email);
                $request->headers->set('password', $result->password);

                return $next($request);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'message' => 'Unauthorized - Invalid or expired token.'
                ]);
            }
        }
    }
}
     