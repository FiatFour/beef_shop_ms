<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class SupplierController extends Controller
{
     //
     public function index(){
        //? Include resource (Eloquent)
        // $suppliers=Supplier::all();
        //! Include resource (Query Builder)
        // $suppliers = DB::table('suppliers')->get();
        //? Include resource (Eloquent) But limit for show of table
        // $suppliers = Supplier::paginate(5);
        //! Include resource (Query Builder) But limit for show of table
        // $suppliers = DB::table('suppliers')->paginate(3);

        //? Join (Eloquent) with Supplier.php
        $suppliers = Supplier::paginate(5);
        //! Join (Query Builder)
        /*
        $suppliers = DB::table('suppliers')
            ->join('users', 'suppliers.user_id', 'users.id')
            ->select('suppliers.*', 'users.name')
            ->paginate(5);
        */

        $trashSuppliers = Supplier::onlyTrashed()->paginate(2);

        return view('dashboard.admin.supplier.index', compact('suppliers', 'trashSuppliers'));
    }

    public function store(Request $request){
        // dd($request->supplier_name); // Show Value (Debug)
        $request->validate([ // Check Value null or not
                'sup_name'=>'required|unique:suppliers|max:255',
                'sup_address' =>'required',
                'sup_tel' =>'required',
            ],
            [
                'supp_name.required'=>'กรุณาป้อนชื่อ Supplier',
                'supp_name.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
                'supp_name.unique'=>'ห้ามตั้งชื่อซ้ำ'
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

    public function edit($id){
        $supplier = Supplier::find($id); // get id from edit button and then find
        return view('dashboard.admin.supplier.edit',compact('supplier'));
    }

    public function update(Request $request, $sup_id){
        $request->validate([ // Check Value null or not
            'sup_name'=>'required|unique:suppliers|max:255'
            ],
            [
                'sup_name.required'=>'กรุณาป้อนชื่อ Supplier',
                'sup_name.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
                'sup_name.unique'=>'ห้ามตั้งชื่อซ้ำ'
            ]
        );

        $update = Supplier::find($sup_id)->update([
            'sup_name' => $request->sup_name,
            // 'user_id' => Auth::user()->id
        ]);

        return redirect()->route('admin.supplier')->with('success', 'Updated!');
    }

    public function softDelete($sup_id){
        // Supplier::findOrFail($sup_id)->delete();
        $delete = Supplier::find($sup_id)->delete();
        return redirect()->back()->with('success', 'Deleted!');
    }

    public function restore($sup_id){
        $restore = Supplier::withTrashed()->find($sup_id)->restore();
        return redirect()->back()->with('success', 'Restored!');
    }

    public function delete($sup_id){ // Permanently Delete
        Supplier::findOrFail($sup_id)->onlyTrashed()->forceDelete();
        return redirect()->back()->with('success', 'Permanently Delete!');
    }
}
