@extends('layouts.app_public')

@section('css')
<style>
</style>
@endsection

@section('content')
<div class="row">
<div class="col-md-12">
	@if (isset($error) && $error != '')
		<div class="m-1 text-center alert alert-danger">
			{{ $error }}	
		</div>
	@endif
</div>
</div>
@endsection