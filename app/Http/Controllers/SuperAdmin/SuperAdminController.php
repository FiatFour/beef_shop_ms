<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;

class SuperAdminController extends Controller
{
    function create(Request $request){
        //Validate Input
        $request->validate([
            'admin_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);

        $admin = new Admin();
        $admin->admin_name = $request->admin_name;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $save = $admin->save();

        return $save ? redirect()->back()->with('success', "You are now registered successfully as Admin") : redirect()->back()->with('fail', "Something went wrong, failed to register");
    }

    function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:admins,email',
            'password' => 'required|min:5|max:30',
            ],
            [
                'email.exists' => "This email is not exists in admins table"
            ]
        );

        $creds = $request->only('email', 'password');

        // dd(Auth::guard('super-admin')->attempt($creds) && Auth::guard('super-admin')->user()->super_admin==1);
        // return Auth::guard('super-admin')->attempt($creds) && auth()->guard('super-admin')->user()->super_admin==1 ? redirect()->route('super-admin.home') : redirect()->route('super-admin.login')->with('fail', "Incorrect credentials");
        if(Auth::guard('super-admin')->attempt($creds) && Auth::guard('super-admin')->user()->super_admin==1){
            return redirect()->route('super-admin.home');
        }else{
            return redirect()->route('super-admin.login')->with('fail', "Incorrect credentials");
        }
    }

    function logout(){
        Auth::guard('super-admin')->logout();
        return redirect('/');
    }
}
