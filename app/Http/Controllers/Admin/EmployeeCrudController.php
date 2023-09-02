<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Employee;
use App\Models\verifyEmployee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class EmployeeCrudController extends Controller
{

    public function indexEmployee(){
        $employees = Employee::all();
        return view('admin.employeeCRUD.index', compact('employees'));
    }

    public function createEmployee(){
        return view('admin.employeeCRUD.create');
    }

    function storeEmployee(Request $request){
        //Validate Input
        $request->validate([
            'name' => 'required',
            'lname' => 'required',
            'department' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'tel' => 'required',
            'img' => 'required',
            'email' => 'required|email|unique:employees,email',
            'password' => 'required|min:5|max:30',
            'confirm_password' => 'required|min:5|max:30|same:password'
        ]);
        // Encryption image
        $img = $request->file('img');

        // Generate image name (random but not the same)
        $name_generate = hexdec(uniqid());

        // include File name extension (example: .PNG -> png)
        $img_ext = strtolower($img->getClientOriginalExtension());

        // Combine Generate image name + File name extension
        $img_name = $name_generate.'.'.$img_ext;

        // Upload and Record
        $upload_location = 'image/employees/';
        // Upload on my computer

        $full_path = $upload_location.$img_name;

        $employee = new Employee();
        $employee->emp_name = $request->name;
        $employee->emp_lname = $request->lname;
        $employee->emp_department = $request->department;
        $employee->emp_address = $request->address;
        $employee->emp_gender = $request->gender;
        $employee->emp_tel = $request->tel;
        $employee->emp_img = $full_path;
        $employee->email = $request->email;
        $employee->password = Hash::make($request->password);
        $save = $employee->save();

        $last_id = $employee->emp_id;

        $token = $last_id.hash('sha256', Str::random(120));
        $verifyURL = route('admin.verifyEmployee',['token'=>$token,'service'=>'Email_verification']);

        VerifyEmployee::create([
            'emp_id' => $last_id,
            'token' => $token,
        ]);
        $message = "Dear <b>".$request->name."</b>";
        $message.= "Thanks for signing up, we just need you to verify your email address to complete setting up your account.";

        $mail_data = [
            'recipient' => $request->email,
            'fromEmail' => $request->email,
            'fromName' => $request->admin_name,
            'subject' => "Email Verification",
            'body' => $message,
            'actionLink' => $verifyURL,
        ];


        Mail::send('sendLinkEmailForgot', $mail_data, function ($message) use ($mail_data) {
            $message->to($mail_data['recipient'])
                ->from($mail_data['fromEmail'], $mail_data['fromName'])
                ->subject($mail_data['subject']);
        });

        $img->move($upload_location, $img_name);
        return $save ? redirect()->back()->with('success', "You need to verify your account. We have sent you an activation link, please check your email.") : redirect()->back()->with('fail', "Something went wrong, failed to register");
    }

    public function editEmployee($emp_id){
        $employee = Employee::findOrFail($emp_id);
        return view('admin.employeeCRUD.edit', compact('employees'));
    }

    public function verifyEmployee(Request $request){
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
}
