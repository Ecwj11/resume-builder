@extends('layouts.app_public')

@section('css')
<style>
	.container {
		width: 900px;
	}
</style>
@endsection

@section('title', $resume->label)

@section('content')
<div class="card">
	<div class="card-body">
		<div class="row">
			<div class="col-md-4">
				@if ($resume->profile_picture != NULL)
				<a href="javascript:void(0);" data-href="{{Storage::url('users/' . $resume->user_id .'/'. $resume->profile_picture)}}" onclick="viewImage(this);">
					<img src="{{Storage::url('users/' . $resume->user_id .'/'. $resume->profile_picture)}}" height="120px">
				</a>
				@else
					<img src="/user.png" height="120px">
				@endif
			</div>
			<div class="col-md-8">
				<div class="card-header">
					<h1>{{ $resume->name }}</h1>
					<h3 style="text-decoration: underline;">{{ $resume->job_title }}</h3>
				</div>				
			</div>
		</div>		
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-4 row" style="margin-right: 15px;">
				<div class="col-md-2">
					<i class="fa fa-phone"></i>
				</div>
				<div class="col-md-10">
					{{ $resume->contact_number }}
				</div>
				<div class="col-md-2">
					<i class="fa fa-envelope"></i>
				</div>
				<div class="col-md-10">
					{{ $resume->email }}
				</div>
				<div class="col-md-2">
					<i class="fa fa-map-marker"></i>
				</div>
				<div class="col-md-10">
					{{ $resume->address }}
				</div>
			</div>
			<div class="col-md-8">
				<div class="card-header">
					<h3>About Me</h3>
				</div>
				{!! $resume->description !!}
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-md-4">
				<div class="card-header">
					<h3>Education</h3>
				</div>
				@foreach ($educations as $education)
				<h4>{{ $education->program }}</h4>
				<small><i>{{ $education->institute }}</i></small>
				<p>{{ date('M Y', strtotime($education->start_date)) . ' - ' . ($education->end_date == '' ? 'Present' : date('M Y', strtotime($education->end_date))) }}</p>
				@endforeach
			</div>
			<div class="col-md-8">
				<div class="card-header">
					<h3>Work Experience</h3>
				</div>
				@foreach ($workExperiences as $workExperience)
				<h4>{{ $workExperience->position }}</h4>
				<small><i style="text-decoration: underline;">{{ $workExperience->company }} / {{ date('M Y', strtotime($workExperience->start_date)) . ' - ' . ($workExperience->end_date == '' ? 'Present' : date('M Y', strtotime($workExperience->end_date))) }}</i></small>
				<p>{!! $workExperience->description !!}</p>
				@endforeach
			</div>
		</div>
	</div>

</div>
@endsection