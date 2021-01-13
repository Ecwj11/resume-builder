<script>
	$(function() {
		initDatePicker();
		initSummernote();
	});

	function initSummernote() {
		$('textarea[data-summernote-code="true"]').summernote({
	        height: 300
	    });
	}

	function initDatePicker() {
		$('.date-picker').datepicker( {
	        changeMonth: true,
	        changeYear: true,
	        showButtonPanel: true,
	        dateFormat: 'yy-mm-dd',
	        yearRange: '1980:' + (new Date).getFullYear(),
	        onClose: function(dateText, inst) { 
	            $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
	        }
	    });
	}

    function logout(e) {
    	$('#logout-form').submit();
    }

    function ajaxError(response) {
        if ($.inArray(response.status, [401, 419]) > -1)
            window.location.href = '/login';
        var error = '';
        if (response.responseJSON) {
            if (response.responseJSON.errors) {
                if (response.responseJSON.errors == 'permission_block') {
                    return alert(response.responseJSON.message);
                    // return notification.showError(response.responseJSON.message);
                }
                var errorType = (typeof response.responseJSON.errors);
                if (errorType == 'string') {
                    error = response.responseJSON.errors;
                } else if ($.isArray(response.responseJSON.errors)) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        error += value + '\n';
                    });
                } else {
                    if (typeof response.responseJSON.errors.error.error !== 'undefined')
                        error = response.responseJSON.errors.error.error;
                    else if (typeof response.responseJSON.errors.error !== 'undefined')
                        error = response.responseJSON.errors.error;
                }
                if (error != '') {
                    // alert(error);
                    notify(error, 'danger');
                }
            } else if (response.responseJSON.warning) {
                var errorType = (typeof response.responseJSON.warning);
                if (errorType == 'string') {
                    error = response.responseJSON.warning;
                } else {
                    $.each(response.responseJSON.warning, function(key, value) {
                        error += value + '\n';
                    });
                }
                if (error != '') {
                    // alert(error);
                    notify(error, 'warning');
                }
            }
        } else {
            alert('Something had happaned, please try again later.');
        }
    }

    function ajaxErrorSwal(response) {
        var error = '';
        if (response.responseJSON) {
            if (response.responseJSON.errors) {
                if ($.isArray(response.responseJSON.errors)) {
                    $.each(response.responseJSON.errors, function(key, value) {
                        error += value + '<br>';
                    });
                } else {
                    if (typeof response.responseJSON.errors.error.error !== 'undefined')
                        error = response.responseJSON.errors.error.error;
                    else if (typeof response.responseJSON.errors.error !== 'undefined')
                        error = response.responseJSON.errors.error;
                    else 
                        error = response.responseJSON.errors;
                }
                if (error != '') {
                    Swal.hideLoading();
                    return Swal.showValidationMessage(
                        error
                    );
                }
            }
        } else {
            Swal.hideLoading();
            return Swal.showValidationMessage(
                'Something had happaned, please try again later.'
            );
        }
    }

    function viewImage(e) {
		var src = $(e).data('href');
		flash_notype({
			title: "Profile Picture",
			message: '<img src="' + src + '" width="100%">'
		})
	}
</script>