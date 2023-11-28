@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order: #{{ $order->id }}</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Back</a>
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
                <div class="col-md-9">
                    @include('admin.message')
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                    <b>Order ID:</b> {{ $order->id }}<br>
                                    <b>Total:</b> ฿{{ number_format($order->grand_total, 2) }}<br>
                                    <b>Order Status:</b>
                                    @if ($order->status == 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($order->status == 'Shipped')
                                        <span class="badge bg-info">Shipped</span>
                                    @elseif($order->status == 'Delivered')
                                        <span class="badge bg-success">Delivered</span>
                                    @else
                                        <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                    <br>
                                    <b>Payment Status:</b>
                                    @if ($order->payment_status == 'Paid')
                                        <span class="badge bg-success">Paid</span>
                                    @else
                                        <span class="badge bg-danger">Not Paid</span>
                                    @endif
                                    <hr>
                                    <h5 class="mb-1 mt-3 text-center">Shipping Address</h5>
                                    <address>
                                        <b>Name: </b> <span>{{ $order->first_name . ' ' . $order->last_name }}</span><br>
                                        {{ $order->address }}<br>
                                        {{ $order->city }}, {{ $order->districtName }}, {{ $order->state }}
                                        {{ $order->zip }} <br>
                                        Phone: {{ $order->mobile }}<br>
                                        Email: {{ $order->email }}
                                    </address>
                                    <b>Shipped Date</b><br>
                                    @if (!empty($order->shipped_date))
                                        {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif
                                    <br>
                                </div>



                                <div class="col-sm-4 invoice-col">
                                    {{-- <b>Invoice #007612</b><br> --}}
                                    <b>Payment Method: </b>
                                    <br>
                                    @if (!empty($order->pay_image))
                                    PromptPay
                                    <img src="{{ asset('uploads/order/' . $order->pay_image) }}" alt=""
                                    width="400px" height="700px">
                                    @else
                                    COD
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderItems as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>฿{{ number_format($item->price, 2) }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>฿{{ number_format($item->total, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>฿{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Discount:
                                            {{ !empty($order->coupon_code) ? '(' . $order->coupon_code . ')' : '' }}</th>
                                        <td>฿{{ number_format($order->discount, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>฿{{ number_format($order->shipping, 2) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>฿{{ number_format($order->grand_total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <form action="" name="changeOrderStatusForm" id="changeOrderStatusForm">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Payment Status</h2>
                                <div class="mb-3">
                                    <select name="payment_status" id="payment_status" class="form-control">
                                        <option value="Paid" {{ $order->payment_status == 'Paid' ? 'selected' : '' }}>
                                            Paid
                                        </option>
                                        <option value="Not Paid" {{ $order->payment_status == 'Not paid' ? 'selected' : '' }}>
                                            Not Paid
                                        </option>
                                    </select>
                                </div>
                                <h2 class="h4 mb-3">Order Status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="Pending" {{ $order->status == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>
                                            Shipped
                                        </option>
                                        <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>
                                            Delivered
                                        </option>
                                        <option value="Cancelled" {{ $order->status == 'Cancelled' ? 'selected' : '' }}>
                                            Cancelled
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="">Shipped Date</label>
                                    <input value="{{ $order->shipped_date }}" type="text" name="shipped_date"
                                        id="shipped_date" class="form-control" placeholder="Shipped Date"
                                        autocomplete="off">
                                </div>
                                <div class="mb-3">
                                    <label>Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Select a employee</option>
                                        @if ($employees->isNotEmpty())
                                            @foreach ($employees as $employee)
                                                <option {{ ($order->employee_id == $employee->id) ? 'selected' : '' }} value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form action="" method="POST" name="sendInvoiceEmail" id="sendInvoiceEmail">
                                <h2 class="h4 mb-3">Send Inovice Email</h2>
                                <div class="mb-3">
                                    <select name="userType" id="userType" class="form-control">
                                        <option value="customer">Customer</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <button class="btn btn-primary">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('customJs')
    <script>
        $(document).ready(function() {
            $('#shipped_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

        $('#changeOrderStatusForm').submit(function(event) {
            event.preventDefault();

            if (confirm("Are you sure you want to change status?")){
                $.ajax({
                    url: '{{ route('admin.orders.changeOrderStatus', $order->id) }}',
                    type: 'post',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('admin.orders.detail', $order->id) }}';
                    }
                });
            }
        });

        $('#sendInvoiceEmail').submit(function(event) {
            event.preventDefault();

            if (confirm("Are you sure you want to send email?")){
                $.ajax({
                    url: '{{ route('admin.orders.sendInvoiceEmail', $order->id) }}',
                    type: 'post',
                    data: $(this).serializeArray(),
                    dataType: 'json',
                    success: function(response) {
                        window.location.href = '{{ route('admin.orders.detail', $order->id) }}';
                    }
                });
            }
        });


    </script>
@endsection
