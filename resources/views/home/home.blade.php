@extends('layouts.app')
@section('css')
<style>
	div.new-field {
		border-bottom: 1px solid black;
    	margin-bottom: 10px;
	}
</style>
@endsection

@section('content')
{{ Form::open(['url' => 'resume', 'id' => 'resume-form', 'files' => true]) }}
<div class="row">
	<div class="col-md-6">
	  	<h1>Information</h1>

	  	<div class="form-group row">
			<div class="col-md-12">
		      	{{ Form::label('label', 'Resume Label') }}
		      	{{ Form::text('label', old('label'), ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
			<div class="col-md-6">
		      	{{ Form::label('name', 'Name') }}
		      	{{ Form::text('name', old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
		    <div class="col-md-6">
		    	{{ Form::label('email', 'Email Address') }}
	      		{{ Form::email('email', old('email') ? old('email') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
	      	</div>
	  	</div>

	  	<div class="form-group row">
	  		<div class="col-md-6">
		      	{{ Form::label('contact_number', 'Contact Number') }}
		      	{{ Form::text('contact_number', old('contact_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
		    <div class="col-md-6">
		    	{{ Form::label('job_title', 'Job Title') }}
	      		{{ Form::text('job_title', old('job_title') ? old('job_title') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	  		<div class="col-md-6">
		    	{{ Form::label('address', 'Address') }}
	      		{{ Form::textarea('address', old('address') ? old('address') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3]) }}
		    </div>
		    <div class="col-md-6">
		      	{{ Form::label('description', 'Description') }}
		      	{{ Form::textarea('description', old('description') ? old('description') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	  		<div class="col-md-6">
	  			{{ Form::label('profile_picture', 'Profile Picture') }}
		      	{{ Form::file('profile_picture', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'accept' => 'image/*']) }}
	      	</div>
	  	</div>
	</div>
	<div class="col-md-6">
		<h1>Education</h1>
		<a href="javascript:void(0);" onclick="addEducation(this);"><i class="fa fa-plus"></i> Add</a>

		<div id="education-field">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('program', 'Program') }}
			      	{{ Form::text('program', old('program') ? old('program') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'program[]']) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('institute', 'University/College') }}
		      		{{ Form::text('institute', old('institute') ? old('institute') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'institute[]']) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('start_year', 'Start Year') }}
		      		{{ Form::select('start_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'start_year[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('end_year', 'End Year') }}
		      		{{ Form::select('end_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'end_year[]']) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', false, ['class' => '', 'onclick' => 'educationOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('grade', 'Grade') }}
			      	{{ Form::text('grade', old('grade') ? old('grade') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'grade[]']) }}
			    </div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<h1>Work Experience</h1>
		<a href="javascript:void(0);" onclick="addWorkExperience(this);"><i class="fa fa-plus"></i> Add</a>

		<div id="work-experience-field">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('position', 'Position') }}
			      	{{ Form::text('position', old('position') ? old('position') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'position[]']) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('company', 'Company') }}
		      		{{ Form::text('company', old('company') ? old('company') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'company[]']) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('we_start_year', 'Start Year') }}
		      		{{ Form::select('we_start_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'we_start_year[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('we_end_year', 'End Year') }}
		      		{{ Form::select('we_end_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'we_end_year[]']) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', false, ['class' => '', 'onclick' => 'educationOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('we_description', 'Description') }}
			      	{{ Form::textarea('we_description', old('we_description') ? old('we_description') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3, 'name' => 'we_description[]']) }}
			    </div>
			</div>
		</div>
	</div>

	<div class="mx-auto col-md-12 text-center w-50">
		{{ Form::submit('Submit', ['class' => 'btn btn-primary']) }}
	</div>
</div>
{{ Form::close() }}
@endsection

@section('js')
<script>
	var educationField = $('#education-field').html();
	var workExperienceField = $('#work-experience-field').html();

	$(function() {
		$('#resume-form').on('submit', function(e) {
			e.preventDefault();
			var data = new FormData(document.querySelector('#resume-form'));

			//check is end year > start year
			var endYears = $('select[name="end_year[]"]');
			var error = [];
			$.each($('select[name="start_year[]"]'), function(k, v) {
				var startYear = v.value;
				var endYear = endYears[k].value;
				if (endYear != '' && endYear < startYear) {
					error.push('Education end year should larger or equal to start year');
				}
			});
			if (error.length > 0) {
				return notify(error.join(", "), 'danger');
			}

			error = [];
			var endYears = $('select[name="we_end_year[]"]');
			$.each($('select[name="we_start_year[]"]'), function(k, v) {
				var startYear = v.value;
				var endYear = endYears[k].value;
				if (endYear != '' && endYear < startYear) {
					error.push('Work experience end year should larger or equal to start year');
				}
			});

			if (error.length > 0) {
				return notify(error.join(", "), 'danger');
			}

			$.ajax({
		        type: "POST",
	        	url: "{{ route('api.createResume') }}",
	        	cache: false,
		        timeout: 30000,
		        data: data,
		        processData: false,
		        contentType: false,
	            headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    },
		        success: function (response) {
		            if(response) {
				  		if(response.data) {
				  			if (response.status) {
				  				notify(response.data, 'success');
				  				return window.location.href = "{{ route('home') }}";
				  			}
				  		}
				  	}
		        },
		        error: function (response) {
		        	ajaxError(response);
		        }
		    });
		});
	});
	
	function addEducation(e) {
		$(e).after('<div class="new-field">' + educationField + '</div>');
		$(e).next('.new-field').find('.form-group:last').append('<div class="col-md-6"><a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove education</a></div>');
	}

	function removeField(e) {
		$(e).closest('.new-field').remove();
	}

	function addWorkExperience(e) {
		$(e).after('<div class="new-field">' + workExperienceField + '</div>');
		$(e).next('.new-field').find('.form-group:last').append('<div class="col-md-6"><a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove work experience</a></div>');
	}

	function educationOngoing(e) {
		var isChecked = $(e).prop('checked');
		if (isChecked) {
			$(e).closest('.form-group').find('select[name="end_year[]"]').removeAttr('required').val('').attr('disabled', 'disabled');
		} else {
			$(e).closest('.form-group').find('select[name="end_year[]"]').attr('required', 'required').removeAttr('disabled');
		}
	}
</script>
@endsection