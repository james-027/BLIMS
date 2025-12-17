<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <div class="row align-items-center justify-content-between mt-3">
        <?php if (!empty($new_button)): ?>
        <div class="col-md-4 col-sm-12 mb-2 mb-md-0 pl-3">

            <button type="button" id="add-registration" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle mr-1"></i> Add Registration
            </button>

        </div>
        <?php endif; ?>

          <div class="row align-items-center justify-content-end mt-3">
        <div class="col-auto pr-1">
            <select id="searchField" class="form-control shadow-sm" style = "cursor:pointer">
                <option value="">All Fields</option>
                <option value="job_order_no">Job Order No</option>
                <option value="lab_code">Lab Code</option>
                <option value="status">Status</option>
                <option value="sample_name">Sample Name</option>
                <option value="test_name">Laboratory Test</option>
            </select>
        </div>
        <div class="col-auto pl-1">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>
    </div>

    <?php if(!empty($verification_jobs)): ?>


    <div id="jobsContainer">
            <?php $this->load->view('registration/registration_container', ['jobs'=>$verification_jobs, 'thColor'=>$thColor, 'display_status'=>$display_status]); ?>

      
    </div>
    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            Click "Add Registration" to Generate Job Order.
        </div>
    </div>
    <?php endif; ?>

    <form method="post" action="<?=base_url($controller.'/add_sample_details')?>" enctype="multipart/form-data"
        id="newSampleForm">
        <div class="modal fade" id="addDetailModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Sample Detail</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <input type="hidden" name="jobIndex" id="modal_jobIndex">
                    <input type="hidden" name="labId" id="modal_labId">
                    <input type="hidden" name="transId" id="modal_transId">

                    <div class="modal-body">

                        <div class="form-group">
                            <label>Sample Name</label>
                            <select id="modal_sampleName" name="sampleName[]"
                                class="form-control dynamic_dropdown_modal" required>
                                <option value="">Sample</option>
                                <?php foreach($samples as $sample): ?>
                                <option value="<?= $sample->id ?>"><?= $sample->sample_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Type of Sample</label>
                            <select id="modal_typeOfSample" name="typeOfSample[]"
                                class="form-control dynamic_dropdown_modal" required></select>
                        </div>

                        <div class="form-group">
                            <label>Test Code</label>
                            <select id="modal_testCode" name="testCode[]" class="form-control dynamic_dropdown_modal"
                                required></select>
                        </div>

                        <div class="form-group">
                            <label>Production/Delivery Date</label>
                            <input type="date" id="modal_productionDate" name="productionDate[]"
                                class="form-control dynamic_dropdown_modal" required>
                        </div>

                        <div class="form-group">
                            <label>Shipment/Supplier</label>
                            <select id="modal_shipmentSupplier" name="shipmentSupplier[]"
                                class="form-control dynamic_dropdown_modal" required>
                                <option value="">Supplier</option>
                                <?php foreach($suppliers as $supplier): ?>
                                <option value="<?= $supplier->id ?>"><?= $supplier->supplier_name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Plate / Van Number</label>
                            <select id="modal_plateVanNumber" name="plateVanNumber[]"
                                class="form-control dynamic_dropdown_modal" required>
                                <option value="">Plate/Van</option>
                                <?php foreach($plate_numbers as $pm): ?>
                                <option value="<?= $pm->plate_number ?>"><?= $pm->plate_number ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Batch / Lot Number</label>
                            <select id="modal_batchLotNumber" name="batchLotNumber[]"
                                class="form-control dynamic_dropdown" required>
                                <option value="">Batch/Lot</option>
                                <?php foreach($batches as $batch): ?>
                                <option value="<?= $batch->batch_number ?>"><?= $batch->batch_number ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Type of Lead Time</label>
                            <select id="modal_leadTimeType" name="leadTimeType[]" class="form-control"
                                required></select>
                        </div>

                        <div class="form-group">
                            <label>COA Required: </label>
                            <input type="checkbox" name="coaRequired[]" id="modal_coaRequired">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="saveNewDetailBtn" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmNewSampleModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="confirmModalLabel">
                            <i class="fas fa-exclamation-circle mr-2"></i> Confirm New Samples
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="confirm-text mt-4">
                            Are you sure you want to add this Samples?
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" id="confirmSubmitNewSample" class="btn btn-success px-4">
                            <i class="fas fa-check mr-1"></i> Yes, Proceed
                        </button>
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    <form method="post" action="<?=base_url($controller.'/replicate_sample_details')?>" enctype="multipart/form-data"
        id="replicateSampleForm">
        <div class="modal fade" id="replicateDetailModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <span class="font-weight-bold">Replicate Sample Details:</span>
                            <span id="replicateLabCode" class="font-weight-bold"></span>
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <input type="hidden" name="jobIndex" id="rep_jobIndex">
                    <input type="hidden" name="labId" id="rep_labId">
                    <input type="hidden" name="transId" id="rep_transId">
                    <input type="hidden" name="detailId" id="rep_detailId">

                    <div class="modal-body">

                        <div class="form-group">
                            <label>Sample Name</label>
                            <input type="text" id="rep_sampleName" name="sampleName[]" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Type of Sample</label>
                            <select id="rep_typeOfSample" name="typeOfSample[]"
                                class="form-control dynamic_dropdown_modal" required></select>

                        </div>

                        <div class="form-group">
                            <label>Test Code</label>
                            <select id="rep_testCode" name="testCode[]" class="form-control dynamic_dropdown_modal"
                                required></select>
                        </div>

                        <div class="form-group">
                            <label>Production/Delivery Date</label>
                            <input type="date" id="rep_productionDate" name="productionDate[]"
                                class="form-control dynamic_dropdown_modal" required>
                        </div>

                        <div class="form-group">
                            <label>Shipment/Supplier</label>
                            <input type="text" id="rep_shipmentSupplier" name="shipmentSupplier[]" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Plate / Van Number</label>
                            <input type="text" id="rep_plateVanNumber" name="plateVanNumber[]" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Batch / Lot Number</label>
                            <input type="text" id="rep_batchLotNumber" name="batchLotNumber[]" class="form-control"
                                required>
                        </div>

                        <div class="form-group">
                            <label>Type of Lead Time</label>
                            <select id="rep_leadTimeType" name="leadTimeType[]" class="form-control" required></select>
                        </div>

                        <div class="form-group">
                            <label>COA Required: </label>
                            <input type="checkbox" name="coaRequired[]" id="rep_coaRequired">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="saveReplicateDetailBtn" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                    </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="confirmReplicateSampleModal" tabindex="-1" role="dialog"
            aria-labelledby="confirmModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="confirmModalLabel">
                            <i class="fas fa-exclamation-circle mr-2"></i> Replicating this Sample
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p class="confirm-text mt-4">
                            Are you sure you want to Replicate this Samples?
                        </p>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" id="confirmReplicateSubmitSample" class="btn btn-success px-4">
                            <i class="fas fa-check mr-1"></i> Yes, Proceed
                        </button>
                        <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>



</div>




<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>