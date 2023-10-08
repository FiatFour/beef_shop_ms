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
                                        <td>{{ $order->id }}//</td>
                                        @if ($orderDetailsWithRelations->isNotEmpty())
                                            @foreach ($orderDetailsWithRelations as $orderDetails)
                                                <td>
                                                    @if ($orderDetails->image != '')
                                                        <img src="{{ asset('uploads/cow/' . $orderDetails->image) }}"
                                                            alt="" width="70px" height="70px">
                                                    @endif
                                                </td>
                                                <td>{{ $orderDetails->cow_id }}</td>
                                                <td>{{ $orderDetails->supplier_id }}</td>
                                                <td>{{ $orderDetails->cow_gene_id }}</td>
                                                <td>{{ number_format($orderDetails->weight, 2) }} kg.</td>
                                                <td>{{ number_format($orderDetails->height, 2) }} cm.</td>
                                                <td>
                                                    @if ($orderDetails->gender == 'Man')
                                                        Man
                                                    @else
                                                        Woman
                                                    @endif
                                                </td>
                                                {{-- <td>{{ !empty($cow->birth) ? $cow->age() : '' }} years</td> --}}

                                                <td>
                                                    <a href="{{ route('admin.order-cows.edit', $orderDetails->id) }}">
                                                        <svg class="filament-link-icon w-4 h-4 mr-1"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path
                                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                            </path>
                                                        </svg>
                                                    </a>
                                                    <a href="#" onclick="deleteRecord({{ $orderDetails->id }})"
                                                        class="text-danger w-4 h-4 mr-1">
                                                        <svg wire:loading.remove.delay="" wire:target=""
                                                            class="filament-link-icon w-4 h-4 mr-1"
                                                            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                            fill="currentColor" aria-hidden="true">
                                                            <path ath fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"></path>
                                                        </svg>
                                                    </a>
                                                </td>
                                            @endforeach
                                        @endif



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
                    {{ $orders->links() }}
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
