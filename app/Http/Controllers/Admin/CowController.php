<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Cow;
use App\Models\CowGene;
use App\Models\Supplier;
use App\Models\TempImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class CowController extends Controller
{
    public function index(Request $request)
    {
        // $cows = Cow::latest();

        $cows = Cow::select('cows.*', 'cow_genes.name as cowGeneName')
        ->latest('cows.id')
        ->leftJoin('cow_genes', 'cow_genes.id',
                   'cows.cow_gene_id');

        if (!empty($request->get('keyword'))) {
            $cows = $cows->where('id', 'like', '%' . $request->get('keyword') . '%');
            // $cows = $cows->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $cows = $cows->paginate(10);
        return view('admin.cows.list', compact('cows'));
    }

    public function create()
    {
        $cowGenes = CowGene::orderBy('name', 'ASC')->get();
        return view('admin.cows.create', compact('cowGenes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cow_gene_id' => 'required',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'gender' => 'required',
            'birth' => 'required',
        ]);

        if ($validator->passes()) {
            $cow = new Cow();
            $cow->cow_gene_id = $request->cow_gene_id;
            $cow->weight = $request->weight;
            $cow->height = $request->height;
            $cow->gender = $request->gender;
            $cow->birth = $request->birth;
            $cow->save();

            if (!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $cow->id . '.' . $ext;
                $sourcePath = public_path() . '/temp/' . $tempImage->name;
                $destPath = public_path() . '/uploads/cow/' . $newImageName;
                File::copy($sourcePath, $destPath);

                $cow->image = $newImageName;
                $cow->save();
            }

            $message = 'Cow added successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
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
        $cow = Cow::find($id);

        if ($cow == null) {
            $message = 'cow not found.';
            Session::flash('error', $message);

            return redirect()->route('admin.cow.index');
        }

        $cowGenes = CowGene::orderBy('name', 'ASC')->get();
        return view('admin.cows.edit', compact('cow', 'cowGenes'));
    }

    public function update(Request $request, $id)
    {
        $cow = Cow::find($id);
        if(empty($cow)){
            Session::flash('error', 'Cow not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Cow not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'cow_gene_id' => 'required',
            'weight' => 'required|numeric',
            'height' => 'required|numeric',
            'gender' => 'required',
            'birth' => 'required',
        ]);

        if ($validator->passes()) {
            $cow->cow_gene_id = $request->cow_gene_id;
            $cow->weight = $request->weight;
            $cow->height = $request->height;
            $cow->gender = $request->gender;
            $cow->birth = $request->birth;
            $cow->last_weight = $request->last_weight;
            $cow->last_height = $request->last_height;
            $cow->dissect_date = $request->dissect_date;
            $cow->dissect_total_kg = $request->dissect_total_kg;
            $cow->save();

            $oldImage = $cow->image;

            // Save Image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $cow->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/cow/'.$newImageName;
                File::copy($sPath, $dPath);

                $cow->image = $newImageName;
                $cow->save();

                // Delete Old Images Here
                File::delete(public_path('/uploads/cow/').$oldImage);
            }

            Session::flash('success', 'Cow updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Cow updated successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function destroy(Request $request, $id){
        $cow = Cow::find($id);

        if(empty($cow)){
            Session::flash('error', 'Cow not found');
            return response()->json([
                'status' => true,
                'message' => 'Cow not found'
            ]);
        }

        File::delete(public_path('/uploads/cow/').$cow->image);
        $cow->delete();

        Session::flash('success', 'Cow deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Cow deleted successfully'
        ]);
    }
}
