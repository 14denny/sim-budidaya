"use strict"

$("#form-upload-excel").submit((e) => {
    e.preventDefault()
    if ($("#input-excel").get(0).files.length == 0) {
        showSwal('error', "Harap pilih file terlebih dahulu");
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlUploadExcel,
        dataType: 'json',
        type: 'post',
        data: new FormData($("#form-upload-excel").get(0)),
        contentType: false,
        cache: false,
        processData: false,
        success: (result) => {
            closeSwal()

            csrf_token = result.csrf_token
            $('input[name=_token]').val(csrf_token)

            showSwal(result.status ? 'success' : 'error', result.msg);
            $("#form-upload-excel").trigger('reset');
        }
    }).fail(() => {
        swalFailed()
    })
})

function checkData() {
    showSwalLoader()
    $.ajax({
        url: urlCheckDataExcel,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            id_lokasi: idLokasi
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                closeSwal()

                $("#lihat-data").html(result.html)
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function prosesImportExcel() {
    showSwalLoader()
    $.ajax({
        url: urlProsesDataExcel,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            id_lokasi: idLokasi
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $('input[name=_token]').val(csrf_token)

            showSwal(result.status ? 'success' : 'error', result.msg)

            if (result.status) {
                $("#lihat-data").html('')
                $("#modal-import").modal('hide')

                const listPeserta = result.listPeserta;
                tablePesertaLokasi.clear()
                $.each(listPeserta, (index, item) => {
                    tablePesertaLokasi.addRow([
                        tablePesertaLokasi.getNextNumber(),
                        item.npm,
                        item.nama,
                        item.jenis_kelamin,
                        item.prodi,
                        item.fakultas,
                        `<button onclick="deletePeserta(this)" data-npm="${item.npm}" data-nama="${item.nama}" class="btn btn-sm btn-icon btn-danger">
                            <i class="fa fa-trash"></i>
                        </button>`
                    ])
                })

            }
        }
    }).fail(() => {
        swalFailed()
    })
}