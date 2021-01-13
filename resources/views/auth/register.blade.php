@extends('layouts.app')

@section('content')

<div class="mx-auto col-md-6">
  {{ Form::open(array('url' => 'register')) }}
  <h1>Register</h1>

  <!-- if there are login errors, show them here -->
  <p>
      {{ Form::label('email', 'Email Address') }}
      {{ Form::email('email', old('email') ? old('email') : '', array('placeholder' => 'Email', 'class' => 'form-control', 'required' => true)) }}
      <p class="text-danger">{{ $errors->has('email') ? $errors->first('email') : '' }}</p>
  </p>

  <p>
      {{ Form::label('password', 'Password') }}
      {{ Form::password('password', array('class' => 'form-control', 'placeholder' => 'Password', 'required' => true)) }}
      <p class="text-danger">{{ $errors->has('password') ? $errors->first('password') : '' }}</p>
  </p>

  <p>{{ Form::submit('Register', array('class' => 'btn btn-primary')) }}</p>
  {{ Form::close() }}
</div>

@endsection