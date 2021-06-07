<!DOCTYPE html>
<html lang="en" >
<head>
    <meta charset="UTF-8">
    <title>Private Chat Laravel Vue Sign in</title>
    <link rel="chat icon" href="{{ asset('favicon.ico') }}" >
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes"><link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<!-- partial:index.partial.html -->
<div class="cont">
    <div  style="text-align: left">

        <p class="login__signup">TEST LOGIN 1: <a>test@gmail.com</a></p>
        <p class="login__signup">PASSWORD 1:  &nbsp;<a>12345678</a></p>
        <br>
        <p class="login__signup">TEST LOGIN 2: <a>test2@gmail.com</a></p>
        <p class="login__signup">PASSWORD 2:  &nbsp;<a>12345678</a></p>
        <br>
        <p class="login__signup">TEST LOGIN ADMIN: <a>lugamafe@gmail.com</a></p>
        <p class="login__signup">PASSWORD ADMIN:  &nbsp;<a>12345678</a></p>
    </div>
    <div class="demo">


        <div class="login">

            <div class="login__check"></div>

            <form method="POST" action="{{ route('login') }}">

                @csrf
                <div class="login__form">



                    <div class="login__row">
                        <svg class="login__icon name svg-icon" viewBox="0 0 20 20">
                            <path d="M0,20 a10,8 0 0,1 20,0z M10,0 a4,4 0 0,1 0,8 a4,4 0 0,1 0,-8" />
                        </svg>
                        <input type="text"  name="username" class="login__input name"  placeholder="Username" value="test@gmail.com"/>
                        @error('username')
                        <span class="invalid-feedback login__signup" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="login__row">
                        <svg class="login__icon pass svg-icon" viewBox="0 0 20 20">
                            <path d="M0,20 20,20 20,8 0,8z M10,13 10,16z M4,8 a6,8 0 0,1 12,0" />
                        </svg>
                        <input type="password" name="password" class="login__input pass" placeholder="Password" value="12345678"/>
                        @error('password')
                        <span class="invalid-feedback login__signup" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>

                    <button type="submit" class="login__submit ">Sign in</button>
                    <p  class="login__signup">Don't have an account? &nbsp;<a href="{{ route('register') }}">Sign up</a></p>
                </div>
            </form>

        </div>

        <div class="app">
            <div class="app__top">

            </div>

            <div class="app__logout">
                <svg class="app__logout-icon svg-icon" viewBox="0 0 20 20">
                    <path d="M6,3 a8,8 0 1,0 8,0 M10,0 10,12"/>
                </svg>
            </div>
        </div>


    </div>
</div>
</body>
</html>
