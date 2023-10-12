<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::latest('orders.created_at')->select('orders.*', 'customers.name', 'customers.email', 'employees.name as employeeName');
        $orders = $orders->leftJoin('customers','customers.id', 'orders.customer_id');
        $orders = $orders->leftJoin('employees','employees.id', 'orders.employee_id');

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
        $employees = Employee::orderBy('name', 'ASC')->get();

        return view('admin.orders.detail', compact('order', 'orderItems', 'employees'));
    }

    public function changeOrderStatus(Request $request, $id){
        $order = Order::find($id);
        $order->status = $request->status;
        $order->payment_status = $request->payment_status;
        $order->shipped_date = $request->shipped_date;
        $order->employee_id = $request->employee_id;
        $order->save();

        $message = "Order status updated successfully";
        Session::flash('success' , $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }

    public function sendInvoiceEmail(Request $request, $orderId){
        orderEmail($orderId, $request->userType);

        $message = "Order email sent successfully";
        Session::flash('success' , $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
