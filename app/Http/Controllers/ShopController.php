<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CowGene;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(){

        $categories = Category::orderBy('name', 'ASC')->with('sub_categories')->where('status',1)->get();
        $cowGenes = CowGene::orderBy('name', 'ASC')->where('status',1)->get();
        $products = Product::orderBy('id', 'DESC')->where('status',1)->get();

        return view('front.shop', compact('categories', 'cowGenes', 'products'));
    }
}
