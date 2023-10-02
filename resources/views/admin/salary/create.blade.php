@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Salary</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.salaries.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="salaryForm" name="salaryForm">
                {{-- @csrf --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label>Employee</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Select a employee</option>
                                        @if ($employees->isNotEmpty())
                                            @foreach ($employees as $employee)
                                                <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount">Amount</label>
                                    <input type="number" name="amount" id="amount" class="form-control"
                                        placeholder="Amount">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="start_work">Start work</label>
                                    <input autocomplete="off" type="text" name="start_work" id="start_work"
                                        class="form-control" placeholder="Start work">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="get_off">Get off work</label>
                                    <input autocomplete="off" type="text" name="get_off" id="get_off"
                                        class="form-control" placeholder="Get off work">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="pay_date">Pay date</label>
                                    <input autocomplete="off" type="text" name="pay_date" id="pay_date"
                                        class="form-control" placeholder="Pay date">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-inline">Status: </label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="status"
                                                value="1">
                                            <label class="form-check-label">Pay</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="status" id="status"
                                                value="0">
                                            <label class="form-check-label">Not pay yet</label>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#salaryForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.salaries.store') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response['status'] == true) {
                        window.location.href = "{{ route('admin.salaries.index') }}";
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

        $(document).ready(function() {
            $('#start_work').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });

            $('#get_off').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });

            $('#pay_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });
    </script>
@endsection
