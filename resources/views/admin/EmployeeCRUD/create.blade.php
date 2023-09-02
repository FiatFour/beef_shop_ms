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
                <div class="card">
                    <div class="card-header">
                        <h4 style="position: relative">เพึ่มข้อมูลพนักงาน
                            <a href="{{ route('admin.employee') }}" class="btn btn-primary float-end" style="position: absolute; right:0;">BACK</a>
                        </h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('admin.addEmployee') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                                @if(Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if(Session::get('fail'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('fail') }}
                                    </div>
                                @endif

                            <div class="form-group">
                                <label for="name">ชื่อ</label>
                                <input type="text" class="form-control" name="name" placeholder="" value="{{ old('name') }}">
                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="last name">นามสกุล</label>
                                <input type="text" class="form-control" name="lname" placeholder="Enter name" value="{{ old('lname') }}">
                                @error('lname') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>ที่อยู่</label>
                                <input type="text" class="form-control" name="address" placeholder="Enter name" value="{{ old('address') }}">
                                @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>เบอร์โทร</label>
                                <input type="tel" class="form-control" name="tel" placeholder="Enter name" value="{{ old('tel') }}">
                                @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="email">อีเมล์</label>
                                <input type="text" class="form-control" name="email" placeholder="Enter email address" value="{{ old('email') }}">
                                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">รหัสผ่าน</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter password" value="{{ old('password') }}">
                                @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="confirm password">ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" name="confirm_password" placeholder="Enter confirm password" value="{{ old('confirm_password') }}">
                                @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>เพศ :</label>
                                <input type="radio" value="1" name="gender" value="{{ old('gender') }}"> ชาย
                                <input type="radio" value="2" name="gender" value="{{ old('gender') }}"> หญิง
                                @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label>รูป</label>
                                <input type="file" name="img" class="form-control" accept=".png, .jpg, .jpeg">
                                @error('img') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <br>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
