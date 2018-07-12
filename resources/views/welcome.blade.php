<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{asset('css/styles.css')}}" rel="stylesheet" type="text/css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <!-- Styles -->
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
        <div class="card">
            <div class="card-header"><h2><a href="{{route("taskone")}}" class="card-link">Task 1</a></h2></div>
            <div class="card-body task-description">
                <h4>Задание</h4>
                <p>
                    Написать обработчик для XML, CSV, JSON файла с данными <a
                            href="https://ru.wikipedia.org/wiki/XML#/media/File:XMLSample.png">https://ru.wikipedia.org/wiki/XML#/media/File:XMLSample.png</a>.
                </p>
                Для XML попробовать 2 разными методами:
                <ul>
                    <li>на вход файл</li>
                    <li>вывод данных на экран из файла в читабельном виде</li>
                    <li>на выходе, файл с обновленной датой и курсом</li>
                </ul>

                <h4>Файлы</h4>
                <ul>
                    <li><a href="{{asset('files/taskone/currencies.csv')}}" download>CSV file</a></li>
                    <li><a href="{{asset('files/taskone/currencies.json')}}" download>JSON file</a></li>
                    <li><a href="{{asset('files/taskone/currencies.xml')}}" download>XML file</a></li>
                </ul>
                <h4>Скриншоты</h4>
                <ul>
                    <li><strong>Upload File</strong> - загрузка выбраного файла</li>
                    <li><strong>Get File Data</strong> - вывод данных в читабельном виде</li>
                    <li><strong>Download Updated File</strong> - скачивание файла с обновленной датой и курсом</li>
                </ul>
                <img src="{{asset('files/taskone/screenshot_1_taskone.png')}}">
            </div>
        </div>
    </div>
</div>
</body>
</html>
