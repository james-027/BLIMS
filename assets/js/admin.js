document.onkeydown = function(e) {
    if (e.ctrlKey && 
        (e.keyCode === 85 )) {
        return true;
    }
};
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

var base_url = $('#base_url').val();

var today = new Date ();
var date = (today.getMonth ()+1)+'/'+today.getDate ()+'/'+today.getFullYear ()+' '+today.getHours () +':'+ today.getMinutes()+':'+today.getSeconds();
$(document).ready(function () {
    
    
    $("html"). on("contextmenu",function(e){ return true; });

    
    
    $(document).on('click', '.refer-link', function(e){
        
        //get the section name from hash
        var sectionName = window.location.hash.slice(1);

        //then show the section
        if(sectionName){
            $('#' + sectionName ).show();
        }
        
    });
    

    $('#running-inventory-section').hide();
    $('#total-qty-section').hide();

    //DROPDOWN INIT
    $('select.basic_dropdown').select2({
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });
    
    $('select.basic-dropdown').select2({
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });

    // $('select.dynamic_dropdown').select2({
    //     width: '100%',
    //     placeholder: 'Select...',
    //     //dropdownPosition: 'below',
    //     theme: 'bootstrap4',
    //     sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),
        
    // });

    $('select.dynamic_dropdown').each(function() {
        var $select = $(this);
        var $tableWrapper = $select.closest('.table-responsive');

        if ($select.find('option[value="_reset"]').length === 0) {
            $select.prepend('<option value="_reset">Select</option>');
        }

        $select.select2({
            placeholder: 'Select',
            theme: 'bootstrap4',
            sorter: data => data.sort((a, b) => {
                if (a.id === '_reset') return -1;
                if (b.id === '_reset') return 1;
                return a.text.localeCompare(b.text);
            }),
            width: '100%',
            dropdownParent: $tableWrapper.length ? $tableWrapper : $select.parent()
        });

        $select.on('change', function() {
            if ($(this).val() === '_reset') {
                $(this).val('').trigger('change'); 
            }
        });
    });

    
    


    $('select.dynamic_dropdown_no_order').select2({
        width: '100%',
        placeholder: 'Select...',
        //dropdownPosition: 'below',
        theme: 'bootstrap4'
    });

    $(".dropdown_2").select2({
        dropdownParent: $("#update-customer"),
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });
    $(".dropdown_3").select2({
        dropdownParent: $("#add-customer"),
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });
    $(".dropdown_4").select2({
        dropdownParent: $("#add-material"),
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        //sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });
    $("select.update-material-dropdown").select2({
        dropdownParent: $("#update-material"),
        width: '100%',
        placeholder: 'Select...',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });
    $(".dropdown_5").select2({
        dropdownParent: $("#add-material-master"),
        width: '100%',
        placeholder: 'Select...',
        //dropdownPosition: 'above',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });

    $('select.dropdown_6').select2({
        width: '100%',
        dropdownParent: $("#add-material-master"),
        placeholder: 'Select...',
        //dropdownPosition: 'below',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });

    $("select.update-mm-dropdown-above").select2({
        dropdownParent: $("#update-material-master"),
        width: '100%',
        placeholder: 'Select...',
        //dropdownPosition: 'above',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });

    $('select.update-mm-dropdown-below').select2({
        width: '100%',
        dropdownParent: $("#update-material-master"),
        placeholder: 'Select...',
        //dropdownPosition: 'below',
        theme: 'bootstrap4',
        sorter: data => data.sort((a, b) => a.text.localeCompare(b.text))
    });

    $(document).on('keydown', 'input', function(e){
        var key = e.keyCode;

        // If the user has pressed enter
        if (key == 13) {
            //alert($('.text-area').val());
            //document.getElementByClass("text-area").value =document.getElementByClass("text-area").value + "\n";
            //$('.text-area').val() = $('.text-area').val() + "\n";
            //alert($('.text-area').val());
            //alert('Hello');
            return false;
        }
        else {
            return true;
        }
    });

    //DATAGRID INIT

    


	
	$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){        
        $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
    });
	
    //USER SCRIPT
	$(document).on('click', '.add-user-btn', function(e){
        
        var formID = '#add-user-form';
        var modalID = '#modal-add-user';
        $(formID)[0].reset();  
        $(modalID).modal({show:true});
        $(formID).find('select').val('').trigger('change');
        $(formID).find('#uType-id').trigger('change');
        
    });

	$(document).on('change.select2', '.key', function(e){
        e.preventDefault();

        var id = $(this).val();
		
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/get-sLoc/',
            data:{id:id},
            method: 'POST',
            success:function(response){
                console.log(response);
             
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    $('.sLoc').empty();
                    $('.sLoc').append(parse_response['info']);
                }else{
                    $('.sLoc').empty();
                    //console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
			
            }
        });
    });
	
    $(document).on('change', '#uType-id', function(e){
        e.preventDefault();
        
        var id = $(this).val();
        moduleAccessGrid.ajax.url(base_url + 'admin/loadModuleAccessTemplateGrid/'+id).load();
        moduleAccessGrid.columns.adjust();
        moduleAccessGrid.ajax.reload();
        
        //alert('hello');
        $.ajax({
            url: base_url + 'admin/get-bcpresetdtl',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    
                    $('#add-user-form').find('.user-bc-id').empty();
                    $('#add-user-form').find('.user-bc-id').append(parse_response['info'].bcID);
                    
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });

        
    });

    $(document).on('change', '#uType2-id', function(e){
        e.preventDefault();
        
        var id = $(this).val();
        
        userModuleAccessGrid.ajax.url(base_url + 'admin/loadModuleAccessTemplateGrid/'+id).load();
        userModuleAccessGrid.columns.adjust();
        userModuleAccessGrid.ajax.reload();
        
        $.ajax({
            url: base_url + 'admin/get-bcpresetdtl',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    
                    $('#update-user').find('.user-bc-id').empty();
                    $('#update-user').find('.user-bc-id').append(parse_response['info'].bcID);
                    
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    
    
    var moduleAccessGrid = $('#tbl-module-access').DataTable({
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
        scrollY:        "500px",
        scrollX:        "300px",
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   false,
		ordering: [],
        paging:false,
        searching:true,
        bInfo : true,
        select : true
		
    });
    
    var userModuleAccessGrid = $('.tbl-user-module-access').DataTable({
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
        scrollY:        "500px",
        scrollX:        "300px",
        scrollCollapse: true,
        paging:         false,
        fixedColumns:   false,
		ordering: [],
        paging: false,
        searching:true,//false by default
        bInfo : true,
        select : true,
        "ajax": {
            url : base_url+'admin/loadUserModuleAccessGrid',
            type : 'GET'
        }

    });

    $(document).on('click', '.change-access', function(e){
        e.preventDefault();
        $('#chAccessModal').modal({show:true});
        $('#change-access-form').find('.select2').trigger('click');
        
    });
    
    var userStatusID = $('#userStatusID').val();
    var userType = $('#userType').val();
    var userGrid = $('#tbl-user').DataTable({
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
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: -2 },
            { responsivePriority: 5, targets: -3 },
            { responsivePriority: 6, targets: -4 },
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/userGrid/'+userStatusID+'/'+userType,
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        userGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        userGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });
    
    $(document).on('click', '.edit-user', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        var update_url = base_url + 'admin/update-user/';
        $.ajax({
            url: base_url + 'admin/modal-user/' + id,
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    // console.log(parse_response['info'].userTypeName);
                    $('#update-user').find('#id').val(id);
                    $('#update-user').find('#user-title').val(parse_response['info'].title);
                    $('#update-user').find('#user-fname').val(parse_response['info'].fname);
                    $('#update-user').find('#user-lname').val(parse_response['info'].lname);
                    $('#update-user').find('#user-email').val(parse_response['info'].email);
                    $('#update-user').find('#user-employee-no').val(parse_response['info'].employeeNo);
                    
                    $('#update-user').find('#key-id').empty();
                    $('#update-user').find('#key-id').append(parse_response['info'].keyID); 
                     

                    $('#update-user').find('#key-id').empty();
                    $('#update-user').find('#key-id').append(parse_response['info'].keyID); 
                     
                    $('#update-user').find('#lab-id').empty();
                    $('#update-user').find('#lab-id').append(parse_response['info'].labID); 
                     

                    
                    $('#update-user').find('#sLoc-id').empty();
                    $('#update-user').find('#sLoc-id').append(parse_response['info'].slocID); 
                    
                    $('#update-user').find('#upline-id').empty();
                    $('#update-user').find('#upline-id').append(parse_response['info'].uplineID); 
                    
                    $('#update-user').find('#uType2-id').empty();
                    $('#update-user').find('#uType2-id').append(parse_response['info'].uTypeID);
                    
                    $('#update-user').find('.user-bc-id').empty();
                    $('#update-user').find('.user-bc-id').append(parse_response['info'].bcID);
                    
                    $('#update-user').find('.user-password').removeAttr('required');
                    $('#update-user').find('.user-password').removeAttr('minlength');
                    $('#update-user').find('.user-password-group').hide();

                    console.log(parse_response['info'].userTypeName);
                    if(parse_response['info'].agencyID == ''){
                        $('#update-user').find('#mobile-number').removeAttr('required');
                        $('#update-user').find('.user-gcash-group').hide();

                        $('#update-user').find('#agency-id').removeAttr('required');
                        $('#update-user').find('.user-agency-group').hide();
                        
                    } else {
                        
                        $('#update-user').find('#agency-id').empty();
                        $('#update-user').find('#agency-id').append(parse_response['info'].agencyID);
                        $('#update-user').find('#mobile-number').val(parse_response['info'].mobileNumber);
                    }
                    
                    //preload datagrid
                    $('#modal-edit-user').modal({show:true});
                    $('#modal-edit-user .modal-title').text("Update user "+parse_response['info'].userID+" - "+parse_response['info'].fname);
                    userModuleAccessGrid.ajax.url(base_url + 'admin/loadUserModuleAccessGrid/'+id).load();
					userModuleAccessGrid.columns.adjust();

                    $('#update-user').attr('action', update_url);
                    $('#update-user').find('#user-update-btn').html('Update');
					$('#update-user').find('.select2').trigger('click');
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.duplicate-user', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        var duplicate_url = base_url + 'admin/add-user/';
        $.ajax({
            url: base_url + 'admin/modal-user/' + id,
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    console.log(parse_response['info'].keyID);
                    $('#update-user').find('#id').val(id);
                    $('#update-user').find('#user-fname').val('');
                    $('#update-user').find('#user-lname').val('');
                    $('#update-user').find('#user-email').val('');
                    $('#update-user').find('#user-employee-no').val('');
                    
                    $('#update-user').find('#key-id').empty();
                    $('#update-user').find('#key-id').append(parse_response['info'].keyID); 
                     
                    
                    $('#update-user').find('#sLoc-id').empty();
                    $('#update-user').find('#sLoc-id').append(parse_response['info'].slocID); 
                    
                    $('#update-user').find('#upline-id').empty();
                    $('#update-user').find('#upline-id').append(parse_response['info'].uplineID); 
                    
                    $('#update-user').find('#uType2-id').empty();
                    $('#update-user').find('#uType2-id').append(parse_response['info'].uTypeID);

                    $('#update-user').find('.user-bc-id').empty();
                    $('#update-user').find('.user-bc-id').append(parse_response['info'].bcID);

                    $('#update-user').find('.user-password').attr('required', true);
                    $('#update-user').find('.user-password').attr('minlength', '7');
                    $('#update-user').find('.user-password-group').show();
                    
                    //preload datagrid
                    $('#modal-edit-user').modal({show:true});
                    $('#modal-edit-user .modal-title').text("Duplicate user "+parse_response['info'].userID+" - "+parse_response['info'].fname);
                    userModuleAccessGrid.ajax.url(base_url + 'admin/loadUserModuleAccessGrid/'+id).load();
                    userModuleAccessGrid.columns.adjust();

                    $('#update-user').attr('action', duplicate_url);
                    $('#update-user').find('#user-update-btn').html('Duplicate');
                    
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('click', '.email-switch-off', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: base_url + 'admin/user-email-switch-off/',
            data: {id:id},
            method: 'POST',
            dataType:"json",
            success:function(data){
                if(data.success==true){
                    showSuccess(data.successMsg);
                    userGrid.ajax.reload(null, false);
                }else{
                    showAlertError(data.successMsg);
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.email-switch-on', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: base_url + 'admin/user-email-switch-on/',
            data: {id:id},
            method: 'POST',
            dataType:"json",
            success:function(data){
                if(data.success==true){
                    showSuccess(data.successMsg);
                    userGrid.ajax.reload(null, false);
                }else{
                    showAlertError(data.successMsg);
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.toggle-inactive', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        $('#activate-user').find('#id').val(id);
        $('#activate-user').find('#val').html(val);
        $('#modal-active-user').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        $('#deactivate-user').find('#id').val(id);
        $('#deactivate-user').find('#val').html(val);
        $('#modal-deactivate-user').modal({show:true});
    });

    $(document).on('click', '.reset-user', function(e){
        e.preventDefault();

        var user_id = $(this).attr('data-id');
        $('#update-password').find('#id').val(user_id);
        $('#modal-reset-user').modal({show:true});
    });

    $(document).on('submit', '#add-user-form', function(event){
        $('#loader-div').removeClass('loaded');
    });

    $(document).on('submit', '#update-password', function(event){
        $('#loader-div').removeClass('loaded');
    });

    $(document).on('click', '.upload-user-btn', function(e){
        var modalID = '#modal-upload-user';
        var formID = '#upload-user';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
    });

    $(document).on('submit', '#upload-user', function(event){  
        event.preventDefault();
        var modalID = '#modal-upload-user';
        var formID = '#upload-user';
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
                userGrid.ajax.reload(null, false);

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
    
    //END USER SCRIPT



    //USER KEY SCRIPT
    var userKey_userID = $('#user-key-user-id').val();
    var userKeyGrid = $('#tbl-user-key').DataTable({
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
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: -2 },
            { responsivePriority: 5, targets: -3 },
            { responsivePriority: 6, targets: -4 },
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/userKeyGrid/'+userKey_userID,
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        userKeyGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        userKeyGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('click', '.edit-user-key', function(e){
        e.preventDefault();
        var userID = $(this).attr('data-user-id');
        var keyID = $(this).attr('data-key-id');
        var userTypeID = $(this).attr('data-user-type-id');
        var hasFilter = $(this).attr('data-has-filter');
        $('#loader-div').removeClass('loaded');
        var update_url = base_url + 'admin/update-user-key/';
        $.ajax({
            url: base_url + 'admin/modal-user-key',
            data: {userID:userID, keyID:keyID, userTypeID:userTypeID},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    console.log(parse_response['info'].keyID);
                    $('#update-user-key').find('#user-id').val(userID);
                    $('#update-user-key').find('#has-filter').val(hasFilter);
                    //$('#update-user-key').find('#user-type-id').val(userTypeID);

                    $('#update-user-key').find('#key-id').empty();
                    $('#update-user-key').find('#key-id').append(parse_response['info'].keyID);
                    
                    $('#update-user-key').find('#uType2-id').empty();
                    $('#update-user-key').find('#uType2-id').append(parse_response['info'].uTypeID);
                    
                    
                    //preload datagrid
                    $('#modal-edit-user-key').modal({show:true});
                    $('#modal-edit-user-key .modal-title').text("Update user key of "+parse_response['info'].fname+" - "+parse_response['info'].lname+' for '+parse_response['info'].key);
                    userModuleAccessGrid.ajax.url(base_url + 'admin/loadUserModuleAccessGrid/'+userID+'/'+keyID+'/'+userTypeID).load();
					userModuleAccessGrid.columns.adjust();

                    $('#update-user-key').attr('action', update_url);
					$('#update-user-key').find('.select2').trigger('click');
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });


    //END OF USER KEY SCRIPT



    //USER ROLE SCRIPT

    $(document).on('change', '.user-role-copy-for-preset', function(e){
        e.preventDefault();
        
        var id = $(this).val();
        
        userModuleAccessGrid.ajax.url(base_url + 'admin/loadModuleAccessTemplateGrid/'+id).load();
        userModuleAccessGrid.columns.adjust();
        userModuleAccessGrid.ajax.reload();
        
        moduleAccessGrid.ajax.url(base_url + 'admin/loadModuleAccessTemplateGrid/'+id).load();
        moduleAccessGrid.columns.adjust();
        moduleAccessGrid.ajax.reload();

        //alert('hello');
    });

    $(document).on('click', '.add-user-role-btn', function(e){
        
        var formID = '#add-user-role-form';
        var modalID = '#modal-add-user-role';
        $(formID)[0].reset();  
        $(modalID).modal({show:true});
        $(formID).find('select').val('').trigger('change');
        moduleAccessGrid.ajax.url(base_url + 'admin/loadModulesGrid').load();
        moduleAccessGrid.columns.adjust();
    });

    $(document).on('change.select2', '#user-role-fetch-user', function(e){
        e.preventDefault();

        var id = $(this).val();
		
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/get-users/',
            data:{id:id},
            method: 'POST',
            success:function(response){
                console.log(response);
             
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    $('.users-list').empty();
                    $('.users-list').append(parse_response['info']);
                }else{
                    $('.users-list').empty();
                    //console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
			
            }
        });
    });

    $(document).on('change', '#user-role', function(e){
        e.preventDefault();
        
        var id = $(this).val();
        
        
        moduleAccessGrid.ajax.url(base_url + 'admin/loadModulesGrid/'+id).load();
        moduleAccessGrid.columns.adjust();
    });
    

    $(document).on('click', '.module-tbl-label', function(){
    
        var id = $(this).attr('data-id');
        
        if($(this).attr('readonly') == 'readonly'){
            return false;
        }
        
        if($(this).is(":checked"))
        {
            $(".check-"+id).prop('checked', true);
        } else {
            $(".check-"+id).prop('checked', false);
        }
    });

    var userRoleGrid = $('#tbl-user-roles').DataTable({
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
            url : base_url+'admin/userRoleGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        userRoleGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        userRoleGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-user-role-form', function(event){  
        event.preventDefault();
        var formID = '#add-user-role-form';
        var modalID = '#modal-add-user-role';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-user-role/',
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
                    userRoleGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-user-role', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        var update_url = base_url + 'admin/update-user-role/';
        $.ajax({
            url: base_url + 'admin/modal-user-role/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    console.log(parse_response['info'].keyID);
                    $('#update-user-role').find('#id').val(id);
                    
                    $('#update-user-role').find('#user-role-fetch-user').empty();
                    $('#update-user-role').find('#user-role-fetch-user').append(parse_response['info'].uTypeID);

                    $('#update-user-role').find('.user-role-copy-for-preset').empty();
                    $('#update-user-role').find('.user-role-copy-for-preset').append(parse_response['info'].uTypeID);

                    $('#update-user-role').find('#bc-id-preset').empty();
                    $('#update-user-role').find('#bc-id-preset').append(parse_response['info'].bcID);
                    
                    //preload datagrid
                    $('#modal-edit-user-role').modal({show:true});
                    $('#modal-edit-user-role .modal-title').text("Update user role "+parse_response['info'].presetHdrID);
                    userModuleAccessGrid.ajax.url(base_url + 'admin/loadUserModulePresetGrid/'+id).load();
					userModuleAccessGrid.columns.adjust();
					userModuleAccessGrid.ajax.reload();

                    $('#update-user-role').find('#user-role-fetch-user').trigger('change');

                    $('#update-user-role').attr('action', update_url);
                    $('#update-user-role').find('#user-role-update-btn').html('Update');
                    $('#update-user-role').find('.select2').trigger('click');
					
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-user-role', function(event){  
        event.preventDefault();
        var formID = '#update-user-role';
        var modalID = '#modal-edit-user-role';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-user-role/',
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
                    userRoleGrid.ajax.reload(null, false);
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
        var formID = '#activate-user-role';
        var modalID = '#modal-activate-user-role';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-user-role';
        var modalID = '#modal-deactivate-user-role';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#deactivate-user-role', function(event){  
        event.preventDefault();
        var formID = '#deactivate-user-role';
        var modalID = '#modal-deactivate-user-role';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-user-role/',
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
                    userRoleGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-user-role', function(event){  
        event.preventDefault();
        var formID = '#activate-user-role';
        var modalID = '#modal-activate-user-role';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-user-role/',
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
                    userRoleGrid.ajax.reload(null, false);
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

    //END OF USER ROLE SCRIPT




    // ROLE SCRIPT

    $(document).on('click', '.add-role-btn', function(e){
        
        var formID = '#add-role-form';
        var modalID = '#modal-add-role';
        $(formID)[0].reset();  
        $(modalID).modal({show:true});
        
    });

    var roleGrid = $('#tbl-roles').DataTable({
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
            url : base_url+'admin/roleGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        roleGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        roleGrid.button( '.buttons-excel' ).trigger();
        
    });

    $(document).on('submit', '#add-role-form', function(event){  
        event.preventDefault();
        var formID = '#add-role-form';
        var modalID = '#modal-add-role';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-role/',
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
                    roleGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-role', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        
        $.ajax({
            url: base_url + 'admin/modal-role/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-role').find('#id').val(id);
                    
                    $('#update-role').find('#user-type-name').val(parse_response['info'].userTypeName);
                    $('#update-role').find('#user-type-level').val(parse_response['info'].userTypeLevel);
                    
                    $('#modal-edit-role').modal({show:true});
                    $('#modal-edit-role .modal-title').text("Update role "+parse_response['info'].userTypeName);
                    $('#update-role').find('#role-update-btn').html('Update');
					
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-role', function(event){  
        event.preventDefault();
        var formID = '#update-role';
        var modalID = '#modal-edit-role';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-role/',
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
                    roleGrid.ajax.reload(null, false);
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
        var formID = '#activate-role';
        var modalID = '#modal-activate-role';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-role';
        var modalID = '#modal-deactivate-role';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#deactivate-role', function(event){  
        event.preventDefault();
        var formID = '#deactivate-role';
        var modalID = '#modal-deactivate-role';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-role/',
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
                    roleGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-role', function(event){  
        event.preventDefault();
        var formID = '#activate-role';
        var modalID = '#modal-activate-role';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-role/',
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
                    roleGrid.ajax.reload(null, false);
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

    //END OF ROLE SCRIPT





    //USER SLOC SCRIPT
    

    $(document).on('click', '.add-user-sloc-btn', function(e){
        
        var formID = '#add-user-sloc-form';
        var modalID = '#modal-add-user-sloc';
        $(formID)[0].reset();  
        $(modalID).modal({show:true});
        $(formID).find('select').val('').trigger('change');
        //$('.users-role-for-key').val('');
        //$('.users-role-for-key').trigger('change');
        //$('.key').val('');
        //$('.key').trigger('change');

    });

    var userSlocGrid = $('#tbl-user-sloc').DataTable({
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
            url : base_url+'admin/userSlocGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        userSlocGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        userSlocGrid.button( '.buttons-excel' ).trigger();
        
    });

    $(document).on('submit', '#add-user-sloc-form', function(event){  
        event.preventDefault();
        var formID = '#add-user-sloc-form';
        var modalID = '#modal-add-user-sloc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-user-sloc/',
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
                    userSlocGrid.ajax.reload(null, false);
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
        var formID = '#activate-user-sloc';
        var modalID = '#modal-activate-user-sloc';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-user-sloc';
        var modalID = '#modal-deactivate-user-sloc';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#deactivate-user-sloc', function(event){  
        event.preventDefault();
        var formID = '#deactivate-user-sloc';
        var modalID = '#modal-deactivate-user-sloc';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-user-sloc/',
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
                    userSlocGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-user-sloc', function(event){  
        event.preventDefault();
        var formID = '#activate-user-sloc';
        var modalID = '#modal-activate-user-sloc';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-user-sloc/',
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
                    userSlocGrid.ajax.reload(null, false);
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

    //END OF USER SLOC SCRIPT



    // SYSTEM MODULES SCRIPT

    $(document).on('click', '.add-sys-module-btn', function(e){
        
        var formID = '#add-sys-module-form';
        var modalID = '#modal-add-sys-module';
        $(formID)[0].reset();  
        $(modalID).modal({show:true});
        
    });

    var sysModuleGrid = $('#tbl-sys-modules').DataTable({
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
            url : base_url+'admin/sysModuleGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        sysModuleGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        sysModuleGrid.button( '.buttons-excel' ).trigger();
        
    });

    $(document).on('submit', '#add-sys-module-form', function(event){  
        event.preventDefault();
        var formID = '#add-sys-module-form';
        var modalID = '#modal-add-sys-module';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-sys-module/',
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
                    sysModuleGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-sys-module', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        
        $.ajax({
            url: base_url + 'admin/modal-sys-module/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-sys-module').find('#id').val(id);
                    
                    $('#update-sys-module').find('#module-desc').val(parse_response['info'].moduleDesc);
                    $('#update-sys-module').find('#alias').val(parse_response['info'].alias);
                    $('#update-sys-module').find('#link').val(parse_response['info'].link);
                    $('#update-sys-module').find('#link-name').val(parse_response['info'].linkName);
                    
                    $('#modal-edit-sys-module').modal({show:true});
                    $('#modal-edit-sys-module .modal-title').text("Update system module "+parse_response['info'].moduleDesc);
                    $('#update-sys-module').find('#sys-module-update-btn').html('Update');
					
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-sys-module', function(event){  
        event.preventDefault();
        var formID = '#update-sys-module';
        var modalID = '#modal-edit-sys-module';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-sys-module/',
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
                    sysModuleGrid.ajax.reload(null, false);
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
        var formID = '#activate-sys-module';
        var modalID = '#modal-activate-sys-module';
        
        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var formID = '#deactivate-sys-module';
        var modalID = '#modal-deactivate-sys-module';

        $(formID).find('#id').val(id);  
        $(modalID).modal({show:true});
    });

    $(document).on('submit', '#deactivate-sys-module', function(event){  
        event.preventDefault();
        var formID = '#deactivate-sys-module';
        var modalID = '#modal-deactivate-sys-module';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-sys-module/',
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
                    sysModuleGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-sys-module', function(event){  
        event.preventDefault();
        var formID = '#activate-sys-module';
        var modalID = '#modal-activate-sys-module';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-sys-module/',
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
                    sysModuleGrid.ajax.reload(null, false);
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

    //END OF SYSTEM MODULES SCRIPT






    //LOCATIOS CLICK HERE
    $(document).on('submit', '#add-towngroup', function(event){  
        event.preventDefault();
        var formID = '#add-towngroup';
        var modalID = '#modal-add-towngroup';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-tg/',
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
                    towngroupGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-towngroup', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-towngroup/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-towngroup').find('#id').val(id);
                    $('#update-towngroup').find('#tg-sDesc').val(parse_response['info'].tgSDesc);
                    $('#update-towngroup').find('#tg-lDesc').val(parse_response['info'].tgLDesc);

                    $('#update-towngroup').find('#bc-id').empty();
                    $('#update-towngroup').find('#bc-id').append(parse_response['info'].bc);

                    $('#modal-edit-towngroup').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-towngroup', function(event){  
        event.preventDefault();
        var formID = '#update-towngroup';
        var modalID = '#modal-edit-towngroup';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-towngroup/',
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
                    towngroupGrid.ajax.reload(null, false);
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

        $('#activate-towngroup').find('#id').val(id);
        $('#modal-active-towngroup').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');

        $('#deactivate-towngroup').find('#id').val(id);
        $('#modal-deactivate-towngroup').modal({show:true});
    });

    $(document).on('submit', '#deactivate-towngroup', function(event){  
        event.preventDefault();
        var formID = '#deactivate-towngroup';
        var modalID = '#modal-deactivate-towngroup';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-towngroup/',
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
                    towngroupGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-towngroup', function(event){  
        event.preventDefault();
        var formID = '#activate-towngroup';
        var modalID = '#modal-active-towngroup';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-towngroup/',
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
                    towngroupGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#add-area', function(event){  
        event.preventDefault();
        var formID = '#add-area';
        var modalID = '#modal-add-area';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-area/',
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
                    areaGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-area', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var bcID = $(this).attr('data-bc-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-area/',
            data: {id:id, bcID:bcID},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-area').find('#id').val(id);
                    $('#update-area').find('#bc-id').val(bcID);
                    $('#update-area').find('#area-sDesc').val(parse_response['info'].areaSDesc);
                    $('#update-area').find('#area-lDesc').val(parse_response['info'].areaLDesc);

                    $('#update-area').find('#tg-id').empty();
                    $('#update-area').find('#tg-id').append(parse_response['info'].tg);

                    $('#modal-edit-area').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-area', function(event){  
        event.preventDefault();
        var formID = '#update-area';
        var modalID = '#modal-edit-area';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-area/',
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
                    areaGrid.ajax.reload(null, false);
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

        $('#activate-area').find('#id').val(id);
        $('#modal-active-area').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');

        $('#deactivate-area').find('#id').val(id);
        $('#modal-deactivate-area').modal({show:true});
    });

    $(document).on('submit', '#deactivate-area', function(event){  
        event.preventDefault();
        var formID = '#deactivate-area';
        var modalID = '#modal-deactivate-area';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-area/',
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
                    areaGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-area', function(event){  
        event.preventDefault();
        var formID = '#activate-area';
        var modalID = '#modal-active-area';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-area/',
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
                    areaGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#add-custmatprice', function(event){  
        event.preventDefault();
        var formID = '#add-custmatprice';
        var modalID = '#modal-add-custmatprice';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-custmatprice/',
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
                    customerMaterialGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-custmatprice', function(e){
        e.preventDefault();
        var id = $(this).attr('data-primary-id');
        var mat_id = $(this).attr('data-mat-id');
        var cust_id = $(this).attr('data-cust-id');
        var bc_id = $(this).attr('data-bc-id');
        var tg_id = $(this).attr('data-tg-id');
        
        if(id != null){
            var postData = {id:id};
            $('#update-custmatprice').find('#id').val(id);
            $('#update-custmatprice').find('.town-display').hide();
        } else if(mat_id != null && cust_id != null){
            var postData = {cust_id:cust_id, mat_id:mat_id};
            $('#update-custmatprice').find('#cust-id').val(cust_id);
            $('#update-custmatprice').find('#mat-id').val(mat_id);
            $('#update-custmatprice').find('.bc-display').hide();
            $('#update-custmatprice').find('.town-display').hide();
            $('#update-custmatprice').find('.town-area-display').hide();
        } else if(mat_id != null && bc_id != null){
            var postData = {mat_id:mat_id,bc_id:bc_id};
            $('#update-custmatprice').find('#mat-id').val(mat_id);
            $('#update-custmatprice').find('#bc-id').val(bc_id);
            $('#update-custmatprice').find('.customer-display').hide();
            $('#update-custmatprice').find('.town-display').hide();
            $('#update-custmatprice').find('.town-area-display').hide();
        } else if(mat_id != null && tg_id != null){
            var postData = {mat_id:mat_id,tg_id:tg_id};
            $('#update-custmatprice').find('#mat-id').val(mat_id);
            $('#update-custmatprice').find('#bc-id').val(bc_id);
            $('#update-custmatprice').find('.customer-display').hide();
            $('#update-custmatprice').find('.town-area-display').hide();
        } else if(mat_id != null){
            var postData = {mat_id:mat_id};
            $('#update-custmatprice').find('#mat-id').val(mat_id);
            $('#update-custmatprice').find('.customer-display').hide();
            $('#update-custmatprice').find('.bc-display').hide();
            $('#update-custmatprice').find('.town-display').hide();
            $('#update-custmatprice').find('.town-area-display').hide();
        } else {
            var postData = {};
        }

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-custmatprice/',
            data: postData,
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-custmatprice').find('#cust-name').val(parse_response['info'].custFullName);
                    $('#update-custmatprice').find('#mat-name').val(parse_response['info'].matDesc);
                    $('#update-custmatprice').find('#bc-name').val(parse_response['info'].bcName);
                    $('#update-custmatprice').find('#town-name').val(parse_response['info'].towngroup);
                    $('#update-custmatprice').find('#town-area-name').val(parse_response['info'].area);

                    $('#update-custmatprice').find('#price1').val(parse_response['info'].price1);
                    $('#update-custmatprice').find('#price2').val(parse_response['info'].price2);
                    $('#update-custmatprice').find('#price3').val(parse_response['info'].price3);
                    $('#update-custmatprice').find('#eqpk').val(parse_response['info'].eqpk);

                    $('#update-custmatprice').find('#suom').empty();
                    $('#update-custmatprice').find('#suom').append(parse_response['info'].suom);

                    $('#modal-edit-custmatprice').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-custmatprice', function(event){  
        event.preventDefault();
        var formID = '#update-custmatprice';
        var modalID = '#modal-edit-custmatprice';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-custmatprice/',
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
                    customerMaterialGrid.ajax.reload(null, false);
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
        
        var id = $(this).attr('data-primary-id');
        var mat_id = $(this).attr('data-mat-id');
        var cust_id = $(this).attr('data-cust-id');
        var bc_id = $(this).attr('data-bc-id');
        var tg_id = $(this).attr('data-tg-id');
        
        if(id != null){
            $('#activate-custmatprice').find('#id').val(id);
        } else if(mat_id != null && cust_id != null){
            $('#activate-custmatprice').find('#cust-id').val(cust_id);
            $('#activate-custmatprice').find('#mat-id').val(mat_id);
        } else if(mat_id != null && bc_id != null){
            $('#activate-custmatprice').find('#mat-id').val(mat_id);
            $('#activate-custmatprice').find('#bc-id').val(bc_id);
        } else if(mat_id != null && tg_id != null){
            $('#activate-custmatprice').find('#mat-id').val(mat_id);
            $('#activate-custmatprice').find('#bc-id').val(bc_id);
        } else if(mat_id != null){
            $('#activate-custmatprice').find('#mat-id').val(mat_id);
        } else {
        }

        $('#modal-active-custmatprice').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        
        var id = $(this).attr('data-primary-id');
        var mat_id = $(this).attr('data-mat-id');
        var cust_id = $(this).attr('data-cust-id');
        var bc_id = $(this).attr('data-bc-id');
        var tg_id = $(this).attr('data-tg-id');
        
        if(id != null){
            $('#deactivate-custmatprice').find('#id').val(id);
        } else if(mat_id != null && cust_id != null){
            $('#deactivate-custmatprice').find('#cust-id').val(cust_id);
            $('#deactivate-custmatprice').find('#mat-id').val(mat_id);
        } else if(mat_id != null && bc_id != null){
            $('#deactivate-custmatprice').find('#mat-id').val(mat_id);
            $('#deactivate-custmatprice').find('#bc-id').val(bc_id);
        } else if(mat_id != null && tg_id != null){
            $('#deactivate-custmatprice').find('#mat-id').val(mat_id);
            $('#deactivate-custmatprice').find('#bc-id').val(bc_id);
        } else if(mat_id != null){
            $('#deactivate-custmatprice').find('#mat-id').val(mat_id);
        } else {
        }

        $('#modal-deactivate-custmatprice').modal({show:true});
    });

    $(document).on('submit', '#deactivate-custmatprice', function(event){  
        event.preventDefault();
        var formID = '#deactivate-custmatprice';
        var modalID = '#modal-deactivate-custmatprice';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-custmatprice/',
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
                    customerMaterialGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-custmatprice', function(event){  
        event.preventDefault();
        var formID = '#activate-custmatprice';
        var modalID = '#modal-active-custmatprice';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-custmatprice/',
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
                    customerMaterialGrid.ajax.reload(null, false);
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


    //STORAGE LOCATION | NOTIF SCRIPT

    $(document).on('click', '.add-sloc', function(e){
        

        var formID = '#add-sLoc';
        var modalID = '#modal-add-sLoc';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var slocIDVal = $('#sloc-id-val-for-notif').val();
    var sLocGrid = $('#tbl-sLoc').DataTable({
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
        //"responsive": true,
        "processing": true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 },
            { responsivePriority: 3, targets: 1 },
            { responsivePriority: 4, targets: -2 },
            { responsivePriority: 5, targets: 2 },
            { responsivePriority: 6, targets: -3 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/sLocGrid/'+slocIDVal,
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    var param1 = $('#param1').val();
    var param2 = $('#param2').val();
    var param3 = $('#param3').val();
    var param4 = $('#param4').val();
    var notifGrid = $('.tbl-view-notif').DataTable({
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
        "processing": true,
        'responsive': false,
        "columnDefs": [
            {
                targets : [ 0 ],
                visible : false,
                searchable : false
            }         
        ],
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        //scrollCollapse: true,
        "ajax": {
            url : base_url + 'admin/notifGrid/'+param1+'/'+param2+'/'+param3+'/'+param4,
            type : 'GET'
        },
        //scrollX: true,
        paging:         true,
        fixedColumns:   false,
        order: [],
        searching:true,
        bInfo : true,
        select : true,
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:nth-child(2) c[r^="A"]', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
        
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        notifGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.dl-dt', function(e){
        
        notifGrid.button( '.buttons-excel' ).trigger();
        
    });


    var historyGrid = $('.tbl-view-history-cg').DataTable({
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
        "processing": true,
        "columnDefs": [
            {
                targets : [ 0 ],
                visible : false,
                searchable : false
            },
            { responsivePriority: 1, targets: 1 },
            { responsivePriority: 2, targets: -1 }            
        ],
        //scrollCollapse: true,
        select : true,
        paging:         true,
        fixedColumns:   false,
        ordering: false,
        searching:true,
        bInfo : true
        
    });

    

    $('#clear-history-btn').show();
    $('.clear-dt-history').click(function () {
        $('#loader-div').removeClass('loaded');
        var ids = $.map(notifGrid.rows('.selected').data(), function (item) {
            return item[0] //NOTIF ID
        });
        if(notifGrid.rows('.selected').data().length > 0){
            var statusID = 15;
            var trigger = 'clear';
            $.ajax({
                url: base_url + 'admin/update_usernotif',
                method:'POST',
                data: {statusID : statusID, trigger:trigger, notifID:ids},
                dataType:"json",
                success:function(data)  
                {
                    if(data.success){
                        
                        $('#announcement-notif').empty();
                        $('#announcement-notif').append(data.item);

                        notifGrid.ajax.reload(null, false);
                        showSuccess(notifGrid.rows('.selected').data().length + ' row(s) cleared');
                    }
                },
                error:function(xhr, textStatus, errorThrown){
                    showError('Error in Saving!');
                    console.log(xhr.responseText);
                    
                }
            });
        } else {
            showWarning(notifGrid.rows('.selected').data().length + ' row(s) cleared');
        }
        $('#loader-div').addClass('loaded');
    });

    $(document).on('click', '.notif-item', function(e){

        $('#clear-history-btn').show();
        var id = 0;
        var notifID = $(this).attr('data-id');
        var notifTypeID = $(this).attr('data-typeid')
        var slocName = $(this).attr('data-name');
        var transTypeID = $(this).attr('data-transtype');
        var refID = $(this).attr('data-ref-id');
        $('#loader-div').removeClass('loaded');
        $('#modal-view-notif').modal({show:true});
        //alert(notifID);
        //alert(notifTypeID);
        if(notifID && slocName){
            //$('#modal-view-notif .modal-title').text("Notif details of "+slocName);
            //window.location.replace(base_url + 'admin/notifications/'+id+'/'+notifTypeID+'/'+notifID+'/15');
            //notifGrid.ajax.url(base_url + 'admin/notifGrid/'+id+'/'+notifTypeID+'/'+notifID+'/15').load();
            if(notifTypeID == 2){
                window.location.replace(base_url + 'transactional/emp-monitoring-pending/'+refID);
            } else if (notifTypeID == 1){
                window.location.replace(base_url + 'admin/storloc/'+refID);
            } else if (notifTypeID == 3){
                window.location.replace(base_url + 'transactional/cg-performance/'+transTypeID+'/'+refID);
            }

        } else {
            //$('#modal-view-notif .modal-title').text("All Notif details");
            window.location.replace(base_url + 'admin/notifications/0/0/0/15');
            //notifGrid.ajax.url(base_url + 'admin/notifGrid/0/0/0/15').load();
        }
        notifGrid.columns.adjust();
        $(document).on('click', '.refresh-dt', function(e){
            notifGrid.ajax.reload(null, false);
        });

        var statusID = 14;
        var trigger = 'read';
        $.ajax({
            url: base_url + 'admin/update_usernotif',
            method:'POST',
            data: {statusID : statusID, trigger:trigger, notifID:notifID},
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    
                    $('#announcement-notif').empty();
                    $('#announcement-notif').append(data.item);
                }
            },
            error:function(xhr, textStatus, errorThrown){
                showError('Error in Saving!');
                console.log(xhr.responseText);
                
            }
        });

        $('#loader-div').addClass('loaded');
    });
                    

    $(document).on('click', '.refresh-dt', function(e){
        
        sLocGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        sLocGrid.button( '.buttons-excel' ).trigger();
    });

    $(document).on('click', '.upload-sloc-btn', function(e){
        var modalID = '#modal-upload-sLoc';
        var formID = '#upload-sLoc';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
    });

    $(document).on('click', '.add-substat', function(e){
        var formID = '#add-substat';
        var modalID = '#modal-add-substat';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
    });

    $(document).on('submit', '#add-substat', function(event){  
        event.preventDefault();

        var formID = '#add-substat';
        var modalID = '#modal-add-substat';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-substat/',
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
                    if($('.dynamic-substatus').val() !== undefined){
                        var option = new Option(data.subStatDesc, data.subStatusID);
                        $('.dynamic-substatus').append($(option));
                        $(".dynamic-substatus").val(data.subStatusID);
                        $('.dynamic_dropdown').trigger('change');
                    }
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

    $(document).on('submit', '#upload-sLoc', function(event){  
        event.preventDefault();
        var formID = '#add-sLoc';
        var modalID = '#modal-add-sLoc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/upload-sloc/',
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
                sLocGrid.ajax.reload(null, false);

                $('#modal-import-result').modal({show:true});
                $('#modal-import-result .modal-title').text('Upload Result');
                $(".tbl-import-result > tbody").empty();
                $(".tbl-import-result > tbody").append(data.importTable);
                if(!data.success){
                    showAlertError(data.successMsg);
                } else {
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

    $(document).on('submit', '#add-sLoc', function(event){  
        event.preventDefault();
        var formID = '#add-sLoc';
        var modalID = '#modal-add-sLoc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-sLoc/',
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
                    sLocGrid.ajax.reload(null, false);
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
 
    $(document).on('click', '.edit-sLoc', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-sLoc/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-sLoc').find('#id').val(id);
                    
                    $('#update-sLoc').find('#sLoc-code').val(parse_response['info'].slocCode);
                    $('#update-sLoc').find('#mobbilenumberslabel').html(parse_response['info'].mobileNumber);

                    
                    $('#update-sLoc').find('#sLoc-name').val(parse_response['info'].slocName);
                    
                    $('#update-sLoc').find('#sLoc-addr').val(parse_response['info'].slocAddr);
                    
                    $('#update-sLoc').find('#bc-id').empty();
                    $('#update-sLoc').find('#bc-id').append(parse_response['info'].bcID);

                    $('#update-sLoc').find('#sLocType-id').empty();
                    $('#update-sLoc').find('#sLocType-id').append(parse_response['info'].slocTypeID);
                    $('#update-sLoc').find('#litterMaterialID').empty();
                    $('#update-sLoc').find('#litterMaterialID').append(parse_response['info'].litterMaterialID);

                    $('#update-sLoc').find('#heatSourceID').empty();
                    $('#update-sLoc').find('#heatSourceID').append(parse_response['info'].heatSourceID);

                    $('#update-sLoc').find('#farmTypeID').empty();
                    $('#update-sLoc').find('#farmTypeID').append(parse_response['info'].farmTypeID);

                    $('#update-sLoc').find('#provinceID').empty();
                    $('#update-sLoc').find('#provinceID').append(parse_response['info'].provinceID);

                    $('#update-sLoc').find('#vetID').empty();
                    $('#update-sLoc').find('#vetID').append(parse_response['info'].vetID);

                    $('#update-sLoc').find('#firstName').val(parse_response['info'].firstName);
                    $('#update-sLoc').find('#surName').val(parse_response['info'].surName);                    
                    $('#update-sLoc').find('#farmName').val(parse_response['info'].farmName);                    
                    $('#update-sLoc').find('#farm').val(parse_response['info'].farm);                    
                    $('#update-sLoc').find('#dateStarted').val(parse_response['info'].dateStarted);
                    $("#dateStartedParent").datepicker("update", new Date(parse_response['info'].dateStarted));

                    $('#update-sLoc').find('#capacity').val(parse_response['info'].capacity);                    
                    $('#update-sLoc').find('#noOfHouse').val(parse_response['info'].noOfHouse);                    
                    $('#update-sLoc').find('#farmCoordinates').val(parse_response['info'].farmCoordinates);
                    $('#update-sLoc').find('#homeAddress').val(parse_response['info'].homeAddress);
                    $('#update-sLoc').find('#cpNo').val(parse_response['info'].cpNo);
                    $('#update-sLoc').find('#email').val(parse_response['info'].email);

                    $('#update-sLoc').find('.select2').trigger('click');

                    $('#modal-edit-sLoc').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.mobile-redirect', function(e){
        e.preventDefault();
        if($(this).attr('data-id')){
            var id = $(this).attr('data-id');
        } else {
            var id = $('#update-sLoc').find('#id').val();
        }
        $('#loader-div').removeClass('loaded');
        var url = base_url + 'admin/mobile/'+id;
        printWindow = window.open( url ,"_self");
        
        $('#loader-div').addClass('loaded');
    });

    $(document).on('click', '.history-sLoc', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var notifTypeID = 1; //sloc
        var slocName = $(this).attr('data-name');
        $('#loader-div').removeClass('loaded');
        $('#modal-view-history-cg').modal({show:true});
        $('#modal-view-history-cg .modal-title').text("Logs of "+slocName);

        historyGrid.ajax.url(base_url + 'admin/historyGrid/'+id+'/'+notifTypeID+'/0/16').load();
        historyGrid.columns.adjust();
        $(document).on('click', '.refresh-dt', function(e){
            historyGrid.ajax.reload(null, false);
        });
        $('#loader-div').addClass('loaded');
    });

    
    
    $(document).on('submit', '#update-sLoc', function(event){  
        event.preventDefault();
        var formID = '#update-sLoc';
        var modalID = '#modal-edit-sLoc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-sLoc/',
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
                    sLocGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        var subStatDesc = $(this).attr('data-substat');
        
        $('#activate-sLoc').find('#id').val(id);
        $('#activate-sLoc').find('#val').html(val);
        $('#activate-sLoc').find('#substat-val').html(subStatDesc);
        $('#modal-active-sLoc').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        
        $('#deactivate-sLoc').find('#id').val(id);
        $('#deactivate-sLoc').find('#val').html(val);

        $('#modal-deactivate-sLoc').modal({show:true});
    });

    $(document).on('submit', '#deactivate-sLoc', function(event){  
        event.preventDefault();
        var formID = '#deactivate-sLoc';
        var modalID = '#modal-deactivate-sLoc';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-sLoc/',
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
                    sLocGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-sLoc', function(event){  
        event.preventDefault();
        var formID = '#activate-sLoc';
        var modalID = '#modal-active-sLoc';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-sLoc/',
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
                    sLocGrid.ajax.reload(null, false);
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
    // END OF STORAGE LOCATION | NOTIF SCRIPT


    //DASHBOARD SCRIPT
    


    //LOGS SCRIPT

    var logsGrid = $('#tbl-sys-logs').DataTable({
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
        //"responsive": true,
        "processing": true,
        "serverSide": true,
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
            url : base_url+'admin/sysLogsGrid',
            type : 'POST'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:nth-child(2) c[r^="A"]', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    

    $(document).on('click', '.refresh-dt', function(e){
        
        logsGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        logsGrid.button( '.buttons-excel' ).trigger();
        
    });


    var userFeedbackGrid = $('#tbl-user-feedback').DataTable({
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
        "processing": true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        scrollCollapse: true,
        "processing": true,
        "serverSide": true,
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/userFeedbackGrid',
            type : 'POST'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:nth-child(2) c[r^="A"]', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
        
    });

    $(document).on('click', '.refresh-dt', function(e){
        userFeedbackGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.dl-dt', function(e){
        
        userFeedbackGrid.button( '.buttons-excel' ).trigger();
        
    });
    

    var activityGrid = $('.tbl-view-activity-log').DataTable({
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
        "processing": true,
        "columnDefs": [
            { responsivePriority: 1, targets: 0 },
            { responsivePriority: 2, targets: -1 }
        ],
        scrollCollapse: true,
        "processing": true,
        "serverSide": true,
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/activityGrid',
            type : 'POST'
        }
        
    });

    $(document).on('click', '.refresh-dt', function(e){
        activityGrid.ajax.reload(null, false);
    });

    $(document).on('click', '#activityLogBtn', function(e){
        
        $('#modal-view-activity-log').modal({show:true});
        
    });

    $(document).on('submit', '#uploadPhotoForm', function(event){  
        event.preventDefault();
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url:base_url+'login/update_profile_pic',
            method:'POST',
            data:new FormData(this),
            contentType:false,
            cache:false,
            processData:false,
            dataType:"json",
            success:function(data)  
            {  
                if(data.success){
                    
                    //alert(data);
                    $('#uploadPhotoForm')[0].reset();  
                    $('#uploadPhotoModal').modal('hide');
                    $('#uploadPhotoModal').on('hidden.bs.modal', function () {
                        $(this).removeData('bs.modal');
                    });
                    url = base_url+'admin';
                    window.location.reload(true);
                    //$('#profileModal').modal('show');
                    
                } else {
                    showAlertError(data.successMsg);
                }

                $('#loader-div').addClass('loaded');
            },
            error:function(xhr, textStatus, errorThrown){
                $eui.messager.alert('Error', 'Error in Saving Transaction!');
                console.log(xhr.responseText);

                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('click', '.background-color-switch', function(e){
        var backgroundColor = $(this).attr('data-color');
        
        var postData = { backgroundColor:backgroundColor };
        $.ajax({
            url: base_url + 'admin/put-user-theme-background/',
            method:'POST',
            data: postData, 
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    location.reload(true);
                    //showSuccess(data.successMsg);
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

    $(document).on('click', '.sidebar-color-switch', function(e){
        var sideBarColor = $(this).attr('data-color');
        
        var postData = { sideBarColor:sideBarColor };
        $.ajax({
            url: base_url + 'admin/put-user-theme-sidebar/',
            method:'POST',
            data: postData, 
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    //showSuccess(data.successMsg);
                    location.reload(true);
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

    $(document).on('click', '.topbar-color-switch', function(e){
        var topBarColor = $(this).attr('data-color');
        
        var postData = { topBarColor:topBarColor };
        $.ajax({
            url: base_url + 'admin/put-user-theme-topbar/',
            method:'POST',
            data: postData, 
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    //showSuccess(data.successMsg);
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

    $(document).on('click', '.logo-header-color-switch', function(e){
        var logoHeaderColor = $(this).attr('data-color');
        
        var postData = { logoHeaderColor:logoHeaderColor };
        $.ajax({
            url: base_url + 'admin/put-user-theme-logo-header/',
            method:'POST',
            data: postData, 
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    //showSuccess(data.successMsg);
                    location.reload(true);
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

    $('.changeBtnColor').on('click', function(){
        //alert('hello');
        var btnColor = $(this).attr('data-color');
        
        var postData = { btnColor:btnColor };
        $.ajax({
            url: base_url + 'admin/put-user-theme-btn/',
            method:'POST',
            data: postData, 
            dataType:"json",
            success:function(data)  
            {
                if(data.success){
                    //showSuccess(data.successMsg);
                    location.reload(true);
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



    $(document).on('click', '.select2', function(e){
        //you can uncomment below codes to see its structure 
        //console.log($('.select2-container').html()) 
        //below change the background color for the input 
        $('#'+$('span[aria-owns]', this).attr('aria-owns')) 
        .parent() 
        .siblings('.select2-search') 
        .find('input') 
        .css('background-color', '#ffffcc') 
        //below change the font color for the selected option 
        //$('#'+$('span[aria-owns]', this).attr('aria-owns')) 
        //.find('li[aria-selected=true]') 
        //.css('color', 'yellow') 
        //below change the font color for <select> 
        $('>span>span>span', this).css('color', 'black') 
        //below change the background color for the arrow of <select> 
        $('>span .select2-selection__arrow', this).css('background-color', 'red') 

        
    });

    //END OF LOGS SCRIPT


    //BUSINESS CENTER SCRIPT
    $(document).on('click', '.add-bc', function(e){
        

        var formID = '#add-bc';
        var modalID = '#modal-add-bc';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var centerTypeID = $('#centerTypeID').val();
    var bcGrid = $('#tbl-bc').DataTable({
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
        //"responsive": true,
        "processing": true,
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
            url : base_url+'admin/bcGrid/'+centerTypeID,
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        bcGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        bcGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });
    
    $(document).on('submit', '#add-bc', function(event){  
        event.preventDefault();
        var formID = '#add-bc';
        var modalID = '#modal-add-bc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-bc/',
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
                    if($('.dynamic-bc').val() !== undefined){
                        var option = new Option(data.bcName, data.bcID);
                        $('.dynamic-bc').append($(option));
                        $(".dynamic-bc").val(data.bcID);
                        $('.dynamic_dropdown').trigger('change');
                        $('.select2').trigger('click');
                    } else {
                        bcGrid.ajax.reload(null, false);
                    }
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
    
    $(document).on('click', '.edit-bc', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-bc/',
            data: {id:id, centerTypeID:centerTypeID},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-bc').find('#id').val(id);
                    
                    $('#update-bc').find('#rg-id').empty();
                    $('#update-bc').find('#rg-id').append(parse_response['info'].rgID);
                    
                    $('#update-bc').find('#center-type-id').empty();
                    $('#update-bc').find('#center-type-id').append(parse_response['info'].centerTypeID);
                    
                    $('#update-bc').find('#bc-code').val(parse_response['info'].bcCode);

                    $('#update-bc').find('#bc-name').val(parse_response['info'].bcName);
                    
                    $('#update-bc').find('#plant-code').val(parse_response['info'].pCode);
                    $('#update-bc').find('.select2').trigger('click');

                    
                    
                    $('#modal-edit-bc').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('submit', '#update-bc', function(event){  
        event.preventDefault();
        var formID = '#update-bc';
        var modalID = '#modal-edit-bc';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-bc/',
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
                    $('#centerTypeID').val(data.centerTypeID);
                    bcGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-bc').find('#id').val(id);
        $('#activate-bc').find('#val').html(val);
        $('#modal-active-bc').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-bc').find('#id').val(id);
        $('#deactivate-bc').find('#val').html(val);
        $('#modal-deactivate-bc').modal({show:true});
    });

    $(document).on('submit', '#deactivate-bc', function(event){  
        event.preventDefault();
        var formID = '#deactivate-bc';
        var modalID = '#modal-deactivate-bc';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-bc/',
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
                    bcGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-bc', function(event){  
        event.preventDefault();
        var formID = '#activate-bc';
        var modalID = '#modal-active-bc';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-bc/',
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
                    bcGrid.ajax.reload(null, false);
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
    //END OF BUSINESS CENTER SCRIPT


    //REGION SCRIPT
    $(document).on('click', '.add-region', function(e){
        

        var formID = '#add-region';
        var modalID = '#modal-add-region';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var regionGrid = $('#tbl-region').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/regionGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });



    $(document).on('click', '.refresh-dt', function(e){
        
        regionGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        regionGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });
    
    $(document).on('submit', '#add-region', function(event){  
        event.preventDefault();
        var formID = '#add-region';
        var modalID = '#modal-add-region';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-region/',
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
                    regionGrid.ajax.reload(null, false);
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
    
    $(document).on('click', '.edit-region', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-region/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-region').find('#id').val(id);
                    
                    $('#update-region').find('#region-sDesc').val(parse_response['info'].rgSDesc);

                    $('#update-region').find('#region-lDesc').val(parse_response['info'].rgLDesc);
                    
                    $('#modal-edit-region').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('submit', '#update-region', function(event){  
        event.preventDefault();
        var formID = '#update-region';
        var modalID = '#modal-edit-region';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-region/',
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
                    regionGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-region').find('#id').val(id);
        $('#activate-region').find('#val').html(val);
        $('#modal-active-region').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-region').find('#id').val(id);
        $('#deactivate-region').find('#val').html(val);
        $('#modal-deactivate-region').modal({show:true});
    });

    $(document).on('submit', '#deactivate-region', function(event){  
        event.preventDefault();
        var formID = '#deactivate-region';
        var modalID = '#modal-deactivate-region';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-region/',
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
                    regionGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-region', function(event){  
        event.preventDefault();
        var formID = '#activate-region';
        var modalID = '#modal-active-region';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-region/',
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
                    regionGrid.ajax.reload(null, false);
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
    //END OF REGION SCRIPT
    
    
    
     //SUPPLIER SCRIPT
    $(document).on('click', '.add-supplier', function(e){

        var formID = '#add-supplier';
        var modalID = '#modal-add-supplier';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    $(document).on('click', '.upload-supplier', function(e){

        var formID = '#upload-supplier';
        var modalID = '#modal-upload-supplier';
        $(modalID).modal({show:true});

        $(formID).find('select').val('').trigger('change');
    });

    var supplierGrid = $('#tbl-supplier').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/supplierGrid',
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
        
        supplierGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        supplierGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-supplier', function(event){  
        event.preventDefault();
        var formID = '#add-supplier';
        var modalID = '#modal-add-supplier';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-supplier/',
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
                    supplierGrid.ajax.reload(null, false);
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
  
$(document).on('submit', '#upload-supplier-form', function(event) {  
    event.preventDefault();
    var formID = '#upload-supplier-form';
    var modalID = '#modal-upload-supplier';
    $('#loader-div').removeClass('loaded');

    $.ajax({
        url: base_url + 'admin/upload-supplier/',
        method: 'POST',
        data: new FormData(this),
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(data) {
            if (!data.success) {
                showAlertError(data.successMsg);
            } else {
                $(formID)[0].reset();
                $(modalID).modal('hide');
                $(modalID).on('hidden.bs.modal', function() {
                    $(this).removeData('bs.modal');
                });
                supplierGrid.ajax.reload(null, false);
                showSuccess(data.successMsg);
            }
            $('#loader-div').addClass('loaded');
        },
        error: function(xhr, textStatus, errorThrown) {
            showError('Error in Uploading!');
            console.log(xhr.responseText);
            $('#loader-div').addClass('loaded');
        }
    });
});

    $(document).on('click', '.edit-supplier', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-supplier/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-supplier').find('#id').val(id);
                    $('#update-supplier').find('#supplierName').val(parse_response['info'].supplier_name);
                    $('#update-supplier').find('#labName').val(parse_response['info'].laboratory_id);
                    $('#modal-edit-supplier').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-supplier', function(event){  
        event.preventDefault();
        var formID = '#update-supplier';
        var modalID = '#modal-edit-supplier';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-supplier/',
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
                    supplierGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-supplier').find('#id').val(id);
        $('#activate-supplier').find('#val').html(val);
        $('#modal-active-supplier').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-supplier').find('#id').val(id);
        $('#deactivate-supplier').find('#val').html(val);
        $('#modal-deactivate-supplier').modal({show:true});
    });

    $(document).on('submit', '#deactivate-supplier', function(event){  
        event.preventDefault();
        var formID = '#deactivate-supplier';
        var modalID = '#modal-deactivate-supplier';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-supplier/',
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
                    supplierGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-supplier', function(event){  
        event.preventDefault();
        var formID = '#activate-supplier';
        var modalID = '#modal-active-supplier';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-supplier/',
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
                    supplierGrid.ajax.reload(null, false);
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
     //END OF SUPPLIER SCRIPT



    //ANALYST SCRIPT
    $(document).on('click', '.add-analyst', function(e){

        var formID = '#add-analyst';
        var modalID = '#modal-add-analyst';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var analystGrid = $('#tbl-analyst').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/analystGrid',
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
        
        analystGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        analystGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-analyst', function(event){  
        event.preventDefault();
        var formID = '#add-analyst';
        var modalID = '#modal-add-analyst';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-analyst/',
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
                    analystGrid.ajax.reload(null, false);
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


    $(document).on('click', '.edit-analyst', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-analyst/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-analyst').find('#id').val(id);
                    $('#update-analyst').find('#analystName').val(parse_response['info'].analyst_name);
                    $('#modal-edit-analyst').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-analyst', function(event){  
        event.preventDefault();
        var formID = '#update-analyst';
        var modalID = '#modal-edit-analyst';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-analyst/',
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
                    analystGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-analyst').find('#id').val(id);
        $('#activate-analyst').find('#val').html(val);
        $('#modal-active-analyst').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-analyst').find('#id').val(id);
        $('#deactivate-analyst').find('#val').html(val);
        $('#modal-deactivate-analyst').modal({show:true});
    });

    $(document).on('submit', '#deactivate-analyst', function(event){  
        event.preventDefault();
        var formID = '#deactivate-analyst';
        var modalID = '#modal-deactivate-analyst';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-analyst/',
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
                    analystGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-analyst', function(event){  
        event.preventDefault();
        var formID = '#activate-analyst';
        var modalID = '#modal-active-analyst';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-analyst/',
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
                    analystGrid.ajax.reload(null, false);
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
    //END OF ANALYST SCRIPT


    //SAMPLE NAME SCRIPT
    $(document).on('click', '.add-sample-name', function(e){

        var formID = '#add-sample-name';
        var modalID = '#modal-add-sample-name';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var samplenameGrid = $('#tbl-sample-name').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/sampleNameGrid',
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
        
        samplenameGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        samplenameGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-sample-name', function(event){  
        event.preventDefault();
        var formID = '#add-sample-name';
        var modalID = '#modal-add-sample-name';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-sample-name/',
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
                    samplenameGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-sample-name', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-sample-name/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-sample-name').find('#id').val(id);
                    $('#update-sample-name').find('#sampleName').val(parse_response['info'].sample_name);
                    $('#update-sample-name').find('#sampleCode').val(parse_response['info'].sample_code);
                    $('#modal-edit-sample-name').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-sample-name', function(event){  
        event.preventDefault();
        var formID = '#update-sample-name';
        var modalID = '#modal-edit-sample-name';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-sample-name/',
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
                    samplenameGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-sample-name').find('#id').val(id);
        $('#activate-sample-name').find('#val').html(val);
        $('#modal-active-sample-name').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-sample-name').find('#id').val(id);
        $('#deactivate-sample-name').find('#val').html(val);
        $('#modal-deactivate-sample-name').modal({show:true});
    });

    $(document).on('submit', '#deactivate-sample-name', function(event){  
        event.preventDefault();
        var formID = '#deactivate-sample-name';
        var modalID = '#modal-deactivate-sample-name';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-sample-name/',
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
                    samplenameGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-sample-name', function(event){  
        event.preventDefault();
        var formID = '#activate-sample-name';
        var modalID = '#modal-active-sample-name';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-sample-name/',
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
                    samplenameGrid.ajax.reload(null, false);
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
    //END OF SAMPLE NAME SCRIPT

    //SAMPLE TYPE SCRIPT
    $(document).on('click', '.add-sample-type', function(e){

        var formID = '#add-sample-type';
        var modalID = '#modal-add-sample-type';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    var sampletypeGrid = $('#tbl-sample-type').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/sampleTypeGrid',
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
        
        sampletypeGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        sampletypeGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-sample-type', function(event){  
        event.preventDefault();
        var formID = '#add-sample-type';
        var modalID = '#modal-add-sample-type';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-sample-type/',
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
                    sampletypeGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-sample-type', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-sample-type/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-sample-type').find('#id').val(id);
                    $('#update-sample-type').find('#sampleType').val(parse_response['info'].sample_type_name);
                    $('#modal-edit-sample-type').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-sample-type', function(event){  
        event.preventDefault();
        var formID = '#update-sample-type';
        var modalID = '#modal-edit-sample-type';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-sample-type/',
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
                    sampletypeGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-sample-type').find('#id').val(id);
        $('#activate-sample-type').find('#val').html(val);
        $('#modal-active-sample-type').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-sample-type').find('#id').val(id);
        $('#deactivate-sample-type').find('#val').html(val);
        $('#modal-deactivate-sample-type').modal({show:true});
    });

    $(document).on('submit', '#deactivate-sample-type', function(event){  
        event.preventDefault();
        var formID = '#deactivate-sample-type';
        var modalID = '#modal-deactivate-sample-type';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-sample-type/',
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
                    sampletypeGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-sample-type', function(event){  
        event.preventDefault();
        var formID = '#activate-sample-type';
        var modalID = '#modal-active-sample-type';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-sample-type/',
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
                    sampletypeGrid.ajax.reload(null, false);
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
    //END OF SAMPLE TYPE SCRIPT
    
     //COMMERCIAL FEEDMILL SCRIPT

    $(document).on('click', '.add-commercial-feedmill', function(e){
        var formID = '#add-commercial-feedmill';
        var modalID = '#modal-add-commercial-feedmill';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });


    var commGrid = $('#tbl-commercial-feedmill').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/commFeedmillGrid',
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
        
        commGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        commGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-commercial-feedmill', function(event){  
        event.preventDefault();
        var formID = '#add-commercial-feedmill';
        var modalID = '#modal-add-commercial-feedmill';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-commercial-feedmill/',
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
                    commGrid.ajax.reload(null, false);
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


    $(document).on('click', '.edit-commercial-feedmill', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-commercial-feedmill/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-commercial-feedmill').find('#id').val(id);
                    $('#update-commercial-feedmill').find('#feedmillCode').val(parse_response['info'].feedmill_code);
                    $('#update-commercial-feedmill').find('#feedmillName').val(parse_response['info'].feedmill_name);
                    $('#update-commercial-feedmill').find('#internalFeedmill').val(parse_response['info'].internal_feedmill_id).trigger('change');
                    $('#modal-edit-commercial-feedmill').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-commercial-feedmill', function(event){  
        event.preventDefault();
        var formID = '#update-commercial-feedmill';
        var modalID = '#modal-edit-commercial-feedmill';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-commercial-feedmill/',
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
                    commGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-commercial-feedmill').find('#id').val(id);
        $('#activate-commercial-feedmill').find('#val').html(val);
        $('#modal-active-commercial-feedmill').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-commercial-feedmill').find('#id').val(id);
        $('#deactivate-commercial-feedmill').find('#val').html(val);
        $('#modal-deactivate-commercial-feedmill').modal({show:true});
    });

    
    $(document).on('submit', '#deactivate-commercial-feedmill', function(event){  
        event.preventDefault();
        var formID = '#deactivate-commercial-feedmill';
        var modalID = '#modal-deactivate-commercial-feedmill';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-commercial-feedmill/',
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
                    commGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-commercial-feedmill', function(event){  
        event.preventDefault();
        var formID = '#activate-commercial-feedmill';
        var modalID = '#modal-active-commercial-feedmill';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-commercial-feedmill/',
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
                     commGrid.ajax.reload(null, false);
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

    
    //END OF COMMERCIAL FEEDMILL SCRIPT


    //INTERNAL FEEDMILL SCRIPT

    $(document).on('click', '.add-internal-feedmill', function(e){
        var formID = '#add-internal-feedmill';
        var modalID = '#modal-add-internal-feedmill';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var internalGrid = $('#tbl-internal-feedmill').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/internalFeedmillGrid',
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
        
        internalGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        internalGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-internal-feedmill', function(event){  
        event.preventDefault();
        var formID = '#add-internal-feedmill';
        var modalID = '#modal-add-internal-feedmill';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-internal-feedmill/',
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
                    internalGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-internal-feedmill', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-internal-feedmill/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-internal-feedmill').find('#id').val(id);
                    $('#update-internal-feedmill').find('#feedmillCode').val(parse_response['info'].feedmill_code);
                    $('#update-internal-feedmill').find('#feedmillName').val(parse_response['info'].feedmill_name);
                    $('#modal-edit-internal-feedmill').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-internal-feedmill', function(event){  
        event.preventDefault();
        var formID = '#update-internal-feedmill';
        var modalID = '#modal-edit-internal-feedmill';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-internal-feedmill/',
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
                    internalGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-internal-feedmill').find('#id').val(id);
        $('#activate-internal-feedmill').find('#val').html(val);
        $('#modal-active-internal-feedmill').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-internal-feedmill').find('#id').val(id);
        $('#deactivate-internal-feedmill').find('#val').html(val);
        $('#modal-deactivate-internal-feedmill').modal({show:true});
    });

    
    $(document).on('submit', '#deactivate-internal-feedmill', function(event){  
        event.preventDefault();
        var formID = '#deactivate-internal-feedmill';
        var modalID = '#modal-deactivate-internal-feedmill';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-internal-feedmill/',
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
                    internalGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-internal-feedmill', function(event){  
        event.preventDefault();
        var formID = '#activate-internal-feedmill';
        var modalID = '#modal-active-internal-feedmill';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-internal-feedmill/',
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
                     internalGrid.ajax.reload(null, false);
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

    //END OF INTERNAL FEEDMILL SCRIPT

    
      //ANIMAL FEED SCRIPT

    $(document).on('click', '.add-animal-feed', function(e){
        var formID = '#add-animal-feed';
        var modalID = '#modal-add-animal-feed';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var animalfeedGrid = $('#tbl-animal-feed').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/animalFeedGrid',
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
        
        animalfeedGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        animalfeedGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-animal-feed', function(event){  
        event.preventDefault();
        var formID = '#add-animal-feed';
        var modalID = '#modal-add-animal-feed';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-animal-feed/',
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
                    animalfeedGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-animal-feed', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-animal-feed/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-animal-feed').find('#id').val(id);
                    $('#update-animal-feed').find('#feedsName').val(parse_response['info'].feeds_name);
                    $('#modal-edit-animal-feed').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-animal-feed', function(event){  
        event.preventDefault();
        var formID = '#update-animal-feed';
        var modalID = '#modal-edit-animal-feed';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-animal-feed/',
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
                    animalfeedGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-animal-feed').find('#id').val(id);
        $('#activate-animal-feed').find('#val').html(val);
        $('#modal-active-animal-feed').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-animal-feed').find('#id').val(id);
        $('#deactivate-animal-feed').find('#val').html(val);
        $('#modal-deactivate-animal-feed').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-animal-feed', function(event){  
        event.preventDefault();
        var formID = '#deactivate-animal-feed';
        var modalID = '#modal-deactivate-animal-feed';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-animal-feed/',
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
                    animalfeedGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-animal-feed', function(event){  
        event.preventDefault();
        var formID = '#activate-animal-feed';
        var modalID = '#modal-active-animal-feed';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-animal-feed/',
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
                     animalfeedGrid.ajax.reload(null, false);
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

    //END OF ANIMAL FEED SCRIPT


    //TEST PARAMETER SCRIPT

    $(document).on('click', '.add-test-parameter', function(e){
        var formID = '#add-test-parameter';
        var modalID = '#modal-add-test-parameter';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var testparameterGrid = $('#tbl-test-parameter').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/testParameterGrid',
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
        
        testparameterGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        testparameterGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-test-parameter', function(event){  
        event.preventDefault();
        var formID = '#add-test-parameter';
        var modalID = '#modal-add-test-parameter';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-test-parameter/',
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
                    testparameterGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-test-parameter', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-test-parameter/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-test-parameter').find('#id').val(id);
                    $('#update-test-parameter').find('#paramName').val(parse_response['info'].param_name);
                    $('#modal-edit-test-parameter').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-test-parameter', function(event){  
        event.preventDefault();
        var formID = '#update-test-parameter';
        var modalID = '#modal-edit-test-parameter';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-test-parameter/',
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
                    testparameterGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-test-parameter').find('#id').val(id);
        $('#activate-test-parameter').find('#val').html(val);
        $('#modal-active-test-parameter').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-test-parameter').find('#id').val(id);
        $('#deactivate-test-parameter').find('#val').html(val);
        $('#modal-deactivate-test-parameter').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-test-parameter', function(event){  
        event.preventDefault();
        var formID = '#deactivate-test-parameter';
        var modalID = '#modal-deactivate-test-parameter';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-test-parameter/',
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
                    testparameterGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-test-parameter', function(event){  
        event.preventDefault();
        var formID = '#activate-test-parameter';
        var modalID = '#modal-active-test-parameter';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-test-parameter/',
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
                     testparameterGrid.ajax.reload(null, false);
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

    //END OF TEST PARAMETER SCRIPT

    //TEST NAME SCRIPT

    $(document).on('click', '.add-test-name', function(e){
        var formID = '#add-test-name';
        var modalID = '#modal-add-test-name';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var testnameGrid = $('#tbl-test-name').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/testNameGrid',
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
        
        testnameGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        testnameGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-test-name', function(event){  
        event.preventDefault();
        var formID = '#add-test-name';
        var modalID = '#modal-add-test-name';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-test-name/',
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
                    testnameGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-test-name', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-test-name/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-test-name').find('#id').val(id);
                    $('#update-test-name').find('#testName').val(parse_response['info'].name);
                    $('#modal-edit-test-name').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-test-name', function(event){  
        event.preventDefault();
        var formID = '#update-test-name';
        var modalID = '#modal-edit-test-name';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-test-name/',
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
                    testnameGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-test-name').find('#id').val(id);
        $('#activate-test-name').find('#val').html(val);
        $('#modal-active-test-name').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-test-name').find('#id').val(id);
        $('#deactivate-test-name').find('#val').html(val);
        $('#modal-deactivate-test-name').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-test-name', function(event){  
        event.preventDefault();
        var formID = '#deactivate-test-name';
        var modalID = '#modal-deactivate-test-name';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-test-name/',
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
                    testnameGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-test-name', function(event){  
        event.preventDefault();
        var formID = '#activate-test-name';
        var modalID = '#modal-active-test-name';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-test-name/',
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
                     testnameGrid.ajax.reload(null, false);
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

    //END OF TEST NAME SCRIPT


    
    //PROFESSION SCRIPT

    $(document).on('click', '.add-professions', function(e){
        var formID = '#add-professions';
        var modalID = '#modal-add-professions';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var professionGrid = $('#tbl-professions').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/professionGrid',
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
        
        professionGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        professionGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-professions', function(event){  

        event.preventDefault();
        var formID = '#add-professions';
        var modalID = '#modal-add-professions';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-professions/',
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
                    professionGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-professions', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-professions/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-professions').find('#id').val(id);
                    $('#update-professions').find('#professionName').val(parse_response['info'].name);
                    $('#modal-edit-professions').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-professions', function(event){  
        event.preventDefault();
        var formID = '#update-professions';
        var modalID = '#modal-edit-professions';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-professions/',
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
                    professionGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-professions').find('#id').val(id);
        $('#activate-professions').find('#val').html(val);
        $('#modal-active-professions').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-professions').find('#id').val(id);
        $('#deactivate-professions').find('#val').html(val);
        $('#modal-deactivate-professions').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-professions', function(event){  
        event.preventDefault();
        var formID = '#deactivate-professions';
        var modalID = '#modal-deactivate-professions';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-professions/',
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
                    professionGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-professions', function(event){  
        event.preventDefault();
        var formID = '#activate-professions';
        var modalID = '#modal-active-professions';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-professions/',
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
                     professionGrid.ajax.reload(null, false);
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

    //END OF PROFESSION SCRIPT





    //TEST METHOD SCRIPT

    $(document).on('click', '.add-test-method', function(e){
        var formID = '#add-test-method';
        var modalID = '#modal-add-test-method';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var testmethodGrid = $('#tbl-test-method').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/testMethodGrid',
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
        
        testmethodGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        testmethodGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-test-method', function(event){  
        event.preventDefault();
        var formID = '#add-test-method';
        var modalID = '#modal-add-test-method';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-test-method/',
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
                    testmethodGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-test-method', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-test-method/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-test-method').find('#id').val(id);
                    $('#update-test-method').find('#methodName').val(parse_response['info'].method_name);
                    $('#modal-edit-test-method').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-test-method', function(event){  
        event.preventDefault();
        var formID = '#update-test-method';
        var modalID = '#modal-edit-test-method';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-test-method/',
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
                    testmethodGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-test-method').find('#id').val(id);
        $('#activate-test-method').find('#val').html(val);
        $('#modal-active-test-method').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-test-method').find('#id').val(id);
        $('#deactivate-test-method').find('#val').html(val);
        $('#modal-deactivate-test-method').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-test-method', function(event){  
        event.preventDefault();
        var formID = '#deactivate-test-method';
        var modalID = '#modal-deactivate-test-method';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-test-method/',
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
                    testmethodGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-test-method', function(event){  
        event.preventDefault();
        var formID = '#activate-test-method';
        var modalID = '#modal-active-test-method';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-test-method/',
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
                     testmethodGrid.ajax.reload(null, false);
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

    //END OF TEST METHOD SCRIPT

    //REFERENCE METHOD SCRIPT
    $(document).on('click', '.add-ref-method', function(e){
        var formID = '#add-ref-method';
        var modalID = '#modal-add-ref-method';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var refmethodGrid = $('#tbl-ref-method').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/refMethodGrid',
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
        
        refmethodGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        refmethodGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-ref-method', function(event){  
        event.preventDefault();
        var formID = '#add-ref-method';
        var modalID = '#modal-add-ref-method';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-ref-method/',
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
                    refmethodGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-ref-method', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-ref-method/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-ref-method').find('#id').val(id);
                    $('#update-ref-method').find('#methodName').val(parse_response['info'].method_name);
                    $('#modal-edit-ref-method').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-ref-method', function(event){  
        event.preventDefault();
        var formID = '#update-ref-method';
        var modalID = '#modal-edit-ref-method';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-ref-method/',
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
                    refmethodGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-ref-method').find('#id').val(id);
        $('#activate-ref-method').find('#val').html(val);
        $('#modal-active-ref-method').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-ref-method').find('#id').val(id);
        $('#deactivate-ref-method').find('#val').html(val);
        $('#modal-deactivate-ref-method').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-ref-method', function(event){  
        event.preventDefault();
        var formID = '#deactivate-ref-method';
        var modalID = '#modal-deactivate-ref-method';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-ref-method/',
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
                    refmethodGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-ref-method', function(event){  
        event.preventDefault();
        var formID = '#activate-ref-method';
        var modalID = '#modal-active-ref-method';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-ref-method/',
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
                     refmethodGrid.ajax.reload(null, false);
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

    //END OF TEST METHOD SCRIPT








    //LABORATORY GROUP SCRIPT

    $(document).on('click', '.add-lab-test-group', function(e){
        var formID = '#add-lab-test-group';
        var modalID = '#modal-add-lab-test-group';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var laboratorygroupGrid = $('#tbl-lab-test-group').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/laboratoryGroupGrid',
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
        
        laboratorygroupGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        laboratorygroupGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-lab-test-group', function(event){  
        event.preventDefault();
        var formID = '#add-lab-test-group';
        var modalID = '#modal-lab-test-group';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-lab-test-group/',
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
                    laboratorygroupGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-lab-test-group', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-lab-test-group/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-lab-test-group').find('#id').val(id);
                    $('#update-lab-test-group').find('#groupName').val(parse_response['info'].group_name);
                    $('#modal-edit-lab-test-group').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-lab-test-group', function(event){  
        event.preventDefault();
        var formID = '#update-lab-test-group';
        var modalID = '#modal-edit-lab-test-group';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-lab-test-group/',
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
                    laboratorygroupGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-lab-test-group').find('#id').val(id);
        $('#activate-lab-test-group').find('#val').html(val);
        $('#modal-active-lab-test-group').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-lab-test-group').find('#id').val(id);
        $('#deactivate-lab-test-group').find('#val').html(val);
        $('#modal-deactivate-lab-test-group').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-lab-test-group', function(event){  
        event.preventDefault();
        var formID = '#deactivate-lab-test-group';
        var modalID = '#modal-deactivate-lab-test-group';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-lab-test-group/',
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
                    laboratorygroupGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-lab-test-group', function(event){  
        event.preventDefault();
        var formID = '#activate-lab-test-group';
        var modalID = '#modal-active-lab-test-group';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-lab-test-group/',
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
                     laboratorygroupGrid.ajax.reload(null, false);
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
    //END OF LABORATORY GROUP SCRIPT

    // NUTRITIONIST SCRIPT

    $(document).on('click', '.add-nutritionist', function(e){
        var formID = '#add-nutritionist';
        var modalID = '#modal-add-nutritionist';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var nutritionistGrid = $('#tbl-nutritionist').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/nutritionistGrid',
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
        
        nutritionistGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        nutritionistGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#add-nutritionist';
        var modalID = '#modal-add-nutritionist';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-nutritionist/',
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
                    nutritionistGrid.ajax.reload(null, false);
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
    
    $(document).on('click', '.edit-nutritionist', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-nutritionist/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-nutritionist').find('#id').val(id);
                    $('#update-nutritionist').find('#nutritionistName').val(parse_response['info'].nutritionist_name);
                    $('#update-nutritionist').find('#emailAddress').val(parse_response['info'].email_address);
                    $('#modal-edit-nutritionist').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#update-nutritionist';
        var modalID = '#modal-edit-nutritionist';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-nutritionist/',
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
                    nutritionistGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-nutritionist').find('#id').val(id);
        $('#activate-nutritionist').find('#val').html(val);
        $('#modal-active-nutritionist').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-nutritionist').find('#id').val(id);
        $('#deactivate-nutritionist').find('#val').html(val);
        $('#modal-deactivate-nutritionist').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#deactivate-nutritionist';
        var modalID = '#modal-deactivate-nutritionist';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-nutritionist/',
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
                    nutritionistGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#activate-nutritionist';
        var modalID = '#modal-active-nutritionist';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-nutritionist/',
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
                     nutritionistGrid.ajax.reload(null, false);
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
    //END OF NUTRITIONIST SCRIPT


    // BATCH NUMBER SCRIPT

    $(document).on('click', '.add-batch-number', function(e){
        var formID = '#add-batch-number';
        var modalID = '#modal-add-batch-number';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var batchnumberGrid = $('#tbl-batch-number').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/batchNumberGrid',
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
        
        batchnumberGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        batchnumberGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-batch-number', function(event){  
        event.preventDefault();
        var formID = '#add-batch-number';
        var modalID = '#modal-add-batch-number';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-batch-number/',
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
                    batchnumberGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-batch-number', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-batch-number/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-batch-number').find('#id').val(id);
                    $('#update-batch-number').find('#batchNumber').val(parse_response['info'].batch_number);
                    $('#modal-edit-batch-number').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-batch-number', function(event){  
        event.preventDefault();
        var formID = '#update-batch-number';
        var modalID = '#modal-edit-batch-number';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-batch-number/',
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
                    batchnumberGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-batch-number').find('#id').val(id);
        $('#activate-batch-number').find('#val').html(val);
        $('#modal-active-batch-number').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-batch-number').find('#id').val(id);
        $('#deactivate-batch-number').find('#val').html(val);
        $('#modal-deactivate-batch-number').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-batch-number', function(event){  
        event.preventDefault();
        var formID = '#deactivate-batch-number';
        var modalID = '#modal-deactivate-batch-number';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-batch-number/',
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
                    batchnumberGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-batch-number', function(event){  
        event.preventDefault();
        var formID = '#activate-batch-number';
        var modalID = '#modal-active-batch-number';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-batch-number/',
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
                     batchnumberGrid.ajax.reload(null, false);
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

    //END OF BATCH NUMBER SCRIPT

    // PLATE NUMBER SCRIPT

    $(document).on('click', '.add-plate-number', function(e){
        var formID = '#add-plate-number';
        var modalID = '#modal-add-plate-number';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var platenumberGrid = $('#tbl-plate-number').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/plateNumberGrid',
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
        
        platenumberGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        platenumberGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-plate-number', function(event){  
        event.preventDefault();
        var formID = '#add-plate-number';
        var modalID = '#modal-add-plate-number';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-plate-number/',
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
                    platenumberGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-plate-number', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-plate-number/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-plate-number').find('#id').val(id);
                    $('#update-plate-number').find('#plateNumber').val(parse_response['info'].plate_number);
                    $('#modal-edit-plate-number').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-plate-number', function(event){  
        event.preventDefault();
        var formID = '#update-plate-number';
        var modalID = '#modal-edit-plate-number';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-plate-number/',
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
                    platenumberGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-plate-number').find('#id').val(id);
        $('#activate-plate-number').find('#val').html(val);
        $('#modal-active-plate-number').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-plate-number').find('#id').val(id);
        $('#deactivate-plate-number').find('#val').html(val);
        $('#modal-deactivate-plate-number').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-plate-number', function(event){  
        event.preventDefault();
        var formID = '#deactivate-plate-number';
        var modalID = '#modal-deactivate-plate-number';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-plate-number/',
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
                    platenumberGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-plate-number', function(event){  
        event.preventDefault();
        var formID = '#activate-plate-number';
        var modalID = '#modal-active-plate-number';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-plate-number/',
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
                     platenumberGrid.ajax.reload(null, false);
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

    //END OF PLATE NUMBER SCRIPT

    // TEST CODE SCRIPT

    $(document).on('click', '.add-test', function(e){
        var formID = '#add-test';
        var modalID = '#modal-add-test';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var testGrid = $('#tbl-test').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/testGrid',
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
        
        testGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        testGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-test', function(event){  
        event.preventDefault();
        var formID = '#add-test';
        var modalID = '#modal-add-test';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-test/',
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
                    testGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-test', function(e) {
        e.preventDefault();
        var id = $(this).attr('data-id');   
        
        $('#loader-div').removeClass('loaded');

        $.ajax({
            url: base_url + 'admin/modal-test/',
            data: { id: id },
            method: 'POST',
            success: function(response) {
                var parse_response = JSON.parse(response);
                
                if (parse_response['result'] == 1) {
                    $('#update-test').find('#id').val(id);

                    $('#update-test').find('#testName').empty();
                    $('#update-test').find('#testName').append(parse_response['info'].test_name_id);

                    $('#update-test').find('#testCode').val(parse_response['info'].test_code);

                    $('#modal-edit-test').modal({ show: true });
                } else {
                    console.log('Error please contact your administrator.');
                }

                $('#loader-div').addClass('loaded');
            }
        });
    });


    $(document).on('submit', '#update-test', function(event){  
        event.preventDefault();
        var formID = '#update-test';
        var modalID = '#modal-edit-test';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-test/',
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
                    testGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-test').find('#id').val(id);
        $('#activate-test').find('#val').html(val);
        $('#modal-active-test').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-test').find('#id').val(id);
        $('#deactivate-test').find('#val').html(val);
        $('#modal-deactivate-test').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-test', function(event){  
        event.preventDefault();
        var formID = '#deactivate-test';
        var modalID = '#modal-deactivate-test';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-test/',
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
                    testGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-test', function(event){  
        event.preventDefault();
        var formID = '#activate-test';
        var modalID = '#modal-active-test';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-test/',
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
                     testGrid.ajax.reload(null, false);
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



    //END OF TEST CODE SCRIPT




        // USER PROFESSION SCRIPT

    $(document).on('click', '.add-user-professions', function(e){
        var formID = '#add-user-professions';
        var modalID = '#modal-add-user-professions';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });




    var userprofessionsGrid = $('#tbl-user-professions').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/userProfessionsGrid',
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
        
        userprofessionsGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        userprofessionsGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-user-professions', function(event){  
        event.preventDefault();
        var formID = '#add-user-professions';
        var modalID = '#modal-add-user-professions';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-user-professions/',
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
                    userprofessionsGrid.ajax.reload(null, false);
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

    // $(document).on('click', '.edit-user-professions', function(e) {
    //     e.preventDefault();
    //     var id = $(this).attr('data-id');   
        
        
    //     $('#loader-div').removeClass('loaded');

    //     $.ajax({
    //         url: base_url + 'admin/modal-user-professions/',
    //         data: { id: id },
    //         method: 'POST',
    //         success: function(response) {
    //             var parse_response = JSON.parse(response);
    //             if (parse_response['result'] == 1) {
    //                 $('#update-user-professions').find('#id').val(id);
    //                 $('#update-user-professions').find('#edit_userID').val(parse_response['info'].userID).trigger('change'); 
    //                 $('#update-user-professions').find('#professionID').val(parse_response['info'].profession_id);
    //                 $('#update-user-professions').find('#LicenseNo').val(parse_response['info'].license_no);
    //                 $('#update-user-professions').find('#ValidUntil').val(parse_response['info'].license_valid);
                    
    //                 $('#modal-edit-user-professions').modal({ show: true });
    //             } else {
    //                 console.log('Error please contact your administrator.');
    //             }

    //             $('#loader-div').addClass('loaded');
    //         }
    //     });
    // });


    $(document).on('click', '.edit-user-professions', function(e) {
    e.preventDefault();
    var id = $(this).attr('data-id');   
    
    $('#loader-div').removeClass('loaded');

    $.ajax({
        url: base_url + 'admin/modal-user-professions/',
        data: { id: id },
        method: 'POST',
        success: function(response) {
            var parse_response = JSON.parse(response);
            if (parse_response['result'] == 1) {
                var form = $('#update-user-professions');
                form.find('#id').val(id);
                form.find('#edit_userID').val(parse_response['info'].userID).trigger('change'); 
                form.find('#professionID').val(parse_response['info'].profession_id);
                form.find('#LicenseNo').val(parse_response['info'].license_no);
                form.find('#ValidUntil').val(parse_response['info'].license_valid);
                var assignedLabs = (parse_response['info'].labs || []).map(String); 
                        form.find('input[name="laboratories[]"]').each(function(){
                        var labId = $(this).val(); 
                        $(this).prop('checked', assignedLabs.includes(labId));
                    });
                $('#modal-edit-user-professions').modal({ show: true });
            } else {
                console.log('Error please contact your administrator.');
            }

            $('#loader-div').addClass('loaded');
        }
    });
});


    $(document).on('submit', '#update-user-professions', function(event){  
        event.preventDefault();
        var formID = '#update-user-professions';
        var modalID = '#modal-edit-user-professions';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-user-professions/',
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
                    userprofessionsGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-user-professions').find('#id').val(id);
        $('#activate-user-professions').find('#val').html(val);
        $('#modal-active-user-professions').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-user-professions').find('#id').val(id);
        $('#deactivate-user-professions').find('#val').html(val);
        $('#modal-deactivate-user-professions').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-user-professions', function(event){  
        event.preventDefault();
        var formID = '#deactivate-user-professions';
        var modalID = '#modal-deactivate-user-professions';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-user-professions/',
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
                    userprofessionsGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-user-professions', function(event){  
        event.preventDefault();
        var formID = '#activate-user-professions';
        var modalID = '#modal-active-user-professions';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-user-professions/',
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
                     userprofessionsGrid.ajax.reload(null, false);
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



    //END OF TEST CODE SCRIPT


    
    // TRANSACTION REASON SCRIPT

    $(document).on('click', '.add-reason', function(e){
        var formID = '#add-test';
        var modalID = '#modal-add-reason';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var reasonGrid = $('#tbl-reason').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/reasonGrid',
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
        
        reasonGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        reasonGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });


    $(document).on('submit', '#add-reason', function(event){  
        event.preventDefault();
        var formID = '#add-reason';
        var modalID = '#modal-add-reason';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-reason/',
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
                    reasonGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-reason', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');   
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-reason/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-reason').find('#id').val(id);
                    $('#update-reason').find('#reasonName').val(parse_response['info'].reason_name);
                    $('#modal-edit-reason').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-reason', function(event){  
        event.preventDefault();
        var formID = '#update-reason';
        var modalID = '#modal-edit-reason';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-reason/',
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
                    reasonGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-reason').find('#id').val(id);
        $('#activate-reason').find('#val').html(val);
        $('#modal-active-reason').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-reason').find('#id').val(id);
        $('#deactivate-reason').find('#val').html(val);
        $('#modal-deactivate-reason').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-reason', function(event){  
        event.preventDefault();
        var formID = '#deactivate-reason';
        var modalID = '#modal-deactivate-reason';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-reason/',
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
                    reasonGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-reason', function(event){  
        event.preventDefault();
        var formID = '#activate-reason';
        var modalID = '#modal-active-reason';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-reason/',
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
                     reasonGrid.ajax.reload(null, false);
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

    //END OF TRANSACTION REASON SCRIPT

        



    //ANIMAL NUTRIONIONIST SCRIPT

    $(document).on('click', '.add-animal-nutritionist', function(e){
        var formID = '#add-animal-nutritionist';
        var modalID = '#modal-add-animal-nutritionist';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var animalNutritionistGrid = $('#tbl-animal-nutritionist').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/animalNutritionistGrid',
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
        
        animalNutritionistGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        animalNutritionistGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-animal-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#add-animal-nutritionist';
        var modalID = '#modal-add-animal-nutritionist';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-animal-nutritionist/',
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
                    animalNutritionistGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-animal-nutritionist', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-animal-nutritionist/',
            data: {id:id},
            method: 'POST',
            success:function(response){

                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-animal-nutritionist').find('#id').val(id);
                    $('#update-animal-nutritionist').find('#animalFeed').val(parse_response['info'].animal_feed_id);
                    $('#update-animal-nutritionist').find('#nutritionistName').val(parse_response['info'].nutritionist_id);
                    $('#update-animal-nutritionist').find('#locationName').val(parse_response['info'].location_id).trigger('change');
                    $('#modal-edit-animal-nutritionist').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-animal-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#update-animal-nutritionist';
        var modalID = '#modal-edit-animal-nutritionist';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-animal-nutritionist/',
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
                    animalNutritionistGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-animal-nutritionist').find('#id').val(id);
        $('#activate-animal-nutritionist').find('#val').html(val);
        $('#modal-active-animal-nutritionist').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-animal-nutritionist').find('#id').val(id);
        $('#deactivate-animal-nutritionist').find('#val').html(val);
        $('#modal-deactivate-animal-nutritionist').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-animal-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#deactivate-animal-nutritionist';
        var modalID = '#modal-deactivate-animal-nutritionist';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-animal-nutritionist/',
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
                    animalNutritionistGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-animal-nutritionist', function(event){  
        event.preventDefault();
        var formID = '#activate-animal-nutritionist';
        var modalID = '#modal-active-animal-nutritionist';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-animal-nutritionist/',
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
                     animalNutritionistGrid.ajax.reload(null, false);
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


    //END OF ANIMAL NUTRITIONIST SCRIPT



            
    //LABORATORY TEST SCRIPT

    $(document).on('click', '.add-lab-test', function(e){
        var formID = '#add-lab-test';
        var modalID = '#modal-add-lab-test';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var labtestGrid = $('#tbl-lab-test').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/labTestGrid',
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
        
        labtestGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        labtestGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

     $(document).on('submit', '#add-lab-test', function(event){  
        event.preventDefault();
        var formID = '#add-lab-test';
        var modalID = '#modal-add-lab-test';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-lab-test/',
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
                    labtestGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-lab-test', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-lab-test/',
            data: {id:id},
            method: 'POST',
            success:function(response){

                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-lab-test').find('#id').val(id);
                    $('#update-lab-test').find('#labtestGroup').val(parse_response['info'].lab_test_grouping_id);
                    $('#update-lab-test').find('#testCode').val(parse_response['info'].test_id);
                    $('#update-lab-test').find('#methodName').val(parse_response['info'].test_method_id);
                    $('#update-lab-test').find('#paramName').val(parse_response['info'].test_param_id);
                    $('#update-lab-test').find('#analystName').val(parse_response['info'].analyst_id);
                    $('#update-lab-test').find('#sampleTypes').val(parse_response['info'].sample_type_id);
                    $('#update-lab-test').find('#refMethod').val(parse_response['info'].ref_method_id);
                    $('#update-lab-test').find('#labName').val(parse_response['info'].laboratory_id);
                    $('#update-lab-test').find('#leadRush').val(parse_response['info'].lead_rush);
                    $('#update-lab-test').find('#leadRegular').val(parse_response['info'].lead_regular);
                    $('#modal-edit-lab-test').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-lab-test', function(event){  
        event.preventDefault();
        var formID = '#update-lab-test';
        var modalID = '#modal-edit-lab-test';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-lab-test/',
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
                    labtestGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-lab-test').find('#id').val(id);
        $('#activate-lab-test').find('#val').html(val);
        $('#modal-active-lab-test').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-lab-test').find('#id').val(id);
        $('#deactivate-lab-test').find('#val').html(val);
        $('#modal-deactivate-lab-test').modal({show:true});
    });
    
    $(document).on('submit', '#deactivate-lab-test', function(event){  
        event.preventDefault();
        var formID = '#deactivate-lab-test';
        var modalID = '#modal-deactivate-lab-test';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-lab-test/',
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
                    labtestGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-lab-test', function(event){  
        event.preventDefault();
        var formID = '#activate-lab-test';
        var modalID = '#modal-active-lab-test';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-lab-test/',
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
                     labtestGrid.ajax.reload(null, false);
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


    //LABORATORIES SCRIPT

    $(document).on('click', '.add-laboratories', function(e){
        var formID = '#add-laboratories';
        var modalID = '#modal-add-laboratories';
        $(modalID).modal({show:true});
        $(formID)[0].reset();
        $(formID).find('select').val('').trigger('change');
    });

    var labGrid = $('#tbl-laboratories').DataTable({
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
            { responsivePriority: 4, targets: -2 }
        ],
        "order": [],
        select : true,
        "lengthMenu": [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, "All"]],
        "ajax": {
            url : base_url+'admin/laboratoriesGrid',
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
        
        labGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        labGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });

    $(document).on('submit', '#add-laboratories', function(event){  
        event.preventDefault();
        var formID = '#add-laboratories';
        var modalID = '#modal-add-laboratories';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-laboratories/',
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
                    labGrid.ajax.reload(null, false);
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

    $(document).on('click', '.edit-laboratories', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-laboratories/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                if(parse_response['result'] == 1){
                    $('#update-laboratories').find('#id').val(id);
                    $('#update-laboratories').find('#identifierCode').val(parse_response['info'].identifier_code);
                    $('#update-laboratories').find('#laboratoryName').val(parse_response['info'].laboratory_name);
                    $('#update-laboratories').find('#addressName').val(parse_response['info'].address);
                    $('#modal-edit-laboratories').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });

    $(document).on('submit', '#update-laboratories', function(event){  
        event.preventDefault();
        var formID = '#update-laboratories';
        var modalID = '#modal-edit-laboratories';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-laboratories/',
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
                    labGrid.ajax.reload(null, false);
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
        var val = $(this).attr('data-val');
        
        $('#activate-laboratories').find('#id').val(id);
        $('#activate-laboratories').find('#val').html(val);
        $('#modal-active-laboratories').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var val = $(this).attr('data-val');
        
        $('#deactivate-laboratories').find('#id').val(id);
        $('#deactivate-laboratories').find('#val').html(val);
        $('#modal-deactivate-laboratories').modal({show:true});
    });

    
    $(document).on('submit', '#deactivate-laboratories', function(event){  
        event.preventDefault();
        var formID = '#deactivate-laboratories';
        var modalID = '#modal-deactivate-laboratories';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-laboratories/',
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
                    labGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-laboratories', function(event){  
        event.preventDefault();
        var formID = '#activate-laboratories';
        var modalID = '#modal-active-laboratories';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-laboratories/',
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
                     labGrid.ajax.reload(null, false);
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

    //END OF INTERNAL FEEDMILL SCRIPT


     

    //KEY SCRIPT
    $(document).on('click', '.add-key', function(e){
        

        var formID = '#add-key';
        var modalID = '#modal-add-key';
        $(modalID).modal({show:true});
        $(formID)[0].reset();

        $(formID).find('select').val('').trigger('change');
    });

    $(document).on('change', '.users-role-for-key', function(e){
        e.preventDefault();

        var id = $(this).val();
        var keyID = null;
		
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/get-users-array',
            data:{id:id, keyID:keyID},
            method: 'POST',
            success:function(response){
                console.log(response);
             
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    $('.users-list').empty();
                    $('.users-list').append(parse_response['info']);
                }else{
                    $('.users-list').empty();
                    //console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
			
            }
        });
    });

    $(document).on('change', '.users-role-for-key-update', function(e){
        e.preventDefault();

        var id = $(this).val();
        var keyID = $('#update-key').find('#id').val();

        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/get-users-array',
            data:{id:id, keyID:keyID},
            method: 'POST',
            success:function(response){
                console.log(response);
             
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    $('.users-list').empty();
                    $('.users-list').append(parse_response['info']);
                }else{
                    $('.users-list').empty();
                    //console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
			
            }
        });
    });

    var keyGrid = $('#tbl-key').DataTable({
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
            url : base_url+'admin/keyGrid',
            type : 'GET'
        },
        buttons: [
            {
                extend: 'excel',
                messageTop: 'Run Date : '+date,
                customize: function( xlsx ) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    //$('row:first c', sheet).attr( 's', '42' );
                    //$('row c[r*="3"]', sheet).attr('s', expDtColor);
                },
                autoFilter: true
            }
        ]
    });

    $(document).on('click', '.refresh-dt', function(e){
        
        keyGrid.ajax.reload(null, false);
    });

    $(document).on('click', '.print-dt', function(e){
        
        keyGrid.button( '.buttons-excel' ).trigger();
        //alert('hello');
    });
    
    $(document).on('submit', '#add-key', function(event){  
        event.preventDefault();
        var formID = '#add-key';
        var modalID = '#modal-add-key';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/add-key/',
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
                    keyGrid.ajax.reload(null, false);
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
    
    $(document).on('click', '.edit-key', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/modal-key/',
            data: {id:id},
            method: 'POST',
            success:function(response){
                var parse_response = JSON.parse(response);
                
                if(parse_response['result'] == 1){
                    
                    $('#update-key').find('#id').val(id);
                    
                    $('#update-key').find('#bcID').empty();
                    $('#update-key').find('#bcID').append(parse_response['info'].bcID);

                    $('#update-key').find('#keyCode').val(parse_response['info'].keyCode);
                    $('#update-key').find('#keyCode2').val(parse_response['info'].keyCode2);
                    $('#update-key').find('.select2').trigger('click');
                    
                    $('#modal-edit-key').modal({show:true});
                }else{
                    console.log('Error please contact your administrator.');
                }
                $('#loader-div').addClass('loaded');
            }
        });
    });
    
    $(document).on('submit', '#update-key', function(event){  
        event.preventDefault();
        var formID = '#update-key';
        var modalID = '#modal-edit-key';
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/update-key/',
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
                    keyGrid.ajax.reload(null, false);
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
        var keyID = $(this).attr('data-key-id');
        
        
        $('#activate-key').find('#id').val(id);
        $('#activate-key').find('#keyID').val(keyID);
        $('#modal-active-key').modal({show:true});
    });

    $(document).on('click', '.toggle-active', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        var keyID = $(this).attr('data-key-id');

        
        $('#deactivate-key').find('#id').val(id);
        $('#deactivate-key').find('#keyID').val(keyID);
        $('#modal-deactivate-key').modal({show:true});
    });

    $(document).on('submit', '#deactivate-key', function(event){  
        event.preventDefault();
        var formID = '#deactivate-key';
        var modalID = '#modal-deactivate-key';
        
        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/deactivate-key/',
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
                    keyGrid.ajax.reload(null, false);
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

    $(document).on('submit', '#activate-key', function(event){  
        event.preventDefault();
        var formID = '#activate-key';
        var modalID = '#modal-active-key';

        $('#loader-div').removeClass('loaded');
        $.ajax({
            url: base_url + 'admin/activate-key/',
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
                    keyGrid.ajax.reload(null, false);
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
    //END OF KEY SCRIPT
    

    //HIDING UI
    $("div.add-access").hide();
    $("#additional-access").click(function() {
        if($(this).is(":checked"))
        {
            $("div.add-access").show();
            $("div.update-access").hide();
            
        } else {
            $("div.add-access").hide();
            $("div.update-access").show();
        }
    });
    $("#additional-access-2").click(function() {
        if($(this).is(":checked"))
        {
            $("div.add-access").show();
            $("div.update-access").hide();
        } else {
            $("div.add-access").hide();
            $("div.update-access").show();
        }
    });
    $("#additional-access-3").click(function() {
        if($(this).is(":checked"))
        {
            $("div.add-access").show();
            $("div.update-access").hide();
        } else {
            $("div.add-access").hide();
            $("div.update-access").show();
        }
    });


    //SELECTING ALL
    $('.users-role-for-key').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".users-role-for-key > option").prop("selected","selected");
            $(".users-role-for-key").trigger("change");
        }
        $(".users-role-for-key option[value=-1]").prop("selected", false).parent().trigger("change");
    });

    $('.users-role-for-key-update').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".users-role-for-key-update > option").prop("selected","selected");
            $(".users-role-for-key-update").trigger("change");
        }
        $(".users-role-for-key-update option[value=-1]").prop("selected", false).parent().trigger("change");
    });

	$('.users-list').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".users-list > option").prop("selected","selected");
            $(".users-list").trigger("change");
        }
        $(".users-list option[value=-1]").prop("selected", false).parent().trigger("change");
    });
    $('.key').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".key > option").prop("selected","selected");
            $(".key").trigger("change");
        }
        $(".key option[value=-1]").prop("selected", false).parent().trigger("change");
    });
    $('.bc').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".bc > option").prop("selected","selected");
            $(".bc").trigger("change");
        }
        $(".bc option[value=-1]").prop("selected", false).parent().trigger("change");
    });
	
	$('.sLoc').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".sLoc > option").prop("selected","selected");
            $(".sLoc").trigger("change");
        }
        $(".sLoc option[value=-1]").prop("selected", false).parent().trigger("change");
    });
	
    $('.subgroup').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".subgroup > option").prop("selected","selected");
            $(".subgroup").trigger("change");
        }
        $(".subgroup option[value=-1]").prop("selected", false).parent().trigger("change");
    });

    $('.materials').on("select2:select", function (e) { 
        var data = e.params.data.text;
        if(data==' Select All'){

            $(".materials > option").prop("selected","selected");
            $(".materials").trigger("change");
        }
        $(".materials option[value=-1]").prop("selected", false).parent().trigger("change");
    });

    //NUMERIC INPUTS
    $(document).on("input", "input.numeric", function() {
        this.value = this.value.replace(/[^0-9\.]/g,'');
    });

    //DATE PICKERS

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

    $('.yearpicker').datepicker({
        clearBtn: true,
        format: 'yyyy',
        autoclose: true,
        viewMode: "years",
        minViewMode: "years",
        startDate: '2021',
        immediateUpdates: true,
        todayHighlight: true
    });

    $('.monthpicker').datepicker({
        clearBtn: true,
        format: 'mm/yyyy',
        autoclose: true,
        viewMode: "months",
        minViewMode: "months",
        startDate: '01/2020',
        immediateUpdates: true,
        todayHighlight: true
    });

    $('#loader-div').addClass('loaded');

    

    
    let $wrapper = $('.wrapper');
    let $minibutton = $('.toggle-sidebar');

    if (!$wrapper.hasClass('sidebar_minimize')) {
        $wrapper.addClass('sidebar_minimize');
        $minibutton.addClass('toggled');
        $minibutton.html('<i class="icon-options-vertical"></i>');
        window.mini_sidebar = 1; 
    }

    $(document).on('click', '.toggle-details', function() {
    const row = $(this).closest('tr');
    const detailRow = $('#detail-' + row.data('id'));
    const icon = $(this).find('i');

    detailRow.slideToggle(200);
    icon.toggleClass('fa-chevron-down fa-chevron-up');
    });


    

    $(document).on('click', '#userGuide', function(){
            
        var url = base_url+'assets/userguide/cgis_user_guide.pdf';
        newPageTitle = $('#sys-name').val()+' User Guide!';
        document.querySelector('title').textContent = newPageTitle;
        printWindow = window.open( url , '_blank');
        printWindow.focus();

    });

    $(document).on('click', '.clear-trans-employees', function(){
        
        
        var modalID = '#modal-clear-trans';
        var docTypeID = $(this).attr('data-doctype');

        Lobibox.confirm({
            title: "System Notice",
            msg: 'Are you sure to clear all employee?',
            //class: 'info',
            //icon: 'fa fa-exclamation-circle',
            callback: function(box, type, ev){
                
                if (type === 'yes'){
                    $.ajax({
                        url: base_url + 'admin/clear-employee',
                        method:'POST',
                        data: {transTypeID:1},
                        dataType:"json",
                        success:function(data)
                        {
                            if(!data.success){
                                showAlertError(data.successMsg);
                                
                            } else {
                                $('.refresh-dt').trigger('click');
                                //$(modalID).modal('hide');
                                showSuccess(data.successMsg);
                            }
                        },
                        error:function(xhr, textStatus, errorThrown){
                            
                            console.log(xhr.responseText);
                            
                        }
                    });
                }
            }
        });

        

    });


    

    


    /* FORM VALIDATION */
    initValidationDefaults();
    /* var form = $('#change-password-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        rules: {
            'new-password': {
            
                minlength: 7
            },
            'confirm-password':{
                
                minlength: 7,
                equalTo: "#change-password-form [name=new-password]"
            }
        }
    }); */

    var form = $('#add-region');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-region');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#update-profile-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#update-password-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules: {
            
            'confirm-pass':{
                equalTo: "#update-password-form [name=new-pass]"
            }
        }
    });


    var form = $('#add-user-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules: {
            'user-employee-no': {
                required: true,
                minlength: 6
            },
            'user-email':{
                required: true,
                email: true
            },
            'user-password': {
                required: true,
                minlength: 7
            }
        }
    });

    var form = $('#update-user');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules: {
            'user-employee-no': {
                required: true,
                minlength: 6
            },
            'user-email':{
                required: true,
                email: true
            }
        }
    });

    var form = $('#update-password');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules: {
            'password2':{
                equalTo: "#update-password [name=password]"
            }
        }
    });

    var form = $('#add-user-role-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#update-user-role-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#add-role-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-role');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-sys-module-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-sys-module');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-key');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-key');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-user-sloc-form');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-bc');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-bc');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-province');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-province');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    
    
    var form = $('#probationary');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser, .itemized_dropdown',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#post-probationary');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#cancel-probationary');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#reset-probationary');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#emp-monitoring-interact');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'mapFileName':{
                required:true,
                extension: "xlsx"
            }
        },
        messages: {  // <-- you must declare messages inside of "messages" option
            'mapFileName':{
                required:"This field is required",                  
                extension:"Select valid input file format"
            }
        }
    });
    
    var form = $('#add-doc-placement');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#update-doc-placement');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#cancel-doc-placement');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#undo-doc-placement');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    
    /* var form = $('#cgperformance');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser'
    }); */
    
    var form = $('#post-cgperformance');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#cancel-cgperformance');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#undo-cgperformance');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#upload-performance');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'sloc-file':{
                required:true,
                extension: "xlsx"
            }
        },
        messages: {  // <-- you must declare messages inside of "messages" option
            'sloc-file':{
                required:"This field is required",                  
                extension:"Select valid input file format"
            }
        }
    });
    
    

    var form = $('#upload-harvest');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'harvest-file':{
                required:true,
                extension: "xlsx"
            }
        },
        messages: {  // <-- you must declare messages inside of "messages" option
            'harvest-file':{
                required:"This field is required",                  
                extension:"Select valid input file format"
            }
        }
    });
    
    var form = $('#undo-harvest');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#cancel-harvest');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#clean-up');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#undo-clean-up');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });

    var form = $('#cancel-clean-up');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#upload-clean-up');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'clean-up-file':{
                required:true,
                extension: "xlsx"
            }
        },
        messages: {  // <-- you must declare messages inside of "messages" option
            'clean-up-file':{
                required:"This field is required",                  
                extension:"Select valid input file format"
            }
        }
    });
    
    var form = $('#add-placement-filter');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-harvest-filter');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-clean-up-filter');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-industry-capacity-filter');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-doc-inventory-filter');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#cancel-industry-capacity');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#undo-industry-capacity');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#form-batchcode');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#upload-batchcode');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'batchcode-file':{
                required:true,
                extension: "xlsx"
            }
        },
        messages: {  // <-- you must declare messages inside of "messages" option
            'batchcode-file':{
                required:"This field is required",                  
                extension:"Select valid input file format"
            }
        }
    });
    
    var form = $('#add-vet');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-vet');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-farmtype');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-farmtype');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-heatsource');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-heatsource');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-littermaterial');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-littermaterial');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-growtype');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-growtype');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#docageconf');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-hatchery');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-hatchery');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-industry');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-industry');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-industry-group');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-industry-group');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-mobile');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-mobile');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-mobile-credits');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-mobile-credits');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#add-user-rating');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
        rules:{
            'rating':{
                required:true
            },
            'user-feedback[]':{
                required:true
            }
        },
    });



    var form = $('#add-maprating');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-maprating');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    var form = $('#add-employmentstatus');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-employmentstatus');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    var form = $('#add-designation');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-designation');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    var form = $('#add-position');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-position');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    var form = $('#add-department');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    var form = $('#update-department');
    form.validate({
        ignore: 'input[type=hidden], .select2-input, .select2-focusser',
        onfocusout: function(e) {  // this option is not needed
            this.element(e);       // this is the default behavior
        },
    });
    
    
    $(document).on('click', '#logoutAct', function(e){
        var userRatingInd = $('#userRatingInd').val();
        if(userRatingInd){
            modalID = '#logoutModal';
        } else {
            modalID = '#logoutModal2';
        }
        $(modalID).modal({show:true});
    });

    $(document).on('click', '#noRateBtn', function(e){
        modalID = '#logoutModal2';
        $(modalID).modal('hide');
        modalID = '#logoutModal';
        $(modalID).modal({show:true});
    });
    
    // END FORM VALIDATION

    /*$(document).ready(function () {
      var base_url = $('#base_url').val();
      var slocID = $('#dashboard-slocID').val();
      var yearFrom = $('#dashboard-yearFrom').val();
      var yearTo = $('#dashboard-yearTo').val();
      var transTypeID = $('#dashboard-transTypeID').val();
      $.ajax({
        url: base_url + 'admin/performanceDashboard',
        data: {slocID:slocID, yearFrom:yearFrom, yearTo:yearTo, transTypeID:transTypeID},
        type: "POST",
        dataType:"json",
        success: function (data) {
            var cg_name = [];
            var net_placement = [];
            var gross_placement = [];
            var harvested_heads = [];
            console.log(data);
            //console.log(data['test1'].slocName);
            
        },
        error: function (data) {

        }
      });
    });*/

    // setInterval(function() {
        

    //     get_dynamic_count();
    // }, 60000);

    get_dynamic_count();
    
    

    $('#add-user-rating').find('.feedback-group').hide();
    $(document).on('click', '.rating-btn-bad', function(e){
    
        $('#add-user-rating').find('.feedback-group').show();
        $('#add-user-rating').find('.feedback-group-label').text('How can the system improve?');
    });
    $(document).on('click', '.rating-btn-good', function(e){
    
        $('#add-user-rating').find('.feedback-group').show();
        $('#add-user-rating').find('.feedback-group-label').text('What did the system do well?');
    });
    

    $(document).on('click', '#show_password', function(e){
    
        $('.password').attr('type', $('.password').is(':password') ? 'text' : 'password');
    });
	
    $('div.modal').attr('data-backdrop', 'static');
});

function get_dynamic_count(){
    var sideBarColorVal = $('#sideBarColorVal').val();
    var btnColorVal = $('#btnColorVal').val();
    var base_url = $('#base_url').val();

    $.ajax({
        url: base_url + 'admin/get_notification_count',
        method:'POST',
        dataType:"json",
        success:function(data)  
        {
            if(data.counter){
                if(data.counter > 0){
                    $('#notif-counter').addClass('notification');
                    $('#notif-counter').text(data.counter);
                }
            } else {
                $('#notif-counter').removeClass('notification');

                $('#notif-counter').text(data.counter);
            }

            /* if(sideBarColorVal == 'orange' || sideBarColorVal == 'orange2'){
                var badgeColor = 'badge-default';
            } else {
                var badgeColor = 'badge-warning';
            } */

            var badgeColor = 'badge-'+btnColorVal;
            
            if(data.pending_placement > 0){
                $('#doc-placement-badge').removeClass('badge '+badgeColor);
                $('#doc-placement-badge').addClass('badge '+badgeColor);
                $('#doc-placement-badge').text(data.pending_placement);
            }else{
                $('#doc-placement-badge').removeClass('badge '+badgeColor);
                $('#doc-placement-badge').text('');
            }

            if(data.pending_performance > 0){
                $('#cg-performance-badge').removeClass('badge '+badgeColor);
                $('#cg-performance-badge').addClass('badge '+badgeColor);
                $('#cg-performance-badge').text(data.pending_performance);
            }else{
                $('#cg-performance-badge').removeClass('badge '+badgeColor);
                $('#cg-performance-badge').text('');
            }
            
            if(data.pending_harvest > 0){
                $('#harvest-badge').removeClass('badge '+badgeColor);
                $('#harvest-badge').addClass('badge '+badgeColor);
                $('#harvest-badge').text(data.pending_harvest);
            }else{
                $('#harvest-badge').removeClass('badge '+badgeColor);
                $('#harvest-badge').text('');
            }
            
            if(data.pending_clean_up > 0){
                $('#clean-up-badge').removeClass('badge '+badgeColor);
                $('#clean-up-badge').addClass('badge '+badgeColor);
                $('#clean-up-badge').text(data.pending_clean_up);
            }else{
                $('#clean-up-badge').removeClass('badge '+badgeColor);
                $('#clean-up-badge').text('');
            }

            if(data.pending_doc_text_buffer > 0){
                $('#doc-text-buffer-badge').removeClass('badge '+badgeColor);
                $('#doc-text-buffer-badge').addClass('badge '+badgeColor);
                $('#doc-text-buffer-badge').text(data.pending_doc_text_buffer);
            }else{
                $('#doc-text-buffer-badge').removeClass('badge '+badgeColor);
                $('#doc-text-buffer-badge').text('');
            }
            
            if(data.pending_industry_capacity > 0){
                $('#industry-capacity-badge').removeClass('badge '+badgeColor);
                $('#industry-capacity-badge').addClass('badge '+badgeColor);
                $('#industry-capacity-badge').text(data.pending_industry_capacity);
            }else{
                $('#industry-capacity-badge').removeClass('badge '+badgeColor);
                $('#industry-capacity-badge').text('');
            }
        },
        error:function(xhr, textStatus, errorThrown){
            showError('Error in Saving!');
            console.log(xhr.responseText);
            
        }
    });


    $('#lineChart').sparkline([102,109,120,99,110,105,115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: 'rgba(255, 255, 255, .5)',
        fillColor: 'rgba(255, 255, 255, .15)'
    });

    $('#lineChart2').sparkline([99,125,122,105,110,124,115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: 'rgba(255, 255, 255, .5)',
        fillColor: 'rgba(255, 255, 255, .15)'
    });

    $('#lineChart3').sparkline([105,103,123,100,95,105,115], {
        type: 'line',
        height: '70',
        width: '100%',
        lineWidth: '2',
        lineColor: 'rgba(255, 255, 255, .5)',
        fillColor: 'rgba(255, 255, 255, .15)'
    });
}


function clearNotif(){
    var base_url = $('#base_url').val();
    
    var statusID = 13;
    var trigger = 'view';

    $.ajax({
        url: base_url + 'admin/update_usernotif',
        method:'POST',
        data: {statusID:statusID, trigger:trigger},
        dataType:"json",
        success:function(data)  
        {
            if(data.success){
                $('#notif-counter').html('');
                $('#notif-counter').removeClass('notification');
                if(data.counter > 0){
                    $('#notif-dropdown-title').html('You have new notification{s}');
                } else {
                    $('#notif-dropdown-title').html('Notification(s)');
                }
                $('#announcement-notif').empty();
                $('#announcement-notif').append(data.item);
            }
        },
        error:function(xhr, textStatus, errorThrown){
            
            console.log(xhr.responseText);
            
        }
    });
}


function number_format(number, decimals, dec_point, thousands_sep) {
    // *     example: number_format(1234.56, 2, ',', ' ');
    // *     return: '1 234,56'
    number = (number + '').replace(',', '').replace(' ', '');
    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return '' + Math.round(n * k) / k;
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function convert_num(number){
    if(number >= 1000000000){
        number = number/1000000000;
        number = number_format(number, 3, '.', ',') + ' B';
    }else if(number >= 1000000 && number < 1000000000){
        number = number/1000000;
        number = number_format(number, 3, '.', ',') + ' M';
    }else if(number > 1000 && number < 1000000){
        number = number/1000;
        number = number_format(number, 2, '.', ',') + ' K';
    }else if(number > 99 && number < 999){
        number = number/1000;
        number = number_format(number, 2, '.', ',');
    }else{
         number = '';
    }
    return number;
}

function toFixed(num, fixed) {
    var re = new RegExp('^-?\\d+(?:\.\\d{0,' + (fixed || -1) + '})?');
    return num.toString().match(re)[0];
}

function initValidationDefaults(){
    //FORM VALIDATION CODE  FOR BOOTSTRAP3
    // override jquery validate plugin defaults
    $.validator.setDefaults({
        highlight: function (element) {
            var $el = $(element);
            var $fgroup = $el.closest('.form-group');
            $fgroup.removeClass('has-success')
                    .addClass('has-error')
                    .addClass('has-feedback')
                    .find('.form-control-feedback').remove();
            var $feedback = $('<i class="form-control-feedback fa fa-times-circle-o"></i>');
            //var $feedback = $('<i class="form-control-feedback"></i>');
            var type = $el[0].type;
            if (type === 'radio' || type === 'radio-inline' || type === 'checkbox' || type === 'checkbox-inline') {
                $fgroup.append($feedback);
            } else if (type === 'file' && $el.closest('.input.input-file').length > 0) {
                //Checking if this input is custom file input
                var $inputWrapper = $el.closest('.input.input-file');
                if ($inputWrapper.length > 0) {
                    $inputWrapper.append($feedback);
                }
            } else {
                if ($el.parent('.input-group').length) {
                    $feedback.insertAfter($el.parent());
                } else {
                    $feedback.insertAfter($el);
                }
            }
            
        },
        unhighlight: function (element) {
            var $el = $(element);
            var $fgroup = $el.closest('.form-group');
            $fgroup.removeClass('has-error')
                    .addClass('has-success')
                    .addClass('has-feedback')
                    .find('.form-control-feedback').remove();
            var $feedback = $('<i class="form-control-feedback fa fa-check"></i>');
            //var $feedback = $('<i class="form-control-feedback"></i>');
            var type = $el[0].type;
            if (type === 'radio' || type === 'radio-inline' || type === 'checkbox' || type === 'checkbox-inline') {
                $fgroup.append($feedback);
            } else if (type === 'file' && $el.closest('.input.input-file').length > 0) {
                //Checking if this input is custom file input
                var $inputWrapper = $el.closest('.input.input-file');
                if ($inputWrapper.length > 0) {
                    $inputWrapper.append($feedback);
                }
            } else {
                if ($el.parent('.input-group').length) {
                    $feedback.insertAfter($el.parent());
                } else {
                    $feedback.insertAfter($el);
                }
            }

            
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, $el) {
            var type = $el[0].type;
            var $fgroup = $el.closest('.form-group');
            if (type === 'radio' || type === 'radio-inline' || type === 'checkbox' || type === 'checkbox-inline') {
                $fgroup.append(error);
            } else if (type === 'file' && $el.closest('.input.input-file').length > 0) {
                //Checking if this input is custom file input
                var $inputWrapper = $el.closest('.input.input-file');
                if ($inputWrapper.length > 0) {
                    $inputWrapper.append(error);
                }
            } else {
                if ($el.parent('.input-group').length) {
                    error.insertAfter($el.parent());
                } else {
                    error.insertAfter($el);
                }
            }
        }
    });
}



    
