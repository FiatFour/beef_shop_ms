<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCustomerVerifyEmail
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('customer')->user()->status == 0) {
            Auth::guard('customer')->logout();
            if (!empty(Auth::guard('customer')->user()->email_verified)) {
                return redirect()->route('account.login')->with('fail', "You need to confirm your account. We have sent you an activation link, please check you email")->withInput();
            }
            return redirect()->route('account.login')->with('fail', "Your account has banned")->withInput();
        }
        return $next($request);
    }
}
