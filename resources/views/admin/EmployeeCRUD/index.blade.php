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

                <li class="nav-item active">
                    <a class="nav-link" href="#">จัดการพนักงาน</a>
                </li>
              </ul>

              <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <a  class="btn btn-outline-danger my-2 my-sm-0" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">Logout</a>
                    <form action=" {{ route('logout') }}" method="POST" class="d-none" id="logout-form">@csrf</form>
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
                        <h4 style="position: relative">ข้อมูลพนักงาน
                            <a href="{{ route('admin.createEmployee') }}" class="btn btn-primary float-end" style="position: absolute; right:0;">เพึ่มข้อมูลพนักงาน</a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <table class="table table-borered">
                            <thead>
                                <th>No</th>
                                <th>รูป</th>
                                <th>ชื่อ</th>
                                <th>นามสกุล</th>
                                <th>หน้าที่</th>
                                <th>ที่อยู่</th>
                                <th>เพศ</th>
                                <th>เบอร์โทร</th>
                                <th>อีเมล์</th>
                                <th>ลงทะเบียนเมื่อ</th>
                                <th>Action</th>
                            </thead>

                            <tbody>
                                @php
                                    $rowNumber = 1;
                                @endphp

                                @foreach($employees as $row)
                                <tr>
                                    <th>{{ $rowNumber++ }}</th>
                                    <td>
                                        <img src="{{ asset($row->emp_img) }}" alt="" width="100px" height="100px">
                                    </td>
                                    <td>{{$row->emp_name}}</td>
                                    <td>{{$row->emp_lname}}</td>
                                    <td>{{$row->emp_department}}</td>
                                    <td>{{$row->emp_address}}</td>
                                    <td>{{$row->emp_gender}}</td>
                                    <td>{{$row->emp_tel}}</td>
                                    <td>{{$row->email}}</td>
                                    <td>
                                        {{Carbon\Carbon::parse($row->created_at)->diffForHumans()}}
                                    </td>
                                    <td>
                                        <a href="{{url('admin/employees/edit/'.$row->emp_id)}}" class="btn btn-warning">Edit</a>
                                        <a href="{{url('admin/employees/delete/'.$row->emp_id)}}" class="btn btn-danger">Delete</a>
                                    </td>
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
</html>
