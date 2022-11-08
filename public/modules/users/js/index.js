const tableUser = KTDataTable;
KTUtil.onDOMContentLoaded((function () {
    tableUser.init('#table-user', "Daftar User", {
        info: true,
        pageLength: 10,
        lengthChange: true,
        columnDefs: [{
            orderable: true,
            targets: [0, 1, 2, 3, 4]
        },
        {
            className: 'text-center',
            target: [0, 4, 5]
        }],
        createdRow: function (row, data, dataIndex, cells) {
            $(row).addClass(data[1]);
        }
    })
}));

$("#form-add-user").submit((e) => {
    e.preventDefault()

    swalConfirm(
        "Konfirmasi",
        "Anda yakin ingin menambahkan user ini?",
        "Ya, tambahkan",
        "success",
        () => {
            showSwalLoader()
            $.ajax({
                url: urlAddUser,
                dataType: 'json',
                type: 'post',
                data: $("#form-add-user").serialize(),
                success: (result) => {
                    csrf_token = result.csrf_token
                    $("input[name=_token]").val(csrf_token)

                    showSwal(result.status ? 'success' : 'error', result.msg)
                    if (result.status) {
                        $("#form-add-user").trigger('reset')
                        $("#role").trigger('change')

                        tableUser.addRow([
                            tableUser.getNextNumber(),
                            result.newUser.username,
                            result.newUser.nama,
                            result.newUser.email,
                            result.newUser.rolename,
                            `<button onclick="deleteUser(this)" data-username="${result.newUser.username}" class="btn btn-sm btn-icon btn-danger">
                                <i class="fa fa-ban"></i>
                            </button>
                            <button onclick="resetPassword(this)" data-username="${result.newUser.username}" class="btn btn-sm btn-icon btn-warning">
                                <i class="fa fa-rotate"></i>
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

function deleteUser(button) {
    const btn = $(button)
    const username = btn.data('username')
    swalConfirm(
        'Konfirmasi',
        `Anda yakin ingin menghapus user <b>${username}</b>?`,
        'Ya, hapus',
        'danger',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlDeleteUser,
                type: 'delete',
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    username: username
                },
                success: (result) => {
                    csrf_token = result.csrf_token

                    showSwal(result.status ? 'success' : 'error', result.msg)

                    if (result.status) {
                        tableUser.deleteRow(`.${username}`)
                    }
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}

function resetPassword(button) {
    const btn = $(button)
    const username = btn.data('username')
    swalConfirm(
        'Konfirmasi',
        `Anda yakin ingin mereset password user <b>${username}</b>?`,
        'Ya, reset',
        'danger',
        () => {
            showSwalLoader()
            $.ajax({
                url: urlResetPassword,
                type: 'post',
                dataType: 'json',
                data: {
                    _token: csrf_token,
                    username: username
                },
                success: (result) => {
                    csrf_token = result.csrf_token

                    showSwal(result.status ? 'success' : 'error', result.msg)
                }
            }).fail(() => {
                swalFailed()
            })
        }
    )
}