<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 
    <title>@yield('title')</title>
    <style>
        svg.w-5.h-5{
            width: 30px;
            height: 30px;
        }
        p.text-sm.text-gray-700.leading-5{
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="app">
        @include('layouts.header')
        <div class="container mt-4 mb-4">
             @yield('content')
        </div>
        @include('layouts.footer')
    </div>
</body>
</html>