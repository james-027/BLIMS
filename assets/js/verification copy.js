$(document).ready(function(){

    const statusSelects = document.querySelectorAll('.test-status-select');

    statusSelects.forEach(select => {
        toggleReason(select);

        select.addEventListener('change', function() {
            toggleReason(select);
        });
    });

    function toggleReason(statusSelect) {
        const reasonSelect = document.querySelector(statusSelect.dataset.target);
        const selectedText = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

        if(selectedText === 'passed') {
            reasonSelect.disabled = true;
            reasonSelect.value = '';
        } else {
            reasonSelect.disabled = false;
        }
    }


    
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

     $('#loader-div').addClass('loaded');

});

$('#confirmSubmit').on('click', function() {
    $('#confirmModal').modal('hide');

    $('#verificationForm').submit();
});


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
    

    let $wrapper = $('.wrapper');
    let $minibutton = $('.toggle-sidebar');

    if (!$wrapper.hasClass('sidebar_minimize')) {
        $wrapper.addClass('sidebar_minimize');
        $minibutton.addClass('toggled');
        $minibutton.html('<i class="icon-options-vertical"></i>');
        window.mini_sidebar = 1; 
    }

       loadVerificationJobs();

    function loadVerificationJobs() {

        $.ajax({
            url: `${baseUrl}${controllerName}/get_verification_jobs`,
            method: 'GET',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && Object.keys(res.data).length > 0) {
                    renderVerificationJobs(res.data);
                } else {
                    $('#verificationJobsContainer').html(`
                        <div class="text-center text-muted my-5">
                            No job orders pending verification
                        </div>
                    `);
                }
            },
            error: function(xhr) {
                $('#verificationJobsContainer').html(`
                    <div class="text-center text-danger my-5">
                        Error fetching verification data.
                    </div>
                `);
                console.error(xhr.responseText);
            }
        });
    }

    function renderVerificationJobs(jobs) {
        let html = '';

        $.each(jobs, function(index, job) {
            let samplesHtml = '';
            if (job.samples && job.samples.length > 0) {
                $.each(job.samples, function(i, s) {
                    samplesHtml += `
                        <tr>
                            <td class="text-center align-middle">${i + 1}</td>
                            <td class="align-middle">${s.lab_code || '-'}</td>
                            <td class="align-middle">${s.sample_name || '-'}</td>
                            <td class="align-middle">${s.delivery_date || '-'}</td>
                            <td class="align-middle">${s.supplier_name || '-'}</td>
                            <td class="align-middle">${s.plate_number || '-'}</td>
                            <td class="align-middle">${s.batch_number || '-'}</td>
                            <td class="align-middle">${s.sample_type_name || '-'}</td>
                            <td class="align-middle">${s.laboratory_tests || '-'}</td>
                            <td class="align-middle">
                                <select name="test_status[${s.trans_detail_id}]" class="form-control form-control-sm" required>
                                    <option value="">Select Status</option>
                                    <?php foreach($test_statuses as $status): ?>
                                    <option value="<?= $status->statusID ?>"><?= $status->statDes ></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="align-middle">
                                <select name="reasons[${s.trans_detail_id}]" class="form-control form-control-sm" required>
                                    <option value="">Select Reason</option>
                                    <?php foreach($reasons as $reason): ?>
                                    <option value="<?= $reason->id ?>"><?= $reason->reason_name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="text-center align-middle">
                                ${s.coa_flag === 'Y'
                                    ? '<span class="badge badge-success">Yes</span>'
                                    : '<span class="badge badge-danger">No</span>'
                                }
                            </td>
                            <td class="align-middle">
                                <input type="text" name="remarks[${s.trans_detail_id}]" class="form-control form-control-sm"
                                    value="${s.existing_remark ? s.existing_remark.replace(/"/g, '&quot;') : ''}">
                            </td>
                        </tr>`;
                });
            } else {
                samplesHtml = `
                    <tr>
                        <td colspan="13" class="text-center text-muted">No samples available</td>
                    </tr>`;
            }

            html += `
                <div class="row justify-content-center mt-4">
                    <div class="col-md-12">
                        <div class="card shadow-sm mb-3">
                            <div class="card-header bg-primary text-white" data-toggle="collapse" data-target="#job-${index}" aria-expanded="true" style="cursor:pointer;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h5 class="mb-0">
                                        Job Order No: <span class="font-weight-bold">${job.job_order_no}</span>
                                    </h5>
                                    <span class="badge badge-light">Samples: ${job.samples.length}</span>
                                </div>
                            </div>
                            <div id="job-${index}" class="collapse show">
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Item No</th>
                                                    <th>Laboratory Code</th>
                                                    <th>Sample Name</th>
                                                    <th>Production Date</th>
                                                    <th>Shipment Supplier</th>
                                                    <th>Plate / Van Number</th>
                                                    <th>Batch / Lot Number</th>
                                                    <th>Type of Sample</th>
                                                    <th>Laboratory Tests</th>
                                                    <th>Test Status</th>
                                                    <th>Reason</th>
                                                    <th>COA Required</th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>${samplesHtml}</tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        });

        $('#verificationJobsContainer').html(html);
    }



});


