<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName(); // get Temp Path

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;
        $productImage->image = 'NULL';
        $productImage->save();

        $imageName = $request->id . '-' . $productImage->id . '-' . time() . '.' . $ext; // 4-1-12341234.jpg
        $destPath = public_path() . '/uploads/product/' . $imageName;
        File::copy($sourcePath, $destPath);
        $productImage->image = $imageName;
        $productImage->save();

        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'imagePath' => asset('uploads/product/'.$productImage->image),
            'message' => "Image saved successfully"
        ]);
    }

    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

        if(empty($productImage)){
            return response()->json([
                'status' => false,
                'message' => "Image not found"
            ]);
        }
        // Delete images from folder
        File::delete(public_path('uploads/product/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => "Image deleted successfully"
        ]);
    }
}
