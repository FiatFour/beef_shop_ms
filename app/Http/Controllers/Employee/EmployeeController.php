<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifyEmployee;
use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    function logout(){
        Auth::guard('employee')->logout();
        return redirect()->route('login');
    }

    public function verify(Request $request){
        $token = $request->token;
        $verifyEmployee = VerifyEmployee::where('token', $token)->first();

        if(!is_null($verifyEmployee)){
            $employee = Employee::find($verifyEmployee->emp_id);
            if(!$employee->email_verified){
                $employee->email_verified = 1;
                $employee->save();

                return redirect()->route('login')->with('info','Your email is verified successfully. You can now login')->with('verifiedEmail', $employee->email);
            }else{
                 return redirect()->route('login')->with('info','Your email is already verified. You can now login')->with('verifiedEmail', $employee->email);
            }
        }
    }
        /*
    function check(Request $request){
        //Validate input
        $request->validate([
                'email' => 'required|email|exists:employees,email',
                'password' => 'required|min:5|max:30'
            ],
            [
                'email.exists' => "This email is not exists in employees table"
            ]
        );
        $creds = $request->only('email', 'password');


        if(Auth::guard('employee')->attempt($creds) && Auth::guard('employee')->user()->is_admin==1){
            return redirect()->route('super-admin.home');
        }
        if(Auth::guard('employee')->attempt($creds)){
            return redirect()->route('employee.home');
        }
        else{
            dd('Hi');
            return redirect()->route('employee.login')->with('fail', "Incorrect credentials");
        }
    }
    */

    /*
    public function showForgotForm(){
        return view('dashboard.employee.forgot');
    }

    public function sendResetLink(Request $request){
        $request->validate([
            'email' => 'required|email|exists:employees,email',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $action_link = route('employee.reset.password.form', ['token' => $token, 'email' => $request->email]);
        $body = "We are received a request to reset the password for <b>Your app Name </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

        Mail::send('email-forgot', ['action_link' => $action_link, 'body' => $body], function($message) use ($request){
            $message->from('noreply@example.com', 'Your App Name');
            $message->to($request->email, 'Your name')
                    ->subject('Reset password');
        });

        return back()->with('success', "We have e-mailed your password reset link!");
    }

    public function showResetForm(Request $request, $token = null){
        return view('dashboard.employee.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:employees,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);

        $check_token = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token,
        ])->first();

        if(!$check_token){
            return back()->withInput()->with('fail', "Invalid token");
        }else{
            Employee::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);

            DB::table('password_resets')->where([
                'email' => $request->email
            ]);

            return redirect()->route('employee.login')->with('info', "Your password has been changed! You can login with new password")
                             ->with('verifiedEmail', $request->email);
        }
    }

    function create(Request $request){
        //Validate Input
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);

        $employee = new employee();
        $employee->emp_name = $request->name;
        // $employee->emp_lname = $request->lname;
        // $employee->emp_tel = $request->tel;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $save = $employee->save();
        $last_id = $employee->emp_id;

        $token = $last_id.hash('sha256', Str::random(120));
        $verifyURL = route('employee.verify',['token'=>$token,'service'=>'Email_verification']);

        VerifyEmployee::create([
            'emp_id' => $last_id,
            'token' => $token,
        ]);
        $message = "Dear <b>".$request->emp_name."</b>";
        $message.= "Thanks for signing up, we just need you to verify your email address to complete setting up your account.";

        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => $request->emp_name,
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
        // return $save ? redirect()->back()->with('success', "You are now registered successfully as employee") : redirect()->back()->with('fail', "Something went wrong, failed to register");
    }
    */
}
