<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
                                        ->latest('sub_categories.id')
                                        ->leftJoin('categories', 'categories.id',
                                                   'sub_categories.category_id');

        if(!empty($request->get('keyword'))){
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%'.$request->get('keyword').'%');
            $subCategories = $subCategories->orWhere('categories.name', 'like', '%'.$request->get('keyword').'%');
        }

        $subCategories = $subCategories->paginate(10);
        return view('admin.sub_category.list', compact('subCategories'));
    }

    public function create(){
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.create', compact('categories'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){
            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category_id;
            $subCategory->save();

            Session::flash('success', "Sub category created successfully.");
            return response([
                'status' => true,
                'message' => "Sub category created successfully."
            ]);
        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id){
        $subCategory = SubCategory::find($id);
        if(empty($subCategory)){
            Session::flash('error', "Record not found");
            return redirect()->route('admin.sub-categories.index');
        }
        $categories = Category::orderBy('name', 'ASC')->get();
        return view('admin.sub_category.edit', compact('categories', 'subCategory'));
    }

    public function update(Request $request, $id){
        $subCategory = SubCategory::find($id);

        if(empty($subCategory)){
            Session::flash('error', "Record not found");
            return response([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('admin.sub-categories.index');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'slug' => 'required|unique:sub_categories',
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category_id' => 'required',
            'status' => 'required',
        ]);

        if($validator->passes()){

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category_id;
            $subCategory->save();

            Session::flash('success', "Sub category updated successfully.");
            return response([
                'status' => true,
                'message' => "Sub category updated successfully."
            ]);
        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $subCategory = SubCategory::find($id);

        if(empty($subCategory)){
            Session::flash('error', "Record not found");
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $subCategory->delete();
        Session::flash('success', "Sub category deleted successfully.");
        return response([
            'status' => true,
            'message' => "Sub category deleted successfully."
        ]);
    }

}
