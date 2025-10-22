    var docHeight = parseInt($(window).height());
    var initTableHeight = docHeight -333; //420


    $('.datepicker-dashboard').datepicker({
        clearBtn: false,
        format: 'mm/dd/yyyy',
        autoclose: true,
        viewMode: "days",
        minViewMode: "days",
        startDate: '01/01/2023',
        immediateUpdates: true,
        todayHighlight: true,
        daysOfWeekHighlighted: ['00']
    }).on('changeDate',function(e){
        
        let inc_date = $('#incentive_date').val();
        let store_id = $('#store_id').val();
        window.location.href = base_url + 'admin/dashboard/0?inc_date=' + inc_date+'&store-id='+store_id;
    });

    $(document).on('change', '#store_id', function(e){
        let inc_date = $('#incentive_date').val();
        let store_id = $(this).val();
        window.location.href = base_url + 'admin/dashboard/0?inc_date=' + inc_date+'&store-id='+store_id;
    });

    function reloadDatatable(){
        let tables = $('.my-datatables-2');
        tables.each(function () { 
            $(this).DataTable().ajax.reload() ;
        });
    }

    let tables = $('.my-datatables-2');
    tables.each(function () {
        // $(this).DataTable().destroy();
        let table     = $(this);
        let dataUrl   = table.data('url');
        let dataTable = table.DataTable({
            "pagingType": "full",
            "language": {
                "emptyTable":     "No data available",
                "lengthMenu":     "Show _MENU_ entries",
                "info":           "Displaying _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty":      "Displaying 0 to 0 of 0 entries",
                'search': '<i class="fa fa-search" aria-hidden="true"></i>',
                "paginate": {
                    "first":      '<i class="fas fa-fast-backward"></i>',
                    "last":       '<i class="fas fa-fast-forward"></i>',
                    "next":       '<i class="fas fa-step-forward"></i>',
                    "previous":   '<i class="fas fa-step-backward"></i>'
                },
            },
            // "responsive": true,
            "processing": true,
            "columnDefs": [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -3 },
                { responsivePriority: 3, targets: 1 },
                { responsivePriority: 4, targets: -4 },
                { responsivePriority: 5, targets: -5 },
                { targets: [5, 6, 7], className: 'text-right' }
            ],
            "order": [],
            select : true,
            "scrollCollapse": true,
            scrollY:        initTableHeight+"px",
            scrollX:        true,
            // fixedColumns: {
            //     leftColumns: 2
            // },
            bPaginate : false,
            "lengthMenu": [[-1], ["All"]],
            "ajax": {
                url : base_url + dataUrl,
                type : 'GET'
            },
            buttons: [
                {
                    extend: 'excel',
                    messageTop: 'Run Date : '+date,
                    customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        
                    },
                    autoFilter: true
                }
            ],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
    
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // Total over all pages

                pageTotalSalesQty = api
                    .column( 5, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                $( api.column( 5 ).footer() ).html(
                    number_format(pageTotalSalesQty, 2, '.', ',')
                );
                
                
                
                pageTotalIncAmt = api
                    .column( 7, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                $( api.column( 7 ).footer() ).html(
                    number_format(pageTotalIncAmt, 2, '.', ',')
                );

            }
        });
    });


    $(document).on('click', '.print-dt', function(e){
        let tables = $('.datatables-ssr');
        tables.each(function () { 
            $(this).DataTable().button( '.buttons-excel' ).trigger();
        });
    });

    

    $(document).on('click', '.refresh-dt', function(e){
        reloadDatatable();
    });


    $('a[data-toggle="pill"]').on('shown.bs.tab', function(e){
        
        reloadDatatable();
    });