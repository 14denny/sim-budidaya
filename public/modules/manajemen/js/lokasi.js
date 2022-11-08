const tableLokasi = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    tableLokasi.init('#table-lokasi', "Daftar Lokasi", {
        info: true,
        pageLength: 10,
        lengthChange: true,
        columnDefs: [{
            orderable: true,
            targets: [0, 1, 2, 4]
        },
        {
            className: 'text-center',
            target: [0, 1, 4]
        }],
        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass(data[1]);
        }
    })
}));


$("#form-add-lokasi").submit((e) => {
    e.preventDefault()

    swalConfirm(
        "Konfirmasi",
        "Anda yakin ingin menambahkan lokasi ini?",
        "Ya, tambahkan",
        "success",
        () => {
            showSwalLoader()
            $.ajax({
                url: urlAddLokasi,
                dataType: 'json',
                type: 'post',
                data: $("#form-add-lokasi").serialize(),
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)
                    if (result.status) {
                        $("#form-add-lokasi").trigger('reset')

                        tableLokasi.addRow([
                            tableLokasi.getNextNumber(),
                            result.newLokasi.id,
                            result.newLokasi.nama_lokasi,
                            `${result.newLokasi.propinsi}, ${result.newLokasi.kabkota}, ${result.newLokasi.kecamatan}, ${result.newLokasi.desa}`,
                            `<button onclick="deleteLokasi(this)" data-id="${result.newLokasi.id}" data-nama="${result.newLokasi.nama_lokasi}" class="btn btn-sm btn-icon btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button onclick="editLokasi(this)" data-id="${result.newLokasi.id}" data-nama="${result.newLokasi.nama_lokasi}" class="btn btn-sm btn-icon btn-light-primary">
                                <i class="fa fa-edit"></i>
                            </button>`
                        ])
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    );
})

$("#form-edit-lokasi").submit((e) => {
    e.preventDefault()

    swalConfirm(
        "Konfirmasi",
        "Anda yakin ingin menyimpan perubahan data lokasi?",
        "Ya, simpan",
        "success",
        () => {
            showSwalLoader()
            $.ajax({
                url: urlEditLokasi,
                dataType: 'json',
                type: 'put',
                data: $("#form-edit-lokasi").serialize(),
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)
                    if (result.status) {
                        $("#form-add-lokasi").trigger('reset')

                        tableLokasi.modRow(`.${result.updateLokasi.id}`, [
                            tableLokasi.getCurrentNumber(`.${result.updateLokasi.id}`),
                            result.updateLokasi.id,
                            result.updateLokasi.nama_lokasi,
                            `${result.updateLokasi.propinsi}, ${result.updateLokasi.kabkota}, ${result.updateLokasi.kecamatan}, ${result.updateLokasi.desa}`,
                            `<button onclick="deleteLokasi(this)" data-id="${result.updateLokasi.id}" data-nama="${result.updateLokasi.nama_lokasi}" class="btn btn-sm btn-icon btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                            <button onclick="editLokasi(this)" data-id="${result.updateLokasi.id}" data-nama="${result.updateLokasi.nama_lokasi}" class="btn btn-sm btn-icon btn-light-primary">
                                <i class="fa fa-edit"></i>
                            </button>`
                        ])

                        $("#form-edit-lokasi").trigger('reset')
                        $("#id_lokasi_edit").val('')
                        $("#modal-edit-lokasi").modal('hide')
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    );
})

function deleteLokasi(button) {
    const btn = $(button)
    const id = btn.data('id')
    const nama = btn.data('nama')
    swalConfirm(
        'Konfirmasi',
        `Anda yakin ingin menghapus lokasi <b>${nama}</b>?`,
        'Ya, hapus',
        'danger',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlDeleteLokasi,
                type: 'delete',
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    id: id
                },
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)

                    if (result.status) {
                        tableLokasi.deleteRow(`.${id}`)
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function editLokasi(button) {
    const btn = $(button)
    const id = btn.data('id')
    $("#id_lokasi_edit").val('')
    $("#form-edit-lokasi").trigger('reset')
    showSwalLoader()
    $.ajax({
        url: urlGetOneLokasi,
        type: 'get',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id: id
        },
        success: (result) => {

            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()

                $("#id_lokasi_edit").val(result.lokasi.id)
                $("#nama_lokasi_edit").val(result.lokasi.nama_lokasi)
                $("#propinsi_edit").val(result.lokasi.propinsi)
                $("#kabkota_edit").val(result.lokasi.kabkota)
                $("#kecamatan_edit").val(result.lokasi.kecamatan)
                $("#desa_edit").val(result.lokasi.desa)

                $("#modal-edit-lokasi").modal('show')
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}