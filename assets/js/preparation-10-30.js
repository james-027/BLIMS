$(document).ready(function(){

function toggleReason(statusSelect) {
    const reasonSelect = $(statusSelect.dataset.target);
    const selectedText = statusSelect.options[statusSelect.selectedIndex].text.toLowerCase();

    if (selectedText === 'passed') {
        reasonSelect.prop('disabled', true)   
                    .val('')                 
                    .trigger('change');  
    } else {
        reasonSelect.prop('disabled', false);
    }
}


    $('.analytical-select').each(function() {
        toggleReason(this);
    });

    $(document).on('change', '.analytical-select', function() {
        toggleReason(this);
    });

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

    let counts = {};

    $('.analytical-select').each(function() {
        let selectedText = $(this).find('option:selected').text().trim();
        if (selectedText) {
            counts[selectedText] = (counts[selectedText] || 0) + 1;
        }
    });

    let summaryHtml = '';
    $.each(counts, function(processName, count) {
        summaryHtml += `
            <div class="summary-card">
                <div class="summary-icon"><i class="fas fa-cogs"></i></div>
                <div class="summary-name">${processName}</div>
                <div class="summary-count">${count}</div>
            </div>
        `;
    });

    // If no processes selected
    if ($.isEmptyObject(counts)) {
        summaryHtml = `<p class="text-center text-muted w-100">No processes selected yet.</p>`;
    }

    // Inject into modal
    $('#dynamicStatusSummary').html(summaryHtml);

    // Show modal
    $('#confirmModalInitial').modal('show');
});




    $('#confirmInitialSubmit').on('click', function() {
        $('#confirmModalInitial').modal('hide');

        $('#initialPreparationForm').submit();

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


    $(document).on('click', '.toggle-details', function() {
    const row = $(this).closest('tr');
    const detailRow = $('#detail-' + row.data('id'));
    const icon = $(this).find('i');

    detailRow.slideToggle(200);
    icon.toggleClass('fa-chevron-down fa-chevron-up');
});



});


