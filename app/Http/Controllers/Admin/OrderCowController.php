<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cow;
use App\Models\CowGene;
use App\Models\Order;
use App\Models\OrderCow;
use App\Models\OrderDetail;
use App\Models\Supplier;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class OrderCowController extends Controller
{
    public function index(Request $request)
    {
        // $cows = Cow::latest();

        // $cows = Cow::select('cows.*', 'cow_genes.name as cowGeneName')
        // ->latest('cows.id')
        // ->leftJoin('cow_genes', 'cow_genes.id',
        //            'cows.cow_gene_id');

        // if (!empty($request->get('keyword'))) {
        //     $cows = $cows->where('id', 'like', '%' . $request->get('keyword') . '%');
        //     // $cows = $cows->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        // }

        // $cows = $cows->paginate(10);

        // $orderCows = Cow::select('order_cows.*', 'cows.id as cowId', 'cows.*')
        // ->latest('order_cows.id')
        // ->leftJoin('cows', 'cows.id',
        //            'order_cows.cow_id');

        // if (!empty($request->get('keyword'))) {
        //     $orderCows = $orderCows->where('id', 'like', '%' . $request->get('keyword') . '%');
        //     // $cows = $cows->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        // }

        // dd($orderCows);

        // $orders = OrderDetail::latest('order_details.created_at')->select('order_details.*', 'order_cows.*', 'cows.*');
        // $orders = $orders->leftJoin('customers','customers.id', 'orders.customer_id');


        // $orders = Order::latest('orders.created_at')->select('orders.*', 'customers.name', 'customers.email');
        // $orders = $orders->leftJoin('customers','customers.id', 'orders.customer_id');


        // $ordersDetails = OrderDetail::latest('order_details.created_at')->select('order_details.*', 'cows.*');
        // $ordersDetails = $ordersDetails->leftJoin('cows','cows.id', 'order_details.cows_id');
        // dd($ordersDetails);

        // $ordersDetails = OrderDetail::latest();
        // $cows = Cow::select('cows.*', 'ordersDetails.cow_id as cowId')
        // ->latest('cows.id')
        // ->leftJoin('ordersDetails', 'ordersDetails.cow_id',
        //            'cows.id');

        // $cows = Cow::find($ordersDetails->cow_id);
        // $orders = Order::find($ordersDetails->order_cow_id);

        // dd($cows);

        $orderDetailsWithRelations = OrderDetail::join('cows', 'order_details.cow_id', '=', 'cows.id')
            ->join('order_cows', 'order_details.order_cow_id', '=', 'order_cows.id')
            ->select('order_details.*', 'cows.*', 'order_cows.*')
            ->orderBy('order_cows.id', 'DESC')
            ->get();

        $orderCows = OrderCow::orderBy('id', 'DESC')->get();
        // $orderCows = $orderCows->paginate(10);
        // dd($orderDetailsWithRelations);
        // $orderDetailsWithRelations = $orderDetailsWithRelations->paginate(10);
        return view('admin.order_cows.list', compact('orderCows', 'orderDetailsWithRelations'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $cowGenes = CowGene::orderBy('name', 'ASC')->get();
        return view('admin.order_cows.create', compact('cowGenes', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cow_gene_id.*' => 'required',
            'weight.*' => 'required|numeric',
            'height.*' => 'required|numeric',
            'gender.*' => 'required',
            'birth.*' => 'required',
            'supplier_id' => 'required',
            'price.*' => 'required|numeric',
            'order_date' => 'required',
        ]);

        if ($validator->passes()) {
            $cowGeneId = $request->cow_gene_id;
            $birth = $request->birth;
            $weight = $request->weight;
            $height = $request->height;
            $price = $request->price;
            $image_id = $request->image_id;
            $gender = $request->gender;

            $orderCow = new OrderCow();
            $orderCow->order_date = $request->order_date;
            $orderCow->supplier_id = $request->supplier_id;
            $total = 0;
            $orderCow->save();

            for ($i = 0; $i < count($cowGeneId); $i++) {
                $cow = new Cow();
                $cow->cow_gene_id = $cowGeneId[$i];
                $cow->weight = $weight[$i];
                $cow->height = $height[$i];
                $cow->gender = $gender[$i];
                $cow->birth = $birth[$i];
                $cow->save();

                $orderDetail = new OrderDetail();
                $orderDetail->cow_id = $cow->id;
                $orderDetail->order_cow_id = $orderCow->id;
                $orderDetail->price = $price[$i];
                $orderDetail->save();

                $total += $price[$i];

                if (!empty($image_id[$i])) {
                    $tempImage = TempImage::find($image_id[$i]);
                    $extArray = explode('.', $tempImage->name);
                    $ext = last($extArray);
                    $newImageName = $cow->id . '.' . $ext;
                    $sourcePath = public_path() . '/temp/' . $tempImage->name;
                    $destPath = public_path() . '/uploads/cow/' . $newImageName;
                    File::copy($sourcePath, $destPath);

                    $cow->image = $newImageName;
                    $cow->save();
                }
            }
            $orderCow->total = $total;
            $orderCow->amount = count($cowGeneId);
            $orderCow->save();

            $message = 'Order Cows added successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }
    }

    public function edit($id)
    {
        $orderCow = OrderCow::find($id);

        if (empty($orderCow)) {
            Session::flash('error', 'Order not found');
            return response()->json([
                'status' => true,
                'message' => 'Order not found'
            ]);
        }

        // $orderDetails = OrderDetail::where('order_cow_id', $orderCow->id)->get();

        // if ($orderDetails->isEmpty()) {
        //     Session::flash('error', 'Order details not found');
        //     return response()->json([
        //         'status' => true,
        //         'message' => 'Order details not found'
        //     ]);
        // }

        // $cows = [];
        // $orderDetails->each(function ($orderDetail) {

        //     $cow = Cow::find($orderDetail->cow_id);
        //     if (empty($cow)) {
        //         Session::flash('error', 'Cow not found');
        //         return response()->json([
        //             'status' => true,
        //             'message' => 'Cow not found'
        //         ]);
        //     }
        //     array_push($cows, $cow);
        // });

        // Session::flash('success', 'Order deleted successfully');
        // return response()->json([
        //     'status' => true,
        //     'message' => 'Order deleted successfully'
        // ]);

        // if ($cows == null) {
        //     $message = 'cow not found.';
        //     Session::flash('error', $message);

        //     return redirect()->route('admin.cow.index');
        // }

        $orderDetailsWithRelations = OrderDetail::join('cows', 'order_details.cow_id', '=', 'cows.id')
            ->join('order_cows', 'order_details.order_cow_id', '=', 'order_cows.id')
            ->select('order_details.*', 'cows.*', 'order_cows.*')
            ->where('order_cows.id', $id)
            ->orderBy('order_cows.id', 'DESC')
            ->get();

        // dd($orderDetailsWithRelations[0]->weight);

        $suppliers = Supplier::orderBy('name', 'ASC')->get();
        $cowGenes = CowGene::orderBy('name', 'ASC')->get();

        return view('admin.order_cows.edit', compact('cowGenes', 'suppliers', 'orderDetailsWithRelations', 'orderCow'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'cow_gene_id.*' => 'required',
            'weight.*' => 'required|numeric',
            'height.*' => 'required|numeric',
            'gender.*' => 'required',
            'birth.*' => 'required',
            'supplier_id' => 'required',
            'price.*' => 'required|numeric',
            'order_date' => 'required',
        ]);

        $orderCow = OrderCow::find($id);

        if (empty($orderCow)) {
            Session::flash('error', 'Order not found');
            return response()->json([
                'status' => true,
                'message' => 'Order not found'
            ]);
        }

        $orderDetailsWithRelations = OrderDetail::join('cows', 'order_details.cow_id', '=', 'cows.id')
            ->join('order_cows', 'order_details.order_cow_id', '=', 'order_cows.id')
            ->select('order_details.*', 'cows.*', 'order_cows.*')
            ->where('order_cows.id', $id)
            ->orderBy('order_cows.id', 'ASC')
            ->get();

        if ($validator->passes()) {
            $cowGeneId = $request->cow_gene_id;
            $birth = $request->birth;
            $weight = $request->weight;
            $height = $request->height;
            $price = $request->price;
            $image_id = $request->image_id;
            $gender = $request->gender;

            $orderCow->order_date = $request->order_date;
            $orderCow->supplier_id = $request->supplier_id;
            $total = 0;
            $orderCow->save();


            for ($i = 0; $i < count($cowGeneId); $i++) {
                if (!empty($orderDetailsWithRelations[$i])) {
                    $cow = Cow::find($orderDetailsWithRelations[$i]->cow_id);
                    $cow->cow_gene_id = $cowGeneId[$i];
                    $cow->weight = $weight[$i];
                    $cow->height = $height[$i];
                    $cow->gender = $gender[$i];
                    $cow->birth = $birth[$i];
                    $cow->save();
                    $orderDetailsWithRelations[$i]->price = $price[$i];

                    $total += $price[$i];
                    $oldImage = $cow->image;

                    if (!empty($image_id[$i])) {
                        $tempImage = TempImage::find($image_id[$i]);
                        $extArray = explode('.', $tempImage->name);
                        $ext = last($extArray);
                        $newImageName = $cow->id . '.' . $ext;
                        $sourcePath = public_path() . '/temp/' . $tempImage->name;
                        $destPath = public_path() . '/uploads/cow/' . $newImageName;
                        File::delete(public_path('/uploads/cow/').$oldImage);
                        File::copy($sourcePath, $destPath);

                        $cow->image = $newImageName;
                        $cow->save();

                    }

                    $orderDetailsWithRelations[$i]->save();
                } else {
                    $cow = new Cow();
                    $cow->cow_gene_id = $cowGeneId[$i];
                    $cow->weight = $weight[$i];
                    $cow->height = $height[$i];
                    $cow->gender = $gender[$i];
                    $cow->birth = $birth[$i];
                    $cow->save();

                    $orderDetail = new OrderDetail();
                    $orderDetail->cow_id = $cow->id;
                    $orderDetail->order_cow_id = $orderCow->id;
                    $orderDetail->price = $price[$i];
                    $orderDetail->save();

                    $total += $price[$i];

                    if (!empty($image_id[$i])) {
                        $tempImage = TempImage::find($image_id[$i]);
                        $extArray = explode('.', $tempImage->name);
                        $ext = last($extArray);
                        $newImageName = $cow->id . '.' . $ext;
                        $sourcePath = public_path() . '/temp/' . $tempImage->name;
                        $destPath = public_path() . '/uploads/cow/' . $newImageName;
                        File::copy($sourcePath, $destPath);


                        $cow->image = $newImageName;
                        $cow->save();
                    }
                }
            }
            $orderCow->total = $total;
            $orderCow->amount = count($cowGeneId);
            $orderCow->save();

            $message = 'Order Cows updated successfully.';
            Session::flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $orderCow = OrderCow::find($id);

        if (empty($orderCow)) {
            Session::flash('error', 'Order not found');
            return response()->json([
                'status' => true,
                'message' => 'Order not found'
            ]);
        }

        $orderDetails = OrderDetail::where('order_cow_id', $orderCow->id)->get();

        if ($orderDetails->isEmpty()) {
            Session::flash('error', 'Order details not found');
            return response()->json([
                'status' => true,
                'message' => 'Order details not found'
            ]);
        }



        $orderCow->delete();
        $orderDetails->each(function ($orderDetail) {
            $orderDetail->delete();

            $cow = Cow::find($orderDetail->cow_id);
            if (empty($cow)) {
                Session::flash('error', 'Cow not found');
                return response()->json([
                    'status' => true,
                    'message' => 'Cow not found'
                ]);
            }

            File::delete(public_path('/uploads/cow/') . $cow->image);
            $cow->delete();
        });

        Session::flash('success', 'Order deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Order deleted successfully'
        ]);
    }
}
