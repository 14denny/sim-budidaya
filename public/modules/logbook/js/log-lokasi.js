"use strict"
var dropzone;
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

const tableLog = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    ModalAddLog.init()
    dropzone = new Dropzone("#foto-log", {
        init: function () {
            this.cleaningUp = false;
            this.on('removedfile', function (file) {
                if (!this.cleaningUp) {
                    if (file.accepted) {
                        const status = window.confirm('Kamu yakin ingin menghapus berkas ini?')
                        if (status) {
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
                        }
                        return status;
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
            // console.log(response)
            const res = JSON.parse(response)
            if (res.status) {
                file_uploaded.push({
                    name: file.name,
                    hashName: res.filename
                });
                // $("#short_url_readonly").val(res.url)
                // $("#short_url").val(res.url)

                // nextBtn.disabled = false;
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
                target: [0, 1, 4]
            },
            {
                className: 'cut-text',
                target: [3]
            },
            // {
            //     visible: false,
            //     target: [1]
            // }
        ],
        createdRow: function (row, data, dataIndex, cells) {
            console.log($(cells[0]).data('id'))
            $(row).addClass("log-" + $(cells[0]).data('id'));
        }
    })
}));

const divSelectTahap = $("#select-tahap");
const divSelectkegiatan = $("#select-kegiatan");
const divSelectDetilKegiatan = $("#select-detil-kegiatan");

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

function getDeskripsiHamaPenyakit(el) {
    const select = $(el)
    const selectedOpt = select.find(':selected')
    if (selectedOpt.val()) {
        const ketJenis = selectedOpt.data('ket-jenis')
        const deskripsi = selectedOpt.data('desc')

        $("#desc").html(`
            <strong>JENIS: ${ketJenis}</strong><br>` +
            (deskripsi ? `<strong>DESKRIPSI:</strong><br>${deskripsi}` : '')
        )
    } else {
        $("#desc").html('')
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

    showSwalLoader()
    $.ajax({
        url: urlInsertHamaPenyakit,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            hama_penyakit: idHamaPenyakit,
        },
        success: (result) => {
            csrf_token = result.csrf_token;
            $('input[name=_token]').val(csrf_token)

            if (result.status) {
                $("#list-hama-penyakit").html('')
                closeSwal()
                const listHamaPenyakit = result.listHamaPenyakit;

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

                //clear select
                $("#hama_penyakit").val('').trigger('change')
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
            console.log(result.datas)

            if (result.status) {
                const datas = result.datas;
                tableLog.clear();
                $.each(datas, (index, item) => {
                    var tr = $('<tr>')
                        .append(`<td data-id="${item.id}">${index + 1}</td>`)
                        .append(`<td>${item.tgl_log}<br>${item.time_start} - ${item.time_end}</td>`)
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

                // console.log(rows)
                // tableLog.addRows(rows)
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        tableLog.hideProcessing()
        swalFailed()
    })
}