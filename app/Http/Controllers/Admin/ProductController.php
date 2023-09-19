<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CowGene;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
// use Image;
class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }

        $products = $products->paginate();
        return view('admin.products.list', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name', 'ASC')->get();
        $cow_genes = CowGene::orderBy('name', 'ASC')->get();
        // return view('admin.products.create', compact('products'));
        return view('admin.products.create', compact('categories', 'cow_genes'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product = new Product;
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->cow_gene_id = $request->cow_gene;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->save();

            // Save Gallery Pics
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {

                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name); // Get name of pic like 1693947083.jpg
                    $ext = last($extArray); // File name extension like jpg.gif.png etc

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext; // 4-1-12341234.jpg
                    $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                    $destPath = public_path() . '/uploads/product/' . $imageName;
                    File::copy($sourcePath, $destPath);
                    $productImage->image = $imageName;
                    $productImage->save();

                    // Generate Product Thumbnails

                    // Large Image
                    // $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                    // $destPath = public_path().'/uploads/product/large/'.$imageName;
                    // $image = Image::make($sourcePath);
                    // $image->resize(1400, null, function ($constraint){
                    //     $constraint->aspectRation();
                    // });
                    // image->save($destPath);

                    // Small Image
                    // $destPath = public_path().'/uploads/product/small/'.$imageName;
                    // $image = Image::make($sourcePath);
                    // $image->fit(300, 300);
                    // image->save($destPath);



                }
            }
            Session()->flash('success', "Product added successfully");
            return response()->json([
                'status' => true,
                'message' => "Product added successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            return redirect()->route('admin.products.index')->with('error', "Product not found");
        }
        // Fetch Product Images
        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $categories = Category::orderBy('name', 'ASC')->get();
        $cow_genes = CowGene::orderBy('name', 'ASC')->get();

        return view('admin.products.edit', compact('product', 'productImages', 'categories', 'subCategories', 'cow_genes'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->qty = $request->qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->cow_gene_id = $request->cow_gene;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->save();

            // Save Gallery Pics
            // if(!empty($request->image_array)){
            //     foreach($request->image_array as $temp_image_id){

            //         $tempImageInfo = TempImage::find($temp_image_id);
            //         $extArray = explode('.',$tempImageInfo->name); // Get name of pic like 1693947083.jpg
            //         $ext = last($extArray); // File name extension like jpg.gif.png etc

            //         $productImage = new ProductImage();
            //         $productImage->product_id = $product->id;
            //         $productImage->image = 'NULL';
            //         $productImage->save();

            //         $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext; // 4-1-12341234.jpg
            //         $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
            //         $destPath = public_path().'/uploads/product/'.$imageName;
            //         File::copy($sourcePath, $destPath);
            //         $productImage->image = $imageName;
            //         $productImage->save();
            //     }
            // }
            Session()->flash('success', "Product updated successfully");
            return response()->json([
                'status' => true,
                'message' => "Product updated successfully"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);

        if (empty($product)) {
            Session::flash('error', "Product not found");
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $productImages = ProductImage::where('product_id', $id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/' . $productImage->image));
            }

            ProductImage::where('product_id', $id)->delete();
        }
        $product->delete();

        Session::flash('success', "Product deleted successfully");
        return response()->json([
            'status' => true,
            'message' => "Product deleted successfully"
        ]);
    }
}
