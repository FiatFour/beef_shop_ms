<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cow index</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
              <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('admin.home') }}">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="{{ route('admin.supplier') }}">Supplier</a>
                </li>


                <li class="nav-item active">
                    <a class="nav-link" href="#">Cow</a>
                  </li>
              </ul>
              <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a  class="btn btn-outline-danger my-2 my-sm-0" href="{{ route('customer.logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">Logout</a>
                    <form action=" {{ route('admin.logout') }}" method="POST" class="d-none" id="logout-form">@csrf</form>
              </form>
            </div>
          </nav>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-md-12">

                @if(session('message'))
                    <div class="alert alert-success">{{ session('message')}} </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 style="position: relative">Cow Details
                            <a href="{{ route('admin.createCow') }}" class="btn btn-primary float-end" style="position: absolute; right:0;">Add cow</a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-borered">
                            <thead>
                                <th>No</th>
                                <th>Cow gene</th>
                                <th>Cow image</th>
                                <th>Cow birth</th>
                                <th>Supplier name</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @php
                                    $rowNumber = 1;
                                @endphp

                                @foreach($cows as $cow)
                                <tr>
                                    <th>{{ $rowNumber++ }}</th>
                                    {{-- <td>55</td> --}}
                                    <td>{{$cow->cow_gene}}</td>
                                    <td>
                                        <img src="{{ asset($cow->cow_img) }}" alt="" width="100px" height="100px">
                                    </td>
                                    <td>
                                        {{Carbon\Carbon::parse($cow->cow_birth)->diffForHumans()}}
                                    </td>
                                    <td>{{$cow->supplier->sup_name}}</td>
                                    {{-- <td>{{$cow->user->name}}</td>  Eloquent --}}
                                    {{-- <td>{{$cow->name}}</td> Query Builder  --}}
                                    {{-- <td>{{Carbon\Carbon::parse($cow->created_at)->diffForHumans()}}</td> --}}


                                    {{--! <td>
                                        @if($cow->created_at == NULL)
                                            Not have a value in Created_at
                                        @else
                                        {{Carbon\Carbon::parse($cow->created_at)->diffForHumans()}}
                                        @endif
                                    </td> --}}

                                    <td>
                                        <a href="{{url('admin/cows/edit/'.$cow->cow_id)}}" class="btn btn-warning">Edit</a>
                                        <a href="{{url('admin/cows/delete/'.$cow->cow_id)}}" class="btn btn-danger">Delete</a>
                                    </td>

                                    {{--! <td>
                                        <a href="{{url('admin/cow/softdelete/'.$cow->cow_id)}}" class="btn btn-danger">Delete</a>
                                    </td> --}}
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
