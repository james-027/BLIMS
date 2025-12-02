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

        <div class="col-md-4 col-sm-12">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <?php if(!empty($verification_jobs)): ?>


    <div id="jobsContainer">
        <?php foreach($verification_jobs as $jobIndex => $job): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse"
                        data-target="#job-<?= $jobIndex ?>" aria-expanded="<?= $jobIndex === 0 ? 'true' : 'false' ?>"
                        style="cursor:pointer;">

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0">
                                Job Order No:
                                <span class="text-white font-weight-bold"><?= $job['job_order_no'] ?></span>
                            </h5>
                            <div class="d-flex align-items-center">
                                <span class="mr-1">
                                 <button type="button" class="btn btn-light btn-sm mr-3 addDetailBtn"
                                        data-job="<?= $jobIndex ?>" 
                                        data-lab-id="<?= $job['laboratory_id']?>"
                                        data-trans-id="<?= $job['trans_id'] ?>"
                                        <?= !empty($job['is_all_released']) 
                                            ? 'disabled style="opacity:0.5;" title="All Released"' 
                                            : '' ?>>
                                    <i class="fas fa-plus"></i> Add Detail
                                </button>
                                </span>
                                <span class="badge badge-light mr-3">Samples:
                                    <?= count($job['samples'] ?? []) ?></span>

                                <span class="ml-2">
                                    <i class="fas fa-chevron-down collapse-icon"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div id="job-<?= $jobIndex ?>" class="collapse <?= $jobIndex === 0 ? 'show' : '' ?>">
                        <div class="card-body p-0">
                            <div class="verification-section">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped mb-0 verification">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;">No.</th>
                                                <th style="width:150px;">Laboratory Code</th>
                                                <th style="width:150px;">Sample Name</th>
                                                <th style="width:120px;">Production Date</th>
                                                <th style="width:150px;">Shipment Supplier</th>
                                                <th>Plate / Van Number</th>
                                                <th>Batch / Lot Number</th>
                                                <th>Type of Sample</th>
                                                <th>Laboratory Tests</th>
                                                <th>Test Parameter</th>
                                                <th style="width:150px;">Test Status</th>
                                                <th style="width:200px;">Reason</th>
                                                <th>COA Required</th>
                                                <th style="width:200px;">Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (!empty($job['samples'])): ?>
                                            <?php $itemNo = 1; ?>
                                            <?php foreach ($job['samples'] as $detail): ?>
                                            <?php
                                                                            $selectedReason = $this->main->get_data('trans_reasons', ['trans_detail_id' => $detail['trans_detail_id']], true);
                                                                        ?>
                                            <tr>
                                                <td class="text-center align-middle"><?= $itemNo++ ?></td>
                                                <td class="align-middle"><?= $detail['lab_code'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['sample_name'] ?? '-' ?></td>
                                                <td class="align-middle">
                                                    <?= !empty($detail['delivery_date']) ? date('Y-m-d', strtotime($detail['delivery_date'])) : '-' ?>
                                                </td>
                                                <td class="align-middle"><?= $detail['supplier_name'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['plate_number'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['batch_number'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['sample_type_name'] ?? '-' ?>
                                                </td>
                                                <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?>
                                                </td>
                                                <td class="align-middle"><?= $detail['param_name'] ?? '-' ?></td>


                                                <td class="text-center align-middle">
                                                    <?php
                                                                                        $statusText = 'ONGOING';
                                                                                        $statusClass = 'badge-primary'; 
                                                                                        foreach($test_statuses as $s){
                            
                                                                                            if($s->statusID == $detail['test_status_id']){
                                                                                                $statusText = $s->statDesc;
                                                                                                if ($s->statusID == 22) {          
                                                                                                    $statusClass = 'badge-success';
                                                                                                } elseif ($s->statusID == 23) {   
                                                                                                    $statusClass = 'badge-danger';
                                                                                                } elseif ($s->statusID == 7) {   
                                                                                                    $statusClass = 'badge-warning text-dark';
                                                                                                } elseif ($s->statusID == 25) {   
                                                                                                    $statusClass = 'badge-dark';
                                                                                                } else {
                                                                                                    $statusClass = 'badge-secondary';
                                                                                                }
                                                                                                break;
                                                                                            }
                                                                                        }
                                                                                    ?>
                                                    <span class="badge <?= $statusClass ?> px-3 py-2">
                                                        <?= $statusText ?>
                                                    </span>

                                                </td>

                                                <td class="align-middle">
                                                    <?php
                                                                                        $reasonText = !empty($detail['latest_reason']) ? $detail['latest_reason'] : 'No Reason Indicated';
                                                                                    ?>
                                                    <span class="text-dark"><?php echo $reasonText; ?></span>
                                                </td>


                                                <!-- COA -->
                                                <td class="text-center align-middle">
                                                    <?php if($detail['coa_flag'] == 'Y'): ?>
                                                    <span class="badge badge-success">Yes</span>
                                                    <?php else: ?>
                                                    <span class="badge badge-danger">No</span>
                                                    <?php endif; ?>
                                                </td>

                                                <!-- Remarks -->
                                                <td class="align-middle">
                                                    <span><?= htmlspecialchars($detail['existing_remark'] ?? 'No Remarks', ENT_QUOTES, 'UTF-8') ?></span>
                                                </td>

                                                <td class="text-center align-middle">
                                                    <span>

                                                     <button type="button"
                                                        class="btn btn-primary btn-sm replicateDetailBtn"
                                                        data-job="<?= $jobIndex ?>"
                                                        data-lab-id="<?= $job['laboratory_id'] ?>"
                                                        data-trans-id="<?= $job['trans_id'] ?>"
                                                        data-detail-id="<?= $detail['trans_detail_id'] ?>"
                                                        data-lab-code="<?= $detail['lab_code'] ?>"
                                                        <?= !empty($detail['replicate_disabled']) ? 'disabled title = "Already Released"' : '' ?>  
                                                    >
                                                        <i class="fas fa-clone" title="Replicate Sample"></i>
                                                    </button>


                                                        
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="13" class="text-center text-muted">No samples available
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
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