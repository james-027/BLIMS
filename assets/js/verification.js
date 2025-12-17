$(document).ready(function(){


function toggleReason(statusSelect) {
    const reasonSelect = $(statusSelect.dataset.target);
    const selectedText = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

    const $row = $(statusSelect).closest('tr');
    if (selectedText === 'passed') {
        reasonSelect.prop('disabled', true)
                    .val('')
                    .trigger('change');

    } else {
        reasonSelect.prop('disabled', false);
    }
}


    $('.test-status-select').each(function() {
        toggleReason(this);
    });

    $(document).on('change', '.test-status-select', function() {
        toggleReason(this);
    });


    
    $('#verificationForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#loader-div').show();
            },
            success: function(response) {
                $('#loader-div').hide();
                if (response.status === 'success') {
                    swal("Success!", response.message, "success");
                    setTimeout(function() {
                        window.location.reload();
                         window.scrollTo(0, 0);
                    }, 2000);
                } else {
                    swal("Oops...", "Something went wrong!", "error");
                }
            },
            error: function(xhr, status, error) {
                $('#loader-div').hide();
                swal("AJAX Error", error, "error");
            }
        });
    });

 

    $('#saveBtn').on('click', function (e) {
            e.preventDefault();

            let passed = 0, hold = 0, failed = 0;
            let invalid = false;

            $('.test-status-select').each(function() {
                let selectedText = $(this).find('option:selected').text().trim().toLowerCase();
                let transId = $(this).attr('name').match(/\d+/)[0];
                let reasonSelect = $(`#reason-${transId}`);
                let remarkInput = $(`input[name='remarks[${transId}]']`);

                if (selectedText === 'passed') passed++;
                else if (selectedText === 'on hold') hold++;
                else if (selectedText === 'failed') failed++;

                if (selectedText === 'on hold' || selectedText === 'failed') {
                    if (!reasonSelect.val() || reasonSelect.val() === '') {
                        invalid = true;
                        reasonSelect.addClass('is-invalid');
                    } else {
                        reasonSelect.removeClass('is-invalid');
                    }

                    if (!remarkInput.val() || remarkInput.val().trim() === '') {
                        invalid = true;
                        remarkInput.addClass('is-invalid');
                    } else {
                        remarkInput.removeClass('is-invalid');
                    }
                } else {
                    reasonSelect.removeClass('is-invalid');
                    remarkInput.removeClass('is-invalid');
                }
            });

            if (invalid) {
                let firstInvalid = $('.is-invalid').first();

                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100 
                }, 500);

                firstInvalid.focus();

                swal("Validation Error", "Please provide Reason and Remarks for On Hold or Failed samples.", "warning");
                return false;
            }


            $('#countPassed').text(passed);
            $('#countHold').text(hold);
            $('#countFailed').text(failed);

            $('#confirmModal').modal('show');
    });



    $('#confirmSubmit').on('click', function() {
        $('#confirmModal').modal('hide');

        $('#verificationForm').submit();
    });







    $('#clearSearch').on('click', function() {
        $('#jobSearch').val('');
        $('.card').show();
        $('tbody tr').show();
    });


    $(document).on('click', '#add-registration', function() {
        window.location.href = baseUrl + controllerName + '/sample_registration';
    });

    $(document).on("click", ".attachment-link", function (e) {
        e.stopPropagation();
    });

    $(document).on("change", "#modal_testCode", function () {
        let labId = $("#addDetailModal").data("lab-id"); 
        let testId = $(this).val();

        if (labId && testId) {
            loadLeadTimes(labId, testId);
        }
    });
    $(document).on("change", "#rep_testCode", function () {
        let labId = $("#replicateDetailModal").data("lab-id"); 
        let testId = $(this).val();

        if (labId && testId) {
            loadLeadTimes(labId, testId);
        }
    });

  function loadSampleAndTestCodes(labId, preselected = {}) {
    if (!labId) return;

    $.ajax({
        url: baseUrl + controllerName + "/get_lab_tests/" + labId,
        type: "GET",
        dataType: "json",
        success: function (response) {
            let sampleDDLs = [$("#modal_typeOfSample"), $("#rep_typeOfSample")];
            let testDDLs = [$("#modal_testCode"), $("#rep_testCode")];

            sampleDDLs.forEach(dd => dd.empty().append('<option value="">Type of Sample</option>'));
            testDDLs.forEach(dd => dd.empty().append('<option value="">Test Code</option>'));

            if (Array.isArray(response.samples)) {
                sampleDDLs.forEach(dd => {
                    response.samples.forEach(item => {
                        let selected = (preselected.sampleTypeId && preselected.sampleTypeId == item.id) ? 'selected' : '';
                        dd.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
                    });
                });
            }

            if (Array.isArray(response.tests)) {
                testDDLs.forEach(dd => {
                    response.tests.forEach(item => {
                        let selected = (preselected.testCodeId && preselected.testCodeId == item.id) ? 'selected' : '';
                        dd.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
                    });
                });
            }

            sampleDDLs.concat(testDDLs).forEach(dd => {
                if (dd.hasClass("select2-hidden-accessible")) dd.trigger("change.select2");
            });
        },
        error: function(err) {
            console.error("Error fetching lab tests", err);
        }
    });
}

function loadLeadTimes(labId, testId) {
    if (!labId || !testId) return;

    $.ajax({
        url: baseUrl + controllerName + "/get_lead_times",
        type: "GET",
        data: { laboratory_id: labId, test_id: testId },
        dataType: "json",
        success: function (response) {
            let leadDDLs = [$("#modal_leadTimeType"), $("#rep_leadTimeType")];

            leadDDLs.forEach(dd => {
                dd.empty().append('<option value="">Select Lead Time</option>');

                if (Array.isArray(response) && response.length > 0) {
                    response.forEach(item => dd.append(`<option value="${item.value}">${item.label}</option>`));
                } else {
                    dd.append('<option value="">No lead times available</option>');
                }

                if (dd.hasClass("select2-hidden-accessible")) dd.trigger("change.select2");
            });
        },
        error: function(err) {
            console.error("Error fetching lead times.", err);
        }
    });
}


    $('#addDetailModal').on('hidden.bs.modal', function () {

        $(this).find('select').val('').trigger('change');
        $(this).find('input[type="text"], input[type="date"], input[type="number"]').val('');
        
        $('#modal_coaRequired').prop('checked', false);

        $('#modal_typeOfSample').html('');
        $('#modal_testCode').html('');
        $('#modal_leadTimeType').html('');
    });


      
    $(document).on('click', '.addDetailBtn', function (e) {
        e.stopPropagation();

        let labId = $(this).data("lab-id");  
        let jobIndex = $(this).data('job');
        let transId = $(this).data("trans-id");

    $('#modal_labId').val(labId);
    $('#modal_jobIndex').val(jobIndex);
    $('#modal_transId').val(transId); 

        $("#addDetailModal")
            .data('job', jobIndex)
            .data('lab-id', labId) 
            .data('trans-id', transId) 
            .modal('show');

        if (labId) {
            loadSampleAndTestCodes(labId);
        }
    });


$(document).on('click', '.replicateDetailBtn', function () {

    let detailId = $(this).data("detail-id");
    let labId = $(this).data("lab-id");  
    let jobIndex = $(this).data("job");
    let modal = $("#replicateDetailModal");
    let transId = $(this).data("trans-id");
    let labCode = $(this).data('lab-code');

    $('#replicateLabCode').text(labCode);

    $('#rep_labId').val(labId);
    $('#rep_transId').val(transId)
    $('#rep_detailId').val(detailId)

     $("#replicateDetailModal")
            .data('job', jobIndex)
            .data('lab-id', labId) 
            .data('trans-id', transId) 
            .data('detail-id', detailId) 
            .modal('show');

        if (labId) {

            loadSampleAndTestCodes(labId);
        }

    modal.find("#rep_jobIndex").val(jobIndex);

    modal.find("input, select").prop("disabled", false);

    modal.find("#rep_sampleName").prop("disabled", true);
    modal.find("#rep_productionDate").prop("disabled", true);
    modal.find("#rep_shipmentSupplier").prop("disabled", true);
    modal.find("#rep_plateVanNumber").prop("disabled", true);
    modal.find("#rep_batchLotNumber").prop("disabled", true);


    $.ajax({
        url: baseUrl + controllerName + "/get_detail_data/" + detailId,
        type: "GET",
        dataType: "json",
        success: function (data) {
            modal.find("#rep_labId").val(data.lab_id);
            modal.find("#rep_transId").val(data.trans_id);

            modal.find("#rep_sampleName").val(data.sample_name);
            modal.find("#rep_productionDate").val(data.delivery_date);
            modal.find("#rep_shipmentSupplier").val(data.supplier_name);
            modal.find("#rep_plateVanNumber").val(data.plate_number);
            modal.find("#rep_batchLotNumber").val(data.batch_number);

            modal.find("#rep_leadTimeType").val(data.lead_time);
            modal.find("#rep_coaRequired").prop("checked", data.coa_flag === "Y");

            modal.modal("show");
        }
    });
});





    // $('#newSampleForm').on('submit', function(e) {
    //     e.preventDefault();
    //     var formData = new FormData(this);
    //     $.ajax({
    //         url: $(this).attr('action'),
    //         type: 'POST',
    //         data: formData,
    //         processData: false,
    //         contentType: false,
    //         dataType: 'json',
    //         beforeSend: function() {
    //             $('#loader-div').show();
    //         },
    //         success: function(response) {
    //             $('#loader-div').hide();
    //             if (response.status === 'success') {
    //                 swal("Success!", response.message, "success");
    //                 setTimeout(function() {
    //                     window.location.reload();
    //                      window.scrollTo(0, 0);
    //                 }, 2000);
    //             } 
    //             else {
    //                 swal("Oops...", "Something went wrong!", "error");
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             $('#loader-div').hide();
    //             swal("AJAX Error", error, "error");
    //         }
    //     });
    // });



    $('#newSampleForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        beforeSend: function() {
            $('#loader-div').show();
        },
        success: function(response) {
            $('#loader-div').hide();
            if (response.status === 'success') {
                swal("Success!", response.message, "success").then(() => {
                    window.location.reload();
                });
            } else if(response.status === 'warning') {
                swal("Warning!", response.message, "warning").then(() => {
                });
            } else {
                swal("Oops...", "Something went wrong!", "error");
            }
        },
        error: function(xhr, status, error) {
            $('#loader-div').hide();
            console.error(xhr.responseText);
            swal("AJAX Error", error, "error");
        }
    });
});


    $('#replicateSampleForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            beforeSend: function() {
                $('#loader-div').show();
            },
            success: function(response) {
                $('#loader-div').hide();
                if (response.status === 'success') {
                    swal("Success!", response.message, "success");
                    setTimeout(function() {
                        window.location.reload();
                         window.scrollTo(0, 0);
                    }, 2000);
                } else {
                    swal("Oops...", "Something went wrong!", "error");
                }
            },
            error: function(xhr, status, error) {
                $('#loader-div').hide();
                swal("AJAX Error", error, "error");
            }
        });
    });

    
    $('#saveNewDetailBtn').on('click', function (e) {
        e.preventDefault();
          let isValid = true;

               $('#newSampleForm').find('select[required], input[required]').each(function() {

                if (!$(this).val()) {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });
            if (isValid) {
                    $('#confirmNewSampleModal').modal('show');

            } else {
                alert('Please fill all required fields.');
            }
    });

        
    $('#saveReplicateDetailBtn').on('click', function (e) {
        e.preventDefault();
          let isValid = true;
    $('#replicateSampleForm').find('select[required]').each(function() {
        if (!$(this).val()) {
            $(this).addClass('is-invalid');
            isValid = false;
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    if (isValid) {
        $('#confirmReplicateSampleModal').modal('show');
    } else {
        alert('Please fill all required fields.');
    }

    });


    $('#confirmReplicateSubmitSample').on('click', function() {
        $('#confirmReplicateSampleModal').modal('hide');
        
        $('#replicateSampleForm').submit();
    });

    $('#confirmSubmitNewSample').on('click', function() {
        $('#confirmNewSampleModal').modal('hide');
        
        $('#newSampleForm').submit();
    });

    
    $('#jobSearch').on('keyup', function() {
        let value = $(this).val().trim();
        let field = $('#searchField').val();
        $.ajax({
            url: baseUrl + controllerName + '/search_details',
            method: 'GET',
            data: { search: value , field : field},
            beforeSend: function() {
                $('#jobsContainer').html('<div class="text-center my-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>');
            },
        success: function(response) {
        if(response.status === 'success') {
            $('#jobsContainer').html(response.html);
            initDynamicDropdowns("#jobsContainer");
            if ($.trim(response.html) === '') {
                $('#jobsContainer').html(`
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-12 text-center text-muted">
                            <i class="fas fa-search"></i> No matching item found.
                        </div>
                    </div>
                `);
            }
        } else {
            $('#jobsContainer').html('<div class="text-center text-danger mt-4">Error fetching results</div>');
        }
    },
            error: function() {
                $('#jobsContainer').html('<div class="text-center text-danger mt-4">Error fetching results</div>');
            }
        });
    });


    
function initDynamicDropdowns(container = document) {

    $(container).find('select.dynamic_dropdown').each(function() {

        var $select = $(this);

        if ($select.hasClass('select2-hidden-accessible')) return;

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

}

$(document).ready(function () {
    initDynamicDropdowns();
});



});


