let jobsTable;

$(document).ready(function () {


    
$.fn.serializeObject = function () {
    let obj = {};
    let arr = this.serializeArray();
    $.each(arr, function () {
        if (obj[this.name]) {
            if (!Array.isArray(obj[this.name])) {
                obj[this.name] = [obj[this.name]];
            }
            obj[this.name].push(this.value);
        } else {
            obj[this.name] = this.value;
        }
    });
    return obj;
};



$.post(baseUrl + controllerName + '/datatable', $('#feedmillFilterForm').serialize(), function(json) {

    let columns = [
        { data: 'sample_name' },
        { data: 'sample_name' },
        { data: 'feedmill' },
        { data: 'lab_code' },
        { data: 'supplier_name' },
        { data: 'plate_number' },
        { data: 'delivery_date' },
        { data: 'latest_timestamp' },
        { data: 'week_number' },
        { data: 'month_name' },
        { data: 'class' },
        { data: 'job_order_no' },
        { data: 'estimated_release_date' },
        { data: 'latest_timestamp' },
        { data: 'test_name' }
    ];

    json.dynamic_test_headers.forEach(tc => {
        columns.push({ data: tc });
    });


    

    // 2️⃣ Initialize DataTable now that columns are ready
    window.jobsTable = $('#jobsTable').DataTable({
        processing: true,
        serverSide: true,
        scrollX: true,
        scrollY: '100%',
        scrollCollapse: true,
        dom: "<'d-flex justify-content-between mb-2'B><'d-flex justify-content-between mb-2'lf>rtip",
        buttons: [
            { extend: 'excelHtml5', text: '<i class="fas fa-file-excel me-1"></i> Excel', className: 'btn btn-success btn-sm' },
            { extend: 'pdfHtml5', text: '<i class="fas fa-file-pdf me-1"></i> PDF', className: 'btn btn-danger btn-sm' }
        ],
        pageLength: 25,
        lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "All"]
    ],
        order: [],
        autoWidth: false,
        responsive: false,
        ajax: {
            url: baseUrl + controllerName + '/datatable',
            type: "POST",
            data: function(d) {
                return $.extend({}, d, $('#feedmillFilterForm').serializeObject());
            },
            dataSrc: 'data' // just return the data array
        },
        columns: columns
    });
}, 'json');






$('#feedmillFilterForm').on('submit', function (e) {
    e.preventDefault();
    jobsTable.ajax.reload();
});

 
    $('.dynamic_dropdown_reports').select2({
        allowClear: true,
        dropdownParent: $('#feedmillFilterForm'),
        width: '100%'
    });

   
    $('#feedmillFilterForm').on('change', 'select, input', function () {
        const exportForm = $('#exportForm');

        exportForm.find('[name="feedmill"]').val($('#feedmillFilter').val());
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
