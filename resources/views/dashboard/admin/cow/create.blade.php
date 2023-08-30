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
                <div class="card">
                    <div class="card-header">
                        <h4 style="position: relative">Add Cow
                            <a href="{{ route('admin.cow') }}" class="btn btn-primary float-end" style="position: absolute; right:0;">BACK</a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.addCow') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label>Select Supplier</label>
                            </div>
                            <select name="sup_id" class="form-control">
                                @foreach($suppliers as $item)
                                    <option value="{{ $item->sup_id }}">{{ $item->sup_name }}</option>
                                @endforeach
                            </select>

                            <div class="mb-3">
                                <label for="Cow gene">Cow Gene</label>
                                <input type="text" name="cow_gene" class="form-control">
                            </div>
                            @error('cow_gene')
                            <div class="my-2">
                                <span class="text-danger my-2">{{$message}}</span>
                            </div>
                            @enderror

                            <div class="mb-3">
                                <label for="Cow image">Cow image</label>
                                <input type="file" name="cow_img" class="form-control" accept=".png, .jpg, .jpeg">
                            </div>
                            @error('cow_img')
                                <div class="my-2">
                                    <span class="text-danger my-2">{{$message}}</span>
                                </div>
                            @enderror

                            <div class="mb-3">
                                <label for="Cow birth">Cow birth</label>
                                <input type="date" name="cow_birth" class="form-control">
                            </div>
                            @error('cow_birth')
                            <div class="my-2">
                                <span class="text-danger my-2">{{$message}}</span>
                            </div>
                            @enderror

                            <br>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
