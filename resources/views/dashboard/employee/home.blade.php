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
                <li class="nav-item active">
                  <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="{{ route('admin.supplier') }}">Supplier</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link" href="{{ route('admin.cow') }}">Cow</a>
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

    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3" style="margin-top: 45;">
                <h4>Employee Dashboard</h4>
                <table class="table table-striped table-inverse table-responsive">
                    <thead class="thead-inverse">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td scope="row">{{ Auth::guard('employee')->user()->emp_name }}</td>
                            <td>{{ Auth::guard('employee')->user()->email }}</td>
                            <td>{{ Auth::guard('employee')->user()->emp_tel }}</td>
                            <td>
                                <a href="{{ route('employee.logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">Logout</a>
                                <form action=" {{ route('employee.logout') }}" method="POST" class="d-none" id="logout-form">@csrf</form>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
