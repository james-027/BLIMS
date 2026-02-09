<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <!-- Report Header -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Filters</strong>
        </div>
        <div class="card-body">
            <form method="POST" id="feedmillFilterForm">
                <div class="row g-3">

                    <!-- Feedmill -->
                    <div class="col-md-3">
                        <label for="feedmillFilter" class="form-label"><strong>Feedmill:</strong></label>
                        <select name="feedmill[]" id="feedmillFilter" class="form-select dynamic_dropdown" multiple size="6">
                                                        <?php foreach ($feedmills as $fm): ?>
                                    <option value="<?= $fm['feedmill_name'] ?>"
                                        <?= (!empty($selected_feedmill) && in_array($fm['feedmill_name'], $selected_feedmill)) ? 'selected' : '' ?>>
                                        <?= $fm['feedmill_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Job Number -->
                    <div class="col-md-3">
                        <label for="jobNumberFilter" class="form-label"><strong>Job Number:</strong></label>
                        <select name="job_number[]" id="jobNumberFilter" class="form-select dynamic_dropdown" multiple size="6">
                            <option value="">-- All Job Numbers --</option>
                                                    <?php 
                                $jobNumbers = array_unique(array_column($jobs, 'job_order_no'));
                                foreach ($jobNumbers as $jn): ?>
                                    <option value="<?= $jn ?>"
                                        <?= (!empty($selected_job_number) && in_array($jn, $selected_job_number)) ? 'selected' : '' ?>>
                                        <?= $jn ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Delivery Date -->
                    <div class="col-md-3">
                        <label class="form-label"><strong>Delivery Date:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">From</span>
                            <input type="date" name="delivery_date_from" class="form-control form-control-sm">
                            <span class="input-group-text">To</span>
                            <input type="date" name="delivery_date_to" class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Date Received -->
                    <div class="col-md-3">
                        <label class="form-label"><strong>Date Received:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">From</span>
                            <input type="date" name="date_received_from" class="form-control form-control-sm">
                            <span class="input-group-text">To</span>
                            <input type="date" name="date_received_to" class="form-control form-control-sm">
                        </div>
                    </div>

                    <!-- Week -->
                    <div class="col-md-3">
                        <label for="weekFilter" class="form-label"><strong>Week#:</strong></label>
                        <select name="week[]" id="weekFilter" class="form-select dynamic_dropdown" multiple size="6">
                            <option value="">-- All Weeks --</option>
                                                    <?php 
                                $weekNumbers = [];
                                foreach ($jobs as $job) {
                                    if (!empty($job['week_number'])) $weekNumbers[] = (int)$job['week_number'];
                                }
                                $weekNumbers = array_unique($weekNumbers);
                                sort($weekNumbers);
                                foreach ($weekNumbers as $w): ?>
                                    <option value="<?= $w ?>"
                                        <?= (!empty($selected_week) && in_array($w, $selected_week)) ? 'selected' : '' ?>>
                                        <?= $w ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Month -->
                    <div class="col-md-3">
                        <label for="monthFilter" class="form-label"><strong>Month:</strong></label>
                        <select name="month[]" id="monthFilter" class="form-select dynamic_dropdown" multiple size="6">
                            <option value="">-- All Months --</option>
                                                    <?php 
                                $monthNumbers = [];
                                foreach ($jobs as $job) {
                                    if (!empty($job['month_name'])) {
                                        $monthNumbers[$job['month_name']] = date('n', strtotime($job['month_name'] . ' 01'));
                                    }
                                }
                                asort($monthNumbers);
                                foreach ($monthNumbers as $monthName => $monthNum): ?>
                                    <option value="<?= $monthNum ?>"
                                        <?= (!empty($selected_month) && in_array($monthNum, $selected_month)) ? 'selected' : '' ?>>
                                        <?= $monthName ?>
                                    </option>
                                <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <a href="<?= base_url($controller . '/export_csv') ?>" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-file-excel"></i> Export CSV
                        </a>
                    </div>

                </div>
            </form>
        </div>
</div>


    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive-report" style="max-height:70vh; overflow-y:auto;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                        <tr>
                            <th style="width:150px;">Feed Type</th>
                            <th style="width:200px;">Sample Name</th>
                            <th style="width:200px;">Feedmill</th>
                            <th style="width:150px;">Laboratory Code</th>
                            <th style="width:150px;">Production/ Delivery Date</th>
                            <th style="width:150px;">Date Received</th>
                            <th style="width:50px;">Week#</th>
                            <th style="width:50px;">Month</th>
                            <th style="width:150px;">Class</th>
                            <th style="width:200px;">Job Number</th>
                            <th style="width:150px;">Estimated Release Date</th>
                            <th style="width:150px;">Actual Release Date</th>
                            <th style="width:150px;">Analysis Requested</th>
                            <th style="width:150px;">CP</th>
                            <th style="width:150px;">MC</th>
                            <th style="width:150px;">Fat</th>
                            <th style="width:150px;">Fiber</th>
                            <th style="width:150px;">Ash</th>
                            <th style="width:150px;">Ca</th>
                            <th style="width:150px;">P</th>
                            <th style="width:150px;">Salt</th>
                            <th style="width:150px;">CI</th>
                            <th style="width:150px;">NFE</th>
                            <th style="width:150px;">ME</th>
                            <th style="width:150px;">Traditional ME</th>
                            <th style="width:150px;">AAfla</th>
                            <th style="width:150px;">T2</th>
                            <th style="width:150px;">Zea</th>
                            <th style="width:150px;">Ochra</th>
                            <th style="width:150px;">DON</th>
                            <th style="width:150px;">Hista</th>
                            <th style="width:150px;">Pan</th>
                            <th style="width:150px;">601</th>
                            <th style="width:150px;">PS</th>
                            <th style="width:150px;">PDI</th>
                            <th style="width:150px;">Fines</th>
                            <th style="width:150px;">Density</th>
                            <th style="width:150px;">Water Activity</th>
                            <th style="width:150px;">Formula Code</th>
                            <th style="width:150px;">Others</th>
                            <th style="width:150px;">Remarks</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php foreach ($jobs as $row): ?>
                        <tr>
                            <td><?= $row['sample_name'] ?></td>
                            <td><?= $row['sample_name'] ?></td>
                            <td><?= $row['feedmill'] ?></td>
                            <td><?= $row['lab_code'] ?></td>
                            <td><?= date('M d, Y', strtotime($row['delivery_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($row['latest_timestamp'])) ?></td>
                            <td><?= $row['week_number'] ?></td>
                            <td><?= $row['month_name'] ?></td>
                            <td></td>
                            <td><?= $row['job_order_no'] ?></td>
                            <td><?= $row['estimated_release_date'] ?></td>
                            <td><?= date('M d, Y', strtotime($row['latest_timestamp'])) ?></td>
                            <td><?= $row['test_name'] ?></td>

                            <td><?= $row['NIR_CP'] ?></td>
                            <td><?= $row['NIR_MC'] ?></td>
                            <td><?= $row['NIR_FAT'] ?></td>
                            <td><?= $row['NIR_CF'] ?></td>
                            <td><?= $row['NIR_ASH'] ?></td>
                            <td><?= $row['NIR_CA'] ?></td>
                            <td><?= $row['NIR_P'] ?></td>
                            <td><?= $row['NIR_SALT'] ?></td>

                            <td><?= $row['CI'] ?></td>
                            <td><?= $row['NFE'] ?></td>
                            <td><?= $row['ME'] ?></td>
                            <td><?= $row['Traditional_ME'] ?></td>
                            <td><?= $row['AAfla'] ?></td>
                            <td><?= $row['T2'] ?></td>
                            <td><?= $row['Zea'] ?></td>
                            <td><?= $row['Ochra'] ?></td>
                            <td><?= $row['DON'] ?></td>
                            <td><?= $row['Hista'] ?></td>
                            <td><?= $row['Pan'] ?></td>
                            <td><?= $row['601'] ?></td>
                            <td><?= $row['PS'] ?></td>
                            <td><?= $row['PDI'] ?></td>
                            <td><?= $row['Fines'] ?></td>
                            <td><?= $row['Density'] ?></td>
                            <td><?= $row['Water_Activity'] ?></td>
                            <td><?= $row['Formula_Code'] ?></td>
                            <td><?= $row['Others'] ?></td>
                            <td><?= $row['Remarks'] ?></td>
                        </tr>
                        <?php endforeach; ?>


                    </tbody>

                </table>
            </div>

        </div>
    </div>
</div>

<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>