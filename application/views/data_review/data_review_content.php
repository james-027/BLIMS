<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($jobs)): ?>

     <div class="row align-items-center justify-content-end mt-3">
        <div class="col-auto pr-1">
            <select id="searchField" class="form-control shadow-sm" style = "cursor:pointer">
                <option value="">All Fields</option>
                <option value="job_order_no">Job Order No</option>
                <option value="lab_code">Lab Code</option>
                <option value="date_submitted">Date Submitted</option>
                <option value="lab_result">Lab Result</option>
                <option value="sample_name">Sample Name</option>
                <option value="test_name">Laboratory Test</option>
            </select>
        </div>
        <div class="col-auto pl-1">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <form method="post" action="<?=base_url($controller.'/submit_data_review')?>" enctype="multipart/form-data"
        id="dataReviewForm">

        <div id="jobsContainer">
          
         <?php $this->load->view('data_review/data_review_container', ['jobs'=>$jobs, 'thColor'=>$thColor, 'display_status'=>$test_statuses]); ?>
        </div>
        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtnData" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger"
                    onclick="window.location.href='<?= base_url('datareview') ?>';">Cancel</button>
            </div>
        </div>

    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Data Review
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModalData" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Test Execution and Data Entry
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
                        <div>Verified</div>
                        <div id="countVerified" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-hold">
                        <div><i class="fas fa-pause-circle fa-lg mb-1"></i></div>
                        <div>Re-Analysis</div>
                        <div id="countReAnalysis" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>
                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with Data Review?
                </p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmDataReviewSubmit" class="btn btn-success px-4">
                    <i class="fas fa-check mr-1"></i> Yes, Proceed
                </button>
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i> Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewLogsModal" tabindex="-1" role="dialog" aria-labelledby="viewLogsModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg border-0">

            <div class="modal-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                <div>
                    <h5 class="modal-title mb-0 font-weight-bold">Laboratory Test Progress Timeline</h5>
                </div>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body bg-light">
                <div id="logsLoader" class="text-center my-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2">Loading logs...</p>
                </div>

                <div id="noLogsMessage" class="text-center text-muted d-none mt-4">
                    <i class="fas fa-info-circle fa-lg mb-2 d-block"></i>
                    No logs available for this test.
                </div>

                <div id="logsTimeline" class="timeline d-none"></div>
            </div>
        </div>
    </div>
</div>






<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>