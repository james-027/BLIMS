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


    <!-- Report Preview Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive" style="max-height:70vh;">
                <table class="table table-bordered table-hover mb-0">
                    <thead class="thead-light sticky-top">
                        <tr>
                            <th>Job Order No</th>
                            <th>Lab Code</th>
                            <th>Sample Name</th>
                            <th>Laboratory Test</th>
                            <th>Result</th>
                            <th>Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($jobs as $job): ?>
                        <tr>
                            <td><?= $job['job_order_no'] ?></td>
                            <td><?= $job['lab_code'] ?></td>
                            <td><?= $job['sample_name'] ?></td>
                            <td><?= $job['test_name'] ?></td>
                            <td><?= $job['test_exec_lab_result'] ?></td>
                            <td><?= date('Y-m-d', strtotime($job['created_at'])) ?></td>
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
