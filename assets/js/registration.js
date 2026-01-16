$(document).ready(function(){

    const toggleBtn = document.getElementById('externalClientToggle');
    const externalInput = document.getElementById('externalClientName');
    let isActive = false;

    toggleBtn.addEventListener('click', function(e) {
        e.preventDefault();
        isActive = !isActive;

        if(isActive){
            toggleBtn.classList.remove('text-warning');
            toggleBtn.classList.add('text-success');
            toggleBtn.innerHTML = '<span class="fas fa-toggle-on fa-lg"></span>';
            externalInput.style.display = 'block';
        } else {
            toggleBtn.classList.remove('text-success');
            toggleBtn.classList.add('text-warning');
            toggleBtn.innerHTML = '<span class="fas fa-toggle-off fa-lg"></span>';
            externalInput.style.display = 'none';
        }
    });

    const deliveryRadios = document.getElementsByName('deliveryType');
    const plateNumberGroup = document.getElementById('plateNumberGroup');
    const wayBillGroup = document.getElementById('wayBillNumber'); 
    const driverGroup = document.getElementById('driverName'); 

    const wayBillLabel = document.querySelector("label[for='wayBillNumber']");
    const driverLabel = document.querySelector("label[for='driverName']");

    deliveryRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'Courier') {
                // Show Way Bill, hide Driver & Plate Number
                wayBillGroup.style.display = 'block';
                wayBillLabel.style.display = 'block';
                wayBillGroup.disabled = false; // enable for validation

                driverGroup.style.display = 'none';
                driverLabel.style.display = 'none';
                driverGroup.disabled = true; // disable hidden input

                plateNumberGroup.style.display = 'none';
            } else {
                // Show Driver & Plate Number, hide Way Bill
                wayBillGroup.style.display = 'none';
                wayBillLabel.style.display = 'none';
                wayBillGroup.disabled = true;

                driverGroup.style.display = 'block';
                driverLabel.style.display = 'block';
                driverGroup.disabled = false;

                plateNumberGroup.style.display = 'block';
            }
        });
    });

    const checkedRadio = Array.from(deliveryRadios).find(r => r.checked);
    if (checkedRadio) checkedRadio.dispatchEvent(new Event('change'));

        let labTestsCache = [];
        let labSamplesCache = [];



    function initializeSelect2(container = $('#sampleDetailsTable')) {
        container.find('select').each(function() {
            if (!$(this).hasClass('select2-hidden-accessible')) {
                let $select = $(this);
                let isPlateOrBatch = $select.hasClass('plate-select') || $select.hasClass('batch-select');
                $select.select2({
                theme: 'bootstrap4',
                width: '100%',
                placeholder: $select.find('option:first').text(),
                allowClear: true,
                dropdownParent: $select.closest('.table-responsive').length 
                    ? $select.closest('.table-responsive') 
                    : $(document.body),
                tags: isPlateOrBatch,
                    language: {
                        noResults: function() {
                            if (isPlateOrBatch) return "No results found. Press Enter to add.";
                            return "No results found";
                        }
                    },
                    matcher: function(params, data) {
                        if ($.trim(params.term) === '') return data;
                        if (data.text.toLowerCase().indexOf(params.term.toLowerCase()) > -1) {
                            return data;
                        }
                        return null;
                    },
                    createTag: function(params) {
                        if (!isPlateOrBatch) return null;
                        let term = $.trim(params.term);
                        if (!term) return null;

                        let exists = false;
                        $select.find('option').each(function() {
                            if ($(this).text().toLowerCase() === term.toLowerCase()) exists = true;
                        });
                        if (exists) return null;

                        return {
                            id: term,
                            text: term,
                            newTag: true
                        };
                    },
                    insertTag: function(data, tag) {
                        data.push(tag);
                    },
                    
                });




            }
        });
    }


    function populateRowDropdowns($row) {
        let $testSelect = $row.find('select[name="laboratoryTests[]"]');
        $testSelect.empty().append('<option value="">Select Test</option>');
        labTestsCache.forEach(t => $testSelect.append(`<option value="${t.id}">${t.name}</option>`));
        $testSelect.trigger('change');

        let $sampleSelect = $row.find('select[name="typeOfSample[]"]');
        $sampleSelect.empty().append('<option value="">Select Sample Type</option>');
        labSamplesCache.forEach(s => $sampleSelect.append(`<option value="${s.id}">${s.name}</option>`));
        $sampleSelect.trigger('change');

        let $leadSelect = $row.find('select[name="leadTimeType[]"]');
        $leadSelect.html('<option value="">Select Lead Time</option>');
    }

    $.fn.select2.defaults.set("dropdownPosition", "below");

    initializeSelect2();

    //DONT COPY THE ROW
    $(document).on('click', '.addRow', function () {
        let $tableBody = $('#sampleDetailsTable tbody');
        let $lastRow = $tableBody.find('tr:last');
        let $newRow = $lastRow.clone(false, false);

        $newRow.find('input[type="text"], input[type="date"]').val('');
        $newRow.find('input[type="checkbox"]').prop('checked', false);

        $newRow.find('span.select2').remove();
        $newRow.find('select').removeAttr('data-select2-id')
            .removeClass('select2-hidden-accessible')
            .removeAttr('tabindex aria-hidden');

        let newIndex = $tableBody.find('tr').length;
        $newRow.find('input[name^="coaRequired"]').attr('name', 'coaRequired[' + newIndex + ']');

        $tableBody.append($newRow);

        let $plateSelect = $newRow.find('select[name="plateVanNumber[]"]');
        $plateSelect.empty().append('<option value="">Plate/Van</option>');
        plateNumbers.forEach(p => $plateSelect.append(`<option value="${p.plate_number}">${p.plate_number}</option>`));

        let $batchSelect = $newRow.find('select[name="batchLotNumber[]"]');
        $batchSelect.empty().append('<option value="">Batch/Lot Number</option>');
        batchNumbers.forEach(b => $batchSelect.append(`<option value="${b.batch_number}">${b.batch_number}</option>`));

        let $sampleSelect = $newRow.find('select[name="sampleName[]"]');
        $sampleSelect.empty().append('<option value="">Sample</option>');
        samples.forEach(s => $sampleSelect.append(`<option value="${s.id}">${s.sample_name}</option>`));

        let $supplierSelect = $newRow.find('select[name="shipmentSupplier[]"]');
        $supplierSelect.empty().append('<option value="">Supplier</option>');
        suppliers.forEach(sp => $supplierSelect.append(`<option value="${sp.id}">${sp.supplier_name}</option>`));

        initializeSelect2($newRow);
        populateRowDropdowns($newRow);
    });


    //COPY THE ADDED ROW
    // $(document).on('click', '.addRow', function () {
    //     let $clickedRow = $(this).closest('tr');
    //     let $tableBody = $('#sampleDetailsTable tbody');

    //     let $newRow = $clickedRow.clone(false, false);

    //     $newRow.find('span.select2').remove();
    //     $newRow.find('select').each(function () {
    //         $(this)
    //             .removeAttr('data-select2-id')
    //             .removeClass('select2-hidden-accessible')
    //             .removeAttr('tabindex aria-hidden');
    //     });

    //     $clickedRow.find('select').each(function (index) {
    //         let val = $(this).val(); 
    //         $newRow.find('select').eq(index).val(val);
    //     });

    //     $clickedRow.find('input[type="text"], input[type="date"], input[type="number"]').each(function (index) {
    //         $newRow.find('input[type="text"], input[type="date"], input[type="number"]').eq(index).val($(this).val());
    //     });

    //     $clickedRow.find('input[type="checkbox"]').each(function (index) {
    //         $newRow.find('input[type="checkbox"]').eq(index).prop('checked', $(this).prop('checked'));
    //     });

    //     let newIndex = $tableBody.find('tr').length;
    //     $newRow.find('input[name^="coaRequired"]').attr('name', 'coaRequired[' + newIndex + ']');

    //     $clickedRow.after($newRow);

    //     initializeSelect2($newRow);
    // });



    $(document).on('click', '.removeRow', function() {
        if($('#sampleDetailsTable tbody tr').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('At least one row is required.');
        }
    });

    $('#internalFeedmill').on('change', function(){
        var internalID = $(this).val();
        var $commercial = $('#commercialFeed');

        if(!internalID){
            $commercial.html('<option value="">Select Feed</option>');
            $commercial.prop('disabled', true);
            return;
        }

        $.ajax({
            url: baseUrl + controllerName + '/get_commercial_feeds/' + internalID,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data.length > 0){
                    $commercial.prop('disabled', false); 
                    $commercial.html('<option value="">Select Feed</option>');
                    $.each(data, function(i, feed){
                        $commercial.append('<option value="'+feed.id+'">'+feed.feedmill_name+'</option>');
                    });
                } else {
                    $commercial.html('<option value="">No related Commercial Feed</option>');
                    $commercial.prop('disabled', true); 
                }
            }
        });
    });

    $('#labLocation').on('change', function () {
        let laboratoryID = $(this).val();

        if (!laboratoryID) {
            $('#sampleDetailsTable tbody select[name="laboratoryTests[]"]').html('<option value="">Select Test</option>');
            $('#sampleDetailsTable tbody select[name="typeOfSample[]"]').html('<option value="">Select Sample Type</option>');
            return;
        }

        $.ajax({
            url: baseUrl + controllerName + '/get_lab_tests/' + laboratoryID,
            type: 'GET',
            dataType: 'json',
            success: function (response) {
                labTestsCache = response.tests || [];
                labSamplesCache = response.samples || [];

                $('#sampleDetailsTable tbody tr').each(function(){
                    populateRowDropdowns($(this));
                });
            },
            error: function () {
                console.error('Failed to load tests for this laboratory.')
            }
        });
    });

    $(document).on('change', 'select[name="laboratoryTests[]"]', function() {
        let $row = $(this).closest('tr');
        let testId = $(this).val();
        let labId = $('#labLocation').val(); 
        let $leadDropdown = $row.find('select[name="leadTimeType[]"]');

        $leadDropdown.html('<option value="">Select Lead Time</option>');

        if (!testId || !labId) return;

        $.ajax({
            url: baseUrl + controllerName + '/get_lead_times',
            type: 'GET',
            dataType: 'json',
            data: {
                laboratory_id: labId,
                test_id: testId
            },
            success: function(data) {
                let options = '<option value="">Lead Time</option>';
                if (data.length > 0) {
                    $.each(data, function(i, lead) {
                        options += `<option value="${lead.value}">${lead.label}</option>`;
                    });
                } else {
                    options += '<option value="">No lead times available</option>';
                }
                $leadDropdown.html(options);
            },
            error: function() {
                console.error('Error fetching lead times.');
            }
        });
    });


    $('#registrationForm').on('submit', function(e) {
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

    const $form = $('#registrationForm');
    const $sampleSection = $('#sampleDetailsTable, #sampleDetailsTable select, #sampleDetailsTable input');
    const $saveBtn = $('button[type="submit"]');
    
    disableSampleSection();

    const headerFields = [
        '#internalFeedmill',
        // '#address',
        '#nutritionist',
        '#labLocation',
    ];

    headerFields.forEach(selector => {
        $(selector).on('change keyup', validateHeaderFields);
    });

    function validateHeaderFields() {
        let allFilled = true;

        headerFields.forEach(selector => {
            const val = $(selector).val()?.trim();
            if (!val) allFilled = false;
        });

        if (allFilled) {
            enableSampleSection();
        } else {
            disableSampleSection();
        }
    }

    function disableSampleSection() {
        $sampleSection.prop('disabled', true).addClass('bg-light');
        $saveBtn.prop('disabled', true);
        $('#sampleDetailsTable').css('opacity', 0.6);
    }

    function enableSampleSection() {
        $sampleSection.prop('disabled', false).removeClass('bg-light');
        $saveBtn.prop('disabled', false);
        $('#sampleDetailsTable').css('opacity', 1);
    }


    document.getElementById('attachFile').addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        const maxSize = 2 * 1024 * 1024; 
        if (file.size > maxSize) {
            alert("The file size exceeds 2 MB. Please choose a smaller file.");
            this.value = ''; 
        }
    }
});

$(document).on('click', '#backToVerification', function() {
    window.location.href = baseUrl + controllerName;
});




});
