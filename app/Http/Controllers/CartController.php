<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
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

        session()->forget('url.intended');
        return view('front.checkout');
    }
}
