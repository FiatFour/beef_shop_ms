<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
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

        }else{
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


}
