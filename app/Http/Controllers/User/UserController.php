<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function create(Request $request){
        //Validate Input
        $request->validate([
            'user_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);

        $user = new User();
        $user->user_name = $request->user_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $save = $user->save();

        return $save ? redirect()->back()->with('success', "You are now registered successfully") : redirect()->back()->with('fail', "Something went wrong, failed to register");
    }

    function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:5|max:30',
        ]);

        $creds = $request->only('email', 'password');
        return Auth::guard('web')->attempt($creds) ? redirect()->route('user.home') : redirect()->route('user.login')->with('fail', "Incorrect credentials");
    }

    function logout(){
        Auth::guard('web')->logout();
        return redirect('/');
    }
}
