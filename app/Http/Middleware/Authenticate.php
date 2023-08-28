<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // return $request->expectsJson() ? null : route('user.login');
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

            if($request->routeIs('admin.*') || $request->routeIs('customer.*') || $request->routeIs('employee.*')){
                return route('login');
            }
            return route('login');
        }
    }
}
