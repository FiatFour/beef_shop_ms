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
                  <a class="nav-link" href="#">Supplier</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.cow') }}">Cow</a>
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


    <div class="py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Supplier Edit form</div>
                        <div class="card-body">
                            <form action="{{url('admin/supplier/update/'.$supplier->sup_id)}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="supplier_name">Supplier Name</label>
                                    <input type="text" class="form-control" name="sup_name" value="{{$supplier->sup_name}}">
                                </div>
                                @error('sup_name')
                                    <div class="my-2">
                                        <span class="text-danger my-2">{{$message}}</span>
                                    </div>
                                @enderror
                                <br>
                                <button value="submit" class="btn btn-warning">Update</button>
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
