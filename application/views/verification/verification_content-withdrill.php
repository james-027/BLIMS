<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($verification_jobs)): ?>

    <div class="row justify-content-end mt-3">
        <div class="col-md-4">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <form method="post" action="<?=base_url($controller.'/submit_verification')?>" enctype="multipart/form-data" id="verificationForm">

        <?php foreach($verification_jobs as $jobIndex => $job): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse" data-target="#job-<?= $jobIndex ?>" aria-expanded="true" style="cursor:pointer;">

                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0">
                                Job Order No:
                                <span class="text-white font-weight-bold"><?= $job['job_order_no'] ?></span>
                            </h5>
                            <span class="badge badge-light">Samples: <?= count($job['samples'] ?? []) ?></span>
                        </div>

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
                    </div>

                    <div id="job-<?= $jobIndex ?>" class="collapse show">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width:50px;">Item No</th>
                                            <th style="width:200px;">Laboratory Code</th>
                                            <th>Sample Name</th>
                                            <th style="width:150px;">Production Date</th>
                                            <th>Shipment Supplier</th>
                                            <th>Plate / Van No.</th>
                                            <th>Batch / Lot No.</th>
                                            <th>Type of Sample</th>
                                            <th>Laboratory Tests</th>
                                            <th style="width:80px;">Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($job['samples'])): ?>
                                        <?php $itemNo = 1; ?>
                                        <?php foreach ($job['samples'] as $detail): ?>
                                        <?php $selectedReason = $this->main->get_data('trans_reasons', ['trans_detail_id' => $detail['trans_detail_id']], true); ?>
                                        <tr class="main-row" data-id="<?= $detail['trans_detail_id'] ?>">
                                            <td class="text-center align-middle"><?= $itemNo++ ?></td>
                                            <td class="align-middle"><?= $detail['lab_code'] ?? '-' ?></td>
                                            <td class="align-middle"><?= $detail['sample_name'] ?? '-' ?></td>
                                            <td class="align-middle"><?= !empty($detail['delivery_date']) ? date('Y-m-d', strtotime($detail['delivery_date'])) : '-' ?></td>
                                            <td class="align-middle"><?= $detail['supplier_name'] ?? '-' ?></td>
                                            <td class="align-middle"><?= $detail['plate_number'] ?? '-' ?></td>
                                            <td class="align-middle"><?= $detail['batch_number'] ?? '-' ?></td>
                                            <td class="align-middle"><?= $detail['sample_type_name'] ?? '-' ?></td>
                                            <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?></td>
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-info btn-sm toggle-details">
                                                    <i class="fas fa-chevron-down"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Drilldown Row -->
                                        <tr class="detail-row" id="detail-<?= $detail['trans_detail_id'] ?>" style="display: none;">
                                            <td colspan="10">
                                                <div class="p-3">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label>Test Status</label>
                                                            <select name="test_status[<?= $detail['trans_detail_id'] ?>]"
                                                                class="form-control form-control-sm dynamic_dropdown" required>
                                                                <option value="">Select Status</option>
                                                                <?php foreach($test_statuses as $status): ?>
                                                                <option value="<?= $status->statusID ?>"
                                                                    <?= $detail['test_status_id'] == $status->statusID ? 'selected' : '' ?>>
                                                                    <?= $status->statDesc ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Reason</label>
                                                            <select name="reasons[<?= $detail['trans_detail_id'] ?>]"
                                                                class="form-control form-control-sm dynamic_dropdown" required>
                                                                <option value="">Select Reason</option>
                                                                <?php foreach($reasons as $reason): ?>
                                                                <option value="<?= $reason->id ?>"
                                                                    <?= ($selectedReason->reason_id ?? '') == $reason->id ? 'selected' : '' ?>>
                                                                    <?= $reason->reason_name ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label>Remarks</label>
                                                            <input type="text" name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                                class="form-control form-control-sm"
                                                                value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                        </div>
                                                    </div>

                                                    <div class="mt-3">
                                                        <strong>COA Required:</strong>
                                                        <?php if($detail['coa_flag'] == 'Y'): ?>
                                                            <span class="badge badge-success">Yes</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">No</span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                        <?php else: ?>
                                        <tr><td colspan="10" class="text-center text-muted">No samples available</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtn" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger" onclick="window.history.back();">Cancel</button>
            </div>
        </div>
    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No job orders pending verification
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- 🔹 Confirmation Modal -->
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

<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';


</script>
