<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FrontController extends Controller
{
    public function index()
    {

        $featuredProducts = Product::where('is_featured', 'Yes')
            ->orderBy('id', 'DESC')
            ->take(8)
            ->where('status', 1)
            ->get();

        $latestProducts = Product::orderBy('id', 'DESC')
            ->where('status', 1)
            ->take(8)
            ->get();

        return view('front.home', compact('featuredProducts', 'latestProducts'));
    }

    public function addToWishlist(Request $request)
    {
        if (Auth::guard('customer')->check() == false) {

            session(['url.intended' => url()->previous()]);
            return response()->json([
                'status' => false,

            ]);
        }

        $product = Product::where('id', $request->id)->first();
        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger">Product not found.</div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'customer_id' => Auth::guard('customer')->user()->id,
                'product_id' => $request->id
            ],
            [
                'customer_id' => Auth::guard('customer')->user()->id,
                'product_id' => $request->id
            ]
        );

        // $wishlist = new Wishlist();
        // $wishlist->customer_id = Auth::guard('customer')->user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><b>"' . $product->title . '"</b> Product added in your wishlist</div>'
        ]);
    }

    public function page($slug){
        $page = Page::where('slug', $slug)->first();

        if($page == null){
            abort(404);
        }

        return view('front.page', compact('page'));
    }
}
