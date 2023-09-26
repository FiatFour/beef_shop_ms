<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\ShippingCharge;
use Illuminate\Support\Facades\Mail;
use PhpParser\Node\Expr\AssignOp\ShiftLeft;

    function getCategories(){
        return Category::orderBy('name', 'ASC')
                ->with('sub_categories')
                ->orderBy('id', 'DESC')
                ->where('status',1)
                ->where('show_home','Yes')
                ->get();
    }

    function getProductImage($productId){
        return ProductImage::where('product_id', $productId)->first();
    }

    function orderEmail($orderId){
        $order = Order::where('id', $orderId)->with('items')->first();
        $mailData = [
            'subject' => 'Thanks for your order',
            'order' => $order
        ];
        Mail::to($order->email)->send(new OrderEmail($mailData));
    }

    function getDistrictInfo($shipping_charge_id){
        return ShippingCharge::where('id', $shipping_charge_id)->first();
    }
?>
