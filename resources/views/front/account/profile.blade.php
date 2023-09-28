@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">My Account</a></li>
                    <li class="breadcrumb-item">Settings</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-11 ">
        <div class="container  mt-5">
            <div class="row">
                <div class="col-md-12">
                    @include('front.account.common.message')
                </div>
                <div class="col-md-3">
                    @include('front.account.common.sidebar')
                </div>
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" name="profileForm" id="profileForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">
                                        <label for="name">Name</label>
                                        <input value="{{ $customer->name }}" type="text" name="name" id="name"
                                            placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ $customer->email }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone">Phone</label>
                                        <input value="{{ $customer->phone }}" maxlength="10" type="text" name="phone"
                                            id="phone" placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>


                    <div class="card mt-5">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Address</h2>
                        </div>
                        <form action="" name="addressForm" id="addressForm">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name">First Name</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}" type="text" name="first_name" id="first_name"
                                            placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="name">Last Name</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}" type="text" name="last_name" id="last_name"
                                            placeholder="Enter Your Last Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="email">Email</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}" type="text" name="email" id="email"
                                            placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6  mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}" maxlength="10" type="text" name="mobile"
                                            id="mobile" placeholder="Enter Your Mobile" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label for="district">District</label>
                                        <select name="district" id="district" class="form-control">
                                            <option value="">Select a District</option>
                                            @if ($shippingCharges->isNotEmpty())
                                                @foreach ($shippingCharges as $shippingCharge)
                                                    <option {{ (!empty($customerAddress) && $customerAddress->shipping_charge_id == $shippingCharge->id) ? 'selected' : '' }} value="{{ $shippingCharge->id }}">{{ $shippingCharge->district }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>

                                    <div class="mb-3">
                                        <label>Address</label>
                                        <textarea name="address" id="address" cols="30" rows="5" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->address : '' }}</textarea>
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Apartment</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->apartment : '' }}" type="text" name="apartment"
                                        id="apartment" placeholder="Apartment" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>City</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}" type="text" name="city"
                                        id="city" placeholder="City" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>State</label>
                                        <input readonly value="Nakhon Si Thammarat" type="text" name="state"
                                        id="state" placeholder="State" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label>Zip</label>
                                        <input value="{{ (!empty($customerAddress)) ? $customerAddress->zip : '' }}" type="text" name="zip"
                                        id="zip" placeholder="Zip" class="form-control">
                                        <p></p>
                                    </div>

                                    <div class="d-flex">
                                        <button class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('customJs')
    <script>
        $('#profileForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateProfile') }}',
                type: 'post',
                data: $(this).serializeArray(),
                success: function(response) {
                    if (response['status'] == true) {
                        window.location.href = "{{ route('account.profile') }}";
                    } else {
                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, #profileForm").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`, "#profileForm").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                        });
                    }
                }
            });
        });

        $('#addressForm').submit(function(event) {
            event.preventDefault();

            $.ajax({
                url: '{{ route('account.updateAddress') }}',
                type: 'post',
                data: $(this).serializeArray(),
                success: function(response) {
                    if (response['status'] == true) {
                        window.location.href = "{{ route('account.profile') }}";
                    } else {
                        var errors = response['errors'];

                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, #addressForm").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`, "#addressForm").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                        });
                    }
                }
            });
        });
    </script>
@endsection
