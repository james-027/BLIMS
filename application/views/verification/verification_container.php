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

                                <?php $jobAttachments = $attachments[$job['trans_id']] ?? []; ?>
                                <?php if (!empty($jobAttachments)): ?>
                                <div class="mt-1">
                                    <strong class="text-white">Attachment:</strong>
                                    <?php foreach ($jobAttachments as $file): ?>
                                    <a href="<?= base_url('uploads/trans_attachments/'.$file['filename']) ?>"
                                        download="<?= $file['original_name'] . '.' . pathinfo($file['filename'], PATHINFO_EXTENSION) ?>"
                                        class="badge badge-light text-dark ml-1 attachment-link">
                                        <i class="fas fa-paperclip"></i>
                                        <?= $file['original_name'] . '.' . pathinfo($file['filename'], PATHINFO_EXTENSION) ?>
                                    </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
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
                                                                <select name="test_status[<?= $detail['trans_detail_id'] ?>]"
                                                                class="form-control form-control-sm test-status-select dynamic_dropdown status-zfix"
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
                                                        </td>

                                                        <td class="align-middle">
                                                            <select
                                                                name="reasons[<?php echo $detail['trans_detail_id']; ?>]"
                                                                id="reason-<?php echo $detail['trans_detail_id']; ?>"
                                                                class="form-control form-control-sm reason-select dynamic_dropdown status-zfix"
                                                                required>
                                                                <option value="">Select Reason</option>
                                                                <?php foreach($reasons as $reason): ?>
                                                                <?php $selected = (!empty($detail['latest_reason']) && $detail['latest_reason'] == $reason->reason_name) ? 'selected' : ''; ?>
                                                                <option value="<?php echo $reason->id; ?>"
                                                                    <?php echo $selected; ?>>
                                                                    <?php echo $reason->reason_name; ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </td>


                                                        <td class="text-center align-middle">
                                                            <?php if($detail['coa_flag'] == 'Y'): ?>
                                                            <span class="badge badge-success">Yes</span>
                                                            <?php else: ?>
                                                            <span class="badge badge-danger">No</span>
                                                            <?php endif; ?>
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