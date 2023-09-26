<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::latest('orders.created_at')->select('orders.*', 'customers.name', 'customers.email');
        $orders = $orders->leftJoin('customers','customers.id', 'orders.customer_id');

        if($request->get('keyword') != ''){
            $orders = $orders->where('customers.name', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('customers.email', 'like', '%'.$request->keyword.'%');
            $orders = $orders->orWhere('orders.id', 'like', '%'.$request->keyword.'%');
        }

        $orders = $orders->paginate(10);

        return view('admin.orders.list', compact('orders'));
    }

    public function detail($id){
        $order = Order::select('orders.*', 'shipping_charges.district as districtName')
                        ->where('orders.id', $id)
                        ->leftJoin('shipping_charges', 'shipping_charges.id', 'orders.shipping_charge_id')
                        ->first();

        $orderItems = OrderItem::where('order_id', $id)->get();

        return view('admin.orders.detail', compact('order', 'orderItems'));
    }
}
