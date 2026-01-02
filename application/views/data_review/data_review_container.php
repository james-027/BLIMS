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
                             <span class="badge badge-light mr-2">Samples:
                                 <?= count($job['samples'] ?? []) ?></span>
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
                                             <th style="width:150px;">Laboratory Code</th>
                                             <th>Sample Name</th>
                                             <th>Laboratory Tests</th>
                                             <th>Lead Time</th>
                                             <th>Status</th>
                                             <th>Lab Result</th>
                                             <th>Remarks</th>
                                             <th>Review Verification</th>
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
                                                     name="date_submitted[<?= $detail['trans_detail_id'] ?>]" value="">
                                                 <?php endif; ?>
                                             </td>
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

                                             <td class="align-middle">
                                                 <span><?= htmlspecialchars($detail['test_exec_lab_result'] ?? 'No Lab Result', ENT_QUOTES, 'UTF-8') ?></span>
                                             </td>

                                             <td class="align-middle" style="max-width: 200px;">
                                                 <span><?= htmlspecialchars($detail['existing_remark'] ?? 'No Remarks', ENT_QUOTES, 'UTF-8') ?></span>
                                             </td>

                                             <td class="text-center align-middle">
                                                 <select id="review_verifications<?= $detail['trans_detail_id'] ?>"
                                                     name="review_verifications[<?= $detail['trans_detail_id'] ?>]"
                                                     class="form-control form-control-sm review-verification-select dynamic_dropdown status-zfix"
                                                     data-target="#review_verifications-<?= $detail['trans_detail_id'] ?>"
                                                     <?= empty($can_modify) ? 'disabled' : '' ?> required>
                                                     <option value="">Select Status</option>
                                                     <?php foreach($review_verifications as $review_verification): ?>
                                                     <option value="<?= $review_verification->statusID ?>"
                                                         <?= $detail['review_verification_status_id'] == $review_verification->statusID ? 'selected' : '' ?>>
                                                         <?= $review_verification->statDesc ?>
                                                     </option>
                                                     <?php endforeach; ?>
                                                 </select>
                                                 <?php if (empty($can_modify)): ?>
                                                 <input type="hidden"
                                                     name="review_verifications[<?= $detail['trans_detail_id'] ?>]"
                                                     value="<?= $detail['review_verification_status_id'] ?>">
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