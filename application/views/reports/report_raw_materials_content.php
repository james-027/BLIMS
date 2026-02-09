<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>


    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Filter Form -->
            <form method="POST" id="feedmillFilterForm" action="<?= base_url($controller . '/index') ?>">
                <div class="row g-3">

                    <!-- Feedmill -->
                    <div class="col-md-3">
                        <label for="feedmillFilter" class="form-label"><strong>Feedmill:</strong></label>
                        <select name="feedmill[]" id="feedmillFilter" class="form-select dynamic_dropdown_reports"
                            multiple>
                            <?php foreach ($feedmills as $fm): ?>
                            <option value="<?= $fm['feedmill_name'] ?>"
                                <?= (!empty($selected_feedmill) && in_array($fm['feedmill_name'], $selected_feedmill)) ? 'selected' : '' ?>>
                                <?= $fm['feedmill_name'] ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="laboratoryFilter" class="form-label"><strong>Laboratory:</strong></label>
                        <select name="laboratory[]" id="laboratoryFilter" class="form-select dynamic_dropdown_reports"
                            multiple>
                            <?php foreach ($laboratories as $lab): ?>
                            <option value="<?= $lab->id ?>"
                                <?= (!empty($selected_laboratory) && in_array($lab->id, $selected_laboratory)) ? 'selected' : '' ?>>
                                <?= $lab->identifier_code ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Job Number -->
                    <div class="col-md-3">
                        <label for="jobNumberFilter" class="form-label"><strong>Job Number:</strong></label>
                        <select name="job_number[]" id="jobNumberFilter" class="form-select dynamic_dropdown_reports"
                            multiple>
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

                    <div class="col-md-3">
                        <label for="supplierFilter" class="form-label">
                            <strong>Supplier:</strong>
                        </label>
                        <select name="supplier[]" id="supplierFilter" class="form-select dynamic_dropdown_reports"
                            multiple>
                            <?php
                                $suppliers = [];
                                foreach ($jobs as $job) {
                                    if (!empty($job['supplier_id'])) {
                                        $suppliers[$job['supplier_id']] = $job['supplier_name'];
                                    }
                                }
                                asort($suppliers);
                                foreach ($suppliers as $id => $name): ?>
                            <option value="<?= $id ?>"
                                <?= (!empty($selected_supplier) && in_array($id, $selected_supplier)) ? 'selected' : '' ?>>
                                <?= $name ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Delivery Date -->
                    <div class="col-md-3">
                        <label class="form-label"><strong>Delivery Date:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">From</span>
                            <input type="date" name="delivery_date_from" class="form-control form-control-sm"
                                value="<?= $selected_delivery_date_from ?>">
                            <span class="input-group-text">To</span>
                            <input type="date" name="delivery_date_to" class="form-control form-control-sm"
                                value="<?= $selected_delivery_date_to ?>">
                        </div>
                    </div>

                    <!-- Date Received -->
                    <div class="col-md-3">
                        <label class="form-label"><strong>Date Received:</strong></label>
                        <div class="input-group">
                            <span class="input-group-text">From</span>
                            <input type="date" name="date_received_from" class="form-control form-control-sm"
                                value="<?= $selected_date_received_from ?>">
                            <span class="input-group-text">To</span>
                            <input type="date" name="date_received_to" class="form-control form-control-sm"
                                value="<?= $selected_date_received_to ?>">
                        </div>
                    </div>

                    <!-- Week -->
                    <div class="col-md-3">
                        <label for="weekFilter" class="form-label"><strong>Week#:</strong></label>
                        <select name="week[]" id="weekFilter" class="form-select dynamic_dropdown_reports" multiple>
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
                        <select name="month[]" id="monthFilter" class="form-select dynamic_dropdown_reports" multiple>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>


    <div class="card shadow-sm">
        <div class="card-body p-2">


            <div class="table-responsive">
                <table id="jobsTable" class="table table-bordered table-hover table-striped mb-0">
                    <thead class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                        <tr>
                            <th style="width:150px;">Raw Material</th>
                            <th style="width:200px;">Sample Name</th>
                            <th style="width:200px;">Location/Feedmill</th>
                            <th style="width:150px;">Laboratory Code</th>
                            <th style="width:150px;">Supplier / Supplier</th>
                            <th style="width:150px;">Plate/Van No.</th>
                            <th style="width:150px;">Production/ Delivery Date</th>
                            <th style="width:150px;">Date Received</th>
                            <th style="width:50px;">Week#</th>
                            <th style="width:50px;">Month</th>
                            <th style="width:150px;">Class</th>
                            <th style="width:200px;">Job Number</th>
                            <th style="width:150px;">Estimated Release Date</th>
                            <th style="width:150px;">Actual Release Date</th>
                            <th style="width:150px;">Analysis Requested</th>

                            <?php foreach($dynamic_test_headers as $test_code): ?>
                            <th style="width:150px;"><?= $test_code ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($jobs as $row): ?>
                        <tr>
                            <td><?= $row['sample_name'] ?></td>
                            <td><?= $row['sample_name'] ?></td>
                            <td><?= $row['feedmill'] ?></td>
                            <td><?= $row['lab_code'] ?></td>
                            <td><?= $row['supplier_name'] ?></td>
                            <td><?= $row['plate_number'] ?></td>
                            <td><?= date('M d, Y', strtotime($row['delivery_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($row['latest_timestamp'])) ?></td>
                            <td><?= $row['week_number'] ?></td>
                            <td><?= $row['month_name'] ?></td>
                            <td></td>
                            <td><?= $row['job_order_no'] ?></td>
                            <td><?= $row['estimated_release_date'] ?></td>
                            <td><?= date('M d, Y', strtotime($row['latest_timestamp'])) ?></td>
                            <td><?= $row['test_name'] ?></td>
                            <?php foreach($dynamic_test_headers as $test_code): ?>
                            <td><?= $row[$test_code] ?? '' ?></td>
                            <?php endforeach; ?>
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