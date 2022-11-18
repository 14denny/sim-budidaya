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