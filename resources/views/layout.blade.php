<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<div class="row justify-content-center top-menu bg-light">
    <div class="col-8">
        <nav class="navbar navbar-light">
            <a class="navbar-brand" href="/">ABZ.HOMEWORK</a>
            <ul class="navbar-nav mr-auto d-inline-block">
                <li class="nav-item d-inline-block">
                    <a class="nav-link" href="{{route('taskone')}}">Task 1</a>
                </li>
                <li class="nav-item d-inline-block">
                    <a class="nav-link" href="{{route('tasktwo')}}">Task 2</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<div class="row justify-content-center content-body">
    <div class="col-8">
        @yield('content')
    </div>
</div>
</body>
</html>