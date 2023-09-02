<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Employee;
use App\Models\Customer;
use Carbon\Carbon;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showForgotForm(){
        return view('forgotPassword');
    }
    public function sendResetLink(Request $request){
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (DB::table('customers')->where('email', $value)->exists() ||
                        DB::table('employees')->where('email', $value)->exists()
                    ){}else{
                        $fail("The selected $attribute is invalid");
                    }
                },
            ],
        ]);

        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now(),
        ]);

        $actionLink = route('resetPasswordForm', ['token' => $token, 'email' => $request->email]);
        $body = "รีเซ็ตรหัสผ่าน <b>Beef Shop </b> กับบัญชี ".$request->email.". คุณสามารถรีเซ็ตนหัสผ่านคุณได้คลิ้กลิ้งด้านล่าง";

        Mail::send('sendLinkEmailForgot', ['actionLink' => $actionLink, 'body' => $body], function($message) use ($request){
            $message->from('noreply@example.com', 'Beef Shop');
            $message->to($request->email, 'Anfat Nilaingan')
                    ->subject('Reset password');
        });

        return back()->with('success', "เราได้ส่งลิ้งเพื่อรีเซ็ตรหัสผ่านของคุณทางอีเมล์แล้ว!");
    }

    public function showResetForm(Request $request, $token = null){
        return view('resetPassword')->with(['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email' => [
                'required',
                'email',
                function ($attribute, $value, $fail) {
                    if (DB::table('customers')->where('email', $value)->exists() ||
                        DB::table('employees')->where('email', $value)->exists()
                    ){}else{
                        $fail("The selected $attribute is invalid");
                    }
                },
            ],
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
            if(Customer::where('email', $request->email)){
                Customer::where('email', $request->email)->update([
                    'password' => Hash::make($request->password)
                ]);
            }
            if(Employee::where('email', $request->email)){
                Employee::where('email', $request->email)->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            DB::table('password_resets')->where([
                'email' => $request->email
            ]);

            return redirect()->route('login')->with('info', "คุณได้ทำการรีเซ็ตรหัสผ่านเรียบร้อยแล้ว! คุณสามารถลงชื่อเข้าใช้ด้วยรหัสผ่านใหม่ของคุณ")
                             ->with('verifiedEmail', $request->email);
        }
    }
}
