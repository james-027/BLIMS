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
                                                                    <a href="<?= base_url('coa/select_template_pdf/'.$detail['trans_detail_id'].'/'.$detail['laboratory_id']) ?>"
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