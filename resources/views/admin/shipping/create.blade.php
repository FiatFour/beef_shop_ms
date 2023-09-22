@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Shipping Management</h1>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            @include('admin.message')
            <form action="" method="POST" id="shippingForm" name="shippingForm">
                {{-- @csrf --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="mb-3">
                                    <label for="district">District Name</label>
                                    <input type="text" name="district" id="district" class="form-control"
                                        placeholder="District Name">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="amount">Amount</label>
                                    <input type="text" name="amount" id="amount" class="form-control"
                                        placeholder="Amount">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="mb-3" style="margin-top: 32px">
                                    <button type="submit" class="btn btn-primary ">Create</button>
                                    <a href="{{ route('admin.shipping.create') }}"
                                        class="btn btn-outline-dark ml-3">Cancel</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-striped">
                                <tr>
                                    <th>ID</th>
                                    <th>District name</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                @if ($shippingCharges->isNotEmpty())
                                    @foreach ($shippingCharges as $shippingCharge)
                                    <tr>
                                        <td>{{ $shippingCharge->id }}</td>
                                        <td>{{ $shippingCharge->district }}</td>
                                        <td>฿{{ $shippingCharge->amount }}</td>
                                        <td>
                                            <a href="{{ route('admin.shipping.edit',$shippingCharge->id) }}" class="btn btn-primary">Edit</a>
                                            <a href="javascript:void(0);" onclick="deleteRecord({{ $shippingCharge->id }})" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#shippingForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.shipping.store') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response['status'] == true) {
                        window.location.href = "{{ route('admin.shipping.create') }}";

                        // $("#district").removeClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback').html("");

                        // $("#amount").removeClass('is-invalid')
                        //     .siblings('p')
                        //     .removeClass('invalid-feedback').html("");
                    } else {
                        var errors = response['errors'];
                        if (errors['district']) {
                            $("#district").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['district']);
                        } else {
                            $("#district").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }

                        if (errors['amount']) {
                            $("#amount").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback').html(errors['amount']);
                        } else {
                            $("#amount").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");
                        }
                    }
                },
                error: function(jqXHR, exception) {
                    // Handle error here, e.g., display an error message
                    console.log("Something went wrong");
                    console.log(jqXHR);
                }
            });
        });

        function deleteRecord(id){
            var url = "{{ route('admin.shipping.delete', 'ID') }}"
            var newUrl = url.replace('ID', id)

            if(confirm("Are you sure you want to delete?")){
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
                            window.location.href = "{{ route('admin.shipping.create') }}";
                        }
                    }
                });
            }
        }
    </script>
@endsection
