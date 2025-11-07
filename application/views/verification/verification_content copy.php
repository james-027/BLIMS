<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    
    <?php if(!empty($verification_jobs)): ?>

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


    <?php if(!$isViewOnly): ?>
    <form method="post" action="<?=base_url($controller.'/submit_verification')?>" enctype="multipart/form-data"
        id="verificationForm">
        <?php endif; ?>

        <?php foreach($verification_jobs as $jobIndex => $job): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse"
                        data-target="#job-<?= $jobIndex ?>" aria-expanded="true" style="cursor:pointer;">

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0">
                                Job Order No:
                                <span class="text-white font-weight-bold"><?= $job['job_order_no'] ?></span>
                            </h5>
                            <span class="badge badge-light">Samples: <?= count($job['samples'] ?? []) ?></span>
                        </div>
                    <?php if (!$isViewOnly): ?>
                        <?php $jobAttachments = $attachments[$job['job_order_no']] ?? $attachments[$jobIndex] ?? []; ?>
                        <?php if (!empty($jobAttachments)): ?>
                        <div class="mt-1">
                            <strong class="text-white">Attachment:</strong>
                            <?php foreach ($jobAttachments as $file): ?>
                            <a href="<?= base_url('uploads/trans_attachments/'.$file['filename']) ?>" target="_blank"
                                class="badge badge-light text-dark ml-1">
                                <i class="fas fa-paperclip"></i> <?= $file['filename'] ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                     <?php endif; ?>   
                    </div>

                    <div id="job-<?= $jobIndex ?>" class="collapse show">
                        <div class="card-body p-0">
                            <div class="verification-section">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped mb-0 verification">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;">No.</th>
                                                <th style="width:250px;">Laboratory Code</th>
                                                <th>Sample Name</th>
                                                <th style="width:120px;">Production Date</th>
                                                <th>Shipment Supplier</th>
                                                <th>Plate / Van Number</th>
                                                <th>Batch / Lot Number</th>
                                                <th>Type of Sample</th>
                                                <th>Laboratory Tests</th>
                                                <th>Test Parameter</th>
                                                <th style="width:150px;">Test Status</th>
                                                <th style="width:200px;">Reason</th>
                                                <th>COA Required</th>
                                                <th style="width:200px;">Remarks</th>
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
                                                <td class="align-middle"><?= $detail['sample_type_name'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['param_name'] ?? '-' ?></td>


                                                <td class="text-center align-middle">
                                                    <?php if($isViewOnly): ?>
                                                    <?php
                                                            $statusText = 'Ongoing';
                                                            $statusClass = 'badge-primary'; 

                                                            foreach($test_statuses as $s){
                                                                if($s->statusID == $detail['test_status_id']){
                                                                    $statusText = $s->statDesc;
                                                                    if (stripos($statusText, 'pass') !== false) {
                                                                        $statusClass = 'badge-success';
                                                                    } elseif (stripos($statusText, 'fail') !== false) {
                                                                        $statusClass = 'badge-danger';
                                                                    } elseif (stripos($statusText, 'hold') !== false) {
                                                                        $statusClass = 'badge-warning text-dark';
                                                                    } else {
                                                                        $statusClass = 'badge-secondary';
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        ?>
                                                    <span class="badge <?= $statusClass ?> px-3 py-2"
                                                        style="font-size:0.85rem;">
                                                        <?= $statusText ?>
                                                    </span>
                                                    <?php else: ?>
                                                    <select name="test_status[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm test-status-select dynamic_dropdown"
                                                        data-target="#reason-<?= $detail['trans_detail_id'] ?>"
                                                        required>
                                                        <option value="">Select Status</option>
                                                        <?php foreach($test_statuses as $status): ?>
                                                        <option value="<?= $status->statusID ?>"
                                                            <?= $detail['test_status_id'] == $status->statusID ? 'selected' : '' ?>>
                                                            <?= $status->statDesc ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php endif; ?>
                                                </td>


                                                <!-- Reason -->
                                                <td class="align-middle">
                                                    <?php if($isViewOnly): ?>
                                                    <?php
                                                            $reasonText = 'No Reason Indicate';
                                                            foreach($reasons as $r){
                                                                if(($selectedReason->reason_id ?? '') == $r->id){
                                                                    $reasonText = $r->reason_name;
                                                                    break;
                                                                }
                                                            }
                                                        ?>
                                                    <span class="text-dark"><?= $reasonText ?></span>
                                                    <?php else: ?>
                                                    <select name="reasons[<?= $detail['trans_detail_id'] ?>]"
                                                        id="reason-<?= $detail['trans_detail_id'] ?>"
                                                        class="form-control form-control-sm reason-select dynamic_dropdown"
                                                        required>
                                                        <option value="">Select Reason</option>
                                                        <?php foreach($reasons as $reason): ?>
                                                        <option value="<?= $reason->id ?>"
                                                            <?= ($selectedReason->reason_id ?? '') == $reason->id ? 'selected' : '' ?>>
                                                            <?= $reason->reason_name ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php endif; ?>
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
                                                    <?php if($isViewOnly): ?>
                                                    <span><?= htmlspecialchars($detail['existing_remark'] ?? 'No Remarks', ENT_QUOTES, 'UTF-8') ?></span>
                                                    <?php else: ?>
                                                    <input type="text" name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                    <?php endif; ?>
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

        <?php if(!$isViewOnly): ?>
        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtn" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger" onclick="window.history.back();">Cancel</button>
            </div>
        </div>
        <?php endif; ?>

        <?php if(!$isViewOnly): ?>
    </form>
    <?php endif; ?>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No job orders pending verification
        </div>
    </div>
    <?php endif; ?>
</div>

<?php if(!$isViewOnly): ?>
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Verification
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
                        <div>Passed</div>
                        <div id="countPassed" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-hold">
                        <div><i class="fas fa-pause-circle fa-lg mb-1"></i></div>
                        <div>On Hold</div>
                        <div id="countHold" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-failed">
                        <div><i class="fas fa-times-circle fa-lg mb-1"></i></div>
                        <div>Failed</div>
                        <div id="countFailed" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>

                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with verification?
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmSubmit" class="btn btn-success px-4">
                    <i class="fas fa-check mr-1"></i> Yes, Proceed
                </button>
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>