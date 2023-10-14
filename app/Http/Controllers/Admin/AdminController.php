<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\VerifyEmployee;
use Carbon\Carbon;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // public function index(){
    //     $employees = Employee::all();
    //     return view('admin.EmployeeCRUD.index', compact('employees'));
    // }

    public function index()
    {
        $totalOrders = Order::where('status', '!=', 'cancelled')->count();
        $totalProducts = Product::count();
        $totalCustomers = Product::count();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        // This month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        // Last month revenue
        $lastMonthStartDate = Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d');
        $lastMonthEndDate = Carbon::now()->subMonth()->endOfMonth()->format('Y-m-d');

        $revenueLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)
            ->sum('grand_total');

        // Last 30 days sale
        $lastThirtyDayStartDate = Carbon::now()->subDays(30)->format('Y-m-d');

        $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            'revenueLastThirtyDays',
            'lastMonthName'
        ));
    }
}
