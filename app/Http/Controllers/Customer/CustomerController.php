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
    function createCustomer(Request $request){
        //Validate Input
        $request->validate([
                'name' => 'required|max:255',
                'lname' => 'required|max:255',
                'gender' => 'required',
                'address' => 'required',
                'tel' => 'required|min:10|max:10',
                'email' => 'required|email|unique:customers,email',
                'password' => 'required|min:5|max:30',
                'confirm_password' => 'required|min:5|max:30|same:password'
            ],[
                'name.required' => "กรุณากรอกชื่อ",
                'lname.required' => "กรุณากรอกนามสกุล",
                'gender.required' => "กรุณาเลือกเพศ",
                'address.required' => "กรุณากรอกที่อยู่",
                'tel.required' => "กรุณาเบอร์โทร",
                'email.required' => "กรุณากรอกอีเมล์",
                'password.required' => "กรุณากรอกรหัสผ่าน",
                'password.min' => "รหัสผ่านที่กรอกต้องมากกว่า 5 ขึ้นไป",
                'password.max' => "รหัสผ่านที่กรอกต้องน้อยกว่า 30 ลงไป",
                'confirm_password.required' => "กรุณากรอกรหัสผ่าน",
                'confirm_password.min' => "รหัสผ่านที่กรอกต้องมากกว่า 5 ขึ้นไป",
                'confirm_password.max' => "รหัสผ่านที่กรอกต้องน้อยกว่า 30 ลงไป",
                'confirm_password.same' => "รหัสผ่านไม่ตรงกัน",
            ]
        );

        $customer = new Customer();
        $customer->cus_name = $request->name;
        $customer->cus_lname = $request->lname;
        $customer->cus_gender = $request->gender;
        $customer->cus_address = $request->address;
        $customer->cus_tel = $request->tel;
        $customer->email = $request->email;
        $customer->password = Hash::make($request->password);
        $save = $customer->save();
        $last_id = $customer->id;

        $token = $last_id.hash('sha256', Str::random(120));
        $verifyURL = route('customer.verifyCustomer',['token'=>$token,'service'=>'Email_verification']);

        VerifyCustomer::create([
            'cus_id' => $last_id,
            'token' => $token,
        ]);
        $message = "ถึงคุณ <b>".$request->name. " " .$request->lname. "</b>";
        $message.= ", คลิ้กลิ้งด้านล่างในการยืนยันอีเมล์เพื่อสำเร็จการสมัครบัญชีนี้";

        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => "Beef Shop",
            'subject' => "ยืนยันอีเมลล์",
            'body' => $message,
            'actionLink' => $verifyURL,
        ];


        Mail::send('sendLinkEmailForgot', $mail_data, function ($message) use ($mail_data) {
            $message->to($mail_data['recipient'])
                ->from($mail_data['fromEmail'], $mail_data['fromName'])
                ->subject($mail_data['subject']);
        });
        return $save ? redirect()->back()->with('success', "คุณต้องทำการยืนยันบัญชีผู้ใช้. เราได้ส่งลิ้งเพื่อยืนยันบัญชีนี้, โปรดเช็คข้อความทางอีเมล์ของคุณที่ได้ทำการสมัคร.") : redirect()->back()->with('fail', "เกิดความผิดพลาดบางอย่าง, ล้มเหลวในการสมัคร");
    }
    public function verifyCustomer(Request $request){
        $token = $request->token;
        $verifyCustomer = VerifyCustomer::where('token', $token)->first();

        if(!is_null($verifyCustomer)){
            $customer = Customer::find($verifyCustomer->cus_id);
            if(!$customer->email_verified){
                $customer->email_verified = 1;
                $customer->save();

                return redirect()->route('account.login')->with('info', "You're ")->with('verifiedEmail', $customer->email);
            }else{
                 return redirect()->route('account.login')->with('info','คุณได้ทำการยืนยันบัญชีก่อนหน้านี้แล้ว, ขณะนี้คุณสามารลงชื่อเข้าใช้ได้!')->with('verifiedEmail', $customer->email);
            }
        }
    }
}
