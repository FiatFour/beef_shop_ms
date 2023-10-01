<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use App\Models\TempImage;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::latest();

        if (!empty($request->get('keyword'))) {
            $employees = $employees->where('name', 'like', '%' . $request->get('keyword') . '%');
            $employees = $employees->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $employees = $employees->paginate(10);
        return view('admin.employee.list', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric',
            'image_id' => 'required',
        ]);

        if ($validator->passes()) {

            $employee = new Employee();
            $employee->name = $request->name;
            $employee->department = $request->department;
            $employee->address = $request->address;
            $employee->phone = $request->phone;
            $employee->gender = $request->gender;
            $employee->email = $request->email;
            $employee->save();

            $tempImage = TempImage::find($request->image_id);
            $extArray = explode('.', $tempImage->name);
            $ext = last($extArray);
            $newImageName = $employee->id . '.' . $ext;
            $sPath = public_path() . '/temp/' . $tempImage->name;
            $dPath = public_path() . '/uploads/employee/' . $newImageName;
            File::copy($sPath, $dPath);

            $employee->image = $newImageName;
            $employee->save();

            $message = 'Employee added successfully.';
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

        $employee = Employee::find($id);

        if ($employee == null) {
            $message = 'Employee not found.';
            Session::flash('error', $message);

            return redirect()->route('admin.employees.index');
        }

        return view('admin.employee.edit', compact('employee'));
    }

    public function update(Request $request, $id){
        $employee = Employee::find($id);
        if(empty($employee)){
            Session::flash('error', 'Employee not found');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Employee not found'
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10|numeric',
        ]);

        if ($validator->passes()) {
            $employee->name = $request->name;
            $employee->department = $request->department;
            $employee->address = $request->address;
            $employee->phone = $request->phone;
            $employee->gender = $request->gender;
            $employee->email = $request->email;
            $employee->save();

            $oldImage = $employee->image;

            // Save Image here
            if(!empty($request->image_id)){
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.', $tempImage->name);
                $ext = last($extArray);
                $newImageName = $employee->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/employee/'.$newImageName;
                File::copy($sPath, $dPath);

                $employee->image = $newImageName;
                $employee->save();

                // Delete Old Images Here
                File::delete(public_path('/uploads/employee/').$oldImage);
            }

            Session::flash('success', 'Employee updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Employee updated successfully',
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy(Request $request, $id){
        $employee = Employee::find($id);

        if(empty($employee)){
            Session::flash('error', 'Employee not found');

            return response()->json([
                'status' => true,
                'message' => 'Employee not found'
            ]);
            // return redirect()->route('admin.categories.index');
        }

        File::delete(public_path('/uploads/employee/').$employee->image);
        $employee->delete();

        Session::flash('success', 'Employee deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }
}
