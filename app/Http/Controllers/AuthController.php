<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\VerifyCustomer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('front.account.login');
    }

    public function register()
    {
        return view('front.account.register');
    }

    public function processRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password',
            'phone' => 'required|min:10|max:10',
            'gender' => 'required'
        ], [
            'confirm_password.required' => 'The confirm password field is required.',
        ]);

        if ($validator->passes()) {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            $customer->gender = $request->gender;
            $customer->email = $request->email;
            $customer->password = Hash::make($request->password);
            $save = $customer->save();

            $last_id = $customer->id;
            $token = $last_id . hash('sha256', Str::random(120));
            $verifyURL = route('account.verifyCustomer', ['token' => $token, 'service' => 'Email_verification']);

            VerifyCustomer::create([
                'cus_id' => $last_id,
                'token' => $token,
            ]);
            $message = "Dear <b>" . $request->name . "</b>";
            $message .= " Thanks for signing up, we just need you to verify your email address to complete setting up your account.";

            $mail_data = [
                'recipient' => $request->email,
                'fromEmail' => $request->email,
                'fromName' => "Beef Shop",
                'subject' => "Email Verification",
                'body' => $message,
                'actionLink' => $verifyURL,
            ];


            Mail::send('sendLinkEmailForgot', $mail_data, function ($message) use ($mail_data) {
                $message->to($mail_data['recipient'])
                    ->from($mail_data['fromEmail'], $mail_data['fromName'])
                    ->subject($mail_data['subject']);
            });

            if ($save) {
                Session::flash('success', "You need to verify your account. We have sent you an activation link, please check your email.");
                return response()->json([
                    'status' => true,
                    'errors' => $validator->errors()
                ]);
            } else {
                Session::flash('fail', "Something went wrong, failed to register");
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ]);
            }
            // return $save ? redirect()->back()->with('success', "You need to verify your account. We have sent you an activation link, please check your email.") : redirect()->back()->with('fail', "Something went wrong, failed to register");
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function verifyCustomer(Request $request)
    {
        $token = $request->token;
        $verifyCustomer = VerifyCustomer::where('token', $token)->first();

        if (!is_null($verifyCustomer)) {
            $customer = Customer::find($verifyCustomer->cus_id);
            if (!$customer->email_verified) {
                $customer->email_verified = 1;
                $customer->email_verified_at = Carbon::now();
                $customer->save();

                return redirect()->route('account.login')->with('info', "Verified your email already, you can login now!")->with('verifiedEmail', $customer->email);
            } else {
                return redirect()->route('account.login')->with('info', 'You have verified your email already , you can login now!')->with('verifiedEmail', $customer->email);
            }
        }
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:5|max:30',

        ]);

        if ($validator->passes()) {
            if (Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
                if (session()->has('url.intended')) {
                    return redirect(session()->get('url.intended'));
                }
                return redirect()->route('account.profile');
            } else {
                //    Session::flash('error', 'Either email/password is incorrect');
                return redirect()->route('account.login')
                    ->withInput($request->only('email'))
                    ->with('error', 'Either email/password is incorrect');
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }

    public function profile()
    {
        return view('front.account.profile');
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('account.login')->with('success', "You successfully logged out!");
    }
}
