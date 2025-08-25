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
        $signin_token = $request->cookie('signin_token');
        $VerifyOTPToken = $request->cookie('VerifyOTPToken');
        $path = $request->path();

        // Try to verify signin_token
        if ($signin_token) {
            $result = JWTToken::VerifyToken($signin_token);

            if ($result !== "Unauthorized") {
                // Set headers for later use
                $request->headers->set('userFullName', $result->userFullName);
                $request->headers->set('userMobile', $result->userMobile);
                $request->headers->set('userId', $result->userId);
                $request->headers->set('instituteId', $result->instituteId);
                $request->headers->set('instituteName', $result->instituteName);

                // If already logged in, don't allow visiting signin page
                if ($path == 'signin') {
                    return redirect('/dashboard');
                }

                return $next($request);
            } else {
                if (in_array($path, ['signin', '/'])) {
                    // Allow guest access to signin and home
                    return $next($request);
                }
                // Token exists but invalid → clear cookie & redirect
                return redirect('signin')->withCookie(cookie()->forget('signin_token'));
            }
        }

        // If OTP token exists (optional use case)
        if ($VerifyOTPToken) {
            $result = JWTToken::VerifyToken($VerifyOTPToken);
            if ($result !== "Unauthorized") {
                $request->headers->set('userEmail', $result->userEmail);
                $request->headers->set('userId', $result->userId);
                return $next($request);
            } else {
                return redirect('otp')->withCookie(cookie()->forget('VerifyOTPToken'));
            }
        }

        // Handle guest user
        if (in_array($path, ['signin', '/'])) {
            // Allow guest access to signin and home
            return $next($request);
        }

        // All other routes require login
        return redirect('signin');
    }

}
