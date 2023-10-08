@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Order Cows</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.order-cows.create') }}" class="btn btn-primary">New Order Cow</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        @include('admin.message')
        <div class="container-fluid">
            <div class="card">
                <form action="" method="GET">
                    <div class="card-header">
                        <div class="card-title">
                            <button type="button" onclick="window.location.href='{{ route('admin.cows.index') }}'"
                                class="btn btn-default btn-sm">Reset</button>
                        </div>

                        <div class="card-tools">
                            <div class="input-group input-group" style="width: 250px;">
                                <input value="{{ Request::get('keyword') }}" type="text" name="keyword"
                                    class="form-control float-right" placeholder="Search">

                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th width="60">ID</th>
                                <th width="150">IMG</th>
                                <th width="150">Cow Id</th>
                                <th width="150">Order cow Id</th>
                                <th>Supplier</th>
                                <th>Gene</th>
                                <th>Weight</th>
                                <th>Height</th>
                                <th>Gender</th>
                                <th>Age</th>
                                <th width="100">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->isNotEmpty())
                                @foreach ($orders as $order)
                                <tr>
                                    @foreach (getOrderDetails($order->id) as $orderDetails)
                                        <td>{{ $orderDetails->cow_id }}</td>
                                    @endforeach
                                        <td>{{ $order->id }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">Records Not Found</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    {{-- {{ $orderDetailsWithRelations->links() }} --}}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        function deleteRecord(id) {
            var url = "{{ route('admin.cows.delete', 'ID') }}"
            var newUrl = url.replace('ID', id)

            if (confirm("Are you sure you want to delete?")) {
                $.ajax({
                    url: newUrl,
                    type: 'delete',
                    data: {},
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response['status']) {
                            window.location.href = "{{ route('admin.cows.index') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
