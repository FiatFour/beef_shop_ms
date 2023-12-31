@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Supplier</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">Back</a>
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
            <form action="" method="POST" id="supplierForm" name="supplierForm">
                {{-- @csrf --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $supplier->name }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Phone" maxlength="10" value="{{ $supplier->phone }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="Address" value="{{ $supplier->address }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="mb-3" style="margin-top: 32px">
                                    <button type="submit" class="btn btn-primary ">Update</button>
                                    <a href="{{ route('admin.suppliers.create') }}"
                                        class="btn btn-outline-dark ml-3">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>


        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#supplierForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.suppliers.update',$supplier->id) }}",
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response['status'] == true) {
                        window.location.href = "{{ route('admin.suppliers.create') }}";
                    } else {
                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                        });
                    }
                },
                error: function(jqXHR, exception) {
                    // Handle error here, e.g., display an error message
                    console.log("Something went wrong");
                    console.log(jqXHR);
                }
            });
        });
    </script>
@endsection
