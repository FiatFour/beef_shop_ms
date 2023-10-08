@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Create Order Cow</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.order-cows.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="POST" id="cowForm" name="cowForm">
                {{-- @csrf --}}
                <div class="card">
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label>Supplier</label>
                                    <select name="supplier_id" id="supplier_id" class="form-control">
                                        <option value="">Select a Supplier</option>
                                        @if ($suppliers->isNotEmpty())
                                            @foreach ($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Order date</label>
                                    <input type="date" name="order_date" id="order_date" class="form-control">
                                    {{-- <input autocomplete="off" type="text" name="birth_date" id="birth_date"
                                    class="form-control" placeholder="Birth date"> --}}
                                    <p></p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card" id="table">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label>Cow Gene</label>
                                    <select name="cow_gene_id[]" id="cow_gene_id" class="form-control">
                                        <option value="">Select a cow gene</option>
                                        @if ($cowGenes->isNotEmpty())
                                            @foreach ($cowGenes as $cowGene)
                                                <option value="{{ $cowGene->id }}">{{ $cowGene->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Birth date</label>
                                    <input type="date" name="birth[]" id="birth" class="form-control">
                                    {{-- <input autocomplete="off" type="text" name="birth_date" id="birth_date"
                                    class="form-control" placeholder="Birth date"> --}}
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight">Weight</label>
                                    <input type="number" name="weight[]" id="weight" class="form-control"
                                        placeholder="Weight">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="height">Height</label>
                                    <input type="number" name="height[]" id="height" class="form-control"
                                        placeholder="Height">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price">Price</label>
                                    <input type="number" name="price[]" id="price" class="form-control"
                                        placeholder="Price">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id[]" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-inline">Gender: </label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender[]" id="gender"
                                                value="Man">
                                            <label class="form-check-label">Man</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender[]" id="gender"
                                                value="Woman">
                                            <label class="form-check-label">Woman</label>
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
                    <a href="{{ route('admin.order-cows.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>

            <div class="col-md-4">
                <div class="mb-3">
                    <button name="add" id="add" href="javascript:void(0);" class="btn btn-success addRow">Add
                        Row</button>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <button name="remove" id="remove" href="javascript:void(0);" class="btn btn-danger deleteRow"
                        onclick="deleteRecord();">Remove Row</button>
                </div>
            </div>
            <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        var count = 0;
        $('.container-fluid').on('click', '.addRow', function() {
            ++count;

            var newRow = `
                    <div class="card-body"  id="addTable${count}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <hr class="border border-info">
                                </div>
                            </div>


                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label>Cow Gene</label>
                                    <select name="cow_gene_id[]" id="cow_gene_id" class="form-control">
                                        <option value="">Select a cow gene</option>
                                        @if ($cowGenes->isNotEmpty())
                                            @foreach ($cowGenes as $cowGene)
                                                <option value="{{ $cowGene->id }}">{{ $cowGene->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Birth date</label>
                                    <input type="date" name="birth[]" id="birth" class="form-control">
                                    {{-- <input autocomplete="off" type="text" name="birth_date" id="birth_date"
                                    class="form-control" placeholder="Birth date"> --}}
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight">Weight</label>
                                    <input type="number" name="weight[]" id="weight" class="form-control"
                                        placeholder="Weight">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="height">Height</label>
                                    <input type="number" name="height[]" id="height" class="form-control"
                                        placeholder="Height">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="price">Price</label>
                                    <input type="number" name="price[]" id="price" class="form-control"
                                        placeholder="Price">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id${count}" name="image_id[]" value="">
                    <label for="image">Image</label>
                    <div id="image${count}" class="dropzone dz-clickable">
                        <div class="dz-message needsclick">
                            <br>Drop files here or click to upload.<br><br>
                        </div>
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label class="form-check-inline">Gender: </label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender[` + count + `]" id="gender"
                                                value="Man">
                                            <label class="form-check-label">Man</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="gender[` + count + `]" id="gender"
                                                value="Woman">
                                            <label class="form-check-label">Woman</label>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                </div>
                `
            $('#table').append(newRow);
            // Initialize Dropzone for the new row
            initializeDropzone(`#image${count}`, `#image_id${count}`);
        });

        $('.container-fluid').on('click', '.deleteRow', function() {
            $(`#addTable${count}`).remove();
            count--;
        });

        $("#cowForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            // $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.order-cows.store') }}",
                type: 'post',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    // $("button[type=submit]").prop('disabled', false);

                    if (response['status']) {
                        window.location.href = "{{ route('admin.order-cows.index') }}";
                    } else {
                        var errors = response.errors;
                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select").removeClass('is-invalid');


                        $.each(errors, function(key, value) {
                            $(`#${key}`,).addClass('is-invalid')
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

        function initializeDropzone(selector, imageIdSelector) {
            Dropzone.autoDiscover = false;

            const dropzone = new Dropzone(selector, {
                // Dropzone configurations
                init: function() {
                    this.on('addedfile', function(file) {
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }
                    });
                },
                url: "{{ route('admin.temp-images.create') }}",
                maxFiles: 1,
                paramName: 'image',
                addRemoveLinks: true,
                acceptedFiles: "image/jpeg,image/png,image/gif",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(file, response) {
                    $(imageIdSelector).val(response.image_id);
                }
            });
        }

        // Initialize Dropzone for the first row
        initializeDropzone('#image', '#image_id');

        // $(document).ready(function() {
        //     $('#birth_date').datetimepicker({
        //         // options here
        //         format: 'Y-m-d',
        //     });
        // });
    </script>
@endsection
