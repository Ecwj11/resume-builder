@extends('layouts.app')

@section('content')

<div class="mx-auto col-md-6">
  {{ Form::open(array('url' => 'login')) }}
  <h1>Login</h1>

  <!-- if there are login errors, show them here -->
  <p class="text-danger">
      {{ $errors->first('email') }}
      {{ $errors->first('password') }}
  </p>

  <p>
      {{ Form::label('email', 'Email Address') }}
      {{ Form::email('email', old('email') ? old('email') : '', array('placeholder' => 'Email', 'class' => 'form-control')) }}
  </p>

  <p>
      {{ Form::label('password', 'Password') }}
      {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password')) }}
  </p>

  <p>{{ Form::submit('Login', array('class' => 'btn btn-primary')) }}</p>
  <p>Don't have an account? <a href="{{ route('register') }}">Click here to register</a></p>
  {{ Form::close() }}
</div>

@endsection