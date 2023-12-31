<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Beef Shop :: Administrative Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4" style="margin-top: 45px">
                <h4>Administrative Panel</h4>
                <form action="{{ route('checkLogin') }}" method="POST" autocomplete="off">
                    @if(Session::get('fail'))
                        <div class="alert alert-danger">
                            {{ Session::get('fail') }}
                        </div>
                    @endif

                    @if(Session::get('info'))
                        <div class="alert alert-info">
                            {{ Session::get('info') }}
                        </div>
                    @endif

                    @csrf
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" class="form-control" name="email" placeholder="Email" value="{{ Session::get('verifiedEmail') ? Session::get('verifiedEmail') : old('email') }}">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="Password" value="{{ old('password')}}">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <a href="{{ route('forgotPasswordForm') }}">Forgot Password?</a>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                    <br>
                    {{-- <a href="{{ route('customer.registerCustomer') }}">สร้างบัญชีผู้ใช้</a> --}}
                </form>
            </div>
        </div>
    </div>
</body>
</html>
