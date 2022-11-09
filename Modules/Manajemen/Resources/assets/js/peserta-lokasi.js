const tablePesertaLokasi = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    tablePesertaLokasi.init('#table-peserta', `Daftar Peserta Lokasi ${namaLokasi}`, {
        info: true,
        pageLength: 10,
        lengthChange: true,
        columnDefs: [{
            orderable: true,
            targets: [0, 1, 2, 3, 4, 5]
        },
        {
            className: 'text-center',
            target: [0, 1, 3, 6]
        }],
        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass(data[1] + " align-middle");
        }
    })
}));


function deletePeserta(button) {
    const btn = $(button)
    const npm = btn.data('npm')
    const nama = btn.data('nama')
    swalConfirm(
        'Konfirmasi',
        `Anda yakin ingin menghapus peserta <b>${nama}</b> dari lokasi <b>${namaLokasi}</b>?`,
        'Ya, hapus',
        'danger',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlDeletePeserta,
                type: 'delete',
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    npm: npm,
                    id_lokasi: idLokasi
                },
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)

                    if (result.status) {
                        tablePesertaLokasi.deleteRow(`.${npm}`)
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function cariMhs() {
    const npm = $("#npm").val()

    if (!npm) {
        showSwal("error", "Harap isi NPM terlebih dahulu")
        return
    }

    showSwalLoader()
    $.ajax({
        url: urlCariMhs,
        type: 'post',
        dataType: 'json',
        data: {
            _token: csrf_token,
            npm: npm
        },
        success: (result) => {
            csrf_token = result.csrf_token
            $("input[name=_token]").val(csrf_token)


            if (result.status) {
                closeSwal()
                const dataMhs = result.response;
                // $("#detil-mhs").html(result.response)
                $("#npm-pencarian").html(dataMhs.nim13)
                $("#nama-pencarian").html(dataMhs.nama_mhs)
                $("#jk-pencarian").html(dataMhs.jenis_kelamin == "2" ? "Laki-laki" : "Perempuan")
                $("#fakultas-pencarian").html(dataMhs.nama_fakultas)
                $("#prodi-pencarian").html(dataMhs.nama_prodi)
            } else {
                showSwal('error', result.msg)
            }
        }
    }).fail(() => {
        swalFailed()
    })
}

function tambahMhs() {

    const npm = $("#npm").val()

    if (!npm) {
        showSwal("error", "Harap isi NPM terlebih dahulu")
        return
    }

    swalConfirm(
        "Konfirmasi",
        `Anda yakin ingin menambahkan mahasiswa <b>${npm}</b> ke lokasi <b>${namaLokasi}</b>?`,
        "Ya, tambahkan",
        "success",
        () => {
            showSwalLoader()
            $.ajax({
                url: urlAddPeserta,
                dataType: 'json',
                type: 'post',
                data: {
                    _token: csrf_token,
                    npm: npm,
                    id_lokasi: idLokasi
                },
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)
                    if (result.status) {
                        $("#npm").val()
                        $("#npm-pencarian").html('')
                        $("#nama-pencarian").html('')
                        $("#jk-pencarian").html('')
                        $("#fakultas-pencarian").html('')
                        $("#prodi-pencarian").html('')

                        tablePesertaLokasi.addRow([
                            tablePesertaLokasi.getNextNumber(),
                            result.newPeserta.npm,
                            result.newPeserta.nama,
                            result.newPeserta.jenis_kelamin,
                            result.newPeserta.prodi,
                            result.newPeserta.fakultas,
                            `<button onclick="deletePeserta(this)" data-npm="${result.newPeserta.npm}" data-nama="${result.newPeserta.nama}" class="btn btn-sm btn-icon btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>`
                        ])
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    );
}