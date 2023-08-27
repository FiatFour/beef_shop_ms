<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsUserVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard('web')->user()->email_verified){
            Auth::guard('web')->logout();
            return redirect()->route('user.login')->with('fail', "You need to confirm your account. We have sent you an activation link, please check you email")->withInput();
        }
        return $next($request);
    }
}
