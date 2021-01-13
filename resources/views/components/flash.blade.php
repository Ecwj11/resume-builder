<script src="{{ asset('js/sweetalert2.all-8.8.1.min.js') }}"></script>
<script>

    function flash_notype(flash) {
        return Swal.fire({title: flash.title, html: flash.message});
    }

    function flash_success(flash) {
        return Swal.fire({title: flash.title, type: 'success', html: flash.message, allowOutsideClick: false});
    }

    function flash_error(flash) {
        return Swal.fire({title: flash.title, type: 'error', html: flash.message, allowOutsideClick: false});
    }

    function flash_notice(flash) {
        return Swal.fire({title: flash.title, type: 'notice', html: flash.message, allowOutsideClick: false});
    }

    function flash_info(flash) {
        return Swal.fire({title: flash.title, type: 'info', html: flash.message, allowOutsideClick: false});
    }

    function flash_confirm_html(flash) {
        Swal.fire({
            title: flash.title,
            html: flash.html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: flash.confirm.text,
            cancelButtonText: flash.cancel.text,
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                return flash.confirm.action();
            }
            return flash.cancel.action();
        });
    }

    function flash_confirm(flash) {
        Swal.fire({
            title: flash.title,
            text: flash.message,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: flash.confirm.text,
            cancelButtonText: flash.cancel.text,
            allowOutsideClick: false
        }).then((result) => {
            if (result.value) {
                return flash.confirm.action();
            }
            return flash.cancel.action();
        });
    }

    function flash_preconfirm_html(flash) {
        Swal.fire({
            title: flash.title,
            html: flash.html,
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: flash.confirm.text,
            cancelButtonText: flash.cancel.text,
            allowOutsideClick: false,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise(function (resolve) {
                    flash.confirm.action();
                })
            }
        });
    }
</script>
