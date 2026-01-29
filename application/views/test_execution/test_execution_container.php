        <?php foreach($jobs as $jobIndex => $job): ?>
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

                            <div class="d-flex align-items-center">

                                <span class="badge badge-light mr-3">Samples:
                                    <?= count($job['samples'] ?? []) ?></span>

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
                                    <table class="table table-bordered table-hover table-striped mb-0 verification">
                                        <thead>
                                            <tr>
                                                <th style="width:50px;">No.</th>
                                                <th style="width:150px;">Date Submitted</th>
                                                <th style="width:150px;">Laboratory Code</th>
                                                <th style="width:150px;">Sample Name</th>
                                                <th style="width:150px;">Laboratory Tests</th>
                                                <th style="width:100px">Lead Time</th>
                                                <th style="width:150px;">Status</th>
                                                <th style="width:150px;">Lab Result</th>
                                                <th style="width:150px;">Remarks</th>
                                                <th style="width:150px;">Logs</th>
                                                <th style="width:150px;">Action</th>
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

                                                <!-- <td class="align-middle">
                                                        <?php if (!empty($detail['date_roundoff'])): ?>
                                                            <?= date('F d, Y', strtotime($detail['date_roundoff'])) ?>
                                                            <input type="hidden"
                                                                name="date_submitted[<?= $detail['trans_detail_id'] ?>]"
                                                                value="<?= htmlspecialchars($detail['date_roundoff']) ?>">
                                                        <?php else: ?>
                                                            <?= date('F d, Y', strtotime($detail['date_submitted'])) ?>
                                                            <input type="hidden"
                                                                name="date_submitted[<?= $detail['trans_detail_id'] ?>]"
                                                                value="<?= htmlspecialchars($detail['date_submitted']) ?>">
                                                        <?php endif; ?>
                                                    </td> -->

                                                <td class="align-middle"><?= $detail['lab_code'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['sample_name'] ?? '-' ?></td>
                                                <td class="align-middle"><?= $detail['laboratory_tests'] ?? '-' ?>
                                                </td>
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
                                                        <?= empty($can_modify) ? 'disabled' : '' ?> required>
                                                        <option value="">Select Status</option>
                                                        <?php foreach($test_statuses as $status): ?>
                                                        <option value="<?= $status->statusID ?>"
                                                            <?= $detail['test_exec_status_id'] == $status->statusID ? 'selected' : '' ?>>
                                                            <?= $status->statDesc ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (empty($can_modify)): ?>
                                                    <input type="hidden"
                                                        name="test_status[<?= $detail['trans_detail_id'] ?>]"
                                                        value="<?= $detail['test_exec_status_id'] ?>">
                                                    <?php endif; ?>
                                                </td>

                                                <td class="align-middle">
                                                    <input type="text"
                                                        name="lab_results[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['test_exec_lab_result'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                        <?= empty($can_modify) ? 'disabled' : '' ?> required>
                                                </td>
                                                <td class="align-middle">
                                                    <input type="text" name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                        class="form-control form-control-sm"
                                                        value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                        <?= empty($can_modify) ? 'disabled' : '' ?> required>
                                                </td>

                                                <td class="align-middle text-center">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-primary view-logs-btn"
                                                        data-id="<?= $detail['trans_detail_id'] ?>"
                                                        data-labcode="<?= htmlspecialchars($detail['lab_code'] ?? '-', ENT_QUOTES, 'UTF-8') ?>">
                                                        <i class="fas fa-history"></i> View Logs
                                                    </button>
                                                </td>

                                                <td class="text-center align-middle">
                                                    <span>

                                                        <?php
                                                    $hasEditAccess = !empty($can_modify);

                                                    $isDisabled = !$hasEditAccess || !empty($detail['replicate_disabled']);

                                                    if (!$hasEditAccess) {
                                                        $replicateTitle = 'You do not have permission to replicate this sample';
                                                    } elseif (!empty($detail['replicate_disabled'])) {
                                                        $replicateTitle = 'Cannot Append Sample to this Lab Code';
                                                    } else {
                                                        $replicateTitle = 'Append Sample to this Lab Code';
                                                    }
                                                ?>

                                                        <button type="button"
                                                            class="btn btn-primary btn-sm replicateDetailBtn"
                                                            data-job="<?= $jobIndex ?>"
                                                            data-lab-id="<?= $job['laboratory_id'] ?>"
                                                            data-trans-id="<?= $job['trans_id'] ?>"
                                                            data-detail-id="<?= $detail['trans_detail_id'] ?>"
                                                            data-lab-code="<?= $detail['lab_code'] ?>"
                                                            <?= $isDisabled ? 'disabled style="opacity:0.5;cursor:not-allowed;"' : '' ?>
                                                            title="<?= $replicateTitle ?>">
                                                            <i class="fas fa-clone"></i>
                                                        </button>



                                                    </span>
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