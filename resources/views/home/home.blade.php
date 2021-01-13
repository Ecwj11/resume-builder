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
		    <div class="col-md-12">
		      	{{ Form::label('description', 'Description') }}
		      	{{ Form::textarea('description', old('description') ? old('description') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3, 'data-summernote-code' => "true"]) }}
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
					{{ Form::label('start_date', 'Start Date') }}
		      		{{--{{ Form::select('start_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'start_year[]']) }}--}}
		      		{{ Form::text('start_date', old('start_date') ? old('start_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => true, 'name' => 'start_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('end_year', 'End Date') }}
		      		{{--{{ Form::select('end_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'end_year[]']) }}--}}
		      		{{ Form::text('end_date', old('end_date') ? old('end_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => true, 'name' => 'end_date[]']) }}
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
					{{ Form::label('we_start_year', 'Start Date') }}
		      		{{--{{ Form::select('we_start_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'we_start_year[]']) }}--}}
		      		{{ Form::text('we_start_date', old('we_start_date') ? old('we_start_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => true, 'name' => 'we_start_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('we_end_year', 'End Date') }}
		      		{{--{{ Form::select('we_end_year', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'we_end_year[]']) }}--}}
		      		{{ Form::text('we_end_date', old('we_end_date') ? old('we_end_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => true, 'name' => 'we_end_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', false, ['class' => '', 'onclick' => 'workExperienceOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::label('we_description', 'Description') }}
			      	{{ Form::textarea('we_description', old('we_description') ? old('we_description') : '', ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3, 'name' => 'we_description[]', 'data-summernote-code' => "true"]) }}
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

			//check is end date > start date
			var endDates = $('[name="end_date[]"]');
			var error = [];
			$.each($('[name="start_date[]"]'), function(k, v) {
				var startDate = v.value;
				var endDate = endDates[k].value;
				if (endDate != '' && endDate < startDate) {
					error.push('Education end date should larger or equal to start date');
				}
			});
			if (error.length > 0) {
				return notify(error.join(", "), 'danger');
			}

			error = [];
			var endDates = $('[name="we_end_date[]"]');
			$.each($('[name="we_start_date[]"]'), function(k, v) {
				var startDate = v.value;
				var endDate = endDates[k].value;
				if (endDate != '' && endDate < startDate) {
					error.push('Work experience end date should larger or equal to start date');
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
		initDatePicker();
		initSummernote();
	}

	function removeField(e) {
		$(e).closest('.new-field').remove();
	}

	function addWorkExperience(e) {
		$(e).after('<div class="new-field">' + workExperienceField + '</div>');
		$(e).next('.new-field').find('.form-group:last').append('<div class="col-md-6"><a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove work experience</a></div>');
		initDatePicker();
		initSummernote();
	}

	function educationOngoing(e) {
		var isChecked = $(e).prop('checked');
		if (isChecked) {
			$(e).closest('.form-group').find('[name="end_date[]"]').removeAttr('required').val('').attr('disabled', 'disabled');
		} else {
			$(e).closest('.form-group').find('[name="end_date[]"]').attr('required', 'required').removeAttr('disabled');
		}
	}

	function workExperienceOngoing(e) {
		var isChecked = $(e).prop('checked');
		if (isChecked) {
			$(e).closest('.form-group').find('[name="we_end_date[]"]').removeAttr('required').val('').attr('disabled', 'disabled');
		} else {
			$(e).closest('.form-group').find('[name="we_end_date[]"]').attr('required', 'required').removeAttr('disabled');
		}
	}
</script>
@endsection