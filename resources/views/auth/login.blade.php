<!DOCTYPE html>
<html>
<head>
    <title>Animated Login Form</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/style.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a81368914c.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<img class="wave" src="{{ asset('assets/login/img/wave.png') }}">
<div class="container">
    <div class="img">
        <img src="{{ asset('assets/login/img/bg.svg') }}">
    </div>
    <div class="login-content">
        <form action="{{ route('login') }}" method="post">
            @csrf
            <img src="{{ asset('assets/login/img/avatar.svg') }}">
            <h2 class="title">Welcome</h2>

            @if(session()->has('error'))
                {{ session('error') }}
            @endif

            <div class="input-div one">
                <div class="i">
                    <i class="fas fa-user"></i>
                </div>
                <div class="div">
                    <h5>Username</h5>
                    <input type="text" class="input" name="email">
                </div>
            </div>
            @error('email')
            <span style="color: red;"> {{ $message }}</span>
            @enderror
            <div class="input-div pass">
                <div class="i">
                    <i class="fas fa-lock"></i>
                </div>
                <div class="div">
                    <h5>Password</h5>
                    <input type="password" class="input" name="password">
                </div>
            </div>
            @error('password')
            <span style="color: red;"> {{ $message }}</span>
            @enderror
            <input type="submit" class="btn" value="Login">
        </form>
    </div>
</div>
<script type="text/javascript" src="{{ asset('assets/login/js/main.js') }}"></script>
</body>
</html>
