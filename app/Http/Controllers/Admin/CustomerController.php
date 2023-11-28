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
    public function index(Request $request)
    {
        $customers = Customer::latest();

        if (!empty($request->get('keyword'))) {
            $customers = $customers->where('name', 'like', '%' . $request->get('keyword') . '%');
            $customers = $customers->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $customers = $customers->paginate(10);
        return view('admin.customer.list', compact('customers'));
    }

    public function create(Request $request)
    {

        return view('admin.customer.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers',
            'password' => 'required|min:5|max:30',
            'phone' => 'required|min:10|numeric',
        ]);

        if ($validator->passes()) {
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->status = $request->status;
            $customer->password = Hash::make($request->password);
            $customer->email_verified = true;
            $customer->save();

            $message = 'Customer added successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {

        $customer = Customer::find($id);

        if ($customer == null) {
            $message = 'Customer not found.';
            Session::flash('error', $message);

            return redirect()->route('admin.customers.index');
        }

        return view('admin.customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {

        $customer = Customer::find($id);

        if ($customer == null) {
            $message = 'Customer not found.';
            Session::flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:customers,email,' . $id . ',id',
            'phone' => 'required|min:10|numeric',
        ]);

        if ($validator->passes()) {
            $customer->name = $request->name;
            $customer->email = $request->email;
            $customer->phone = $request->phone;
            $customer->status = $request->status;

            if ($request->password != '') {
                $customer->password = Hash::make($request->password);
            }

            $customer->email_verified = true;
            $customer->save();

            $message = 'Customer updated successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {

        $customer = Customer::find($id);

        if ($customer == null) {
            $message = 'Customer not found.';
            Session::flash('error', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        }

        $customer->delete();
        $message = 'Customer deleted successfully.';
        Session::flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
