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
    




$(document).on('click', '#add-registration', function() {
    window.location.href = baseUrl + controllerName + '/sample_registration';
});


});


