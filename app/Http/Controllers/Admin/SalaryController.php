<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Salary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SalaryController extends Controller
{
    public function index(Request $request)
    {
        // $salaries = Salary::latest();
        $salaries = Salary::select('salaries.*', 'employees.name as employeeName')
                                        ->latest('salaries.id')
                                        ->leftJoin('employees', 'employees.id',
                                                   'salaries.employee_id');
        if (!empty($request->get('keyword'))) {
            $salaries = $salaries->where('name', 'like', '%' . $request->get('keyword') . '%');
            $salaries = $salaries->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }

        $salaries = $salaries->paginate(10);
        return view('admin.salary.list', compact('salaries'));
    }

    public function create()
    {
        $employees = Employee::orderBy('name', 'ASC')->get();
        return view('admin.salary.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            // Get off work date must be greater than Start date
            if (!empty($request->start_work) && !empty($request->get_off)) {
                $startWork = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_work);
                $getOff = Carbon::createFromFormat('Y-m-d H:i:s', $request->get_off);

                if ($getOff->gt($startWork) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['get_off' => "Get off work date must be greater than start date"]
                    ]);
                }
            }

            $salary = new Salary();
            $salary->employee_id = $request->employee_id;
            $salary->amount = $request->amount;
            $salary->status = $request->status;
            $salary->pay_date = $request->pay_date;
            $salary->start_work = $request->start_work;
            $salary->get_off = $request->get_off;
            $salary->save();


            $message = 'Salary added successfully.';
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
        $salary = Salary::find($id)->first();

        if ($salary == null) {
            $message = 'salary not found.';
            Session::flash('error', $message);

            return redirect()->route('admin.salary.index');
        }

        $employees = Employee::orderBy('name', 'ASC')->get();
        return view('admin.salary.edit', compact('salary', 'employees'));
    }

    public function update(Request $request, $id)
    {

        $salary = Salary::find($id);
        if(empty($salary)){
            Session::flash('error', "Record not found");
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $validator = Validator::make($request->all(), [
            'employee_id' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            // Get off work date must be greater than Start date
            if (!empty($request->start_work) && !empty($request->get_off)) {
                $startWork = Carbon::createFromFormat('Y-m-d H:i:s', $request->start_work);
                $getOff = Carbon::createFromFormat('Y-m-d H:i:s', $request->get_off);

                if ($getOff->gt($startWork) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['get_off' => "Get off work date must be greater than start date"]
                    ]);
                }
            }

            $salary->employee_id = $request->employee_id;
            $salary->amount = $request->amount;
            $salary->status = $request->status;
            $salary->pay_date = $request->pay_date;
            $salary->start_work = $request->start_work;
            $salary->get_off = $request->get_off;
            $salary->save();


            $message = 'Salary updated successfully.';
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

    public function destroy(Request $request, $id){
        $salary = Salary::find($id);

        if(empty($salary)){
            Session::flash('error', "Record not found");
            return response([
                'status' => false,
                'notFound' => true
            ]);
        }

        $salary->delete();
        Session::flash('success', "Salary deleted successfully.");
        return response([
            'status' => true,
            'message' => "Salary deleted successfully."
        ]);
    }
}
