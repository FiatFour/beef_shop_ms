<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // return $request->expectsJson() ? null : route('account.login');
        if(! $request->expectsJson()){
            // if($request->routeIs('super-admin.*')){
            //     return route('super-admin.login');
            // }
            // if($request->routeIs('admin.*')){
            //     return route('admin.login');
            // }
            // if($request->routeIs('customer.*')){
            //     return route('customer.login');
            // }
            // if($request->routeIs('employee.*')){
            //     return route('employee.login');
            // }

//! Wait to edit
            /*
            if($request->routeIs('login.'))
            {
                if(Auth::guard('customer')->user()){
                    return redirect()->route('customer.home');
                }else if(Auth::guard('employee')->user()->is_admin == 1){
                    return redirect()->route('admin.home');
                }else if(Auth::guard('employee')->user()){
                    return redirect()->route('employee.home');
                }
            }
            */

            // if($request->routeIs('customer.*')){
            //     return route('account.login');
            // }
            if($request->routeIs('admin.*') || $request->routeIs('customer.*') || $request->routeIs('employee.*')){
                return route('login');
            }
            return $request->expectsJson() ? null : route('account.login');
        }
    }
}
