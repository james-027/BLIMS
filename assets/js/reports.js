$(document).ready(function() {
    $('#feedmillFilterForm').on('submit', function(e) {
        e.preventDefault(); // prevent default form submission

        var form = $(this);
        $.ajax({
            url: '', // current page
            type: 'POST',
            data: form.serialize(),
            beforeSend: function() {
                $('#loader-div').show(); // show loader
            },
            success: function(response) {
                // replace table content only
                var newContent = $(response).find('.table-responsive-report').html();
                $('.table-responsive-report').html(newContent);
            },
            complete: function() {
                $('#loader-div').hide(); // hide loader
            },
            error: function() {
                alert('Error filtering feedmill. Please try again.');
            }
        });
    });
});
