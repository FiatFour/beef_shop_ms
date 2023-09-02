<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ลืมรหัสผ่าน</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4" style="margin-top: 45px">
                <h4>ลืมรหัสผ่าน</h4>
                <form action="{{ route('resetPasswordLink') }}" method="POST" autocomplete="off">
                    @if(Session::get('fail'))
                        <div class="alert alert-danger">
                            {{ Session::get('fail') }}
                        </div>
                    @endif

                    @if(Session::get('success'))
                        <div class="alert alert-success">
                            {{ Session::get('success') }}
                        </div>
                    @endif

                    @csrf
                    <p>
                        กรอกอีเมล์ของคุณ, เราจะทำการส่งลิ้งเพื่อทำการรีเช็ตรหัสผ่านของคุณ
                    </p>
                    <div class="form-group">
                        <label for="email">อีเมล์</label>
                        <input type="text" class="form-control" name="email" placeholder="กรอกอีเมล์" value="{{ Session::get('verifiedEmail') ? Session::get('verifiedEmail') : old('email') }}">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">ยืนยัน</button>
                    </div>
                    <br>
                    <a href="{{ route('login') }}">ลงชื่อเข้าใช้</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
