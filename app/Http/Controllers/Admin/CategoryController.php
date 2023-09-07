<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
// use Intervention\Image\Facades\Image;
// use Intervention\Image\Image;
// use Image;
class CategoryController extends Controller
{
    public function index(Request $request){
        // $categories = Category::orderBy('created_at', 'DESC')->paginate(10);
        $categories = Category::latest();

        if(!empty($request->get('keyword'))){
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(10);
        return view('admin.category.list', compact('categories'));
    }

    public function create(){
        return view('admin.category.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories',
        ]);

        if ($validator->passes()) {
            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            // Save Image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id.'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                // Generate Image Thumbnail
                // $dPathThumb = public_path().'/uploads/category/thumb/'.$newImageName;
                // $img = Image::make($sPath); // Create an instance of Image
                // $img->resize(450, 600);
                // $img->save($dPathThumb);

                $category->image = $newImageName;
                $category->save();
            }

            Session::flash('success', 'Category added successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category added successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id){
        $category = Category::find($id);
        if(empty($category)){
            return redirect()->route('admin.categories.index');
        }

        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, $id){
        $category = Category::find($id);
        if(empty($category)){
            Session::flash('error', 'Category not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->save();

            $oldImage = $category->image;

            // Save Image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/category/'.$newImageName;
                File::copy($sPath, $dPath);

                $category->image = $newImageName;
                $category->save();

                // Delete Old Images Here
                File::delete(public_path('/uploads/category/').$oldImage);
            }

            Session::flash('success', 'Category updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Category updated successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $category = Category::find($id);

        if(empty($category)){
            Session::flash('error', 'Category not found');

            return response()->json([
                'status' => true,
                'message' => 'Category not found'
            ]);
            // return redirect()->route('admin.categories.index');
        }

        File::delete(public_path('/uploads/category/').$category->image);
        $category->delete();

        Session::flash('success', 'Category deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully'
        ]);
    }

}
