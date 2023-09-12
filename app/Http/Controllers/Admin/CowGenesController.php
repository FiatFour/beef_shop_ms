<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CowGene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CowGenesController extends Controller
{
    public function index(Request $request){
        $cow_genes = CowGene::latest('id');

        if($request->get('keyword')){
            $cow_genes = $cow_genes->where('name','like','%'.$request->keyword.'%');
        }
        $cow_genes = $cow_genes->paginate(10);

        return view('admin.cow_gene.list', compact('cow_genes'));
    }

    public function create(){
        return view('admin.cow_gene.create');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:cow_genes'
        ]);

        if($validator->passes()){
            $cow_gene = new CowGene();
            $cow_gene->name = $request->name;
            $cow_gene->slug = $request->slug;
            $cow_gene->status = $request->status;
            $cow_gene->save();

            Session::flash('success', "Cow gene added successfully.");
            return response()->json([
                'status' => true,
                'message' => "Cow gene added successfully."
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit(Request $request, $id){
        $cow_gene = CowGene::find($id);

        if(empty($cow_gene)){
            Session::flash('error', "Record not found.");
            return redirect()->route('admin.cow-genes.index');
        }

        return view('admin.cow_gene.edit', compact('cow_gene'));
    }

    public function update(Request $request, $id){

        $cow_gene = CowGene::find($id);

        if(empty($cow_gene)){
            Session::flash('error', "Record not found.");

            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:cow_genes,slug,'.$cow_gene->id.',id'
        ]);

        if($validator->passes()){

            $cow_gene->name = $request->name;
            $cow_gene->slug = $request->slug;
            $cow_gene->status = $request->status;
            $cow_gene->save();

            Session::flash('success', "Cow gene updated successfully.");
            return response()->json([
                'status' => true,
                'message' => "Cow gene added successfully."
            ]);
        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $cow_gene = CowGene::find($id);

        if(empty($cow_gene)){
            Session::flash('error', "Record not found");
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $cow_gene->delete();
        Session::flash('success', "Cow gene deleted successfully.");
        return response([
            'status' => true,
            'message' => "Cow gene deleted successfully."
        ]);
    }
}
