<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>
<body style="background-color: #55b1e2">
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
