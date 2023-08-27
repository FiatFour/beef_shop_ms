<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if($guard === 'super-admin' && Auth::guard('super-admin')->user()->super_admin == 1){
                    return redirect()->route('super-admin.home');
                }
                if($guard === 'admin'){
                    return redirect()->route('admin.home');
                }

                if($guard === 'user'){
                    return redirect()->route('user.home');
                }

                if($guard === 'customer'){
                    return redirect()->route('customer.home');
                }

                // return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
