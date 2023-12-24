<?php

namespace App\Http\Middleware;

use App\Traits\ResponseTrait;
use Closure;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmailVerified
{
    use ResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $this->fail("unauthorized", 401);
        }

        if (Auth::user()->email_verified_at == null || Auth::user()->email_verified_at == "") {
            $data = [
                "verified" => false,
            ];
            return $this->success("Email is not verified", $data, 403);
        }

        return $next($request);
    }
}
