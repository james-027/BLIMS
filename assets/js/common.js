
$(document).ready(function () {
    var base_url = $('#base_url').val();
    var today    = new Date ();
    var date     = (today.getMonth ()+1)+'/'+today.getDate ()+'/'+today.getFullYear ()+' '+today.getHours () +':'+ today.getMinutes()+':'+today.getSeconds();

    function showError(message, delay=6000) {
        Lobibox.notify("error", { 
            size: "mini",
            position: "bottom right",
            rounded: true,
            msg: message,
            sound: true,
            soundPath: base_url+'/assets/js/lobibox-master/sounds/',
            icon: 'fas fa-exclamation-circle',
            delay: delay
        });
    }
    

    function showSuccess(message, delay=6000) {
        Lobibox.notify("success", { 
            size: "mini",
            position: "bottom right",
            
            rounded: true,
            msg: message,
            sound: true,
            soundPath: base_url+'/assets/js/lobibox-master/sounds/',
            icon: 'fas fa-check-circle',
            delay: delay
        });
    }

    function showWarning(message, delay=6000) {
        Lobibox.notify("warning", { 
            size: "mini",
            position: "bottom right",
            rounded: true,
            msg: message,
            sound: true,
            soundPath: base_url+'/assets/js/lobibox-master/sounds/',
            icon: 'fas fa-exclamation-circle',
            delay: delay
        });
    }

    function showAlertError(message = 'Error.'){
        Lobibox.alert("error", //AVAILABLE TYPES: "error", "info", "success", "warning"
        {
            icons: {
                bootstrap: {
                    
                    info: 'fas fa-exclamation-circle'
                }
            },
            //icon: 'fas fa-exclamation-circle',
            title:'Notice',
            msg: message,
            closeButton: false,
            draggable: true,
            buttons: {
                ok: {
                    'class': 'lobibox-btn lobibox-btn-cancel',
                    text: 'Got it',
                    closeOnClick: true
                }
            }
        });
    }

    function showAlertWarning(message = 'Error.'){
        Lobibox.alert("warning", //AVAILABLE TYPES: "error", "info", "success", "warning"
        {
            
            msg: message,
            closeButton: false,
            draggable: true,
            icon: 'fas fa-exclamation-circle',
            buttons: {
                ok: {
                    'class': 'lobibox-btn lobibox-btn-default',
                    text: 'Got it',
                    closeOnClick: true
                }
            }
        });
    }

    function showAlertInfo(message = 'Error.'){
        Lobibox.alert("info", //AVAILABLE TYPES: "error", "info", "success", "warning"
        {
            
            msg: message,
            closeButton: false,
            draggable: true,
            icon: 'fas fa-exclamation-circle',
            buttons: {
                ok: {
                    'class': 'lobibox-btn lobibox-btn-no',
                    text: 'Got it',
                    closeOnClick: true
                }
            }
        });
    }


    // Common JS functions
    $(document).on('submit', '#store-form', function(event){  
        event.preventDefault();
        var formID  = '#store-form';
        var dataUrl = $(formID).data('url');
        // var modalID = '#modal-store';
        var modalID = $(formID).parents('.modal');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url     : base_url + dataUrl,
            method  : 'POST',
            data    : $(formID).serialize(),
            dataType: "json",
            success : function(data)
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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


    $(document).on('submit', '#update-form', function(event){  
        event.preventDefault();
        var formID  = '#update-form';
        var dataUrl = $(formID).data('url');
        // var modalID = '#modal-edit';
        var modalID = $(formID).parents('.modal');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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
        var formID = '#activate-form';
        var modalID = '#modal-activate';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-form';
        var modalID = '#modal-deactivate';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#activate-form', function(event){  
        event.preventDefault();
        var formID  = '#activate-form';
        var dataUrl = $(formID).data('url');
        var modalID = '#modal-activate';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('submit', '#deactivate-form', function(event){  
        event.preventDefault();
        var formID  = '#deactivate-form';
        var dataUrl = $(formID).data('url');
        var modalID = '#modal-deactivate';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('click', '.edit', function(e){
        e.preventDefault();
        let id  = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        $.ajax({
            url: base_url + url,
            data: {id:id},
            method: 'POST',
            success:function(response){
                let parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#modal-edit').find('.modal-body').html(parse_response['html']);
                    $('#modal-edit').modal({show:true});

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
                }else{
                    console.log('Error please contact your administrator.');
                }
            }
        });
    });

    function reloadDatatableSSR(){
        let tables = $('.datatables-ssr');
        tables.each(function () { 
            $(this).DataTable().ajax.reload() ;
        });
    }

    let tables = $('.datatables-ssr');
    tables.each(function () {
        // $(this).DataTable().destroy();
        let table     = $(this);
        let dataUrl   = table.data('url');
        let dataTable = table.DataTable({
            "pagingType": "full",
            "language": {
                "emptyTable": "No data available",
                "lengthMenu": "Show _MENU_ entries",
                "info"      : "Displaying _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty" : "Displaying 0 to 0 of 0 entries",
                'search'    : '<i class="fa fa-search" aria-hidden="true"></i>',
                "paginate"  : {
                    "first":      '<i class="fas fa-fast-backward"></i>',
                    "last":       '<i class="fas fa-fast-forward"></i>',
                    "next":       '<i class="fas fa-step-forward"></i>',
                    "previous":   '<i class="fas fa-step-backward"></i>'
                },
            },
            "columnDefs": [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: -1 },
                { responsivePriority: 3, targets: 1 },
                { responsivePriority: 4, targets: -2 }
            ],
            "order": [],
            select : true,
            "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
            "ajax": {
                url : base_url + dataUrl,
                type : 'GET'
            },
            buttons: [
                {
                    extend: 'excel',
                    messageTop: 'Run Date : ' + date,
                    customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        //$('row:first c', sheet).attr( 's', '42' );
                        //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                    },
                    autoFilter: true
                }
            ]
        });
    });

    $(document).on('click', '.print-dt', function(e){
        let tables = $('.datatables-ssr');
        tables.each(function () { 
            $(this).DataTable().button( '.buttons-excel' ).trigger();
        });
    });

    $(document).on('click', '.export-table', function(e){
        let link = $(this).data('url');
        window.open(link);
    });

    $(document).on('click', '.refresh-dt', function(e){
        reloadDatatableSSR();
    });

    $('.store-select').change(event => {
        addMatGroup(event.target);
    });

    $('.store-hurdle-yearpicker').change(event => {
        addMatGroup('.store-select');
    });

    function addMatGroup(element) {
        const store           = $(element);
        const year            = $(element).parents('form').find('#store-hurdle-yearpicker');
        const url             = base_url + 'store_hurdle/get_store_avail_mat_group/' + store.val() + '/' + year.val();
        let   matGroupElement = $(element).parents('form').find('.mat-group-select');
        $(matGroupElement).load(url);
    }

    $('#store-hurdle-yearpicker-filter').change(event => {
        let year = $('#store-hurdle-yearpicker-filter').val();
        window.location.href = base_url + 'store_hurdle/?year=' + year;
    });

    // dynamic-generic.js copied functions

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
            url        : base_url + url,
            method     : 'POST',
            data       : new FormData(this),
            contentType: false,
            cache      : false,
            processData: false,
            dataType   : "json",
            success    : function(data)
            {
                $(formID)[0].reset();  
                $(modalID).modal('hide');
                $(modalID).on('hidden.bs.modal', function () {
                    $(this).removeData('bs.modal');
                });

                reloadDatatableSSR();

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

    $('.store-additional-hurdle-select').change(event => {
        addMatGroup2(event.target);
    });

    $('.additional-store-hurdle-datepicker').change(event => {
        addMatGroup2('.store-additional-hurdle-select');
    });

    function addMatGroup2(element) {
        const store           = $(element);
        const year            = $(element).parents('form').find('#additional-store-hurdle-datepicker');
        const url             = base_url + 'store_hurdle/get_store_avail_mat_group/' + store.val() + '/' + year.val();
        let   matGroupElement = $(element).parents('form').find('.mat-group-additional-hurdle-select');
        $(matGroupElement).load(url);
    }

    $(document).on('click', '.store-additional-hurdle', function(e){
        e.preventDefault();
        let id  = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        $.ajax({
            url: base_url + url,
            data: {id:id},
            method: 'POST',
            success:function(response){
                let parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#modal-store-additional-hurdle').find('.modal-body').html(parse_response['html']);
                    $('#modal-store-additional-hurdle').modal({show:true});

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
                }else{
                    console.log('Error please contact your administrator.');
                }
            }
        });
    });

    $(document).on('submit', '#store-additional-hurdle-form', function(event){  
        event.preventDefault();
        var formID  = '#store-additional-hurdle-form';
        var dataUrl = $(formID).data('url');
        var modalID = '#modal-store-additional-hurdle';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('click', '.upload-additional-hurdle-btn', function(e){
        var modalID = '#modal-upload-additional-hurdle-form';
        var formID = '#upload-additional-hurdle-form';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
    });

    $(document).on('submit', '#upload-additional-hurdle-form', function(event){  
        event.preventDefault();
        var modalID = '#modal-upload-additional-hurdle-form';
        var formID = '#upload-additional-hurdle-form';
        var url = $(formID).data('url');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url        : base_url + url,
            method     : 'POST',
            data       : new FormData(this),
            contentType: false,
            cache      : false,
            processData: false,
            dataType   : "json",
            success    : function(data)
            {
                $(formID)[0].reset();  
                $(modalID).modal('hide');
                $(modalID).on('hidden.bs.modal', function () {
                    $(this).removeData('bs.modal');
                });

                reloadDatatableSSR();

                $('#modal-import-additional-hurdle-result').modal({show:true});
                $('#modal-import-additional-hurdle-result .modal-title').text('Upload Result');
                $(".tbl-import-result-import-additional-hurdle > tbody").empty();
                $(".tbl-import-result-import-additional-hurdle > tbody").append(data.import_table);
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

    $(document).on('submit', '#store-version-form', function(event){  
        event.preventDefault();
        var formID  = '#store-version-form';
        var dataUrl = $(formID).data('url');
        // var modalID = '#modal-store';
        var modalID = $(formID).parents('.modal');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data       : new FormData(this),
            contentType: false,
            cache      : false,
            processData: false,
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('submit', '#update-version-form', function(event){  
        event.preventDefault();
        var formID  = '#update-version-form';
        var dataUrl = $(formID).data('url');
        // var modalID = '#modal-edit';
        var modalID = $(formID).parents('.modal');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data       : new FormData(this),
            contentType: false,
            cache      : false,
            processData: false,
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('click', '.edit-command', function(e){
        e.preventDefault();
        let id  = $(this).attr('data-id');
        let url = $(this).attr('data-url');
        $.ajax({
            url: base_url + url,
            data: {id:id},
            method: 'POST',
            success:function(response){
                let parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#modal-edit-command').find('.modal-body').html(parse_response['html']);
                    $('#modal-edit-command').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
            }
        });
    });

    $(document).on('click', '.toggle-inactive-version-command', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#activate-form-version-command';
        var modalID = '#modal-activate-version-command';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active-version-command', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-form-version-command';
        var modalID = '#modal-deactivate-version-command';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#activate-form-version-command', function(event){  
        event.preventDefault();
        var formID  = '#activate-form-version-command';
        var dataUrl = $(formID).data('url');
        var modalID = '#modal-activate-version-command';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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

    $(document).on('submit', '#deactivate-form-version-command', function(event){  
        event.preventDefault();
        var formID  = '#deactivate-form-version-command';
        var dataUrl = $(formID).data('url');
        var modalID = '#modal-deactivate-version-command';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + dataUrl,
            method:'POST',
            data: $(formID).serialize(), 
            dataType:"json",
            success:function(data)  
            {
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
                    $(formID)[0].reset();  
                    $(modalID).modal('hide');
                    $(modalID).on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    reloadDatatableSSR();
                    showSuccess(data.successMsg);
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



    // Common JS functions ends here
});