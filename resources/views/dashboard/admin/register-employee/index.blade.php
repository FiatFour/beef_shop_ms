<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
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

                <li class="nav-item active">
                  <a class="nav-link" href="{{ route('admin.supplier') }}">Supplier</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Dropdown
                  </a>
                  <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </div>
                </li>
                <li class="nav-item">
                  <a class="nav-link disabled" href="#">Disabled</a>
                </li>
              </ul>
              <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a  class="btn btn-outline-danger my-2 my-sm-0" href="{{ route('customer.logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">Logout</a>
                    <form action=" {{ route('employee.logout') }}" method="POST" class="d-none" id="logout-form">@csrf</form>
              </form>
            </div>
          </nav>
    </header>

    <div class="py-4" >
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    @if(session('success'))
                        <div class="alert alert-success">{{session('success')}}</div>
                    @endif
                    <div class="card">
                        <div class="card-header">Supplier Table</div>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Supplier Name</th>
                                <th scope="col">Supplier Address</th>
                                <th scope="col">Supplier Tel</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Edit</th>
                                <th scope="col">Delete</th>

                              </tr>
                            </thead>
                            <tbody>
                                @foreach($suppliers as $supplier)
                                    <tr>
                                        <th>{{$suppliers->firstItem()+$loop->index}}</th>
                                        <td>{{$supplier->sup_name}}</td>
                                        <td>{{$supplier->sup_address}}</td>
                                        <td>{{$supplier->sup_tel}}</td>
                                        {{-- <td>{{$supplier->user->name}}</td>  Eloquent --}}
                                        {{-- <td>{{$supplier->name}}</td> Query Builder  --}}
                                        {{-- <td>{{Carbon\Carbon::parse($supplier->created_at)->diffForHumans()}}</td> --}}
                                        <td>
                                            @if($supplier->created_at == NULL)
                                                Not have a value in Created_at
                                            @else
                                            {{Carbon\Carbon::parse($supplier->created_at)->diffForHumans()}}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{url('admin/supplier/edit/'.$supplier->sup_id)}}" class="btn btn-warning">Edit</a>
                                        </td>
                                        <td>
                                            <a href="{{url('admin/supplier/softdelete/'.$supplier->sup_id)}}" class="btn btn-danger">Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                            {{$suppliers->links()}}
                    </div>
                    @if(count($trashSuppliers) > 0)
                    <div class="card my-2">
                        <div class="card-header">Trash Table</div>
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col">No.</th>
                                <th scope="col">Supplier Name</th>
                                <th scope="col">Supplier Address</th>
                                <th scope="col">Supplier Tel</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Restore</th>
                                <th scope="col">Permanently Delete</th>

                              </tr>
                            </thead>
                            <tbody>
                                @foreach($trashSuppliers as $supplier)
                                    <tr>
                                        <th>{{$trashSuppliers->firstItem()+$loop->index}}</th>
                                        <td>{{$supplier->sup_name}}</td>
                                        <td>{{$supplier->sup_address}}</td>
                                        <td>{{$supplier->sup_tel}}</td>
                                        {{-- <td>{{$supplier->user->name}}</td>  Eloquent --}}
                                        {{-- <td>{{$supplier->name}}</td> Query Builder  --}}
                                        {{-- <td>{{Carbon\Carbon::parse($supplier->created_at)->diffForHumans()}}</td> --}}
                                        <td>
                                            @if($supplier->created_at == NULL)
                                                Not have a value in Created_at
                                            @else
                                            {{Carbon\Carbon::parse($supplier->created_at)->diffForHumans()}}
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{url('admin/supplier/restore/'.$supplier->sup_id)}}" class="btn btn-warning">Restore</a>
                                        </td>
                                        <td>
                                            <a href="{{url('admin/supplier/delete/'.$supplier->sup_id)}}" class="btn btn-danger">Permanently Delete</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>
                          {{$trashSuppliers->links()}}
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Supplier Form</div>
                        <div class="card-body">
                            <form action="{{route('admin.addSupplier')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="sup_name">Supplier Name</label>
                                    <input type="text" class="form-control" name="sup_name">

                                    <label for="sup_address">Supplier Address</label>
                                    <input type="text" class="form-control" name="sup_address">

                                    <label for="sup_tel">Supplier Tel</label>
                                    <input type="tel" class="form-control" name="sup_tel">
                                </div>
                                @error('sup_name')
                                    <div class="my-2">
                                        <span class="text-danger my-2">{{$message}}</span>
                                    </div>
                                @enderror

                                @error('sup_address')
                                    <div class="my-2">
                                        <span class="text-danger my-2">{{$message}}</span>
                                    </div>
                                @enderror

                                @error('sup_tel')
                                    <div class="my-2">
                                        <span class="text-danger my-2">{{$message}}</span>
                                    </div>
                                @enderror
                                <br>
                                <button value="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ mix('/js/app.js') }}"></script>
</html>
