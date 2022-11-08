function showSwalLoader() {
    Swal.fire({
        title: '<h3>Harap tunggu...</h3>',
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading()
        }
    });
}

function closeSwal() {
    Swal.close()
}

function showSwal(status, msg) {
    Swal.fire({
        html: msg,
        icon: status,
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-light-primary"
        }
    });
}

function showSwalThen(status, msg, then) {
    Swal.fire({
        html: msg,
        icon: status,
        buttonsStyling: false,
        confirmButtonText: "Ok",
        customClass: {
            confirmButton: "btn btn-light-primary"
        }
    }).then(then);
}

function swalConfirm(title, html, confirmText, confirmColor, confirmAction, cancelAction) {
    Swal.fire({
        title: title,
        html: html,
        icon: "info",
        buttonsStyling: false,
        showDenyButton: true,
        confirmButtonText: confirmText,
        denyButtonText: 'Batalkan',
        customClass: {
            confirmButton: `btn btn-${confirmColor}`,
            denyButton: 'btn btn-secondary'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            confirmAction();
        } else {
            if (cancelAction) {
                cancelAction()
            }
        }
    })
}

function swalFailed(msg) {
    msg = msg ? msg : "Terjadi kesalahan. Harap coba lagi atau refresh halaman ini"
    showSwal('error', msg);
}