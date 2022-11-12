"use strict"
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
KTUtil.onDOMContentLoaded((function () {
    ModalAddLog.init()
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

function closeModal() {
    swalConfirm('Konfirmasi', 'Kamu yakin ingin menutup halaman ini? Data yang sudah kamu input akan dihapus.',
        'Ya, yakin',
        'danger',
        () => {
            $("#form-add-log").trigger('reset')
            $("#fase").val('').trigger('change')
            $("#ada_hama_penyakit").trigger('change')
            $("#hama_penyakit").val('').trigger('change')
            $("#list-hama-penyakit").html('')
            $("#modal-add-log").modal('hide')
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
            id_lokasi: idLokasi
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
            id_lokasi: idLokasi,
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
            id_lokasi: idLokasi,
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