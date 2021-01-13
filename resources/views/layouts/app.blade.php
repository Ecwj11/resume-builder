<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

        <link rel="stylesheet" type="text/css" href="{{ asset('css/ladda-themeless.min.css') }}" />

        <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" />

        <link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery.powertip-1.3.1/css/jquery.powertip.css') }}" />

        <link rel="stylesheet" type="text/css" href="{{ asset('css/jquery-ui.css') }}" />

        <link rel="stylesheet" href="{{ asset('plugins/summernote/css/summernote-bs4.css') }}">

        <link rel="stylesheet" href="{{ asset('css/project.css') }}" />

        <!-- Styles -->
        <style>
            .ui-datepicker-calendar {
                display: none;
            }
        </style>

        @yield('css')
    </head>
    <body>
        <div class="flex-center position-ref" style="height: 60px;">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ route('home') }}">Home</a>
                        <a href="javascript:void(0);">{{ Auth::user()->email }}</a>
                        {{ Form::open(array('url' => 'logout', 'class' => 'hidden', 'id' => 'logout-form')) }}                        
                        {{ Form::submit('Logout', array('class' => 'hidden')) }}
                        {{ Form::close() }}
                        <a href="javascript:void(0);" onclick="logout(this);">Logout</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
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
        
        <script src="{{ asset('plugins/summernote/js/summernote-bs4.js') }}"></script>
        
        <script src="{{ asset('js/project.js') }}"></script>

        @yield('js')

        @include('layouts/js')

        @include('flashy::message')

        @include('components/flash')
    </body>
</html>