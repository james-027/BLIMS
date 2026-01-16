<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <!-- Report Header -->
    <div class="card shadow-sm ">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">Report Feeds</h4>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-outline-success btn-sm">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>

            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive" style="max-height:70vh;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                        <tr>
                            <th>Sample Name</th>
                            <th>Feedmill</th>
                            <th>Job Order No</th>
                            <th>Laboratory Code</th>
                            <th>Laboratory Test</th>
                            <th>Result</th>
                            <th>Production/ Delivery Date</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jobs as $job): ?>
                        <?php foreach ($job['details'] as $detail): ?>
                        <tr>
                            <td><?= $detail['sample_name'] ?></td>
                            <td><?= $job['feedmill'] ?></td> 
                            <td><?= $job['job_order_no'] ?></td>
                            <td><?= $detail['lab_code'] ?></td>
                            <td><?= $detail['test_name'] ?></td>
                            <td><?= $detail['test_exec_lab_result'] ?></td>
                            <td><?= date('M d, Y', strtotime($detail['delivery_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($detail['created_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
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