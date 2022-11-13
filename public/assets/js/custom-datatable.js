"use strict";

jQuery.fn.dataTable.Api.register('processing()', function (show) {
    return this.iterator('table', function (ctx) {
        ctx.oApi._fnProcessingDisplay(ctx, show);
    });
});

var KTDataTable = function () {
    // Shared variables
    var table;
    var datatable;

    var initDataTable = function (options={}) {
        datatable = $(table).DataTable(options);
    }

    var exportButtons = (documentTitle) => {
        // const documentTitle = documentTitle;
        var buttons = new $.fn.dataTable.Buttons(table, {
            buttons: [
                {
                    extend: 'copyHtml5',
                    title: documentTitle,
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'excelHtml5',
                    title: documentTitle,
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: documentTitle,
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: documentTitle,
                    exportOptions: {
                        columns: 'th:not(:last-child)'
                    }
                }
            ]
        }).container().appendTo($('#datatable-export-btn'));

        // Hook dropdown menu click event to datatable export buttons
        const exportButtons = document.querySelectorAll('#datatable-export [data-kt-export]');
        exportButtons.forEach(exportButton => {
            exportButton.addEventListener('click', e => {
                e.preventDefault();

                // Get clicked export value
                const exportValue = e.target.getAttribute('data-kt-export');
                const target = document.querySelector('.dt-buttons .buttons-' + exportValue);

                target.click();
            });
        });
    }

    var delayTimer;
    var lastValueSearch = ""
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {

            //prevent useless key listened (alt, ctrl, shift, etc)
            //listen only to changed value
            if (e.target.value != lastValueSearch) {
                lastValueSearch = e.target.value
                if (delayTimer) {
                    clearTimeout(delayTimer);
                }

                delayTimer = setTimeout(function () {
                    datatable.search(e.target.value).draw();
                }, 1000);
            }
        });
    }



    return {
        init: function (tableid, documentTitle, options) {
            table = document.querySelector(tableid);

            if (!table) {
                return;
            }

            initDataTable(options);
            exportButtons(documentTitle);
            handleSearchDatatable();
        },
        getNextNumber: function () {
            const lastRow = datatable.row(':last').data();
            return parseInt(lastRow ? lastRow[0] : 0) + 1
        },
        getCurrentNumber: function (rowClass) {
            const rowData = datatable.row(rowClass).data()
            return parseInt(rowData ? rowData[0] : -1)
        },
        addRow: function (data) {
            datatable.row.add(data).draw()
        },
        addRows: function (data) {
            datatable.rows.add(data).draw()
        },
        deleteRow: function (selector) {
            // delete row
            datatable.row($(selector)).remove()

            //reorder number
            datatable.rows().every(function (rowIdx, tableLoop, rowLoop) {
                var data = this.data();
                data[0] = rowLoop + 1
                datatable.row(rowIdx).data(data)
            });

            datatable.draw()
        }, modRow: function (rowClass, data) {
            datatable.row(rowClass).data(data).draw()
        },
        reloadAjax: function () {
            datatable.ajax.reload(null, false)
        },
        clear:function(){
            datatable.clear().draw()
        },
        showProcessing: function(){
            datatable.processing(true)
        },
        hideProcessing: function(){
            datatable.processing(false)
        }
    }
}();

