<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/gbhl-logo.jpg') }}" >
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=YourDesiredFont">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Domain Management Tool</title>
</head>
<body>
    <div class="container">
    <div class="formContainer text-center">
        <form class="signupForm" action="{{ route('login-user') }}" method='POST'>
                @if(Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
                @endif
                @if(Session::has('fail'))
            <div class="alert alert-danger">{{Session::get('fail')}}</div>
                @endif
            <div class="form-group my-3">
                <img src="{{ asset('images/bslogo.png') }}" alt="Logo" style="max-width: 70%;">
                <h4>Login</h4>

                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="" required class="form-control" id="email" name="email" placeholder="Enter Email" value="{{old ('email')}}">
                    <span class="text-danger">@error('email') {{$message}} @enderror</span>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" required class="form-control" id="password" name="password" placeholder="Enter Password" value="{{old ('password')}}">
                    <span class="text-danger">@error('password') {{$message}} @enderror</span>
                </div>

                <button class="btn btn-nav fw-bold" type="submit">Login</button>
            </div>
        </form>
    </div>  
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set the current year dynamically
        document.addEventListener('DOMContentLoaded', function () {
            let currentYear = new Date().getFullYear();
            document.getElementById('copyright').innerHTML =
                '&copy; Bulkstream Limited ' + currentYear;
        });
    </script>
</body>
</html>
