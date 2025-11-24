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

    $('#resultVerificationForm').on('submit', function(e) {
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

        let approved = 0, disapproved = 0;
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
            }
        });

        if (approved === 0 && disapproved === 0) {
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

    // $('#saveBtnTest').on('click', function(e) {
    //     e.preventDefault();

    //     let complete = 0, ongoing = 0;
    //     let invalid = false;

    //     $('.test-status-select').each(function() {
    //         let $statusSelect = $(this);
    //         let selectedText = $statusSelect.find('option:selected').text().trim().toLowerCase();
    //         let transId = $statusSelect.attr('name').match(/\d+/)[0];
    //         let $labResult = $(`input[name='lab_results[${transId}]']`);

    //         if (selectedText === 'complete') complete++;
    //         else if (selectedText === 'ongoing') ongoing++;

    //         if (selectedText === 'complete') {
    //             if ($labResult.length === 0 || !$labResult.val().trim()) {
    //                 invalid = true;
    //                 $labResult.addClass('is-invalid');
    //             } else {
    //                 $labResult.removeClass('is-invalid');
    //             }
    //         } else {
    //             $labResult.removeClass('is-invalid');
    //         }
    //     });

    //     if (invalid) {
    //         let $firstInvalid = $('.is-invalid').first();
    //         if ($firstInvalid.length > 0) {
    //             $('html, body').animate({
    //                 scrollTop: $firstInvalid.offset().top - 100
    //             }, 500);
    //             $firstInvalid.focus();
    //         }
    //         swal("Validation Error", "Please provide Lab Result For Completed Test Execution and Data Entry.", "warning");
    //         return false;
    //     }

    //     $('#countComplete').text(complete);
    //     $('#countOngoing').text(ongoing);

    //     $('#confirmModalTestExec').modal('show');
    // });


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


$('#jobSearch').on('keyup', function() {
    let value = $(this).val().toLowerCase().trim();
    let matchFoundOverall = false; 

    $('.card').each(function() {
        let card = $(this);
        let matchFound = false;
        let jobOrder = card.find('.card-header h5').text().toLowerCase();

        card.find('tbody tr').each(function() {
            let rowText = $(this).text().toLowerCase();
            if (rowText.includes(value) || jobOrder.includes(value)) {
                $(this).show();
                matchFound = true;
            } else {
                $(this).hide();
            }
        });

        if (matchFound) {
            card.show();
            matchFoundOverall = true;
        } else {
            card.hide();
        }
    });

    $('#noResultsMessage').remove();

    if (!matchFoundOverall && value !== '') {
        $('.page-inner').append(`
            <div id="noResultsMessage" class="text-center text-muted mt-4">
                <h5><i class="fas fa-search"></i> No matching item found.</h5>
            </div>
        `);
    }
});


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

    document.querySelectorAll('.select-all-btn').forEach(btn => {
        btn.addEventListener('click', function(event) {
            event.stopPropagation(); // prevent collapse

            let jobId = this.dataset.job;
            let checkboxes = document.querySelectorAll(jobId + " input[type='checkbox']");

            let enabledCheckboxes = Array.from(checkboxes).filter(cb => !cb.disabled);

            let allChecked = enabledCheckboxes.every(cb => cb.checked);

            enabledCheckboxes.forEach(cb => cb.checked = !allChecked);

            if (allChecked) {
                this.innerHTML = '<i class="fas fa-check-square mr-1"></i> Select All';
                this.classList.remove("btn-success");
            } else {
                this.innerHTML = '<i class="fas fa-times mr-1"></i> Unselect All';
                this.classList.add("btn-success");
            }
        });
    });



    


});


