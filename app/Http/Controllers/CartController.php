<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

// use Cart;
// use Gloudemans\Shoppingcart\Cart;
// use Gloudemans\Shoppingcart\Facades\Cart;
// use Gloudemans\Shoppingcart\Facades\Cart;
class CartController extends Controller
{
    public function addToCart(Request $request){
        $product = Product::with('product_images')->find($request->id);

        if($product == null){
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        if(Cart::count() > 0){
            // echo "Product already in cart";
            // Products found in cart
            // Check if this product already in the cart
            // Return as message that product already added in your cart
            // If product not found in the cart, then add product in cart

            // $cartContent = Cart::content();
            $productAlreadyExits = false;
            foreach(Cart::content() as $ProductItem){
                if($ProductItem->id == $product->id){
                    $productAlreadyExits = true;
                }
            }

            if($productAlreadyExits == false){
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message = '<b>'.$product->title.'</b> added in your cart successfully.';
                Session::flash('success', $message);
            }else{
                $status = false;
                $message = '<b>'.$product->title.'</b> already added in cart';
            }
        }else{
            // Cart is empty.
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<b>'.$product->title.'</b> added in your cart successfully.';
            Session::flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart(){
        $cartContents = Cart::content();
        return view('front.cart', compact('cartContents'));
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
        // Check qty available in stock
        if($product->track_qty == 'Yes'){
            if($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = '<b>'.$product->title."</b> updated to Cart successfully";
                $status = true;
                Session::flash('success', $message);
            }else{
                $message = '<b>'.$product->title."</b> Request qty('.$qty.') not available in stock.";
                $status = false;
                Session::flash('error', $message);
            }
        }else{
            Cart::update($rowId, $qty);
            $message = '<b>'.$product->title."</b> updated to Cart  successfully";
            $status = true;
            Session::flash('success', $message);
        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request){
        $itemInfo = Cart::get($request->rowId);

        if($itemInfo == null){
            $errorMessage = 'Item not found in cart';
            Session::flash('error', $errorMessage);

            return response()->json([
                'status' => false,
                'message' => $errorMessage
            ]);
        }
        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully.';
        Session::flash('success', $message);
        return response()->json([
            'status' => false,
            'message' => $message
        ]);
    }

    public function checkout(){

        // If cart is empty redirect to cart page
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        // If use is not logged in then redirect to login page
        if(Auth::guard('customer')->check() == false){
            if(!session()->has('url.intended')){
                    session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('customer_id', Auth::guard('customer')->user()->id)->first();
        $shippingCharges = ShippingCharge::orderBy('district','ASC')->get();

        session()->forget('url.intended');
        return view('front.checkout', compact('customerAddress', 'shippingCharges'));
    }

    public function processCheckout(Request $request){
        //* Step -1 Apply Validation
        $validator = Validator::make($request->all(),[
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'district' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Please fix the errors',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        //? Step -2 Save user address
        //  $customerAddress = CustomerAddress::find();
         $customer = Auth::guard('customer')->user();
         CustomerAddress::updateOrCreate(
            ['customer_id' => $customer->id],
            [
                'customer_id' => $customer->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'shipping_charge_id' => $request->district,
                'address' => $request->address,
                'apartment' => $request->apartment,
                'city' => $request->city,
                'state' => $request->state,
                'zip' => $request->zip,
            ]
         );

        //! Step -3 store data in orders table
        if($request->payment_method == 'cod'){
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            $grandTotal = $subTotal + $shipping ;

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->customer_id = $customer->id;

            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->apartment = $request->apartment;
            $order->state = $request->state;
            $order->city = $request->city;
            $order->zip = $request->zip;
            $order->notes = $request->order_notes;
            $order->shipping_charge_id = $request->district;
            $order->save();

            //* Step -4 store order items in order items table
            foreach(Cart::content() as $item){
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price*$item->qty;
                $orderItem->save();
            }

            Session::flash('success', "You have successfully placed your order.");

            Cart::destroy();
            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true
            ]);
        }else{

        }
    }

    public function thankyou($id){
        return view('front.thanks',[
            'id' => $id
        ]);
    }

}
