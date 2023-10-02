@extends('admin.layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Cow</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('admin.cows.index') }}" class="btn btn-primary">Back</a>
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

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label>Cow Gene</label>
                                    <select name="cow_gene_id" id="cow_gene_id" class="form-control">
                                        <option value="">Select a cow gene</option>
                                        @if ($cowGenes->isNotEmpty())
                                            @foreach ($cowGenes as $cowGene)
                                                <option {{ ( $cow->cow_gene_id == $cowGene->id) ? 'selected' : ''}}  value="{{ $cowGene->id }}">{{ $cowGene->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="weight">Weight</label>
                                    <input type="number" name="weight" id="weight" class="form-control"
                                        placeholder="Weight" value="{{ $cow->weight }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="height">Height</label>
                                    <input type="number" name="height" id="height" class="form-control"
                                        placeholder="Height" value="{{ $cow->height }}">
                                    <p></p>
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Birth date</label>
                                    <input type="date" name="birth" id="birth" class="form-control" value="{{ $cow->birth }}">
                                    {{-- <input autocomplete="off" type="text" name="birth_date" id="birth_date"
                                        class="form-control" placeholder="Birth date"> --}}
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="hidden" id="image_id" name="image_id" value="">
                                    <label for="image">Image</label>
                                    <div id="image" class="dropzone dz-clickable" >
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
                                            <input {{ ($cow->gender == 'Man') ? 'checked' : '' }} class="form-check-input" type="radio" name="gender" id="gender"
                                                value="Man">
                                            <label class="form-check-label">Man</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input {{ ($cow->gender == 'Woman') ? 'checked' : '' }} class="form-check-input" type="radio" name="gender" id="gender"
                                                value="Woman">
                                            <label class="form-check-label">Woman</label>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Last weight</label>
                                    <input type="number" name="last_weight" id="last_weight" class="form-control"
                                        placeholder="Last Weight" value="{{ $cow->last_weight }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Last height</label>
                                    <input type="number" name="last_height" id="last_height" class="form-control"
                                        placeholder="Last Height" value="{{ $cow->last_height }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Dissect date</label>
                                    <input autocomplete="off" type="text" name="dissect_date" id="dissect_date"
                                        class="form-control" placeholder="Dissect date" value="{{ $cow->dissect_date }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label>Dissect total KG.</label>
                                    <input type="number" name="dissect_total_kg" id="dissect_total_kg" class="form-control"
                                        placeholder="Dissect total KG" value="{{ $cow->dissect_total_kg }}">
                                    <p></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="{{ route('admin.cows.index') }}" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customJs')
    <script>
        $("#cowForm").submit(function(event) {
            event.preventDefault();
            var element = $(this);
            $("button[type=submit]").prop('disabled', true);
            $.ajax({
                url: "{{ route('admin.cows.update', $cow->id) }}",
                type: 'put',
                data: element.serializeArray(),
                dataType: 'json',
                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);

                    if (response['status'] == true) {
                        window.location.href = "{{ route('admin.cows.index') }}";
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

        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
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
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });

        $(document).ready(function() {
            $('#dissect_date').datetimepicker({
                // options here
                format: 'Y-m-d H:i:s',
            });
        });

    </script>
@endsection
