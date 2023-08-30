<?php

namespace App\Http\Controllers;

use App\Models\Cow;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
class CowController extends Controller
{
    public function index(){
        $cows = Cow::all();
        return view('dashboard.admin.cow.index', compact('cows'));
    }

    public function create(){
        $suppliers = Supplier::all();

        return view('dashboard.admin.cow.create', compact('suppliers'));
    }

    public function store(Request $request){

        $request->validate([ // Check Value null or not
            'cow_gene'=>'required|unique:cows|max:255',
            'cow_img'=>'required|mimes:jpg,jpeg,png'
        ],
        [
            'cow_gene.required'=>'กรุณาป้อนชื่อบริการ',
            'cow_gene.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
            'cow_gene.unique'=>'ห้ามตั้งชื่อซ้ำ',
            'cow_img.required'=>'กรุณาแนบรูปภาพ',
            ]
        );
        // Encryption image
        $cow_img = $request->file('cow_img');

        // Generate image name (random but not the same)
        $name_generate = hexdec(uniqid());

        // include File name extension (example: .PNG -> png)
        $img_ext = strtolower($cow_img->getClientOriginalExtension());

        // Combine Generate image name + File name extension
        $img_name = $name_generate.'.'.$img_ext;

        // Upload and Record
        $upload_location = 'image/cows/';
        $full_path = $upload_location.$img_name;

        $supplier = Supplier::findOrFail($request->sup_id);
        $supplier->cows()->create([
            'cow_gene' => $request->cow_gene,
            'cow_img' =>  $full_path,
            'cow_birth' =>  $request->cow_birth,
            'created_at' => Carbon::now(),
        ]);

        // $cow = new Cow;
        // $cow->cow_gene = $request->cow_gene;
        // $cow->cow_img = $full_path;
        // $cow->cow_birth = $request->cow_birth;
        // $cow->created_at = Carbon::now();
        // $supplier->cows()->save($cow);


        // Upload on my computer
        $cow_img->move($upload_location, $img_name);

        return redirect()->route('admin.cow')->with('message', 'Cow Created');
    }

    public function edit($cow_id){
        $suppliers = Supplier::all();
        $cow = Cow::findOrFail($cow_id);
        return view('dashboard.admin.cow.edit', compact('suppliers', 'cow'));
    }

    public function update(Request $request, $cow_id){
        $request->validate([ // Check Value null or not
            'cow_gene'=>'max:255',
            'cow_img'=>'mimes:jpg,jpeg,png'
        ],
        [
            'cow_gene.max'=>'ห้ามป้อนเกิน 255 ตัวอักษร',
            ]
        );


        // $supplier = Supplier::findOrFail($request->sup_id);
        // $supplier->cows()->where('cow_id', $cow_id)->update([
        //     'cow_gene' => $request->cow_gene,
        //     'cow_img' =>  $full_path,
        //     'cow_birth' =>  $request->cow_birth,
        //     'created_at' => Carbon::now(),
        // ]);
        $cow = Supplier::findOrFail($request->sup_id)
                        ->cows()->where('cow_id', $cow_id)->first();

        // Encryption image
        $cow_img = $request->file('cow_img');
        if($cow_img){ //? Update Name and IMG
            // Generate image name (random but not the same)
             $name_generate = hexdec(uniqid());
             // include File name extension (example: .PNG -> png)
             $img_ext = strtolower($cow_img->getClientOriginalExtension());
              // Combine Generate image name + File name extension
              $img_name = $name_generate.'.'.$img_ext;
               // Upload and Update record
              $upload_location = 'image/cows/';
               $full_path = $upload_location.$img_name;
               // Upload on my computer
            // Update to Database
            $cow->cow_img = $full_path;
            // Delete old image and and Update new image instead
            $old_image = $request->old_image;
            unlink($old_image); // Delete Old IMG
            $cow_img->move($upload_location, $img_name); // The New image instead of Old image
        }
        // Update to Database
        $cow->cow_gene = $request->cow_gene;
        $cow->cow_birth = $request->cow_birth;
        $cow->updated_at = Carbon::now();
        $cow->update();

        return redirect('admin/cows')->with('message', 'Cow Updated!');
        // return redirect()->route('admin.cow')->with('message', 'Cow Created');
    }

    public function destroy($cow_id){
        Cow::findOrFail($cow_id)->delete();
        return redirect('admin/cows')->with('message', 'Cow Deleted!');
    }
}
