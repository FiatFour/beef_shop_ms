<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VerifyAdmin;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Str;


use App\Article;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;

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
        $admin->admin_lname = $request->admin_lname;
        $admin->admin_tel = $request->admin_tel;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $save = $admin->save();
        $last_id = $admin->admin_id;

        $token = $last_id.hash('sha256', Str::random(120));
        $verifyURL = route('admin.verify',['token'=>$token,'service'=>'Email_verification']);

        VerifyAdmin::create([
            'admin_id' => $last_id,
            'token' => $token,
        ]);
        $message = "Dear <b>".$request->admin_name."</b>";
        $message.= "Thanks for signing up, we just need you to verify your email address to complete setting up your account.";

        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => $request->admin_name,
            'subject' => "Email Verification",
            'body' => $message,
            'actionLink' => $verifyURL,
        ];


        Mail::send('email-template', $mail_data, function ($message) use ($mail_data) {
            $message->to($mail_data['recipient'])
                ->from($mail_data['fromEmail'], $mail_data['fromName'])
                ->subject($mail_data['subject']);
        });
        return $save ? redirect()->back()->with('success', "You need to verify your account. We have sent you an activation link, please check your email.") : redirect()->back()->with('fail', "Something went wrong, failed to register");
        // return $save ? redirect()->back()->with('success', "You are now registered successfully as Admin") : redirect()->back()->with('fail', "Something went wrong, failed to register");
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
        return redirect()->route('super-admin.login');
    }


}
