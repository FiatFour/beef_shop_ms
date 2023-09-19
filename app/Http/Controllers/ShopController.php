<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CowGene;
use App\Models\Product;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $cowGenesArray = [];

        $categories = Category::orderBy('name', 'ASC')->with('sub_categories')->where('status', 1)->get();
        $cowGenes = CowGene::orderBy('name', 'ASC')->where('status', 1)->get();
        $products = Product::where('status', 1);

        // Apply Filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            $products = $products->where('category_id', $category->id);
            $categorySelected = $category->id;
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            $products = $products->where('sub_category_id', $subCategory->id);
            $subCategorySelected = $subCategory->id;
        }

        if (!empty($request->get('cow-gene'))) {
            $cowGenesArray = explode(',', $request->get('cow-gene'));
            $products = $products->whereIn('cow_gene_id', $cowGenesArray);
        }

        if ($request->get('price_max') != '' && $request->get('price_min') != '') {
            if ($request->get('price_max') == 1000) {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), 1000000]);
            } else {
                $products = $products->whereBetween('price', [intval($request->get('price_min')), intval($request->get('price_max'))]);
            }
        }

        if ($request->get('sort') != '') {
            if ($request->get('sort') == 'latest') {
                $products = $products->orderBy('id', 'DESC'); // or can change to 'id' -> 'created_at
            } else if ($request->get('sort') == 'price_asc') {
                $products = $products->orderBy('price', 'ASC');
            } else {
                $products = $products->orderBy('price', 'DESC');
            }
        } else {
            $products = $products->orderBy('id', 'DESC'); // or can change to 'id' -> 'created_at
        }

        $products = $products->paginate(6);
        $priceMin = intval($request->get('price_min'));
        $priceMax = (intval($request->get('price_max')) == 0) ? 1000 : $request->get('price_max');
        $sort = $request->get('sort');

        return view('front.shop', compact(
            'categories',
            'cowGenes',
            'products',
            'categorySelected',
            'subCategorySelected',
            'cowGenesArray',
            'priceMin',
            'priceMax',
            'sort'
        ));
    }

    public function product($slug){
        $product = Product::where('slug', $slug)->with('product_images')->first();
        if($product == null){
            abort(404);
        }

        $relatedProducts = [];
        // Fetch Related Products
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);
            $relatedProducts = Product::whereIn('id', $productArray)->get();
        }

        return view('front.product', compact('product', 'relatedProducts'));
    }

}
