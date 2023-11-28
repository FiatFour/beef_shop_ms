@extends('front.layouts.app')

@section('content')
    <section class="section-1">
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel"
            data-bs-interval="false">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <!-- <img src="images/carousel-1.jpg" class="d-block w-100" alt=""> -->

                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/1.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/1.jpg') }}" />
                        <img src="{{ asset('front-assets/images/1.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Beef Shop</h1>
                            {{-- <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo
                                stet amet amet ndiam elitr ipsum diam</p> --}}
                            <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/2.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/2.jpg') }}" />
                        <img src="{{ asset('front-assets/images/2.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Beef Shop</h1>
                            {{-- <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo
                                stet amet amet ndiam elitr ipsum diam</p> --}}
                                <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <picture>
                        <source media="(max-width: 799px)" srcset="{{ asset('front-assets/images/3.jpg') }}" />
                        <source media="(min-width: 800px)" srcset="{{ asset('front-assets/images/3.jpg') }}" />
                        <img src="{{ asset('front-assets/images/3.jpg') }}" alt="" />
                    </picture>

                    <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                        <div class="p-3">
                            <h1 class="display-4 text-white mb-3">Beef Shop
                            </h1>
                            {{-- <p class="mx-md-5 px-5">Lorem rebum magna amet lorem magna erat diam stet. Sadips duo
                                stet amet amet ndiam elitr ipsum diam</p> --}}
                                <a class="btn btn-outline-light py-2 px-4 mt-3" href="{{ route('front.shop') }}">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    </section>
    <section class="section-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Quality Product</h5>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Fast Shipping</h2>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-cart-arrow-down text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">Easy to buy</h2>
                    </div>
                </div>
                <div class="col-lg-3 ">
                    <div class="box shadow-lg">
                        <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                        <h2 class="font-weight-semi-bold m-0">24/7 Support</h5>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="section-3">
        <div class="container">
            <div class="section-title">
                <h2>Categories</h2>
            </div>
            <div class="row pb-3">
                @if (getCategories()->isNotEmpty())
                    @foreach (getCategories() as $category)
                        <div class="col-lg-3">
                            <a href="{{ route('front.shop',$category->slug) }}">
                            <div class="cat-card">
                                <div class="left">
                                    @if ($category->image != '')
                                        <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt=""
                                            class="img-fluid">
                                            @endif
                                            {{-- <img src="{{ asset('front-assets/images/cat-1.jpg') }}" alt="" class="img-fluid"> --}}
                                        </div>
                                        <div class="right">
                                            <div class="cat-data">
                                                <h2>{{ $category->name }}</h2>
                                                {{-- <p>100 Products</p> --}}
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>
            <div class="row pb-3">
                @if ($featuredProducts->isNotEmpty())
                    @foreach ($featuredProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/' . $productImage->image) }}" style="width: 306px; height: 306px;">
                                        @else
                                            <img class="card-img-top" src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                        @endif

                                    </a>

                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                            @else
                                            <a class="btn btn-dark" href="javascript:void(0);">
                                                Out Of Stock
                                            </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="{{ route('front.product', $product->slug) }}"> ({{ getCowGeneName($product->cow_gene_id)->name }}) {!! ($product->status == 2) ? '!Pre order!'  : '' !!}<br>{{ $product->title }}</a>
                                    <h6>{{ number_format( $product->kg, 2) }} KG.</h6>
                                    <div class="price mt-2">

                                        <span class="h5"><strong>฿{{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-underline">
                                                <del>฿{{ $product->compare_price }}</del>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Latest Produsts</h2>
            </div>
            <div class="row pb-3">
                @if ($latestProducts->isNotEmpty())
                    @foreach ($latestProducts as $product)
                        @php
                            $productImage = $product->product_images->first();
                        @endphp
                        <div class="col-md-3">
                            <div class="card product-card">
                                <div class="product-image position-relative">
                                    <a href="{{ route('front.product', $product->slug) }}" class="product-img">

                                        @if (!empty($productImage->image))
                                            <img class="card-img-top"
                                                src="{{ asset('uploads/product/' . $productImage->image) }}" style="width: 306px; height: 306px;">
                                        @else
                                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}">
                                        @endif

                                    </a>
                                    <a onclick="addToWishList({{ $product->id }})" class="whishlist" href="javascript:void(0);"><i class="far fa-heart"></i></a>

                                    <div class="product-action">
                                        @if ($product->track_qty == 'Yes')
                                            @if ($product->qty > 0)
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                            @else
                                            <a class="btn btn-dark" href="javascript:void(0);">
                                                Out Of Stock
                                            </a>
                                            @endif
                                        @else
                                            <a class="btn btn-dark" href="javascript:void(0);" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> Add To Cart
                                            </a>
                                        @endif
                                    </div>

                                </div>
                                <div class="card-body text-center mt-3">
                                    <a class="h6 link" href="{{ route('front.product', $product->slug) }}"> ({{ getCowGeneName($product->cow_gene_id)->name }}) {!! ($product->status == 2) ? '<span style="color:red">!Pre order!</span>'  : '' !!}<br>{{ $product->title }}</a>
                                    <h6>{{ number_format( $product->kg, 2) }} KG.</h6>
                                    <div class="price mt-2">

                                        <span class="h5"><strong>฿{{ $product->price }}</strong></span>
                                        @if ($product->compare_price > 0)
                                            <span class="h6 text-underline">
                                                <del>฿{{ $product->compare_price }}</del>
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endsection
