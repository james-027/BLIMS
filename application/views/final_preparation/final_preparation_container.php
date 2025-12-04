        <?php foreach($jobs as $jobIndex => $job): ?>
            <div class="row justify-content-center mt-4">
                <div class="col-md-12">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>"
                            data-toggle="collapse" data-target="#job-<?= $jobIndex ?>"
                            aria-expanded="<?= $jobIndex === 0 ? 'true' : 'false' ?>" style="cursor:pointer;">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h5 class="mb-0">
                                    Job Order No:
                                    <span class="text-white font-weight-bold"><?= $job['job_order_no'] ?></span>
                                </h5>

                                <div class="d-flex align-items-center">
                                    <span class="badge badge-light mr-2">Samples:
                                        <?= count($job['samples'] ?? []) ?></span>
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
                                                    <th style="width:50px;" title="No.">No.</th>
                                                    <th style="width:150px;" title="Laboratory Code">Laboratory Code
                                                    </th>
                                                    <th title="Sample Name">Sample Name</th>
                                                    <th style="width:120px;" title="Production Date">Production Date
                                                    </th>
                                                    <th title="Shipment Supplier">Shipment Supplier</th>
                                                    <th title="Plate / Van Number">Plate / Van Number</th>
                                                    <th title="Batch / Lot Number">Batch / Lot Number</th>
                                                    <th title="Type of Sample">Type of Sample</th>
                                                    <th title="Laboratory Tests">Laboratory Tests</th>
                                                    <th title="Test Parameter">Test Parameter</th>
                                                    <th title="Pre Analytical Analyst">Pre Analytical Analyst</th>
                                                    <th title="Preparation Verification">Preparation Verification</th>
                                                    <th title="Remarks">Remarks</th>

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
                                                        <select id="prepverification-<?= $detail['trans_detail_id'] ?>"
                                                            name="prep_verifications[<?= $detail['trans_detail_id'] ?>]"
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
                                                        <input type="text"
                                                            name="remarks[<?= $detail['trans_detail_id'] ?>]"
                                                            class="form-control form-control-sm"
                                                            value="<?= htmlspecialchars($detail['existing_remark'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
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


