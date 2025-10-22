

    //DYNAMIC SCRIPT
    $(document).on('click', '.add-form', function(e){
        
        var id = 0;
        var formID = '#add-form';
        var modalID = '#modal-add-form';
        let url = $(this).attr('data-url');
        

        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['success'] == 1){
                    
                    $(modalID).find('.modal-body').empty();
                    $(modalID).find('.modal-body').html(parse_response['html']);
                    $(modalID).modal({show:true});
                    
                    // $(formID)[0].reset();
                    $('select.dynamic_dropdown_no_order').select2({
                        width: '100%',
                        placeholder: 'Select...',
                        theme: 'bootstrap4'
                    });

                    $('.datepicker').datepicker({
                        clearBtn: true,
                        format: 'mm/dd/yyyy',
                        autoclose: true,
                        viewMode: "days",
                        minViewMode: "days",
                        startDate: '01/01/2010',
                        immediateUpdates: true,
                        todayHighlight: true,
                        daysOfWeekHighlighted: ['00']
                    });
                    
                    $(formID).find('select').val('').trigger('change');
                }else{
                    showAlertError(parse_response['msg']);
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });


    let tblUrl = $('#tblUrl').val();
    var dtGrid = $('.my-datatables').DataTable({
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
        "responsive": true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: -2 },
            { responsivePriority: 5, targets: 2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url + tblUrl,
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
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        dtGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        dtGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });
    
    $(document).on('submit', '#add-form', function(event){  
        event.preventDefault();
        var formID = '#add-form';
        var url = $(formID).data('url');
        var modalID = '#modal-add-form';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.msg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    dtGrid.ajax.reload(null, false);
                    showSuccess(data.msg);
                }
                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('click', '.edit-form', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var modalID = '#modal-edit-form';
        var formID = '#update-form';
        let url = $(this).attr('data-url');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['success'] == 1){
                    
                    $(modalID).find('.modal-body').empty();
                    $(modalID).find('.modal-body').html(parse_response['html']);
                    

                    $('select.dynamic_dropdown_no_order').select2({
                        width: '100%',
                        placeholder: 'Select...',
                        theme: 'bootstrap4'
                    });
                    $(formID).find('.select2').trigger('click');

                    $(modalID).modal({show:true});
                }else{
                    showAlertError(parse_response['msg']);
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('submit', '#update-form', function(event){  
        event.preventDefault();
        var formID = '#update-form';
        var url = $(formID).data('url');
        var modalID = '#modal-edit-form';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            
            url: base_url + url,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)
            {
                if(!data.success){
                    showAlertError(data.msg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    dtGrid.ajax.reload(null, false);
                    showSuccess(data.msg);
                }
                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('click', '.toggle-inactive', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        var formID = '#activate-form';
        var modalID = '#modal-activate-form';
        
        $(formID).find('#id').val(id);
        $(formID).find('#val').html(val);
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        var formID = '#deactivate-form';
        var modalID = '#modal-deactivate-form';
        
        $(formID).find('#id').val(id);
        $(formID).find('#val').html(val);
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#deactivate-form', function(event){  
        event.preventDefault();
        var formID = '#deactivate-form';
        var url = $(formID).data('url');
        var modalID = '#modal-deactivate-form';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            method:'POST',
            data: $(formID).serialize(),
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.msg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    dtGrid.ajax.reload(null, false);
                    showSuccess(data.msg);
                }
                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#activate-form', function(event){  
        event.preventDefault();
        var formID = '#activate-form';
        var url = $(formID).data('url');
        var modalID = '#modal-activate-form';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            method:'POST',
            data: $(formID).serialize(),
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.msg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    dtGrid.ajax.reload(null, false);
                    showSuccess(data.msg);
                }
                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.upload-btn', function(e){
        var modalID = '#modal-upload-form';
        var formID = '#upload-form';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
    });

    $(document).on('submit', '#upload-form', function(event){  
        event.preventDefault();
        var modalID = '#modal-upload-form';
        var formID = '#upload-form';
        var url = $(formID).data('url');

        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + url,
            method:'POST',
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            dataType:"json",
            success:function(data)  
            {
                $(formID)[0].reset();  
                $(modalID).modal('hide');
                $(modalID).on('hidden.bs.modal', function () {
                    $(this).removeData('bs.modal');
                });
                dtGrid.ajax.reload(null, false);

                $('#modal-import-result').modal({show:true});
                $('#modal-import-result .modal-title').text('Upload Result');
                $(".tbl-import-result > tbody").empty();
                $(".tbl-import-result > tbody").append(data.import_table);
                if(!data.success){
                    showAlertError(data.msg);
                } else {    
                    showSuccess(data.msg);
                }
                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                $('#loader-div').addClass('loaded');
            }
        });
    });
    //END OF DYNAMIC SCRIPT



    


    /* FORM VALIDATION */
    initValidationDefaults();
    
    var form = $('#add-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    // END FORM VALIDATION



