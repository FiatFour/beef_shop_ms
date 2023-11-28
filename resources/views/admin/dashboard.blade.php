@extends('admin.layouts.app')

@section('content')
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<div class="container-fluid">
			<div class="row mb-2">
				<div class="col-sm-6">
					<h1>Dashboard</h1>
				</div>
				<div class="col-sm-6">

				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalOrders }}</h3>
							<p>Total Orders</p>
						</div>
						<div class="icon">
							<i class="ion ion-bag"></i>
						</div>
						<a href="{{ route('admin.orders.index') }}" class="small-box-footer bg-success">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

                <div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalProducts }}</h3>
							<p>Total Products</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{ route('admin.products.index') }}" class="small-box-footer bg-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalCustomers }}</h3>
							<p>Total Customers</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{ route('admin.customers.index') }}" class="small-box-footer bg-secondary">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalEmployees - 1 }}</h3>
							<p>Total Employees</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{ route('admin.employees.index') }}" class="small-box-footer bg-danger">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalCows }}</h3>
							<p>Total Cows</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{ route('admin.cows.index') }}" class="small-box-footer bg-info">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-2 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>{{ $totalOrderCows }}</h3>
							<p>Total Order Cows</p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a href="{{ route('admin.order-cows.index') }}" class="small-box-footer bg-warning">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($totalRevenue, 2) }}</h3>
							<p>Total Orders Sale</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-success">&nbsp;</a> --}}
						<a href="{{ route('admin.orders.index') }}" class="small-box-footer bg-success">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($revenueThisMonth, 2) }}</h3>
							<p>Revenue Orders This Month</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-success">&nbsp;</a> --}}
						<a href="{{ route('admin.orders.index') }}" class="small-box-footer bg-success">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($revenueLastMonth, 2) }}</h3>
							<p>Revenue Orders Last Month ({{ $lastMonthName }})</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-success">&nbsp;</a> --}}
						<a href="{{ route('admin.orders.index') }}" class="small-box-footer bg-success">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($revenueThisYear, 2) }}</h3>
							<p>Revenue Orders This Year </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-success">&nbsp;</a> --}}
						<a href="{{ route('admin.orders.index') }}" class="small-box-footer bg-success">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                {{-- <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($revenueLastThirtyDays, 2) }}</h3>
							<p>Revenue last 30 days</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div> --}}
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($totalSalaries, 2) }}</h3>
							<p>Total Salaries </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-danger">&nbsp;</a> --}}
						<a href="{{ route('admin.salaries.index') }}" class="small-box-footer bg-danger">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
				</div>


				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($salaryThisMonth, 2) }}</h3>
							<p>Salary This Month </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-danger">&nbsp;</a> --}}
						<a href="{{ route('admin.salaries.index') }}" class="small-box-footer bg-danger">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($salaryLastMonth, 2) }}</h3>
							<p>Salary Last Month ({{ $lastMonthName }})</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-danger">&nbsp;</a> --}}
						<a href="{{ route('admin.salaries.index') }}" class="small-box-footer bg-danger">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($salaryThisYear, 2) }}</h3>
							<p>Salary This Year </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-danger">&nbsp;</a> --}}
						<a href="{{ route('admin.salaries.index') }}" class="small-box-footer bg-danger">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($totalCostOfFoods, 2) }}</h3>
							<p>Total Cost Of Foods </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-info">&nbsp;</a> --}}
						<a href="{{ route('admin.cows.index') }}" class="small-box-footer bg-info">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($costOfFoodThisMonth, 2) }}</h3>
							<p>Cost Of Food This Mouth </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-info">&nbsp;</a> --}}
						<a href="{{ route('admin.cows.index') }}" class="small-box-footer bg-info">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($costOfFoodLastMonth, 2) }}</h3>
							<p>Cost Of Food Last Mouth ({{ $lastMonthName }})</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-info">&nbsp;</a> --}}
						<a href="{{ route('admin.cows.index') }}" class="small-box-footer bg-info">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($costOfFoodThisYear, 2) }}</h3>
							<p>Cost Of Food This Year </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-info">&nbsp;</a> --}}
						<a href="{{ route('admin.cows.index') }}" class="small-box-footer bg-info">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>

                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($totalOrderCowCosts, 2) }}</h3>
							<p>Total Order Cow Costs </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-warning">&nbsp;</a> --}}
						<a href="{{ route('admin.order-cows.index') }}" class="small-box-footer bg-warning">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($orderCowCostThisMonth, 2) }}</h3>
							<p>Order Cow Cost This Mouth </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-warning">&nbsp;</a> --}}
						<a href="{{ route('admin.order-cows.index') }}" class="small-box-footer bg-warning">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($orderCowCostLastMonth, 2) }}</h3>
							<p>Order Cow Cost Last Mouth ({{ $lastMonthName }})</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-warning">&nbsp;</a> --}}
						<a href="{{ route('admin.order-cows.index') }}" class="small-box-footer bg-warning">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($orderCowCostThisYear, 2) }}</h3>
							<p>Order Cow Cost This Year </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						{{-- <a href="javascript:void(0);" class="small-box-footer bg-warning">&nbsp;</a> --}}
						<a href="{{ route('admin.order-cows.index') }}" class="small-box-footer bg-warning">More info <i class="fas fa-arrow-circle-right"></i></a>
					</div>
				</div>


                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($accountTotal, 2) }}</h3>
							<p>Total Account </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer bg-dark">&nbsp;</a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($accountThisMonth, 2) }}</h3>
							<p>Account This Month </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer bg-dark">&nbsp;</a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($accountLastMonth, 2) }}</h3>
							<p>Account Last Month ({{ $lastMonthName }})</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer bg-dark">&nbsp;</a>
					</div>
				</div>
                <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($accountThisYear, 2) }}</h3>
							<p>Account This Year </p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer bg-dark">&nbsp;</a>
					</div>
				</div>
                {{-- <div class="col-lg-3 col-6">
					<div class="small-box card">
						<div class="inner">
							<h3>฿{{ number_format($salaryLastThirtyDays, 2) }}</h3>
							<p>Salary last 30 days</p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a href="javascript:void(0);" class="small-box-footer">&nbsp;</a>
					</div>
				</div> --}}
			</div>
		</div>
		<!-- /.card -->
	</section>
	<!-- /.content -->
@endsection

@section('customJs')
    <script>

    </script>
@endsection
