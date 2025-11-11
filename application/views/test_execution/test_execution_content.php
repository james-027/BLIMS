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

    <form method="post" action="<?=base_url($controller.'/submit_test_exec')?>" enctype="multipart/form-data"
        id="testExecutionForm">
        <?php foreach($jobs as $jobIndex => $job): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm mb-3">
                    <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>" data-toggle="collapse"
                        data-target="#job-<?= $jobIndex ?>" aria-expanded="true"
                        style="cursor:pointer;">

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

                    <div id="job-<?= $jobIndex ?>" class="collapse show">
                        <div class="card-body p-0">
                            <div class="verification-section">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped mb-0 verification">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;">No.</th>
                                                <th>Date Submitted</th>
                                                <th style="width:250px;">Laboratory Code</th>
                                                <th>Sample Name</th>
                                                <th>Laboratory Tests</th>
                                                <th style ="width:100px">Lead Time</th>
                                                <th>Status</th>
                                                <th>Lab Result</th>
                                                <th>Remarks</th>
                                                <th>Logs</th>
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
                                                <td class="align-middle"><?= $detail['lab_code'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['sample_name'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?></td>
                                                <td class="align-middle">
                                                    <?= $detail['lead_time'] ?? '-' ?>
                                                    <input type="hidden" 
                                                        name="lead_time[<?= $detail['trans_detail_id'] ?>]" 
                                                        value="<?= isset($detail['lead_time']) ? htmlspecialchars($detail['lead_time']) : '' ?>">
                                                </td>

                                                <td class="text-center align-middle">
                                                    <select id="test_status<?= $detail['trans_detail_id'] ?>"
                                                        name="test_status[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm test-status-select dynamic_dropdown status-zfix"
                                                        data-target="#test_status-<?= $detail['trans_detail_id'] ?>"
                                                        required>
                                                        <option value="">Select Status</option>
                                                        <?php foreach($test_statuses as $status): ?>
                                                        <option value="<?= $status->statusID ?>"
                                                            <?= $detail['test_exec_status_id'] == $status->statusID ? 'selected' : '' ?>>
                                                            <?= $status->statDesc ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>

                                                <td class="align-middle">
                                                    <input type="text"
                                                        name="lab_results[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['test_exec_lab_result'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                </td>
                                                <td class="align-middle">
                                                    <input type="text" name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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