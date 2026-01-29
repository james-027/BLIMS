<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>


 


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
                            <input
                                type="text"
                                id="rep_typeOfSample"
                                name="typeOfSample[]"
                                class="form-control"
                                readonly
                            >
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
    
<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($jobs)): ?>

     <div class="row align-items-center justify-content-end mt-3">
        <div class="col-auto pr-1">
            <select id="searchField" class="form-control shadow-sm" style = "cursor:pointer">
                <option value="">All Fields</option>
                <option value="job_order_no">Job Order No</option>
                <option value="lab_code">Lab Code</option>
                <option value="date_submitted">Date Submitted</option>
                <option value="lab_result">Lab Result</option>
                <option value="sample_name">Sample Name</option>
                <option value="test_name">Laboratory Test</option>
            </select>
        </div>
        <div class="col-auto pl-1">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <form method="post" action="<?=base_url($controller.'/submit_test_exec')?>" enctype="multipart/form-data"
        id="testExecutionForm">

        <div id="jobsContainer">
            <?php $this->load->view('test_execution/test_execution_container', ['jobs'=>$jobs, 'thColor'=>$thColor, 'display_status'=>$test_statuses]); ?>
          
        </div>
        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtnTest" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger"
                    onclick="window.location.href='<?= base_url('testexecution') ?>';">Cancel</button>
            </div>
        </div>

    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Test Execution and Data Entry
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModalTestExec" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Test Execution and Data Entry
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center font-weight-bold mb-3 text-dark">Status Summary</p>

                <div class="status-summary">
                    <div class="status-box status-passed">
                        <div><i class="fas fa-check-circle fa-lg mb-1"></i></div>
                        <div>Complete</div>
                        <div id="countComplete" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-hold">
                        <div><i class="fas fa-pause-circle fa-lg mb-1"></i></div>
                        <div>On Going</div>
                        <div id="countOngoing" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>
                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with Test Execution and Data Entry?
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmTestExecSubmit" class="btn btn-success px-4">
                    <i class="fas fa-check mr-1"></i> Yes, Proceed
                </button>
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewLogsModal" tabindex="-1" role="dialog" aria-labelledby="viewLogsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                <div>
                    <h5 class="modal-title mb-0 font-weight-bold">Laboratory Test Progress Timeline</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body bg-light">
                <div id="logsLoader" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading logs...</p>
                </div>

                <div id="noLogsMessage" class="text-center text-muted d-none mt-4">
                    <i class="fas fa-info-circle fa-lg mb-2 d-block"></i>
                    No logs available for this test.
                </div>

                <div id="logsTimeline" class="timeline d-none"></div>
            </div>
        </div>
    </div>
</div>






<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>