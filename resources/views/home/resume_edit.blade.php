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
{{ Form::open(['url' => 'resume', 'id' => 'resume-form']) }}
<div class="row">
	<div class="col-md-6">
	  	<h1>Information</h1>

	  	<div class="form-group row">
	  		<div class="col-md-12">
		      	{{ Form::label('label', 'Resume Label') }}
		      	{{ Form::text('label', $resume->label, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	  		<div class="col-md-6">
		      	{{ Form::label('name', 'Name') }}
		      	{{ Form::text('name', $resume->name, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
		    <div class="col-md-6">
			    {{ Form::label('email', 'Email Address') }}
		      	{{ Form::email('email', $resume->email, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	      	<div class="col-md-6">
			    {{ Form::label('contact_number', 'Contact Number') }}
	      		{{ Form::text('contact_number', $resume->contact_number, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
		    <div class="col-md-6">
			    {{ Form::label('job_title', 'Job Title') }}
	      		{{ Form::text('job_title', $resume->job_title, ['class' => 'form-control', 'placeholder' => '', 'required' => true]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	      	<div class="col-md-6">
			    {{ Form::label('address', 'Address') }}
	      		{{ Form::textarea('address', $resume->address, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3]) }}
		    </div>
		    <div class="col-md-12">
			    {{ Form::label('description', 'Description') }}
	      		{{ Form::textarea('description', $resume->description, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 3, 'data-summernote-code' => "true"]) }}
		    </div>
	  	</div>

	  	<div class="form-group row">
	  		<div class="col-md-6">
	  			{{ Form::label('profile_picture', 'Profile Picture') }}
		      	{{ Form::file('profile_picture', ['class' => 'form-control', 'placeholder' => '', 'required' => $resume->profile_picture == NULL ? true : false, 'accept' => 'image/*']) }}
	      	</div>
	      	<div class="col-md-6">
	      		@if ($resume->profile_picture != NULL)
	      		<a href="javascript:void(0);" data-href="{{Storage::url('users/' . $resume->user_id .'/'. $resume->profile_picture)}}" onclick="viewImage(this);">
	      			<img src="{{Storage::url('users/' . $resume->user_id .'/'. $resume->profile_picture)}}" width="80px">
	      		</a>
	      		@endif
	      	</div>
	  	</div>
	</div>
	<div class="col-md-6">
		<h1>Education</h1>
		<a href="javascript:void(0);" onclick="addEducation(this);"><i class="fa fa-plus"></i> Add</a>

		<div id="education-field" class="hidden">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('program', 'Program') }}
			      	{{ Form::text('program', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'name' => 'program[]']) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('institute', 'University/College') }}
		      		{{ Form::text('institute', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'name' => 'institute[]']) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('start_date', 'Start Year') }}
		      		{{--{{ Form::select('start_date', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => false, 'name' => 'start_date[]']) }}--}}
		      		{{ Form::text('start_date', old('start_date') ? old('start_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'start_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('end_date', 'End Year') }}
		      		{{--{{ Form::select('end_date', $yearList, null, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => false, 'name' => 'end_date[]']) }}--}}
		      		{{ Form::text('end_date', old('end_date') ? old('end_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'end_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', false, ['class' => '', 'onclick' => 'educationOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('grade', 'Grade') }}
			      	{{ Form::text('grade', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'name' => 'grade[]']) }}
			    </div>
			</div>
		</div>

		@foreach ($educations as $education)
		<div class="new-field">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('program', 'Program') }}
			      	{{ Form::text('program', $education->program, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'program_' . $education->id]) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('institute', 'University/College') }}
		      		{{ Form::text('institute', $education->institute, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'institute_' . $education->id]) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('start_date', 'Start Year') }}
		      		{{--{{ Form::select('start_date', $yearList, $education->start_date, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => true, 'name' => 'start_date_' . $education->id]) }}--}}
		      		{{ Form::text('start_date', $education->start_date, ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => true, 'name' => 'start_date_' . $education->id]) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('end_date', 'End Year') }}
		      		{{--{{ Form::select('end_date', $yearList, $education->end_date, ['class' => 'form-control', 'placeholder' => 'Select year', 'required' => $education->end_date == NULL ? false : true, 'name' => 'end_date_' . $education->id]) }}--}}
		      		{{ Form::text('end_date', $education->end_date, ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => $education->end_date == NULL ? false : true, 'name' => 'end_date_' . $education->id]) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', $education->end_date == NULL ? true : false, ['class' => '', 'onclick' => 'educationOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('grade', 'Grade') }}
			      	{{ Form::text('grade', $education->grade, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'grade_' . $education->id]) }}
			    </div>
			    @if ($loop->index > 0)
				    <div class="col-md-6">
				    	<a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove education</a>
				    </div>
				@endif
			</div>
		</div>
		@endforeach
	</div>

	<div class="col-md-6">
		<h1>Work Experience</h1>
		<a href="javascript:void(0);" onclick="addWorkExperience(this);"><i class="fa fa-plus"></i> Add</a>

		<div id="work-experience-field" class="hidden">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('position', 'Position') }}
			      	{{ Form::text('position', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'name' => 'position[]']) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('company', 'Company') }}
		      		{{ Form::text('company', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'name' => 'company[]']) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('we_start_date', 'Start Year') }}
		      		{{ Form::text('we_start_date', old('we_start_date') ? old('we_start_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'we_start_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('we_end_date', 'End Year') }}
		      		{{ Form::text('we_end_date', old('we_end_date') ? old('we_end_date') : '', ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'we_end_date[]']) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', false, ['class' => '', 'onclick' => 'workExperienceOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::label('we_description', 'Description') }}
			      	{{ Form::textarea('we_description', '', ['class' => 'form-control', 'placeholder' => '', 'required' => false, 'rows' => 3, 'name' => 'we_description[]', 'data-summernote-code' => "true"]) }}
			    </div>			    
			</div>
		</div>

		@foreach ($workExperiences as $workExperience)
		<div class="new-field">
			<div class="form-group row">
				<div class="col-md-6">
					{{ Form::label('position', 'Position') }}
			      	{{ Form::text('position', $workExperience->position, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'position_' . $workExperience->id]) }}
			    </div>
			    <div class="col-md-6">
			    	{{ Form::label('company', 'Company') }}
		      		{{ Form::text('company', $workExperience->company, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'name' => 'company_' . $workExperience->id]) }}
			    </div>
			</div>

			<div class="form-group row">
				<div class="col-md-4">
					{{ Form::label('we_start_date', 'Start Year') }}
		      		{{ Form::text('we_start_date', $workExperience->start_date, ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'we_start_date_' . $workExperience->id]) }}
		      	</div>
		      	<div class="col-md-4">
					{{ Form::label('we_end_date', 'End Year') }}		      		
		      		{{ Form::text('we_end_date', $workExperience->end_date, ['class' => 'date-picker form-control', 'placeholder' => '', 'required' => false, 'name' => 'we_end_date_' . $workExperience->id]) }}
		      	</div>
		      	<div class="col-md-4">
		      		{{ Form::label('ongoing', 'Ongoing') }}<br/>
		      		{{ Form::checkbox('ongoing', '1', $workExperience->end_date == NULL ? true : false, ['class' => '', 'onclick' => 'workExperienceOngoing(this);']) }}
		      	</div>
			</div>

			<div class="form-group row">
				<div class="col-md-12">
					{{ Form::label('we_description', 'Description') }}
			      	{{ Form::textarea('we_description', $workExperience->description, ['class' => 'form-control', 'placeholder' => '', 'required' => true, 'rows' => 5, 'name' => 'we_description_' . $workExperience->id, 'data-summernote-code' => "true"]) }}
			    </div>
			    @if ($loop->index > 0)
				    <div class="col-md-6">
				    	<a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove work experience</a>
				    </div>
				@endif
			</div>
		</div>
		@endforeach
	</div>

	<div class="mx-auto col-md-12 text-center w-50">
		{{ Form::submit('Update', ['class' => 'btn btn-primary']) }}
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

			data.append('id', '{{ $resume->id }}');
			$.ajax({
		        type: "POST",
	        	url: "{{ route('api.updateResume') }}",
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
				  				window.location.reload();
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
	}

	function removeField(e) {
		$(e).closest('.new-field').remove();
	}

	function addWorkExperience(e) {
		$(e).after('<div class="new-field">' + workExperienceField + '</div>');
		$(e).next('.new-field').find('.form-group:last').append('<div class="col-md-6"><a href="javascript:void(0);" onclick="removeField(this);" class="text-danger"><i class="fa fa-minus"></i> Remove work experience</a></div>');
		initDatePicker();
	}

	function educationOngoing(e) {
		var isChecked = $(e).prop('checked');
		if (isChecked) {
			$(e).closest('.form-group').find('select[id="end_date"]').removeAttr('required').val('').attr('disabled', 'disabled');
		} else {
			$(e).closest('.form-group').find('select[id="end_date"]').attr('required', 'required').removeAttr('disabled');
		}
	}

	function workExperienceOngoing(e) {
		var isChecked = $(e).prop('checked');
		if (isChecked) {
			$(e).closest('.form-group').find('select[id="we_end_date"]').removeAttr('required').val('').attr('disabled', 'disabled');
		} else {
			$(e).closest('.form-group').find('select[id="we_end_date"]').attr('required', 'required').removeAttr('disabled');
		}
	}	
</script>
@endsection