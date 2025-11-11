<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($jobs)): ?>

    <div class="row align-items-center justify-content-end mt-3">
        <div class="col-md-4 col-sm-12">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <form method="post" action="<?=base_url($controller.'/submit_final_prep')?>" enctype="multipart/form-data" id="finalPreparationForm">
        <?php foreach($jobs as $jobIndex => $job): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse"
                        data-target="#job-<?= $jobIndex ?>" aria-expanded="<?= $jobIndex === 0 ? 'true' : 'false' ?>"  style="cursor:pointer;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h5 class="mb-0">
                                Job Order No:
                                <span class="text-white font-weight-bold"><?= $job['job_order_no'] ?></span>
                            </h5>

                            <div class="d-flex align-items-center">
                                <span class="badge badge-light mr-2">Samples: <?= count($job['samples'] ?? []) ?></span>
                                <i class="fas fa-chevron-down collapse-icon"></i>
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
                                                <th style="width:250px;">Laboratory Code</th>
                                                <th>Sample Name</th>
                                                <th style="width:120px;">Production Date</th>
                                                <th>Shipment Supplier</th>
                                                <th>Plate / Van Number</th>
                                                <th>Batch / Lot Number</th>
                                                <th>Type of Sample</th>
                                                <th>Laboratory Tests</th>
                                                <th>Test Parameter</th>
                                                <th>Pre Analytical Analyst</th>
                                                <th>Preparation Verification</th>
                                                <th>Remarks</th>
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

                                                <td class="align-middle" style="max-width: 200px;">
                                                    <span>
                                                        <?php
                                                            $selectedAnalytical = 'No Status';
                                                            foreach ($analyticals as $analytical) {
                                                                if ($detail['pre_analytical_id'] == $analytical->statusID) {
                                                                    $selectedAnalytical = $analytical->statDesc;
                                                                    break;
                                                                }
                                                            }
                                                            echo htmlspecialchars($selectedAnalytical, ENT_QUOTES, 'UTF-8');
                                                        ?>
                                                    </span>
                                                </td>


                                            <td class="text-center align-middle">
                                                <select  id="prepverification-<?= $detail['trans_detail_id'] ?>" name="prep_verifications[<?= $detail['trans_detail_id'] ?>]"
                                                    class="form-control form-control-sm verification-select dynamic_dropdown status-zfix"
                                                    data-target="#prepverification-<?= $detail['trans_detail_id'] ?>"
                                                    required>
                                                    <option value="">Select Verification</option>
                                                    <?php foreach($prep_verifications as $prep_verification): ?>
                                                        <option value="<?= $prep_verification->statusID ?>"
                                                            <?= $detail['prep_verification_status_id'] == $prep_verification->statusID ? 'selected' : '' ?>>
                                                            <?= $prep_verification->statDesc ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <td class="align-middle">
                                                    <input type="text" name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                            </td>


                                            </tr>
                                            <?php endforeach; ?>
                                            <?php else: ?>
                                            <tr>
                                                <td colspan="10" class="text-center text-muted">No samples available</td>
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

        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtnFinal" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger" onclick="window.location.href='<?= base_url('finalpreparation') ?>';">Cancel</button>
            </div>
        </div>

    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Final Preparation data
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModalFinal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Final Prepration
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
                    <div class="status-box status-failed">
                        <div><i class="fas fa-times-circle fa-lg mb-1"></i></div>
                        <div>Failed</div>
                        <div id="countFailed" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>

                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with Final Preparation?
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmFinalSubmit" class="btn btn-success px-4">
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
