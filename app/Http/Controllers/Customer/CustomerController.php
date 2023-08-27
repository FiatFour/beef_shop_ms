<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\VerifyCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Ui\Presets\React;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    function create(Request $request){
        //Validate Input
        $request->validate([
            'cus_name' => 'required',
            'email' => 'required|email|unique:cuss,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);

        $customer = new Customer();
        $customer->cus_name = $request->cus_name;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $save = $customer->save();
        $last_id = $customer->cus_id;

        $token = $last_id.hash('sha256', Str::random(120));
        $verifyURL = route('customer.verify',['token'=>$token,'service'=>'Email_verification']);

        VerifyCustomer::create([
            'cus_id' => $last_id,
            'token' => $token,
        ]);
        $message = "Dear <b>".$request->cus_name."</b>";
        $message.= "Thanks for signing up, we just need you to verify your email address to complete setting up your account.";

        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => $request->cus_name,
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
    }


    function check(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:5|max:30',
        ]);

        $creds = $request->only('email', 'password');
        return Auth::guard('customer')->attempt($creds) ? redirect()->route('customer.home') : redirect()->route('customer.login')->with('fail', "Incorrect credentials");

    }

    function logout(){
        Auth::guard('customer')->logout();
        return redirect()->route('customer.login');
    }

    public function verify(Request $request){
        $token = $request->token;
        $verifyCustomer = VerifyCustomer::where('token', $token)->first();

        if(!is_null($verifyCustomer)){
            $customer = Customer::find($verifyCustomer->cus_id);
            if(!$customer->email_verified){
                $customer->email_verified = 1;
                $customer->save();

                return redirect()->route('customer.login')->with('info','Your email is verified successfully. You can now login')->with('verifiedEmail', $customer->email);
            }else{
                 return redirect()->route('customer.login')->with('info','Your email is already verified. You can now login')->with('verifiedEmail', $customer->email);
            }
        }
    }

    public function showForgotForm(){
        return view('dashboard.customer.forgot');
    }

    public function sendResetLink(Request $request){
        $request->validate([
            'email' => 'required|email|exists:customers,email',
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $action_link = route('customer.reset.password.form', ['token' => $token, 'email' => $request->email]);
        $body = "We are received a request to reset the password for <b>Your app Name </b> account associated with ".$request->email.". You can reset your password by clicking the link below";

        Mail::send('email-forgot', ['action_link' => $action_link, 'body' => $body], function($message) use ($request){
            $message->from('noreply@example.com', 'Your App Name');
            $message->to($request->email, 'Your name')
                    ->subject('Reset password');
        });

        return back()->with('success', "We have e-mailed your password reset link!");
    }

    public function showResetForm(Request $request, $token = null){
        return view('dashboard.customer.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:customers,email',
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
            Customer::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);

            DB::table('password_resets')->where([
                'email' => $request->email
            ]);

            return redirect()->route('customer.login')->with('info', "Your password has been changed! You can login with new password")
                             ->with('verifiedEmail', $request->email);
        }
    }
}
