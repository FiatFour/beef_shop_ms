<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request){
        $pages = Page::latest();

        if($request->keyword != ''){
            $pages = $pages->where('name', 'like', '%'.$request->keyword.'%');
        }

        $pages = $pages->paginate(10);
        return view('admin.pages.list', compact('pages'));
    }

    public function create(){
        return view('admin.pages.create');
    }

    public function store(Request $request, ){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page = new Page();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        $message = 'Page added successfully.';
        Session::flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);

    }

    public function edit($id){

        $page = Page::find($id);

        if($page == null){
            Session::flash('error', 'Page not found');
            return redirect()->route('admin.pages.index');
        }

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id){

        $page = Page::find($id);

        if($page == null){
            Session::flash('error', 'Page not found');
            return response()->json([
                'status' => true,
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        $message = 'Page updated successfully.';
        Session::flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);

    }

    public function destroy($id){
        $page = Page::find($id);

        if($page == null){
            Session::flash('error', 'Page not found');
            return response()->json([
                'status' => true,
            ]);
        }

        $page->delete();

        $message = 'Page deleted successfully.';
        Session::flash('success', $message);
        return response()->json([
            'status' => true,
            'message' => $message
        ]);
    }
}
