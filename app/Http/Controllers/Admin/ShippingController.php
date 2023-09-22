<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create()
    {
        $shippingCharges = ShippingCharge::get();
        return view('admin.shipping.create', compact('shippingCharges'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district' => 'required|unique:shipping_charges,district',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()) {
            $shippingCharge = new ShippingCharge();
            $shippingCharge->district = $request->district;
            $shippingCharge->amount = $request->amount;
            $shippingCharge->save();

            Session::flash('success', 'Shipping added successfully ');
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
        $shippingCharge = ShippingCharge::find($id);
        // dd($shippingCharge);
        return view('admin.shipping.edit', compact('shippingCharge'));
    }

    public function update(Request $request, $id)
    {
        $shippingCharge = ShippingCharge::find($id);

        $validator = Validator::make($request->all(), [
            'district' => 'unique:shipping_charges,district,' . $shippingCharge->id . ',id',
            'amount' => 'numeric'
        ]);

        if ($validator->passes()) {

            if ($shippingCharge == null) {
                Session::flash('error', 'Shipping not found');
                return response()->json([
                    'status' => true
                ]);
            }

            $shippingCharge->district = $request->district;
            $shippingCharge->amount = $request->amount;
            $shippingCharge->save();

            Session::flash('success', 'Shipping updated successfully ');
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
        $shippingCharge = ShippingCharge::find($id);
        if ($shippingCharge == null) {
            Session::flash('error', 'Shipping not found');
            return response()->json([
                'status' => true
            ]);
        }

        $shippingCharge->delete();

        Session::flash('success', 'Shipping deleted successfully ');
        return response()->json([
            'status' => true
        ]);
    }
}
