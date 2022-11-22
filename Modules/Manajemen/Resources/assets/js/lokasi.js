const tableLokasi = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    tableLokasi.init('#table-lokasi', "Daftar Lokasi", {
        info: true,
        pageLength: 10,
        lengthChange: true,
        columnDefs: [{
            orderable: false,
            targets: [5]
        },
        {
            className: 'text-center',
            target: [0, 1, 5]
        }],
        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass(data[1] + " align-middle");
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
                            result.newLokasi.periode,
                            `Propinsi ${result.newLokasi.ket_propinsi}, ${result.newLokasi.ket_kabkota}, Kecamatan ${result.newLokasi.ket_kecamatan}, Desa ${result.newLokasi.ket_desa}`,
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
                            `Propinsi ${result.updateLokasi.ket_propinsi}, ${result.updateLokasi.ket_kabkota}, Kecamatan ${result.updateLokasi.ket_kecamatan}, Desa ${result.updateLokasi.ket_desa}`,
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
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id: id,
            edit: 1
        },
        success: (result) => {

            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()

                $("#id_lokasi_edit").val(result.lokasi.id)
                $("#nama_lokasi_edit").val(result.lokasi.nama_lokasi)
                $("#tahun_awal_edit").val(result.lokasi.tahun_awal).trigger('change')
                $("#bulan_awal_edit").val(result.lokasi.bulan_awal).trigger('change')
                $("#tahun_akhir_edit").val(result.lokasi.tahun_akhir).trigger('change')
                $("#bulan_akhir_edit").val(result.lokasi.bulan_akhir).trigger('change')

                $("#select-propinsi-edit").html(result.selectProp)
                $("#propinsi_edit").val(result.lokasi.propinsi)
                $("#propinsi_edit").select2({
                    dropdownParent: "#select-propinsi-edit"
                })

                $("#select-kabkota-edit").html(result.selectKabkota)
                $("#kabkota_edit").val(result.lokasi.kabkota)
                $("#kabkota_edit").select2({
                    dropdownParent: "#select-kabkota-edit"
                })

                $("#select-kecamatan-edit").html(result.selectKecamatan)
                $("#kecamatan_edit").val(result.lokasi.kecamatan)
                $("#kecamatan_edit").select2({
                    dropdownParent: "#select-kecamatan-edit"
                })

                $("#select-desa-edit").html(result.selectDesa)
                $("#desa_edit").val(result.lokasi.desa)
                $("#desa_edit").select2({
                    dropdownParent: "#select-desa-edit"
                })

                $("#modal-edit-lokasi").modal('show')
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changePropinsi(el, edit = false) {
    const select = $(el)

    const id = select.val()

    $(`#select-kabkota${edit ? '-edit' : ''}`).html('')
    $(`#select-kecamatan${edit ? '-edit' : ''}`).html('')
    $(`#select-desa${edit ? '-edit' : ''}`).html('')

    if (!id) {
        return
    }

    showSwalLoader()

    $.ajax({
        url: urlGetKabkota,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id: id,
            edit: (edit ? 1 : 0)
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()

                $(`#select-kabkota${edit ? '-edit' : ''}`).html(result.select2)
                $(`#kabkota${edit ? '_edit' : ''}`).select2({
                    placeholder: 'Pilih Kabupaten/Kota'
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeKabkota(el, edit = false) {
    const select = $(el)

    const id = select.val()

    $(`#select-kecamatan${edit ? '-edit' : ''}`).html('')
    $(`#select-desa${edit ? '-edit' : ''}`).html('')

    if (!id) {
        return
    }

    showSwalLoader()

    $.ajax({
        url: urlGetKecamatan,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id: id,
            edit: (edit == false ? 0 : 1)
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()

                $(`#select-kecamatan${edit ? '-edit' : ''}`).html(result.select2)
                $(`#kecamatan${edit ? '_edit' : ''}`).select2({
                    placeholder: 'Pilih Kecamatan'
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeKecamatan(el, edit = false) {
    const select = $(el)

    const id = select.val()

    $(`#select-desa${edit ? '-edit' : ''}`).html('')

    if (!id) {
        return
    }

    showSwalLoader()

    $.ajax({
        url: urlGetDesa,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id: id,
            edit: (edit ? 1 : 0)
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()

                $(`#select-desa${edit ? '-edit' : ''}`).html(result.select2)
                $(`#desa${edit ? '_edit' : ''}`).select2({
                    placeholder: 'Pilih Desa'
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}