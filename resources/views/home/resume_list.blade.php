@extends('layouts.app')

@section('css')
<style>
	.offscreen {
    	position: absolute;
    	left: -999em;
	}
	.hidden {
		display: none;
	}
	label {
		font-size: 85%;
	}
	table thead tr th, table tbody tr td {
		font-size: 12px;
	}
	table tbody tr td:last-child {
		width: fit-content;
	}
	[data-notify="progressbar"] {
		margin-bottom: 0px;
		position: absolute;
		bottom: 0px;
		left: 0px;
		width: 100%;
		height: 5px;
	}
</style>
@endsection

@section('content')
<div class="row">
<div class="col-md-12">
	<div class="m-1">
		@if (!Auth::user()->isAdmin())
			<a href="{{ route('resumeCreate') }}" class="btn btn-primary mb-1">Create Resume</a>
		@endif
		<table id="ajax_datatable" class="table table-striped table-bordered dtr-inline">
	        <thead class="">
	        	<tr>
	        		@foreach ($dataColumns as $key => $val)
	        			<th>{{ $val }}</th>
	        		@endforeach
	        	</tr>
			</thead>
		</table>
	</div>
</div>
</div>
@endsection

@section('js')
<script>
	var datatable;

	$(function() {
		var url = "{{ route('api.resumeListing') }}";
		var offset = 0;
	    var length = 10;
	    var start = 0;
	    var failureCount = 0;
		$(function() {
			datatable = $('#ajax_datatable').DataTable({
			    pageLength: -1,
			    lengthMenu: [[25, 50, 100, 500, -1], [25, 50, 100, 500, "All"]],
			    columns: [
			    @foreach ($dataColumns as $key => $value)
			        { data: "{{ $key }}", {{ in_array($key, []) ? 'orderable: false' : '' }} },
		        @endforeach		    
			    ],			    
			    aaSorting: [],
			    ajax: {
			    	url : url,
			    	type : 'POST',
			    	data: function ( d ) {
				    },
				    headers: {
				        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				    },
			    },
			    processing: false,
	            serverSide: true,
	            bPaginate: false,
	            bInfo: false,
	            bFilter: false,
	            drawCallback: function(response) {
	            }
			});

		});
	 
	});

	function share(e) {
		var id = $(e).data('id');
		var url = $(e).data('url');
		var html = '<div class="row">' + 
			'<div class="col-md-10"><input type="text" readonly id="share-url-' + id + '" value="' + url + '" class="form-control"></div>' +
			'<div class="col-md-2"><a href="javascript:void(0);" data-toggle="tooltip" title="Copy Resume Link" data-id="share-url-' + id + '" onclick="copy(this);"><i class="fa fa-copy"></i></a></div>' +
			'</div>';
		flash_notype({
			title: 'Share resume',
			message: html
		});

		$('.swal2-container [data-toggle="tooltip"]').powerTip({ placement: 'n' });
	}

	function copy(e) {
		var id = $(e).data('id');
		var copyCjs = document.getElementById(id);
		copyCjs.select();
	  	copyCjs.setSelectionRange(0, 99999999);
	  	document.execCommand("copy", false, null);
	  	Swal.showValidationMessage('Copied resume link successfully. <i id="timer">3</i>');
	  	var timer = 3;
	  	var timerInterval = setInterval(function() {
	  		timer -= 1;
	  		$('.swal2-container #timer').html(timer);
	  	}, 1000);
	  	setTimeout(function() {
	  		$('.swal2-container #swal2-validation-message').hide();
	  		clearInterval(timerInterval);
	  	}, 3000);
	}

	function deleteResume(e) {
		var id = $(e).data('id');
		var label = $(e).data('label');
		var title = 'Delete resume.';
		if ($('.swal2-container .swal2-title:contains("' + title + '")').length == 0) {
			var html = 'Confirm delete resume ' + label + '?';
			flash_preconfirm_html({
	            title: title,
	            html: html,
	            confirm: {
	                text: 'Confirm',
	                action: function () {
	                    $.ajax({
	                        method: "POST",
	                        url: "{{ route('api.deleteResume') }}",
	                        dataType: 'json',
	                        cache: false,
	                        timeout: 30000,
	                        data: {
	                        	id: id,
	                        	label: label
	                        },
	                        headers: {
	                        	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	                        },
	                    }).done(function (response) {
	                        if(response) {
	                            if (response.status) {
	                                if(response.data) {
	                                	Swal.close();
	                                	datatable.ajax.reload();
	                                	return notify(response.data, 'success');
	                                }
	                            }
	                        }
	                    }).fail(function (response) {
	                    	ajaxErrorSwal(response);
	                    });
	                }
	            },
	            cancel: {
	                text: 'Cancel',
	                action: function () {}
	            }
	        });
		}
	}
</script>
@endsection