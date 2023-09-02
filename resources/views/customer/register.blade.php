<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ลูกค้า | สมัครบัญชี</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4" style="margin-top: 10px">
                <h4>สมัครบัญชี</h4>
                <form action="{{ route('customer.createCustomer') }}" method="POST" autocomplete="off">
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

                    @csrf
                    <div class="form-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" class="form-control" name="name" placeholder="ชื่อ" value="{{ old('name') }}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label>นามสกุล</label>
                        <input type="text" class="form-control" name="lname" placeholder="นามสกุล" value="{{ old('lname') }}">
                        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label >ที่อยู่</label>
                        <input type="text" class="form-control" name="address" placeholder="ที่อยู่" value="{{ old('address') }}">
                        @error('address') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label >เบอร์โทรศัพท์</label>
                        <input type="tel" pattern="[0]{1}[0-9]{9}" class="form-control" name="tel" placeholder="เบอร์โทรศัพท์" value="{{ old('tel') }}">
                        @error('tel') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">อีเมลล์</label>
                        <input type="text" class="form-control" name="email" placeholder="อีเมลล์" value="{{ old('email') }}">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" placeholder="รหัสผ่าน" value="{{ old('password') }}">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="confirm password">ยืนยันรหัสผ่าน</label>
                        <input type="password" class="form-control" name="confirm_password" placeholder="ยืนยันรหัสผ่าน" value="{{ old('confirm_password') }}">
                        @error('confirm_password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-check form-check-inline">เพศ: </label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio1" value="1">
                            <label class="form-check-label" for="inlineRadio1">ชาย</label>
                          </div>
                          <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="inlineRadio2" value="2">
                            <label class="form-check-label" for="inlineRadio2">หญิง</label>
                          </div>
                          @error('gender') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">สมัคร</button>
                    </div>
                    <a href="{{ route('login') }}">ฉันมีบัญชีผู้ใช้อยู่แล้ว</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
