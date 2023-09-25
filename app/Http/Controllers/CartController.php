<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Carbon\Carbon;
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
    public function addToCart(Request $request)
    {
        $product = Product::with('product_images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        if (Cart::count() > 0) {
            // echo "Product already in cart";
            // Products found in cart
            // Check if this product already in the cart
            // Return as message that product already added in your cart
            // If product not found in the cart, then add product in cart

            // $cartContent = Cart::content();
            $productAlreadyExits = false;
            foreach (Cart::content() as $ProductItem) {
                if ($ProductItem->id == $product->id) {
                    $productAlreadyExits = true;
                }
            }

            if ($productAlreadyExits == false) {
                Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);

                $status = true;
                $message = '<b>' . $product->title . '</b> added in your cart successfully.';
                Session::flash('success', $message);
            } else {
                $status = false;
                $message = '<b>' . $product->title . '</b> already added in cart';
            }
        } else {
            // Cart is empty.
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = '<b>' . $product->title . '</b> added in your cart successfully.';
            Session::flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function cart()
    {
        $cartContents = Cart::content();
        return view('front.cart', compact('cartContents'));
    }

    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
        // Check qty available in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = '<b>' . $product->title . "</b> updated to Cart successfully";
                $status = true;
                Session::flash('success', $message);
            } else {
                $message = '<b>' . $product->title . "</b> Request qty('.$qty.') not available in stock.";
                $status = false;
                Session::flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = '<b>' . $product->title . "</b> updated to Cart  successfully";
            $status = true;
            Session::flash('success', $message);
        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {
        $itemInfo = Cart::get($request->rowId);

        if ($itemInfo == null) {
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

    public function checkout()
    {
        $discount = 0;

        // If cart is empty redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }

        // If use is not logged in then redirect to login page
        if (Auth::guard('customer')->check() == false) {
            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }

            return redirect()->route('account.login');
        }

        session()->forget('url.intended');
        $customerAddress = CustomerAddress::where('customer_id', Auth::guard('customer')->user()->id)->first();
        $shippingCharges = ShippingCharge::orderBy('district', 'ASC')->get();

        $subTotal = Cart::subtotal(2, '.', '');
        // Apply Discount here
        if (Session::has('code')) {
            $code = Session::get('code');
            if ($code->type = 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Calculate shipping here
        // $customerShipping = $customerAddress->shipping_charge_id;
        if ($customerAddress != '') {
            $shippingInfo = ShippingCharge::where('id', $customerAddress->shipping_charge_id)->first();
            // echo $shippingInfo->amount;
            $totalQty = 0;
            $totalShippingCharge = 0;
            $grandTotal = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            // $totalShippingCharge = $totalQty * $shippingInfo->amount;
            $totalShippingCharge = $shippingInfo->amount;
            $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout', [
            'customerAddress' => $customerAddress,
            'shippingCharges' => $shippingCharges,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
        ]);
    }

    public function processCheckout(Request $request)
    {
        //* Step -1 Apply Validation
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
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
        if ($request->payment_method == 'cod') {
            // $shipping = 0;
            $discountCodeId ='';
            $promoCode ='';
            $discount = 0;
            $subTotal = Cart::subtotal(2, '.', '');
            // $grandTotal = $subTotal + $shipping;

            // Apply Discount here
            if (Session::has('code')) {
                $code = Session::get('code');
                if ($code->type = 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }
            // Calculate Shipping
            $shippingInfo = ShippingCharge::where('id', $request->district)->first();
            // dd($shippingInfo->district);
            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            if ($shippingInfo != null) {
                // $shippingCharge = $totalQty*$shippingInfo->amount;

                $shippingCharge = $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;
            } else {
                // $shippingInfo = ShippingCharge::where('id', $request->shipping_charge_id)->first();

                // $shippingCharge = $totalQty*$shippingInfo->amount;
                // $grandTotal = ($subTotal - $discount) + $shippingCharge;
            }

            $order = new Order;
            $order->subtotal = $subTotal;
            $order->shipping = $shippingCharge;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->discount_coupon_id = $discountCodeId;
            $order->coupon_code = $promoCode;
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
            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem;
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();
            }

            Session::flash('success', "You have successfully placed your order.");
            Session::forget('code');
            Cart::destroy();
            return response()->json([
                'message' => 'Order saved successfully',
                'orderId' => $order->id,
                'status' => true
            ]);
        } else {
        }
    }

    public function thankyou($id)
    {
        return view('front.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(2, '.', '');
        $discount = 0;
        $discountString = '';
        // Apply Discount here
        if (Session::has('code')) {
            $code = Session::get('code');
            if ($code->type = 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }

            $discountString = '<div class="mt-4" id="discount-response">
                                    <b>' . Session::get('code')->code . '</b>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                               </div>';
        }


        if ($request->shipping_charge_id > 0) {

            $shippingInfo = ShippingCharge::where('id', $request->shipping_charge_id)->first();

            $totalQty = 0;
            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {

                // $shippingCharge = $totalQty*$shippingInfo->amount;
                $shippingCharge = $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 2),
                    'discount' => number_format($discount, 2),
                    'discountString' => $discountString,
                    'shippingCharge' => number_format($shippingCharge, 2)
                ]);
            } else {
                // $shippingInfo = ShippingCharge::where('id', $request->shipping_charge_id)->first();

                // $shippingCharge = $totalQty*$shippingInfo->amount;
                // $grandTotal = ($subTotal - $discount) + $shippingCharge;

                // return response()->json([
                //     'status' => true,
                //     'discount' => number_format($discount, 2),
                //     'discountString' => $discountString,
                //     'grandTotal' => number_format($grandTotal, 2),
                //     'shippingCharge' => number_format($shippingCharge, 2),
                // ]);
            }
        } else {
            return response()->json([
                'status' => true,
                'grandTotal' => number_format($subTotal - $discount, 2),
                'discount' => number_format($discount, 2),
                'discountString' => $discountString,
                'shippingCharge' => number_format(0, 2),

            ]);
        }
    }

    public function applyDiscount(Request $request)
    {
        // dd($request->code);
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid discount coupon',

            ]);
        }

        // Check if coupon start date is valid or not
        $now = Carbon::now();
        if ($code->starts_at != '') {
            $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);

            if ($now->lt($startDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }

        // Check if coupon end date is valid or not
        $now = Carbon::now();
        if ($code->expires_at != '') {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);

            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }

        // Max Uses Check
        if($code->max_uses > 0){
            $couponUsed = Order::where('discount_coupon_id', $code->id)->count();

            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon',
                ]);
            }
        }

        // Max Uses User Check
        if($code->max_uses_user > 0){
            $couponUsedByUser = Order::where(['discount_coupon_id' => $code->id, 'customer_id' => Auth::guard('customer')->user()->id])->count();

            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon.',
                ]);
            }
        }

        // Min amount condition Check
        $subTotal = Cart::subtotal(2, '.', '');
        if($code->min_amount > 0){
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'message' => 'You min amount must be ฿'.$code->min_amount.'.',
                ]);
            }
        }

        Session::put('code', $code);
        return $this->getOrderSummery($request);
    }

    public function removeDiscount(Request $request)
    {
        Session::forget('code');
        return $this->getOrderSummery($request);
    }
}
