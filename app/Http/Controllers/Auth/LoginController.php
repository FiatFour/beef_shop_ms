<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    function checkLogin(Request $request){
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
        ]);

        $creds = $request->only('email', 'password');
        if(Auth::guard('customer')->attempt($creds)){
            return redirect()->route('customer.home');
        }else if(Auth::guard('employee')->attempt($creds) && Auth::guard('employee')->user()->is_admin == 1){
            return redirect()->route('admin.home');
        }else if(Auth::guard('employee')->attempt($creds)){
            return redirect()->route('employee.home');
        }
        else{
            return redirect()->route('login')->with('fail', "Incorrect credentials");
        }

    }

    public function index(){
        if(Auth::guard('employee')->user()){
            if(Auth::guard('employee')->user()->is_admin==0){
                return redirect()->route('employee.home');
            }else{
                return redirect()->route('admin.home');
            }
        }
        if(Auth::guard('customer')->user()){
            return redirect()->route('customer.home');
        }

        return view('login');
    }

    public function logout(){
        if(Auth::guard('customer')){
            Auth::guard('customer')->logout();
        }
        if(Auth::guard('employee')){
            Auth::guard('employee')->logout();
        }
        return redirect()->route('login');
    }
}
