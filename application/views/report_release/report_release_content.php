<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($jobs)): ?>

<div class="row align-items-center justify-content-end mt-3">
    <!-- <div class="col-auto pr-1">
        <select id="searchColumn" class="form-control shadow-sm dynamic_dropdown">
            <option value="all">All Columns</option>
            <option value="job_order_no">Job Order</option>
            <option value="lab_code">Laboratory Code</option>
            <option value="sample_name">Sample Name</option>
            <option value="client_name">Client</option>
            <option value="nutritionist_name">Nutritionist</option>
        </select>
    </div> -->

    <div class="col-auto pl-1">
        <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
    </div>
</div>


    <form method="post" action="<?=base_url($controller.'/submit_for_release')?>" enctype="multipart/form-data"
        id="releaseForm">
        <div id="jobsContainer">
            <?php foreach($jobs as $jobIndex => $job): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse"
                            data-target="#job-<?= $jobIndex ?>" aria-expanded="true" style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <div>
                                    <h5 class="mb-0">
                                        Job Order No:
                                        <span class="text-white font-weight-bold job_order_no"><?= $job['job_order_no'] ?></span>
                                    </h5>
                                    <div class="mt-1">
                                        Client: <span class="text-white font-weight-bold client_name"><?= $job['client_name'] ?>
                                    </div>
                                    <div class="mt-1">
                                        Nutritionist: <span
                                            class="text-white font-weight-bold nutritionist_name"><?= $job['nutritionist_name'] ?>
                                    </div>

                                </div>
                                <div class="d-flex align-items-center">

                                    <span class="badge badge-light mr-3">
                                        Samples: <?= count($job['samples'] ?? []) ?>
                                    </span>

                                    <span class="mr-3">
                                        <button type="button"
                                            class="btn btn-sm btn-warning text-dark font-weight-bold select-all-btn"
                                            data-job="#job-<?= $jobIndex ?>"
                                    >
                                            <i class="fas fa-check-square mr-1"></i> Select All
                                        </button>
                                    </span>


                                    <span class="ml-2">
                                        <i class="fas fa-chevron-down collapse-icon"></i>
                                    </span>

                                </div>

                            </div>

                        </div>

                        <div id="job-<?= $jobIndex ?>" class="collapse show">
                            <div class="card-body p-0">
                                <div class="verification-section">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-striped mb-0 verication">
                                            <thead>
                                                <tr>
                                                    <th style="width:50px;" title="No.">No.</th>
                                                    <th style="width:150px;" title="Date Submitted">Date Submitted</th>
                                                    <th style="width:150px;" title="Laboratory Code">Laboratory Code</th>
                                                    <th title="Sample Name">Sample Name</th>
                                                    <th title="Laboratory Tests">Laboratory Tests</th>
                                                    <th title="Result">Result</th>
                                                    <th title="Test Execution">Test Execution</th>
                                                    <th title="Data Review">Data Review</th>
                                                    <th style="width:110px;" title="Result Verification">Result Verification
                                                    </th>
                                                    <th title="With COA required">With COA required</th>
                                                    <th title="Releasing">For Releasing</th>
                                                    <th style="width:150px;" title="Reference No.">Reference No.</th>
                                                    <th title="Generate COA">Generate COA</th>
                                                    <th title="Logs">Logs</th>

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
                                                    <td class="align-middle">
                                                        <?php if (!empty($detail['date_submitted'])): ?>
                                                        <?= date('F d, Y', strtotime($detail['date_submitted'])) ?>
                                                        <input type="hidden"
                                                            name="date_submitted[<?= $detail['trans_detail_id'] ?>]"
                                                            value="<?= htmlspecialchars($detail['date_submitted']) ?>">
                                                        <?php else: ?>
                                                        -
                                                        <input type="hidden"
                                                            name="date_submitted[<?= $detail['trans_detail_id'] ?>]"
                                                            value="">
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="align-middle lab_code"><?= $detail['lab_code'] ?? '-' ?></td>
                                                    <td class="align-middle sample_name"><?= $detail['sample_name'] ?? '-' ?></td>
                                                    <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?></td>
                                                    <td class="align-middle">
                                                        <span><?= htmlspecialchars($detail['test_exec_lab_result'] ?? 'No Lab Result', ENT_QUOTES, 'UTF-8') ?></span>
                                                    </td>

                                                    <td class="align-middle" style="max-width: 200px;">
                                                        <span>
                                                            <?php
                                                                $selectedStatus = 'No Status';
                                                                foreach ($display_status as $status) {
                                                                    if ($detail['test_exec_status_id'] == $status->statusID) {
                                                                        $selectedStatus = $status->statDesc;
                                                                        break;
                                                                    }
                                                                }
                                                                echo htmlspecialchars($selectedStatus, ENT_QUOTES, 'UTF-8');
                                                            ?>
                                                        </span>
                                                    </td>

                                                    <td class="align-middle" style="max-width: 200px;">
                                                        <span>
                                                            <?php
                                                                $selectedStatus = 'No Status';
                                                                foreach ($display_status as $status) {
                                                                    if ($detail['review_verification_status_id'] == $status->statusID) {
                                                                        $selectedStatus = $status->statDesc;
                                                                        break;
                                                                    }
                                                                }
                                                                echo htmlspecialchars($selectedStatus, ENT_QUOTES, 'UTF-8');
                                                            ?>
                                                        </span>
                                                    </td>
                                                    <td class="align-middle" style="max-width: 200px;">
                                                        <span>
                                                            <?php
                                                                $selectedStatus = 'No Status';
                                                                foreach ($display_status as $status) {
                                                                    if ($detail['test_result_id'] == $status->statusID) {
                                                                        $selectedStatus = $status->statDesc;
                                                                        break;
                                                                    }
                                                                }
                                                                echo htmlspecialchars($selectedStatus, ENT_QUOTES, 'UTF-8');
                                                            ?>
                                                        </span>
                                                    </td>

                                                    <td class="text-center align-middle">
                                                        <?php if($detail['coa_flag'] == 'Y'): ?>
                                                        <span class="badge badge-success">Yes</span>
                                                        <?php else: ?>
                                                        <span class="badge badge-danger">No</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <?php
                                                    $encrypted_id = $this->encryption->encrypt($detail['trans_detail_id']);
                                                    $encrypted_id = rtrim(strtr(base64_encode($encrypted_id), '+/', '-_'), '=');

                                                    ?>

                                                    <td class="align-middle text-center">
                                                    <input 
                                                            type="checkbox" 
                                                            name="releasing[<?= $detail['trans_detail_id'] ?>]"
                                                            class="select-releasing" 
                                                            <?= (!empty($detail['is_released']) && $detail['is_released'] == 1) ? 'checked data-prechecked="1" disabled' : '' ?>
                                                        >

                                                    </td>

                                                    <td class="align-middle"><?= $detail['release_ref_number'] ?? '-' ?></td>


                                                    <td class="align-middle text-center">
                                                        <?php if (!empty($detail['is_released'])): ?>
                                                            <a href="<?= base_url('coa/select_template_pdf/'.$encrypted_id.'/'.$detail['laboratory_id']) ?>"
                                                            class="btn btn-sm btn-primary" title="Download COA PDF">
                                                                <i class="fa fa-download"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <button class="btn btn-sm btn-primary" disabled title="Not Released">
                                                                <i class="fa fa-download"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </td>


                                                    <td class="align-middle text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-primary view-logs-btn"
                                                            data-id="<?= $detail['trans_detail_id'] ?>"
                                                            data-labcode="<?= htmlspecialchars($detail['lab_code'] ?? '-', ENT_QUOTES, 'UTF-8') ?>">
                                                            <i class="fas fa-history"></i> View Logs
                                                        </button>
                                                    </td>



                                                </tr>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">No samples available
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
        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtnRelease" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger"
                    onclick="window.location.href='<?= base_url('datareview') ?>';">Cancel</button>
            </div>
        </div>

    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Data Review
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModalRelease" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Releasing
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
                        <div>Number of Releasing</div>
                        <div id="countRelease" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>
                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with Releasing?
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmReleaseSubmit" class="btn btn-success px-4">
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