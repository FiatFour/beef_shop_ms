<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ลงชื่อเข้าใช้</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-4 offset-md-4" style="margin-top: 45px">
                <h4>ลงชื่อเข้าใช้</h4>
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
                        <label for="email">อีเมลล์</label>
                        <input type="text" class="form-control" name="email" placeholder="กรอกอีเมลล์" value="{{ Session::get('verifiedEmail') ? Session::get('verifiedEmail') : old('email') }}">
                        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" class="form-control" name="password" placeholder="กรอกรหัสผ่าน" value="{{ old('password')}}">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <a href="{{ route('forgotPasswordForm') }}">ลืมรหัสผ่าน</a>

                    <div class="form-group mt-2">
                        <button type="submit" class="btn btn-primary">ลงชื่อเข้าใช้</button>
                    </div>
                    <br>
                    <a href="{{ route('customer.registerCustomer') }}">สร้างบัญชีผู้ใช้</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>



<!--
  This example requires some changes to your config:

  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
<!--
  This example requires updating your template:

  ```
  <html class="h-full bg-white">
  <body class="h-full">
  ```
-->

{{-- <!DOCTYPE html>
<html lang="en" class="h-full bg-white">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full">

    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-sm">
          <img class="mx-auto h-10 w-auto" src="https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600" alt="Your Company">
          <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
        </div>

        <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
          <form class="space-y-6" action="{{ route('check') }}" method="POST" autocomplete="off">
            @csrf
            @if(Session::get('fail'))
            <div role="alert">
                <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                  Danger
                </div>
                <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                  <p>{{ Session::get('fail') }}</p>
                </div>
              </div>
            @endif

            @if(Session::get('info'))
            <div class="bg-blue-100 border-t border-b border-blue-500 text-blue-700 px-4 py-3" role="alert">
                <p class="font-bold">Informational message</p>
                <p class="text-sm">{{ Session::get('info') }}</p>
            </div>
            @endif

            <div>
              <label for="email" class="block text-sm font-medium leading-6 text-gray-900 px-2">Email address</label>
              <div class="mt-2">
                <input id="email" name="email" type="email" autocomplete="email" required class="px-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                @error('email') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            </div>

            <div>
              <div class="flex items-center justify-between">
                <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                <div class="text-sm">
                  <a href="{{ route('forgot.password.form') }}" class="font-semibold text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                </div>
              </div>
              <div class="mt-2">
                <input id="password" name="password" type="password" autocomplete="current-password" required class="px-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
              </div>
            </div>

            <div>
              <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
            </div>
          </form>

          <p class="mt-10 text-center text-sm text-gray-500">
            Not a member?
            <a href="{{ route('customer.register') }}" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">Create new Accont</a>
          </p>
        </div>
      </div>
</body>
</html> --}}
