<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index(Request $request){
        $customers = Customer::latest();

        if(!empty($request->get('keyword'))){
            $customers = $customers->where('name', 'like', '%'.$request->get('keyword').'%');
            $customers = $customers->orWhere('email', 'like', '%'.$request->get('keyword').'%');

        }

        $customers = $customers->paginate(10);
        return view('admin.customer.list', compact('customers'));
    }

    public function create(Request $request){

        return view('admin.customer.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:5|max:30',
            'phone' => 'required|min:10|numeric',
            'gender' => 'required'
        ]);

        if($validator->passes()){
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->gender = $request->gender;
            $customer->password = Hash::make($request->password);
            $customer->email_verified = true;
            $customer->save();

            $message = 'Customer added successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
