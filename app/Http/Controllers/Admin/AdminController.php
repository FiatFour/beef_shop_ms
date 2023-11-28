<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cow;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderCow;
use App\Models\Product;
use App\Models\Salary;
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
        $totalCustomers = Customer::count();
        $totalEmployees = Employee::count();
        $totalCows = Cow::count();
        $totalOrderCows = OrderCow::count();

        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('grand_total');
        $totalSalaries = Salary::where('status', '=', 1)->sum('amount');
        $totalOrderCowCosts = OrderCow::sum('total');
        $cows = Cow::where('dissect_date', '!=', null)->get();

        $totalCostOfFoods = 0;
        foreach ($cows as $cow) {
            $totalCostOfFoods += $cow->costOfFood();
        }

        // This month revenue
        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $startOfYear = Carbon::now()->startOfYear()->format('Y-m-d');
        $currentDate = Carbon::now()->format('Y-m-d');
        $lastMonthName = Carbon::now()->subMonth()->startOfMonth()->format('M');

        $revenueThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('grand_total');

        $revenueThisYear = Order::where('status', '!=', 'cancelled')
            ->whereDate('created_at', '>=', $startOfYear)
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

        // $revenueLastThirtyDays = Order::where('status', '!=', 'cancelled')
        //     ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
        //     ->whereDate('created_at', '<=', $currentDate)
        //     ->sum('grand_total');

        $salaryThisYear = Salary::where('status', '=', 1)
            ->whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('amount');

        $salaryThisMonth = Salary::where('status', '=', 1)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('amount');

        $salaryLastMonth = Salary::where('status', '=', 1)
            ->whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)
            ->sum('amount');

        // $salaryLastThirtyDays = Salary::where('status', '=', 1)
        //     ->whereDate('created_at', '>=', $lastThirtyDayStartDate)
        //     ->whereDate('created_at', '<=', $currentDate)
        //     ->sum('amount');

        $cowThisYear = Cow::where('dissect_date', '!=', null)
            ->whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $currentDate)
            ->get();
        $costOfFoodThisYear = 0;
        foreach ($cowThisYear as $cow) {
            $costOfFoodThisYear += $cow->costOfFood();
        }

        $cowThisMonth = Cow::where('dissect_date', '!=', null)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->get();
        $costOfFoodThisMonth = 0;
        foreach ($cowThisMonth as $cow) {
            $costOfFoodThisMonth += $cow->costOfFood();
        }

        $cowLastMonth = Cow::where('dissect_date', '!=', null)
            ->whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)
            ->get();
        $costOfFoodLastMonth = 0;
        foreach ($cowLastMonth as $cow) {
            $costOfFoodLastMonth += $cow->costOfFood();
        }


        $orderCowCostThisYear = OrderCow::whereDate('created_at', '>=', $startOfYear)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('total');

        $orderCowCostThisMonth = OrderCow::whereDate('created_at', '>=', $startOfMonth)
            ->whereDate('created_at', '<=', $currentDate)
            ->sum('total');

        $orderCowCostLastMonth = OrderCow::whereDate('created_at', '>=', $lastMonthStartDate)
            ->whereDate('created_at', '<=', $lastMonthEndDate)
            ->sum('total');

        $accountTotal = $totalRevenue - ($totalSalaries + $totalCostOfFoods + $totalOrderCowCosts);
        $accountThisYear = $revenueThisYear - ($salaryThisYear + $costOfFoodThisYear + $orderCowCostThisYear);
        $accountThisMonth = $revenueThisMonth - ($salaryThisMonth + $costOfFoodThisMonth + $orderCowCostThisMonth);
        $accountLastMonth = $revenueLastMonth - ($salaryLastMonth + $costOfFoodLastMonth + $orderCowCostLastMonth);
        return view('admin.dashboard', compact(
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'totalRevenue',
            'revenueThisMonth',
            'revenueLastMonth',
            // 'revenueLastThirtyDays',
            'lastMonthName',
            'totalEmployees',
            'salaryThisMonth',
            'salaryLastMonth',
            // 'salaryLastThirtyDays',
            'totalCows',
            'totalOrderCows',
            'totalSalaries',
            'totalCostOfFoods',
            'costOfFoodThisMonth',
            'costOfFoodLastMonth',
            'totalOrderCowCosts',
            'orderCowCostThisMonth',
            'orderCowCostLastMonth',
            'revenueThisYear',
            'salaryThisYear',
            'costOfFoodThisYear',
            'orderCowCostThisYear',
            'accountTotal',
            'accountThisYear',
            'accountThisMonth',
            'accountLastMonth',

        ));
    }
}
