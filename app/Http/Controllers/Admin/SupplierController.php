<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function create()
    {
        $suppliers = Supplier::get();
        return view('admin.supplier.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|numeric|min:10',
            'address' => 'required',
        ]);

        if ($validator->passes()) {
            $supplier = new Supplier();
            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->save();

            Session::flash('success', 'Supplier added successfully ');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::find($id);
        return view('admin.supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::find($id);

        $validator = Validator::make($request->all(), [
            'name' => 'unique:suppliers,name,' . $supplier->id . ',id',
            'phone' => 'required|numeric|min:10',
            'address' => 'required',
        ]);

        if ($validator->passes()) {

            if ($supplier == null) {
                Session::flash('error', 'Supplier not found');
                return response()->json([
                    'status' => true
                ]);
            }

            $supplier->name = $request->name;
            $supplier->phone = $request->phone;
            $supplier->address = $request->address;
            $supplier->save();

            Session::flash('success', 'Supplier updated successfully ');
            return response()->json([
                'status' => true
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier == null) {
            Session::flash('error', 'Supplier not found');
            return response()->json([
                'status' => true
            ]);
        }

        $supplier->delete();

        Session::flash('success', 'Supplier deleted successfully ');
        return response()->json([
            'status' => true
        ]);
    }
}
