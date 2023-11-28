<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordEmail;
use App\Models\Customer;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\VerifyCustomer;
use App\Models\Wishlist;
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
            'phone' => 'required|min:10|numeric',
            // 'gender' => 'required'
        ], [
            'confirm_password.required' => 'The confirm password field is required.',
        ]);

        if ($validator->passes()) {
            $customer = new Customer;
            $customer->name = $request->name;
            $customer->phone = $request->phone;
            // // $customer->gender = $request->gender;
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
        $customerId = Auth::guard('customer')->user()->id;
        $customer = Customer::where('id', $customerId)->first();
        $customerAddress = CustomerAddress::where('customer_id', $customerId)->first();
        $shippingCharges = ShippingCharge::orderBy('district', 'ASC')->get();

        return view('front.account.profile', compact('customer', 'shippingCharges', 'customerAddress'));
    }

    public function updateProfile(Request $request)
    {
        $customerId = Auth::guard('customer')->user()->id;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $customerId . ',id',
            'phone' => 'required|min:10|numeric'
        ]);

        if ($validator->passes()) {
            $customer = Customer::find($customerId);
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->save();

            Session::flash('success', 'Profile Updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Profile Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateAddress(Request $request)
    {
        $customerId = Auth::guard('customer')->user()->id;

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'district' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required|min:10|numeric',
        ]);

        if ($validator->passes()) {
            CustomerAddress::updateOrCreate(
                ['customer_id' => $customerId],
                [
                    'customer_id' => $customerId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'shipping_charge_id' => $request->district,
                    'address' => $request->address,
                    'apartment' => $request->apartment,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                ]
            );

            Session::flash('success', 'Address Updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Address Updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('account.login')->with('success', "You successfully logged out!");
    }

    public function orders()
    {
        $customer = Auth::guard('customer')->user();
        $orders = Order::where('customer_id', $customer->id)->orderBy('created_at', 'DESC')->get();

        return view('front.account.order', compact('orders'));
    }

    public function orderDetail($id)
    {
        $customer = Auth::guard('customer')->user();
        $order = Order::where('customer_id', $customer->id)->where('id', $id)->first();
        $orderItems = OrderItem::where('order_id', $id)->get();
        // $orderItemsCount = OrderItem::where('order_id', $id)->count();
        return view('front.account.order-detail', compact('order', 'orderItems'));
    }

    public function wishlist()
    {
        $wishlists = Wishlist::where('customer_id', Auth::guard('customer')->user()->id)->with('product')->get();

        return view('front.account.wishlist', compact('wishlists'));
    }

    public function removeProductFromWishList(Request $request)
    {
        $wishlist = Wishlist::where('customer_id', Auth::guard('customer')->user()->id)->where('product_id', $request->id)->first();

        if ($wishlist == null) {
            Session::flash('error', 'Product already removed.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            Wishlist::where('customer_id', Auth::guard('customer')->user()->id)->where('product_id', $request->id)->delete();
            Session::flash('success', 'Product removed successfully.');
            return response()->json([
                'status' => true,
            ]);
        }
    }

    public function showChangePasswordForm()
    {
        return view('front.account.change-password');
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:new_password'
        ]);

        if ($validator->passes()) {
            $customer = Customer::select('id', 'password')->where('id', Auth::guard('customer')->user()->id)->first();

            if (!Hash::check($request->old_password, $customer->password)) {
                Session::flash('error', 'Your old password is incorrect, please try again.');
                return response()->json([
                    'status' => true,

                ]);
            }

            Customer::where('id', $customer->id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Session::flash('success', 'You have successfully changed your password.');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function forgotPassword()
    {
        return view('front.account.forgot-password');
    }

    public function processForgetPassword(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email'
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        $token = Str::random(60);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        // Send Email Here

        $customer = Customer::where('email', $request->email)->first();
        $formData = [
            'token' => $token,
            'customer' => $customer,
            'mailSubject' => 'You have requested to reset your password'

        ];
        Mail::to($request->email)->send(new ResetPasswordEmail($formData));
        return redirect()->route('front.forgotPassword')->with('success', 'Please check your inbox to reset your password.');
    }

    public function resetPassword($token)
    {
        $tokenExists = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenExists == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request');
        }

        return view('front.account.reset-password', [
            'token' => $token
        ]);
    }

    public function processResetPassword(Request $request)
    {
        $token = $request->token;
        $tokenObj = DB::table('password_reset_tokens')->where('token', $token)->first();

        if ($tokenObj == null) {
            return redirect()->route('front.forgotPassword')->with('error', 'Invalid request');
        }

        $customer = Customer::where('email', $tokenObj->email)->first();

        $validator  = Validator::make($request->all(), [
            'new_password' => 'required|min:5',
            'confirm_password' => 'required|min:5|same:new_password'
        ]);

        if ($validator->fails()) {
            return redirect()->route('front.resetPassword', $token)->withInput()->withErrors($validator);
        }

        Customer::where('id', $customer->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        DB::table('password_reset_tokens')->where('email', $customer->email)->delete();
        return redirect()->route('account.login')->with('success', 'You have successfully updated your password.');
    }
}
