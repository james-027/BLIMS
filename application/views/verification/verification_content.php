<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <div class="row align-items-center justify-content-between mt-3" style = "text">
        <?php if (!empty($new_button)): ?>
        <div class="col-md-4 col-sm-12 mb-2 mb-md-0 pl-3">
        </div>
        <?php endif; ?>
        <div class="row align-items-center justify-content-end mt-3">
        <div class="col-auto pr-1">
            <select id="searchField" class="form-control shadow-sm" style = "cursor:pointer">
                <option value="">All Fields</option>
                <option value="job_order_no">Job Order No</option>
                <option value="lab_code">Lab Code</option>
                <option value="lab_result">Lab Result</option>
                <option value="sample_name">Sample Name</option>
                <option value="test_name">Laboratory Test</option>
            </select>
        </div>
        <div class="col-auto pl-1">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>
    </div>

    <?php if(!empty($verification_jobs)): ?>

        <form method="post" action="<?=base_url($controller.'/submit_verification')?>" enctype="multipart/form-data"
            id="verificationForm">
            <div id="jobsContainer">
                <?php $this->load->view('verification/verification_container', ['jobs'=>$verification_jobs, 'thColor'=>$thColor, 'display_status'=>$test_statuses]); ?>
            </div>


            <div class="row justify-content-end mt-3">
                <div class="col-md-12 d-flex justify-content-end gap-2">
                    <button type="button" id="saveBtn" class="btn btn-success mr-2">Save</button>
                    <button type="button" class="btn btn-danger"
                        onclick="window.location.href='<?= base_url('verification') ?>';">Cancel</button>

                </div>
            </div>

        </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Verification
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Verification
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center font-weight-bold mb-3 text-dark">Status Summary</p>
                <div class="status-summary">
                    <div class="status-box status-passed">
                        <div><i class="fas fa-check-circle fa-lg mb-1"></i></div>
                        <div>Passed</div>
                        <div id="countPassed" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-hold">
                        <div><i class="fas fa-pause-circle fa-lg mb-1"></i></div>
                        <div>On Hold</div>
                        <div id="countHold" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-failed">
                        <div><i class="fas fa-times-circle fa-lg mb-1"></i></div>
                        <div>Failed</div>
                        <div id="countFailed" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>

                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with verification?
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmSubmit" class="btn btn-success px-4">
                    <i class="fas fa-check mr-1"></i> Yes, Proceed
                </button>
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>