@extends('front.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="" id="orderForm" name="orderForm" method="POST">
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Shipping Address</h2>
                        </div>
                        <div class="card shadow-lg border-0 ">
                            <div class="card-body checkout-form">
                                {{-- <div class="">
                                    <input type="radio" name="shipping_method" value="cod" id="pick_up">
                                    <label for="pick_up" class="form-check-label">Pick up the item by myself</label>
                                </div>

                                <div class="">
                                    <input checked type="radio" name="shipping_method" value="cod" id="shipping">
                                    <label for="shipping" class="form-check-label">Shiping</label>
                                </div> --}}
                                <div class="row" id="shipping-form">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="first_name" id="first_name" class="form-control"
                                                placeholder="First Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->first_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="last_name" id="last_name" class="form-control"
                                                placeholder="Last Name"
                                                value="{{ !empty($customerAddress) ? $customerAddress->last_name : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email"
                                                value="{{ !empty($customerAddress) ? $customerAddress->email : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <select name="district" id="district" class="form-control">

                                                <option value="">Select a District</option>
                                                @if ($shippingCharges->isNotEmpty())
                                                    @foreach ($shippingCharges as $shippingCharge)
                                                        <option
                                                            {{ !empty($customerAddress) && $customerAddress->shipping_charge_id == $shippingCharge->id ? 'selected' : '' }}
                                                            value="{{ $shippingCharge->id }}">
                                                            {{ $shippingCharge->district }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12 shipping">
                                        <div class="mb-3">
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control">{{ !empty($customerAddress) ? $customerAddress->address : '' }}</textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12 shipping">
                                        <div class="mb-3">
                                            <input type="text" name="apartment" id="apartment" class="form-control"
                                                placeholder="Apartment, suite, unit, etc. (optional)"
                                                value="{{ !empty($customerAddress) ? $customerAddress->apartment : '' }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4 shipping">
                                        <div class="mb-3">
                                            <input type="text" name="city" id="city" class="form-control"
                                                placeholder="City"
                                                value="{{ !empty($customerAddress) ? $customerAddress->city : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 shipping">
                                        <div class="mb-3">
                                            <input readonly type="text" name="state" id="state"
                                                class="form-control" placeholder="State" value="Nakhon Si Thammarat">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-4 shipping">
                                        <div class="mb-3">
                                            <input type="text" name="zip" id="zip" class="form-control"
                                                placeholder="Zip"
                                                value="{{ !empty($customerAddress) ? $customerAddress->zip : '' }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <input type="text" name="mobile" id="mobile" class="form-control"
                                                placeholder="Mobile No." maxlength="10"
                                                value="{{ !empty($customerAddress) ? $customerAddress->mobile : '' }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)"
                                                class="form-control">{{ !empty($customerAddress) ? $customerAddress->notes : '' }}</textarea>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Summery</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body"></div>
                            @foreach (Cart::content() as $item)
                                <div class="d-flex justify-content-between pb-2">
                                    <div class="h6">{{ (getProduct($item->id)->status == 2) ? '(Pre order) ' : ''  }} {{ $item->name }} X {{ $item->qty }}</div>
                                    <div class="h6">฿{{ $item->price * $item->qty }}</div>
                                </div>
                            @endforeach
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                <div class="h6"><strong>฿{{ Cart::subtotal() }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Discount</strong></div>
                                <div class="h6"><strong id="discount_value">฿{{ $discount }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong
                                        id="shippingAmount">฿{{ number_format($totalShippingCharge, 2) }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="grandTotal">฿{{ number_format($grandTotal, 2) }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="input-group apply-coupan mt-4">
                            <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code"
                                id="discount_code">
                            <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                        </div>


                        <div id="discount-response-wrapper">
                            @if (Session::has('code'))
                                <div class="mt-4" id="discount-response">
                                    <b>{{ Session::get('code')->code }}</b>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                                </div>
                            @endif
                        </div>

                        <div class="card payment-form ">
                            <h3 class="card-title h5 mb-3">Payment Method</h3>
                            <div>
                                <input checked type="radio" name="payment_method" value="cod"
                                    id="payment_method_one">
                                <label for="payment_method_one" class="form-check-label">COD</label>
                            </div>

                            <div class="">
                                <input type="radio" name="payment_method" value="promptpay" id="payment_method_two">
                                <label for="payment_method_two" class="form-check-label">PromptPay</label>
                            </div>

                            <div class="card-body p-0 d-none" id="card-payment-form">
                                <div class="mb-3">
                                    <img src="{{ asset('image/promptpay/promptpay.jpg') }}" alt="">
                                </div>
                                <div class="row">
                                    <div class="mb-3">
                                        <input type="hidden" id="image_id" name="image_id" value="">
                                        <label for="image" class="mb-2">Upload a transfer slip photo ⬇️</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">
                                                <br>Drop files here or click to upload.<br><br>
                                            </div>
                                        </div>
                                        <p></p>
                                    </div>
                                </div>
                            </div>



                            {{-- <div class="card-body p-0 d-none" id="card-payment-form">
                                <div class="mb-3">
                                    <label for="card_number" class="mb-2">Card Number</label>
                                    <input type="text" name="card_number" id="card_number"
                                        placeholder="Valid Card Number" class="form-control">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">Expiry Date</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY"
                                            class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="expiry_date" class="mb-2">CVV Code</label>
                                        <input type="text" name="expiry_date" id="expiry_date" placeholder="123"
                                            class="form-control">
                                    </div>
                                </div>
                            </div> --}}

                            <div class="pt-4">
                                {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                                <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                            </div>
                        </div>
                    </div>



                    <!-- CREDIT CARD FORM ENDS HERE -->

                </div>
            </form>
        </div>
        {{-- </div> --}}
    </section>
@endsection

@section('customJs')
    <script>
        $('#payment_method_one').click(function() {
            if ($(this).is(":checked") == true) {
                $('#card-payment-form').addClass('d-none');
            }
        });

        $('#payment_method_two').click(function() {
            if ($(this).is(":checked") == true) {
                $('#card-payment-form').removeClass('d-none');
            }
        });
        $('#pick_up').click(function() {
            if ($(this).is(":checked") == true) {
                $('#shipping-form').addClass('d-none');
            }
        });

        $('#shipping').click(function() {
            if ($(this).is(":checked") == true) {
                $('#shipping-form').removeClass('d-none');
            }
        });

        $('#district').change(function() {
            if ($('#district').val() == 2) {
                $('.shipping').addClass('d-none');
            } else {
                $('.shipping').removeClass('d-none');
            }
        });

        // $('#pay_img').change(function() {
        //     if ($('#pay_img').val() != '') {
        //         $('button[type="submit"]').prop('disabled', false);

        //     } else {
        //         $('button[type="submit"]').prop('disabled', true);
        //     }
        // });

        $('#orderForm').submit(function(event) {
            event.preventDefault();

            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: '{{ route('front.processCheckout') }}',
                type: 'post',
                data: $(this).serializeArray(),
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {

                    if (response['status'] == true) {
                        // $('.error').removeClass('invalid-feedback').html('');
                        // $("input[type='text'], select, textarea").removeClass('is-invalid');

                        window.location.href = "{{ url('thanks/') }}/" + response.orderId;
                    } else {
                        var errors = response['errors'];
                        $('button[type="submit"]').prop('disabled', false);

                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, textarea").removeClass('is-invalid');

                        $.each(errors, function(key, value) {
                            $(`#${key}`).addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(value);
                        });
                    }
                }
            });
        })

        $('#district').change(function() {
            $.ajax({
                url: '{{ route('front.getOrderSummery') }}',
                type: 'post',
                data: {
                    shipping_charge_id: $(this).val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $('#shippingAmount').html('฿' + response.shippingCharge);
                        $('#grandTotal').html('฿' + response.grandTotal);

                    }
                }
            });
        });

        $('#apply-discount').click(function() {
            $.ajax({
                url: '{{ route('front.applyDiscount') }}',
                type: 'post',
                data: {
                    code: $('#discount_code').val(),
                    district: $('#district').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $('#shippingAmount').html('฿' + response.shippingCharge);
                        $('#grandTotal').html('฿' + response.grandTotal);
                        $('#discount_value').html('฿' + response.discount);
                        $('#discount-response-wrapper').html(response.discountString)
                    } else {
                        $('#discount-response-wrapper').html("<span class='text-danger'>" + response
                            .message + "</span>")
                    }
                }
            });
        });

        $('body').on('click', '#remove-discount', function() {
            $.ajax({
                url: '{{ route('front.removeDiscount') }}',
                type: 'post',
                data: {
                    district: $('#district').val()
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status == true) {
                        $('#shippingAmount').html('฿' + response.shippingCharge);
                        $('#grandTotal').html('฿' + response.grandTotal);
                        $('#discount_value').html('฿' + response.discount);
                        $('#discount-response').html('');
                        $('#discount_code').val('');

                    }
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
            url: "{{ route('temp-images.create') }}",
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
    </script>
@endsection
