<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/ladda-themeless.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/project.css') }}" />

        <!-- Styles -->
        <style>        
        </style>

        @yield('css')
    </head>
    <body>
        <div class="flex-center position-ref" style="height: 60px;">
        </div>

        <div class="container">
            @yield('content')
        </div>

        <!-- jQuery -->
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>

        <script src="{{ asset('js/bootstrap-notify.js') }}"></script>

        <script src="{{ asset('js/spin.min.js') }}"></script>
        <script src="{{ asset('js/ladda.min.js') }}"></script>

        <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>

        <script type="text/javascript" src="{{ asset('plugins/jquery.powertip-1.3.1/jquery.powertip.js') }}"></script>
        
        <script type="text/javascript" src="{{ asset('js/jquery.cardcheck.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('plugins/jquery.countdownTimer/jquery.countdownTimer.min.js') }}"></script>
        
        <script type="text/javascript" src="{{ asset('js/html2canvas.min.js') }}"></script>
        
        <script src="{{ asset('js/project.js') }}"></script>

        @yield('js')

        @include('layouts/js')

        @include('flashy::message')

        @include('components/flash')
    </body>
</html>