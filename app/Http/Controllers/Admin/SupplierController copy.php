<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
     //
     public function indexSupplier(){
        //? Join (Eloquent) with Supplier.php
        $suppliers = Supplier::paginate(5);
        return view('admin.supplier.index', compact('suppliers'));
    }

    public function storeSupplier(Request $request){
        // dd($request->supplier_name); // Show Value (Debug)
        $request->validate([ // Check Value null or not
                'sup_name'=>'required|unique:suppliers,sup_name|max:255',
                'sup_address' =>'required',
                'sup_tel' =>'required',
            ],
            [
                'sup_name.required'=>'กรุณาป้อนชื่อ Supplier',
                'sup_name.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
                'sup_name.unique'=>'ห้ามตั้งชื่อซ้ำ'
            ]
        );

        //? Record resource (Eloquent)
        $supplier = new Supplier;
        $supplier->sup_name = $request->sup_name;
        $supplier->sup_address = $request->sup_address;
        $supplier->sup_tel = $request->sup_tel;
        $supplier->save();
        return redirect()->back()->with('success', 'Recorded!');

        //! Record resource (Query Builder)
        /*
        $data = array();
        $data['supplier_name'] = $request->supplier_name;
        // $data['user_id'] = Auth::user()->id;

        DB::table('suppliers')->insert($data);
        */
        return redirect()->back()->with('success', 'Recorded!');
    }

    public function editSupplier($id){
        $supplier = Supplier::find($id); // get id from edit button and then find
        return view('admin.supplier.edit',compact('supplier'));
    }

    public function updateSupplier(Request $request, $id){
        $request->validate([ // Check Value null or not
            'sup_name'=>'required|unique:suppliers,sup_name|max:255'
            ],
            [
                'sup_name.required'=>'กรุณาป้อนชื่อ Supplier',
                'sup_name.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
                'sup_name.unique'=>'ห้ามตั้งชื่อซ้ำ'
            ]
        );

        $update = Supplier::find($id)->update([
            'sup_name' => $request->sup_name,
            // 'user_id' => Auth::user()->id
        ]);

        return redirect()->route('admin.supplier')->with('success', 'Updated!');
    }
    public function deleteSupplier($id){ // Permanently Delete
        Supplier::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Deleted!');
    }
}
