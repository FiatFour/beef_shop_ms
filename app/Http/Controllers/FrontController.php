<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\Employee;
use App\Models\Page;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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

    public function sendContactEmail(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:10',

        ]);

        if($validator->passes()){
            // Send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have received a contact email'
            ];

            $admin = Employee::where('name', 'admin')->first();
            Mail::to($admin->email)->send(new ContactEmail($mailData));

            Session::flash('success', 'Thanks for contacting us, we will get back to you soon.');
            return response()->json([
                'status' => true,
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


}
