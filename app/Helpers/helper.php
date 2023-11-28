<?php

use App\Mail\OrderEmail;
use App\Models\Category;
use App\Models\Cow;
use App\Models\CowGene;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Page;
use App\Models\Product;
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

    function getProduct($productId){
        return Product::where('id', $productId)->first();
    }
    function orderEmail($orderId, $userType = "customer"){
        $order = Order::where('id', $orderId)->with('items')->first();

        if($userType == 'customer'){
            $subject = "Thanks for your order";
            $email = $order->email;
        }else{
            $subject = "You have received an order";
            $email = env('ADMIN_EMAIL');
        }

        $mailData = [
            'subject' => $subject,
            'order' => $order,
            'userType' => $userType
        ];

        Mail::to($email)->send(new OrderEmail($mailData));
    }

    function getDistrictInfo($shipping_charge_id){
        return ShippingCharge::where('id', $shipping_charge_id)->first();
    }

    function staticPages(){
        $pages = Page::orderBy('name', 'ASC')->get();
        return $pages;
    }

    // function getCows($cowId){
    //     return Cow::orderBy('id', 'DESC')
    //             ->with('orderDetails')
    //             ->orderBy('created_at', 'DESC')
    //             ->where('id',$cowId)
    //             ->get();
    // }

    // function getOrderCows($orderCowId){
    //     return Cow::orderBy('id', 'DESC')
    //             ->with('orderDetails')
    //             ->orderBy('created_at', 'DESC')
    //             ->where('id',$orderCowId)
    //             ->get();
    // }
    // function getOrderDetails($orderCowId){
    //     return OrderDetail::orderBy('cow_id', 'DESC')
    //             ->with('cows')
    //             ->orderBy('id', 'DESC')
    //             ->where('order_cow_id',$orderCowId)
    //             ->where('id', 'order_details.cow_id')
    //             ->get();
    // }

    function getDetails($orderCowId){
        return OrderDetail::find('order_detail_id', $orderCowId)->get();
    }

    function getCowGeneName($cowGeneId){
        return CowGene::where('id',$cowGeneId)->first();
    }


?>
