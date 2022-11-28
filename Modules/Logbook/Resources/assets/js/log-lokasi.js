"use strict"
var dropzone, dropzoneEdit;
var datePickerEdit, startTimePickerEdit, endTimePickerEdit;
var ModalAddLog = function () {
    var tglPicker, startTimePicker, endTimePicker;
    return {
        init: function () {
            const modal = document.getElementById("modal-add-log");
            const form = modal.querySelector("#form-add-log");
            tglPicker = flatpickr(form.querySelector('#tgl_log'), {
                enableTime: false,
                dateFormat: "d-m-Y",
                maxDate: 'today',
            }), startTimePicker = flatpickr(form.querySelector("#time_start"), {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            }), endTimePicker = flatpickr(form.querySelector("#time_end"), {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
            });
        }
    }
}();

var file_uploaded = [];
var file_uploaded_edit = [];

const tableLog = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    ModalAddLog.init()
    dropzone = new Dropzone("#foto-log", {
        init: function () {
            this.cleaningUp = false;
            this.on('removedfile', function (file) {
                if (!this.cleaningUp) {
                    if (file.accepted) {
                        $.each(file_uploaded, (index, item) => {
                            if (item.name == file.name) {
                                $.ajax({
                                    url: urlDeleteFotoTmp,
                                    dataType: 'json',
                                    type: 'post',
                                    data: {
                                        _token: csrf_token,
                                        filename: item.hashName
                                    },
                                    success: (result) => {
                                        csrf_token = result.csrf_token;
                                        $("input[name=_token]").val(csrf_token)

                                        $(`img[alt='${file.name}']`).closest('.dz-image-preview').remove()
                                    }
                                }).fail(() => {
                                    swalFailed()
                                })
                            }
                        })
                        return true;
                    } else {
                        $(`img[alt='${file.name}']`).closest('.dz-image-preview').remove()
                        return true;
                    }
                }
            });
            this.cleanUp = function () {
                this.cleaningUp = true;
                this.removeAllFiles();
                this.cleaningUp = false;
            };
        },
        dictRemoveFileConfirmation: "Kamu yakin ingin menghapus foto ini?",
        url: urlUploadFotoTmp,
        params: {
            _token: csrf_token,
        },
        paramName: "file_unggah",
        maxFiles: 4,
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        accept: function (file, done) {
            if (!file.type.startsWith('image/')) {
                done('Berkas yang dapat diunggah hanya gambar.')
            } else {
                done()
            }
        },
        success: function (file, response) {
            const res = JSON.parse(response)
            if (res.status) {
                file_uploaded.push({
                    name: file.name,
                    hashName: res.filename
                });
            } else {
                showSwal('error', res.msg)
            }

        }
    })

    dropzoneEdit = new Dropzone("#foto-log-edit", {
        init: function () {
            this.cleaningUp = false;
            this.on('removedfile', function (file) {
                if (!this.cleaningUp) {
                    if (file.accepted) {
                        $.each(file_uploaded_edit, (index, item) => {
                            if (item.name == file.name) {
                                $.ajax({
                                    url: urlDeleteFotoTmp,
                                    dataType: 'json',
                                    type: 'post',
                                    data: {
                                        _token: csrf_token,
                                        filename: item.hashName
                                    },
                                    success: (result) => {
                                        csrf_token = result.csrf_token;
                                        $("input[name=_token]").val(csrf_token)

                                        $(`img[alt='${file.name}']`).closest('.dz-image-preview').remove()
                                    }
                                }).fail(() => {
                                    swalFailed()
                                })
                            }
                        })
                        return true;
                    } else {
                        $(`img[alt='${file.name}']`).closest('.dz-image-preview').remove()
                        return true;
                    }
                }
            });
            this.cleanUp = function () {
                this.cleaningUp = true;
                this.removeAllFiles();
                this.cleaningUp = false;
            };
        },
        dictRemoveFileConfirmation: "Kamu yakin ingin menghapus foto ini?",
        url: urlUploadFotoTmp,
        params: {
            _token: csrf_token,
        },
        paramName: "file_unggah",
        maxFiles: 4,
        maxFilesize: 5, // MB
        addRemoveLinks: true,
        accept: function (file, done) {
            if (!file.type.startsWith('image/')) {
                done('Berkas yang dapat diunggah hanya gambar.')
            } else {
                done()
            }
        },
        success: function (file, response) {
            const res = JSON.parse(response)
            if (res.status) {
                file_uploaded_edit.push({
                    name: file.name,
                    hashName: res.filename
                });
            } else {
                showSwal('error', res.msg)
            }

        }

    })

    tableLog.init('#table-log', "Daftar Log Budidaya", {
        info: true,
        pageLength: 10,
        lengthChange: true,
        processing: true,
        columnDefs: [
            {
                orderable: false,
                targets: '_all',
                className: 'align-middle'
            },
            {
                className: 'text-center',
                target: [0, 2, 5]
            },
            {
                className: 'fit-td px-5 text-center',
                target: [0]
            },
            {
                className: 'cut-text',
                target: [4]
            },
            // {
            //     visible: false,
            //     target: [1]
            // }
        ],
        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass("log-" + $(cells[0]).data('id'));
        }
    })

    datePickerEdit = flatpickr($('#tgl_log-edit'), {
        enableTime: false,
        dateFormat: "d-m-Y",
        maxDate: 'today',
    });
    startTimePickerEdit = flatpickr($("#time_start-edit"), {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });
    endTimePickerEdit = flatpickr($("#time_end-edit"), {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
    });

}));

const divSelectTahap = $("#select-tahap");
const divSelectkegiatan = $("#select-kegiatan");
const divSelectDetilKegiatan = $("#select-detil-kegiatan");

const divSelectTahapEdit = $("#select-tahap-edit");
const divSelectkegiatanEdit = $("#select-kegiatan-edit");
const divSelectDetilKegiatanEdit = $("#select-detil-kegiatan-edit");

function getCSRF(callback) {
    $.ajax({
        url: urlCSRF,
        success: (csrf) => {
            csrf_token = csrf
            $("input[name=_token]").val(csrf_token)
            callback()
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeFase(el) {
    const select = $(el)

    const fase = select.val();

    divSelectTahap.html('')
    divSelectkegiatan.html('')
    divSelectDetilKegiatan.html('')

    if (!fase) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetTahap,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            fase: fase
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectTahap.html(result.html)
                $("#tahap").select2({
                    dropdownParent: '#select-tahap',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}
function changeFaseEdit(el) {
    const select = $(el)

    const fase = select.val();

    divSelectTahapEdit.html('')
    divSelectkegiatanEdit.html('')
    divSelectDetilKegiatanEdit.html('')

    if (!fase) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetTahap,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            fase: fase,
            edit: 1
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectTahapEdit.html(result.html)
                $("#tahap-edit").select2({
                    dropdownParent: '#select-tahap-edit',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeTahap(el) {
    const select = $(el)

    const tahap = select.val();
    divSelectkegiatan.html('')
    divSelectDetilKegiatan.html('')

    if (!tahap) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetKegiatan,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            tahap: tahap
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectkegiatan.html(result.html)
                $("#kegiatan").select2({
                    dropdownParent: '#select-kegiatan',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeTahapEdit(el) {
    const select = $(el)

    const tahap = select.val();
    divSelectkegiatanEdit.html('')
    divSelectDetilKegiatanEdit.html('')

    if (!tahap) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetKegiatan,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            tahap: tahap,
            edit: 1
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectkegiatanEdit.html(result.html)
                $("#kegiatan-edit").select2({
                    dropdownParent: '#select-kegiatan-edit',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeKegiatan(el) {
    const select = $(el)

    const kegiatan = select.val();
    divSelectDetilKegiatan.html('')

    if (!kegiatan) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetDetilKegiatan,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            kegiatan: kegiatan
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectDetilKegiatan.html(result.html)
                $("#detil-kegiatan").select2({
                    dropdownParent: '#select-detil-kegiatan',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function changeKegiatanEdit(el) {
    const select = $(el)

    const kegiatan = select.val();
    divSelectDetilKegiatanEdit.html('')

    if (!kegiatan) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetDetilKegiatan,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            kegiatan: kegiatan,
            edit: 1
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $("input[name=_token]").val(csrf_token)

            if (result.status) {
                closeSwal()
                divSelectDetilKegiatanEdit.html(result.html)
                $("#detil-kegiatan-edit").select2({
                    dropdownParent: '#select-detil-kegiatan-edit',
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function getDeskripsiHamaPenyakit(el) {
    const select = $(el)
    const selectedOpt = select.find(':selected')
    $("#ditemukan_lainnya").hide()
    if (selectedOpt.val() && selectedOpt.val() != '-1') {
        const ketJenis = selectedOpt.data('ket-jenis')
        const deskripsi = selectedOpt.data('desc')

        $("#desc").html(`
            <strong>JENIS: ${ketJenis}</strong><br>` +
            (deskripsi ? `<strong>DESKRIPSI:</strong><br>${deskripsi}` : '')
        )
    } else {
        if (selectedOpt.val() == '-1') {
            $("#ditemukan_lainnya").show()
        } else {
            $("#desc").html('')
        }
    }

}

function getDeskripsiHamaPenyakitEdit(el) {
    const select = $(el)
    const selectedOpt = select.find(':selected')
    if (selectedOpt.val() && selectedOpt.val() != '-1') {
        const ketJenis = selectedOpt.data('ket-jenis')
        const deskripsi = selectedOpt.data('desc')

        $("#desc-edit").html(`
            <strong>JENIS: ${ketJenis}</strong><br>` +
            (deskripsi ? `<strong>DESKRIPSI:</strong><br>${deskripsi}` : '')
        )
    } else {
        if (selectedOpt.val() == '-1') {
            $("#ditemukan_lainnya-edit").show()
        } else {
            $("#desc-edit").html('')
        }
    }

}

function toggleAddHama(el) {
    const checkbox = $(el)
    if (checkbox.prop('checked')) {
        $("#add-hama-penyakit").slideDown()
    } else {
        $("#hama_penyakit").val('').trigger('change')
        $("#add-hama-penyakit").slideUp()
    }
}

function toggleAddHamaEdit(el) {
    const checkbox = $(el)
    if (checkbox.prop('checked')) {
        $("#add-hama-penyakit-edit").slideDown()
    } else {
        $("#hama_penyakit-edit").val('').trigger('change')
        $("#add-hama-penyakit-edit").slideUp()
    }
}

function clearForm() {
    $("#form-add-log").trigger('reset')
    $("#fase").val('').trigger('change')
    $("#ada_hama_penyakit").trigger('change')
    $("#hama_penyakit").val('').trigger('change')
    $("#list-hama-penyakit").html('')
    dropzone.cleanUp()
    file_uploaded = []
    $("#modal-add-log").modal('hide')
}

function clearFormEdit() {
    $("#form-add-log-edit").trigger('reset')
    $("#select-fase-edit").html('')
    $("#select-tahap-edit").html('')
    $("#select-kegiatan-edit").html('')
    $("#select-detil-kegiatan-edit").html('')
    $("#ada_hama_penyakit-edit").trigger('change')
    $("#hama_penyakit-edit").val('').trigger('change')
    $("#list-hama-penyakit-edit").html('')
    $("#id_logbook").val('')
    dropzoneEdit.cleanUp()
    file_uploaded_edit = []
    $("#modal-edit-log").modal('hide')
}

function closeModal() {
    swalConfirm('Konfirmasi', 'Kamu yakin ingin menutup halaman ini? Data yang sudah kamu input akan dihapus.',
        'Ya, yakin',
        'danger',
        () => {
            clearForm()
        },
        () => { }
    )
}

function closeModalEdit() {
    swalConfirm('Konfirmasi', 'Kamu yakin ingin menutup halaman ini? Data yang sudah kamu ubah akan dihapus.',
        'Ya, yakin',
        'danger',
        () => {
            clearFormEdit()
        },
        () => { }
    )
}

function openModal() {
    //hapus log hama penyakit tmp
    showSwalLoader()
    $.ajax({
        url: urlClearLogTmp,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            closeSwal()

            const modal = $("#modal-add-log").modal({
                backdrop: 'static',
                keyboard: false
            })
            modal.modal('show')

        }
    }).fail(() => {
        swalFailed()
    })
}

function tambahHamaPenyakit() {
    const idHamaPenyakit = $("#hama_penyakit").val()
    if (!idHamaPenyakit) {
        return;
    }

    //idHamaPenyakit == -1 artinya ada penemuan lain
    const penemuanLain = $("#ditemukan_lainnya").val();
    if (idHamaPenyakit == '-1' && !penemuanLain) {
        showSwal('info', 'Harap masukkan penemuan yang kamu temukan');
        return;
    }

    showSwalLoader()
    $.ajax({
        url: urlInsertHamaPenyakit,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            penemuan_lain: penemuanLain,
            hama_penyakit: idHamaPenyakit,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                $("#list-hama-penyakit").html('')
                closeSwal()
                const listHamaPenyakit = result.listHamaPenyakit;
                const listPenemuanLain = result.listPenemuanLain;

                $.each(listHamaPenyakit, (index, hamaPenyakitTmp) => {
                    $("#list-hama-penyakit").append(`
                    <tr>
                        <td class="text-center align-middle">${hamaPenyakitTmp.jenis_hama_penyakit}</td>
                        <td class="align-middle">${hamaPenyakitTmp.ket}</td>
                        <td class="text-center">
                            <button type="button" onclick="hapusHamaPenyakitTmp(this)" data-id="${hamaPenyakitTmp.id_hama_penyakit_tmp}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                });

                $.each(listPenemuanLain, (index, item) => {
                    $("#list-hama-penyakit").append(`
                    <tr>
                        <td class="text-center align-middle">-</td>
                        <td class="align-middle">${item.penemuan}</td>
                        <td class="text-center">
                            <button type="button" onclick="hapusPenemuanLainTmp(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                });

                //clear select
                $("#hama_penyakit").val('').trigger('change')
                $("#ditemukan_lainnya").val('')
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function tambahHamaPenyakitEdit() {
    const idHamaPenyakit = $("#hama_penyakit-edit").val()
    if (!idHamaPenyakit) {
        return;
    }

    //idHamaPenyakit == -1 artinya ada penemuan lain
    const penemuanLain = $("#ditemukan_lainnya-edit").val();
    if (idHamaPenyakit == '-1' && !penemuanLain) {
        showSwal('info', 'Harap masukkan penemuan yang kamu temukan');
        return;
    }

    showSwalLoader()
    $.ajax({
        url: urlInsertHamaPenyakit,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            penemuan_lain: penemuanLain,
            hama_penyakit: idHamaPenyakit,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                $("#list-hama-penyakit-edit").html('')
                closeSwal()
                const listHamaPenyakit = result.listHamaPenyakit;
                const listPenemuanLain = result.listPenemuanLain;

                $.each(listHamaPenyakit, (index, hamaPenyakitTmp) => {
                    $("#list-hama-penyakit-edit").append(`
                    <tr>
                        <td class="text-center align-middle">${hamaPenyakitTmp.jenis_hama_penyakit}</td>
                        <td class="align-middle">${hamaPenyakitTmp.ket}</td>
                        <td class="text-center">
                            <button type="button" onclick="hapusHamaPenyakitTmp(this)" data-id="${hamaPenyakitTmp.id_hama_penyakit_tmp}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                });

                $.each(listPenemuanLain, (index, item) => {
                    $("#list-hama-penyakit-edit").append(`
                    <tr>
                        <td class="text-center align-middle">-</td>
                        <td class="align-middle">${item.penemuan}</td>
                        <td class="text-center">
                            <button type="button" onclick="hapusPenemuanLainTmp(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    `);
                });

                //clear select
                $("#hama_penyakit-edit").val('').trigger('change')
                $("#ditemukan_lainnya-edit").val('')
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function hapusHamaPenyakitTmp(el) {
    const btn = $(el)
    const idHamaPenyakitTmp = btn.data('id')
    if (!idHamaPenyakitTmp) {
        return;
    }

    showSwalLoader()
    $.ajax({
        url: urlDeleteHamaPenyakit,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id_hama_penyakit_tmp: idHamaPenyakitTmp,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                closeSwal()
                btn.closest('tr').remove()
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function hapusPenemuanLainTmp(el) {
    const btn = $(el)
    const idPenemuanLain = btn.data('id')
    if (!idPenemuanLain) {
        return;
    }

    showSwalLoader()
    $.ajax({
        url: urlDeletePenemuanLain,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id_penemuan_lain: idPenemuanLain,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                closeSwal()
                btn.closest('tr').remove()
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function submitLog() {
    const form = $("#form-add-log")

    if (file_uploaded.length == 0) {
        showSwal('info', 'Harap lampirkan foto kegiatan terlebih dahulu')
        return;
    }

    swalConfirm(
        'Konfirmasi',
        'Apakah kamu sudah yakin data sudah benar?',
        'Ya, yakin',
        'success',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlSubmitLog,
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: (result) => {
                    csrf_token = result.csrf_token;
                    $('input[name=_token]').val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg);
                    if (result.status) {
                        clearForm()
                        reloadTable()
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function submitLogEdit() {
    const form = $("#form-edit-log")

    var warning = '';
    if (file_uploaded_edit.length > 0) {
        warning = '<br><span class="text-danger">Terdapat foto yang baru diunggah, foto lama akan dihapus!<span>'
    }

    swalConfirm(
        'Konfirmasi',
        'Apakah kamu sudah yakin data sudah benar?' + warning,
        'Ya, yakin',
        'success',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlSubmitLogEdit,
                type: 'post',
                dataType: 'json',
                data: form.serialize(),
                success: (result) => {
                    csrf_token = result.csrf_token;
                    $('input[name=_token]').val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg);
                    if (result.status) {
                        clearFormEdit()
                        reloadTable()
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function reloadTable() {
    tableLog.showProcessing()
    $.ajax({
        url: urlLoadTable,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token
        },
        success: (result) => {
            tableLog.hideProcessing()
            csrf_token = result.csrf_token
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                const datas = result.datas;
                tableLog.clear();
                $.each(datas, (index, item) => {
                    var tr = $('<tr>')
                        .append(`<td data-id="${item.id}">${index + 1}</td>`)
                        .append(`<td>${item.peserta_insert}</td>`)
                        .append(`<td>${item.tgl_log}<br>${item.time_start.substr(0, 5)} - ${item.time_end.substr(0, 5)}</td>`)
                        .append(`<td>${item.ket_fase}<br>${item.ket_tahap}<br>${item.ket_kegiatan}
                        ${item.detil_kegiatan ? '<br>' + item.ket_detil_kegiatan : ''}</td>`)
                        .append(`<td>${item.deskripsi}</td>`)
                        .append(`
                        <td>
                            <button onclick="showLog(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-secondary"><i
                                    class="fa fa-eye"></i></button>
                            <button onclick="editLog(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-info"><i
                                    class="fa fa-pen"></i></button>
                            <button onclick="deleteLog(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-danger"><i
                                    class="fa fa-trash"></i></button>
                        </td>
                        `)
                        .append(`</tr>`)

                    tableLog.addRow(tr)
                })
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        tableLog.hideProcessing()
        swalFailed()
    })
}

function showLog(el) {
    const btn = $(el)

    const idLog = btn.data('id')

    if (!idLog) {
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlGetLog,
        dataType: 'json',
        type: 'post',
        data: {
            _token: csrf_token,
            id: idLog
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                closeSwal()

                const log = result.log
                const hamaPenyakit = result.hamaPenyakit
                const listPenemuanLain = result.listPenemuanLain
                const foto = result.foto

                $("#detil-kegiatan-show").html(log.deskripsi)
                $("#tgl-log-show").val(log.tgl_log)
                $("#time-start-show").val(log.time_start)
                $("#time-end-show").val(log.time_end)
                $("#fase-show").val(log.ket_fase)
                $("#tahap-show").val(log.ket_tahap)
                $("#kegiatan-show").val(log.ket_kegiatan)
                if (log.detil_kegiatan) {
                    $("#fase-detil-kegiatan-show").show()
                    $("#fase-detil-kegiatan-show").val(log.ket_detil_kegiatan)
                } else {
                    $("#fase-detil-kegiatan-show").hide()
                    $("#fase-detil-kegiatan-show").val('')
                }

                var tbody = "";
                if (hamaPenyakit.length > 0 || listPenemuanLain.length > 0) {
                    $.each(hamaPenyakit, (index, item) => {
                        tbody += `<tr>`
                        tbody += `<td class="text-center">${item.jenis_hama_penyakit}</td>`
                        tbody += `<td>${item.ket}</td>`
                        tbody += `<td>${item.deskripsi ? item.deskripsi : ''}</td>`
                        tbody += `</tr>`
                    })
                    $.each(listPenemuanLain, (index, item) => {
                        tbody += `<tr>`
                        tbody += `<td class="text-center">-</td>`
                        tbody += `<td>${item.penemuan}</td>`
                        tbody += `<td>-</td>`
                        tbody += `</tr>`
                    })
                    $('#list-hama-penyakit-show').html(tbody)
                } else {
                    $('#list-hama-penyakit-show').html(`<tr class="text-center"><td colspan="3">Tidak ada data</td></tr>`)
                }


                if (foto.length > 0) {
                    var img = ""
                    $.each(foto, (index, item) => {
                        img += `<img src="${baseUrlFoto}/${item.filename}" class="rounded-4 img-fluid mh-300px p-2 mw-50" style="grid-auto-flow: dense">`
                    })
                    $('#foto-log-show').html(img)
                } else {
                    $('#foto-log-show').html('')
                }

                $("#modal-show-log").modal('show')
            } else {
                showSwal('info', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function editLog(el) {
    const btn = $(el)

    const idLog = btn.data('id')

    if (!idLog) {
        return
    }

    //hapus log hama penyakit tmp
    showSwalLoader()
    $.ajax({
        url: initEditLog,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            id_log: idLog
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (!result.status) {
                showSwal('error', result.msg)
                return
            }

            $.ajax({
                url: urlGetLog,
                dataType: 'json',
                type: 'post',
                data: {
                    _token: csrf_token,
                    id: idLog,
                    edit: 1
                },
                success: (result) => {
                    csrf_token = result.csrf_token
                    $('input[name=_token]').val(csrf_token)

                    if (result.status) {
                        closeSwal()

                        const log = result.log
                        const hamaPenyakit = result.hamaPenyakit
                        const listPenemuanLain = result.listPenemuanLain
                        const foto = result.foto

                        $("#id_logbook").val(idLog);

                        $("#detil-edit").val(log.deskripsi)
                        datePickerEdit.setDate(log.tgl_log)
                        startTimePickerEdit.setDate(log.time_start)
                        endTimePickerEdit.setDate(log.time_end)

                        $("#select-fase-edit").html(result.select2Fase)
                        $("#fase-edit").val(log.fase)
                        $("#fase-edit").select2({
                            dropdownParent: '#select-fase-edit',
                        })

                        $("#select-tahap-edit").html(result.select2Tahap)
                        $("#tahap-edit").val(log.tahap)
                        $("#tahap-edit").select2({
                            dropdownParent: '#select-tahap-edit',
                        })

                        $("#select-kegiatan-edit").html(result.select2Kegiatan)
                        $("#kegiatan-edit").val(log.kegiatan)
                        $("#kegiatan-edit").select2({
                            dropdownParent: '#select-kegiatan-edit',
                        })

                        if (log.detil_kegiatan) {
                            $("#select-detil-kegiatan-edit").html(result.select2DetilKegiatan)
                            $("#detil-kegiatan-edit").val(log.detil_kegiatan)
                            $("#detil-kegiatan-edit").select2({
                                dropdownParent: '#select-detil-kegiatan-edit',
                            })
                        } else {
                            $("#select-detil-kegiatan-edit").html('')
                        }
                        // $("#fase").val(log.fase)
                        // $("#tahap-show").val(log.ket_tahap)
                        // $("#kegiatan-show").val(log.ket_kegiatan)
                        // if (log.detil_kegiatan) {
                        //     $("#fase-detil-kegiatan-show").show()
                        //     $("#fase-detil-kegiatan-show").val(log.ket_detil_kegiatan)
                        // } else {
                        //     $("#fase-detil-kegiatan-show").hide()
                        //     $("#fase-detil-kegiatan-show").val('')
                        // }

                        var tbody = "";
                        if (hamaPenyakit.length > 0 || listPenemuanLain.length > 0) {
                            $.each(hamaPenyakit, (index, item) => {
                                tbody += `
                                <tr>
                                    <td class="text-center align-middle">${item.jenis_hama_penyakit}</td>
                                    <td class="align-middle">${item.ket}</td>
                                    <td class="text-center">
                                        <button type="button" onclick="hapusHamaPenyakitTmp(this)" data-id="${item.id_hama_penyakit_tmp}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                `
                            })
                            $.each(listPenemuanLain, (index, item) => {
                                tbody += `
                                <tr>
                                    <td class="text-center align-middle">-</td>
                                    <td class="align-middle">${item.penemuan}</td>
                                    <td class="text-center">
                                        <button type="button" onclick="hapusPenemuanLainTmp(this)" data-id="${item.id}" class="btn btn-sm btn-icon btn-light-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                `
                            })
                            $('#list-hama-penyakit-edit').html(tbody)
                            $("#ada_hama_penyakit-edit").prop('checked', true).trigger('change')
                        } else {
                            $("#ada_hama_penyakit-edit").prop('checked', false).trigger('change')
                            $('#list-hama-penyakit-edit').html(`<tr class="text-center"><td colspan="3">Tidak ada data</td></tr>`)
                        }


                        if (foto.length > 0) {
                            var img = ""
                            $.each(foto, (index, item) => {
                                img += `<img src="${baseUrlFoto}/${item.filename}" class="rounded img-fluid mh-300px p-2 mw-50" style="grid-auto-flow: dense">`
                            })
                            $('#foto-lama').show()
                            $('#foto-log-show-edit').html(img)
                        } else {
                            $('#foto-lama').hide()
                            $('#foto-log-show-edit').html('')
                        }

                        const modal = $("#modal-edit-log").modal({
                            backdrop: 'static',
                            keyboard: false
                        })
                        modal.modal('show')
                    } else {
                        showSwal('info', result.msg)
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    }).fail(() => {
        swalFailed()
    })
}

function deleteLog(el) {
    const btn = $(el)

    swalConfirm(
        'Konfirmasi',
        'Kamu yakin ingin menghapus log kegiatan ini? Data yang sudah dihapus tidak dapat dikembalikan lagi',
        'Ya, hapus',
        'danger',
        () => {
            showSwalLoader();
            $.ajax({
                url: urlDeleteLog,
                dataType: 'json',
                type: 'post',
                data: {
                    _token: csrf_token,
                    id_log: btn.data('id')
                },
                success: (result) => {
                    csrf_token = result.csrf_token
                    $('input[name=_token]').val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg);
                    reloadTable()

                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function cetakLogbook() {
    showSwalLoader()
    getCSRF(() => {
        closeSwal()
        $("#cetak-logbook").submit()
    })
}

function cetakLogbookLokasi() {
    showSwalLoader()
    getCSRF(() => {
        closeSwal()
        $("#cetak-logbook-lokasi").submit()
    })
}