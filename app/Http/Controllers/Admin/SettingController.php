<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('admin.change-password');
    }

    public function processChangePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:5|max:30',
            'confirm_password' => 'required|same:new_password'
        ]);

        $id = Auth::guard('employee')->user()->id;
        $admin = Employee::where('id', $id)->first();

        if ($validator->passes()) {

            if (!Hash::check($request->old_password, $admin->password)) {
                Session::flash('error', 'Your old password is incorrect, please try again.');
                return response()->json([
                    'status' => true,
                ]);
            }

            Employee::where('id', $id)->update([
                'password' => Hash::make($request->new_password)
            ]);

            Session::flash('success', 'You have successfully changed your password');
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
}
