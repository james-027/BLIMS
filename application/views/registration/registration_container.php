  <?php foreach($jobs as $jobIndex => $job): ?>
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
                                    <?php
                                $addDetailTitle = !empty($job['is_all_released'])
                                    ? "Cannot Append Sample to this JO"  
                                    : "Append Sample to this JO"; 
                                ?>

                                <?php if (!empty($edit_button)): ?>
                                    <button type="button" class="btn btn-light btn-sm mr-3 addDetailBtn"
                                        data-job="<?= $jobIndex ?>" data-lab-id="<?= $job['laboratory_id'] ?>"
                                        data-trans-id="<?= $job['trans_id'] ?>"
                                        <?= !empty($job['is_all_released']) ? 'disabled style="opacity:0.5;"' : '' ?>
                                        title="<?= $addDetailTitle ?>">
                                        <i class="fas fa-plus"></i> Add Detail
                                    </button>
                                <?php endif; ?>

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

                                                        <?php
                                                $replicateTitle = !empty($detail['replicate_disabled'])
                                                    ? "Cannot Append Sample to this Lab Code"
                                                    : "Append Sample to this Lab Code"; 
                                                ?>
                                                        <button type="button"
                                                            class="btn btn-primary btn-sm replicateDetailBtn"
                                                            data-job="<?= $jobIndex ?>"
                                                            data-lab-id="<?= $job['laboratory_id'] ?>"
                                                            data-trans-id="<?= $job['trans_id'] ?>"
                                                            data-detail-id="<?= $detail['trans_detail_id'] ?>"
                                                            data-lab-code="<?= $detail['lab_code'] ?>"
                                                            <?= !empty($detail['replicate_disabled']) ? 'disabled' : '' ?>
                                                            title="<?= $replicateTitle ?>">
                                                            <i class="fas fa-clone"></i>
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