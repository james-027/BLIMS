let jobsTable;

$(document).ready(function () {

    jobsTable = $('#jobsTable').DataTable({
        scrollX: true,
        scrollY: '100%',
        scrollCollapse: true,
        dom: "<'d-flex justify-content-between mb-2'B><'d-flex justify-content-between mb-2'lf>rtip",
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel me-1"></i> Excel',
                className: 'btn btn-success btn-sm'
            }
        ],
        paging: true,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        order: [], 
        searching: true,
        info: true,
        autoWidth: false,
        fixedHeader: false,
        responsive: false
    });

    $('#feedmillFilterForm').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const $filterBtn = form.find('button[type="submit"]');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            beforeSend: function () {
                $('#loader-div').fadeIn(100);
                $filterBtn.prop('disabled', true).text('Filtering...');
            },

            success: function (response) {

    if ($.fn.DataTable.isDataTable('#jobsTable')) {
        jobsTable.destroy();
    }
                const newTable = $(response).find('#jobsTable');
                $('#jobsTable').replaceWith(newTable);

                jobsTable = $('#jobsTable').DataTable({
                    scrollX: true,
                    scrollY: '100%',
                    scrollCollapse: true,
                    dom: "<'d-flex justify-content-between mb-2'B><'d-flex justify-content-between mb-2'lf>rtip",
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel me-1"></i> Excel',
                            className: 'btn btn-success btn-sm'
                        }
                    ],
                    paging: true,
                    pageLength: 25,
                    lengthMenu: [10, 25, 50, 100],
                    ordering: true,
                    order: [],
                    searching: true,
                    info: true,
                    autoWidth: false,
                    responsive: false
                });
            },
            complete: function () {
                $('#loader-div').fadeOut(100);
                $filterBtn.prop('disabled', false).text('Filter');
            },
            error: function () {
                alert('Error filtering feedmill. Please try again.');
                $filterBtn.prop('disabled', false).text('Filter');
            }
        });
    });

    $('.dynamic_dropdown_reports').select2({
        allowClear: true,
        dropdownParent: $('#feedmillFilterForm'),
        width: '100%'
    });

    $('#feedmillFilterForm').on('change', 'select, input', function () {
        const exportForm = $('#exportForm');

        exportForm.find('[name="feedmill"]').val($('#feedmillFilter').val());
        exportForm.find('[name="laboratory"]').val($('#laboratoryFilter').val());
        exportForm.find('[name="job_number"]').val($('#jobNumberFilter').val());
        exportForm.find('[name="delivery_date_from"]').val($('[name="delivery_date_from"]').val());
        exportForm.find('[name="delivery_date_to"]').val($('[name="delivery_date_to"]').val());
        exportForm.find('[name="date_received_from"]').val($('[name="date_received_from"]').val());
        exportForm.find('[name="date_received_to"]').val($('[name="date_received_to"]').val());
        exportForm.find('[name="week"]').val($('#weekFilter').val());
        exportForm.find('[name="month"]').val($('#monthFilter').val());
        exportForm.find('[name="supplier"]').val($('#supplierFilter').val());
    });

    $('#feedmillFilterForm').trigger('change');

});
