$(document).ready(function(){

    $('#initialPreparationForm').on('submit', function(e) {
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

    $('#finalPreparationForm').on('submit', function(e) {
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

    $('#dataReviewForm').on('submit', function(e) {
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

  $('#releaseForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        $('.select-releasing[data-prechecked="1"]').each(function() {
            var name = $(this).attr('name');
            formData.delete(name);
        });

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


    let changedRows = new Set();

    $('.result-verification-status-select, input[name^="result_verification_remarks"]').on('change input', function () {
        let id;

        if ($(this).hasClass('result-verification-status-select')) {
            id = $(this).attr('id').replace('result_verifications', '');
        } else {
            id = $(this).attr('name').match(/\d+/)[0];
        }

        changedRows.add(id);
    });


    $('#resultVerificationForm').on('submit', function(e) {
        e.preventDefault();

        let formData = new FormData();

        $('.result-verification-status-select').each(function() {

            let id = $(this).attr('id').replace('result_verifications', '');
            let resultVal = $(this).val();
            let remarkVal = $('input[name="result_verification_remarks[' + id + ']"]').val();

            if (!resultVal && !remarkVal) return;

            formData.append('result_verifications[' + id + ']', resultVal);
            formData.append('result_verification_remarks[' + id + ']', remarkVal);
        });

        $.ajax({
            url: $('#resultVerificationForm').attr('action'),
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
                    }, 1500);
                } else {
                    swal("Oops...", response.message || "Something went wrong!", "error");
                }
            },
            error: function(xhr) {
                $('#loader-div').hide();
                console.error(xhr.responseText);
                swal("AJAX Error", "Request failed. Check console.", "error");
            }
        });
    });


    $('#testExecutionForm').on('submit', function(e) {
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


    $('#saveBtnInitial').on('click', function(e) {

        e.preventDefault();

        let homogenizing = 0, grinding = 0, as_is = 0;

        $('.analytical-select').each(function() {
            let selectedText = $(this).find('option:selected').text().trim().toLowerCase();
            if (selectedText === 'homogenizing') homogenizing++;
            else if (selectedText === 'grinding') grinding++;
            else if (selectedText === 'as is') as_is++;
        });

        $('#countHomogenizing').text(homogenizing);
        $('#countGrinding').text(grinding);
        $('#countAsIs').text(as_is);

        $('#confirmModalInitial').modal('show');
    });

    $('#saveBtnRelease').on('click', function(e) {
        e.preventDefault();

        let countRelease = 0;

        $('.select-releasing').each(function() {
            if ($(this).is(':checked') && !$(this).data('prechecked')) {
                countRelease++;
            }
        });

        if (countRelease === 0) {
            swal("No new selections!", "You haven’t selected any new checkboxes. Only new selections can be saved.", "info");
            return;
        }

        $('#countRelease').text(countRelease);
        $('#confirmModalRelease').modal('show');
    });



    $('#saveBtnData').on('click', function(e) {
        e.preventDefault();

        let reanalysis = 0, verified = 0;

        $('.review-verification-select').each(function() {
            let selectedText = $(this).find('option:selected').text().trim().toLowerCase();
            if (selectedText === 're-analysis') reanalysis++;
            else if (selectedText === 'verified') verified++;
        });

        if (reanalysis === 0 && verified === 0) {
            swal("Warning!", "Please select at least one 'Re-Analysis' or 'Verified' before proceeding.", "warning");
            return;
        }

        $('#countReAnalysis').text(reanalysis);
        $('#countVerified').text(verified);

        $('#confirmModalData').modal('show');
    });

    $('#saveBtnResultVerification').on('click', function(e) {
        e.preventDefault();

        let approved = 0, disapproved = 0; re_analysis = 0;
        let missingRemarks = false;

        $('.result-verification-status-select').each(function() {
            let selectedText = $(this).find('option:selected').text().trim().toLowerCase();
            let transDetailId = $(this).attr('id').replace('result_verifications','');
            let remarkInput = $('input[name="result_verification_remarks['+transDetailId+']"]');

            
            if (selectedText === 'approved') {
                approved++;
            } else if (selectedText === 'disapproved') {
                disapproved++;
                if (remarkInput.val().trim() === '') {
                    missingRemarks = true;
                    remarkInput.addClass('is-invalid'); 
                } else {
                    remarkInput.removeClass('is-invalid');
                }
            } else if (selectedText === 're-analysis') {
                re_analysis++;
                if (remarkInput.val().trim() === '') {
                    missingRemarks = true;
                    remarkInput.addClass('is-invalid'); 
                } else {
                    remarkInput.removeClass('is-invalid');
                }
            }
        });

        if (approved === 0 && disapproved === 0 && re_analysis === 0) {
            swal("Warning!", "Please select Test Result before proceeding.", "warning");
            return;
        }

        if (missingRemarks) {
                let firstInvalid = $('.is-invalid').first();
                

                $('html, body').animate({
                    scrollTop: firstInvalid.offset().top - 100 
                }, 500);

                firstInvalid.focus();
            swal("Warning!", "Please enter remarks for all disapproved items.", "warning");
            return false;
        }

        $('#countApproved').text(approved);
        $('#countDisapproved').text(disapproved);
        $('#countReAnalysis').text(re_analysis);

        $('#confirmModalResultVerification').modal('show');
    });


    $('#saveBtnFinal').on('click', function(e) {

        e.preventDefault();
        
        let passed = 0, failed = 0;
        let invalid = false;

        $('.verification-select').each(function() {
            let selectedText = $(this).find('option:selected').text().trim().toLowerCase();
            let transId = $(this).attr('name').match(/\d+/)[0];
            let prepVeriSelect = $(`#prepverification-${transId}`);
            let remarkInput = $(`input[name='remarks[${transId}]']`);

            if (selectedText === 'passed') passed++;
            else if (selectedText === 'failed') failed++;

            if (selectedText === 'failed') {
                if (!prepVeriSelect.val() || prepVeriSelect.val() === '') {
                    invalid = true;
                    prepVeriSelect.addClass('is-invalid');
                } else {
                    prepVeriSelect.removeClass('is-invalid');
                }

                if (!remarkInput.val() || remarkInput.val().trim() === '') {
                    invalid = true;
                    remarkInput.addClass('is-invalid');
                } else {
                    remarkInput.removeClass('is-invalid');
                }
            } else {
                prepVeriSelect.removeClass('is-invalid');
                remarkInput.removeClass('is-invalid');
            }


        });

        if (invalid) {
            let firstInvalid = $('.is-invalid').first();
            

            $('html, body').animate({
                scrollTop: firstInvalid.offset().top - 100 
            }, 500);

            firstInvalid.focus();
            swal("Validation Error", "Please provide Remarks for Failed Final Verification.", "warning");
            return false;
        }


        $('#countPassed').text(passed);
        $('#countFailed').text(failed);

        $('#confirmModalFinal').modal('show');
    });



    $('#saveBtnTest').on('click', function(e) {
        e.preventDefault();

        let complete = 0, ongoing = 0;
        let invalid = false;

        $('.test-status-select').each(function() {
            let $statusSelect = $(this);
            let selectedText = $statusSelect.find('option:selected').text().trim().toLowerCase();
            let transId = $statusSelect.attr('name').match(/\d+/)[0];
            let $labResult = $(`input[name='lab_results[${transId}]']`);
            let $remarks = $(`input[name='remarks[${transId}]']`);
            let $leadTime = $(`input[name='lead_time[${transId}]']`);
            let $dateSubmitted = $(`input[name='date_submitted[${transId}]']`);

            if (selectedText === 'complete') complete++;
            else if (selectedText === 'ongoing') ongoing++;

            if (selectedText === 'complete') {
                if ($labResult.length === 0 || !$labResult.val().trim()) {
                    invalid = true;
                    $labResult.addClass('is-invalid');
                } else {
                    $labResult.removeClass('is-invalid');
                }
            } else {
                $labResult.removeClass('is-invalid');
            }

            let leadTime = parseInt($leadTime.val()); 
            let submitted = $dateSubmitted.val();

            if (submitted && !isNaN(leadTime)) {
                let submittedDate = new Date(submitted);
                let now = new Date();
                let diffDays = Math.floor((now - submittedDate) / (1000 * 60 * 60 * 24));

                if (diffDays > leadTime) {
                    if (!$remarks.val().trim()) {
                        invalid = true;
                        $remarks.addClass('is-invalid');
                    } else {
                        $remarks.removeClass('is-invalid');
                    }
                } else {
                    $remarks.removeClass('is-invalid');
                }
            }
        });

        if (invalid) {
            let $firstInvalid = $('.is-invalid').first();
            if ($firstInvalid.length > 0) {
                $('html, body').animate({
                    scrollTop: $firstInvalid.offset().top - 100
                }, 500);
                $firstInvalid.focus();
            }
            swal("Validation Error", "Please provide Lab Result for Completed Tests and Remarks for Delayed Lead Time.", "warning");
            return false;
        }

        $('#countComplete').text(complete);
        $('#countOngoing').text(ongoing);

        $('#confirmModalTestExec').modal('show');
    });

    $('#confirmInitialSubmit').on('click', function() {
        $('#confirmModalInitial').modal('hide');

        $('#initialPreparationForm').submit();

    });


    $('#confirmFinalSubmit').on('click', function() {
        $('#confirmModalFinal').modal('hide');

        $('#finalPreparationForm').submit();
    });
    $('#confirmDataReviewSubmit').on('click', function() {
        $('#confirmModalData').modal('hide');
      $('#dataReviewForm').submit();
    });


    $('#confirmReleaseSubmit').on('click', function() {
        $('#confirmModalRelease').modal('hide');
         $('#releaseForm').submit();
    });



    $('#confirmResultVerificationSubmit').on('click', function() {
        $('#confirmModalResultVerification').modal('hide');


         $('#resultVerificationForm').submit();
    });

    $('#confirmTestExecSubmit').on('click', function() {
        $('#confirmModalTestExec').modal('hide');

        $('#testExecutionForm').submit();
    });

// $('#jobSearch').on('keyup', function() {
//     let searchVal = $(this).val().toLowerCase().trim();
//     let column = $('#searchColumn').val();

//     $('#jobsContainer .card').each(function() {
//         let card = $(this);
//         let match = false;

//         if (column === 'all') {
//             // search header + table
//             if (card.text().toLowerCase().includes(searchVal)) {
//                 match = true;
//             }
//         } else if (column === 'nutritionist_name') {
//             let val = card.find('.nutritionist_name').text().toLowerCase();
//             if (val.includes(searchVal)) match = true;
//         } else if (column === 'client_name') {
//             let val = card.find('.client_name').text().toLowerCase();
//             if (val.includes(searchVal)) match = true;
//         } else {
//             // table td search
//             card.find('table tbody tr').each(function() {
//                 let td = $(this).find('td.' + column);
//                 if (td.length && td.text().toLowerCase().includes(searchVal)) {
//                     match = true;
//                     return false;
//                 }
//             });
//         }

//         card.toggle(match);
//     });
// });






    $('#clearSearch').on('click', function() {
        $('#jobSearch').val('');
        $('.card').show();
        $('tbody tr').show();
    });

    $(document).on('click', '.toggle-details', function() {
    const row = $(this).closest('tr');
    const detailRow = $('#detail-' + row.data('id'));
    const icon = $(this).find('i');

    detailRow.slideToggle(200);
    icon.toggleClass('fa-chevron-down fa-chevron-up');
    });

    $(document).on('click', '.view-logs-btn', function() {
        const transDetailId = $(this).data('id');
        const labCode = $(this).data('labcode');

        $('#logLabCode').text(labCode);
        $('#viewLogsModal').modal('show');

        $('#logsLoader').removeClass('d-none');
        $('#logsTimeline').addClass('d-none');
        $('#noLogsMessage').addClass('d-none');

        $.ajax({
            url: `${baseUrl}${controllerName}/get_logs/${transDetailId}`,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#logsLoader').addClass('d-none');

                if (response.length > 0) {
                    let timeline = '';
                    response.forEach(log => {
                        timeline += `
                            <div class="timeline-item">
                                <div class="timeline-date">${log.log_date}</div>
                                <div class="timeline-content">
                                    <div class="timeline-header">
                                        <strong>${log.module}</strong>
                                    </div>
                                    <div class="timeline-remarks">
                                        <strong>Job Order:</strong> ${log. jo|| '-'}
                                    </div>
                                    <div class="timeline-remarks">
                                        <strong>Laboratory Code:</strong> ${log. lab_code|| '-'}
                                    </div>
                                    <div class="timeline-remarks">
                                        <strong>Action:</strong> ${log. action|| '-'}
                                    </div>
                                    <div class="timeline-remarks">
                                        <strong>Remarks:</strong> ${log.remarks || '-'}
                                    </div>
                                    <div class="timeline-user">
                                        <strong>Prepared by:</strong>${log.performed_by || 'System'}
                                    </div>
                                </div>
                            </div>
                        `;
                    });

                    $('#logsTimeline').html(timeline).removeClass('d-none');
                } else {
                    $('#noLogsMessage').removeClass('d-none');
                }
            },
            error: function() {
                $('#logsLoader').addClass('d-none');
                $('#noLogsMessage')
                    .removeClass('d-none')
                    .text('Error loading logs.');
            }
        });
    });

document.querySelector('#jobsContainer').addEventListener('click', function(event) {
    let btn = event.target.closest('.select-all-btn'); 
    if (!btn) return;

    event.stopPropagation(); // prevent collapse

    let jobId = btn.dataset.job;
    let checkboxes = document.querySelectorAll(jobId + " input[type='checkbox']");
    let enabledCheckboxes = Array.from(checkboxes).filter(cb => !cb.disabled);
    let allChecked = enabledCheckboxes.every(cb => cb.checked);

    enabledCheckboxes.forEach(cb => cb.checked = !allChecked);

    if (allChecked) {
        btn.innerHTML = '<i class="fas fa-check-square mr-1"></i> Select All';
        btn.classList.remove("btn-success");
    } else {
        btn.innerHTML = '<i class="fas fa-times mr-1"></i> Unselect All';
        btn.classList.add("btn-success");
    }
});


$('#releaseFilter').on('change', function () {
    const params = new URLSearchParams(window.location.search);

    if (this.value === '') {
        params.delete('is_released');
    } else {
        params.set('is_released', this.value);
    }

    params.set('page', 1); 
    window.location.search = params.toString();
});



$(document).ready(function() {
    const params = new URLSearchParams(window.location.search);
    const filter = params.get('is_released') ?? '';
    $('#releaseFilter').val(filter);
});



    $('#jobSearch').on('keyup', function() {
        let value = $(this).val().trim();
            if (value === '') {
        window.location.href = baseUrl + controllerName;
        return;
    }

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




        $(document).on("change", "#rep_testCode", function () {
        let labId = $("#replicateDetailModal").data("lab-id"); 
        let testId = $(this).val();

        if (labId && testId) {
            loadLeadTimes(labId, testId);
        }
    });


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
    modal.find("#rep_typeOfSample").prop("disabled", true);
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
            modal.find("#rep_typeOfSample").val(data.sample_type_name);
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


    
  function loadSampleAndTestCodes(labId, preselected = {}) {
    if (!labId) return;

    $.ajax({
        url: baseUrl + controllerName + "/get_lab_tests/" + labId,
        type: "GET",
        dataType: "json",
        success: function (response) {
            let testDDLs = [$("#modal_testCode"), $("#rep_testCode")];
            testDDLs.forEach(dd => dd.empty().append('<option value="">Test Code</option>'));

            if (Array.isArray(response.tests)) {
                testDDLs.forEach(dd => {
                    response.tests.forEach(item => {
                        let selected = (preselected.testCodeId && preselected.testCodeId == item.id) ? 'selected' : '';
                        dd.append(`<option value="${item.id}" ${selected}>${item.name}</option>`);
                    });
                });
            }

        },
        error: function(err) {
            console.error("Error fetching lab tests", err);
        }
    });
}

        
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



    $('#jobsContainer .collapse.show').each(function(){
        $(this).prev('.card-header').find('.collapse-icon').addClass('rotated');
    });

    $('#jobsContainer').on('show.bs.collapse', '.collapse', function () {
        $(this).prev('.card-header').find('.collapse-icon').addClass('rotated');
    });

    $('#jobsContainer').on('hide.bs.collapse', '.collapse', function () {
        $(this).prev('.card-header').find('.collapse-icon').removeClass('rotated');
    });




});


