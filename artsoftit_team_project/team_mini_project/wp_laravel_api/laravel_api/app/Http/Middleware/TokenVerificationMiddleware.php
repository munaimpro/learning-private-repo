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
        // $path = $request->path();

        // Try to verify signin_token
        if ($signin_token) {
            $result = JWTToken::VerifyToken($signin_token);

            if ($result !== "Unauthorized") {
                // Set headers for later use
                $request->headers->set('name', $result->userFullName);
                $request->headers->set('phone', $result->userMobile);
                $request->headers->set('email', $result->userMobile);
                $request->headers->set('userId', $result->userId);

                return $next($request);
            } else {
                return redirect('signin');
            }
        }
    }
}
