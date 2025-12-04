<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <?php if(!empty($jobs)): ?>

    <div class="row align-items-center justify-content-end mt-3">
        <div class="col-md-4 col-sm-12">
            <input type="text" id="jobSearch" class="form-control shadow-sm" placeholder="🔍 Search here">
        </div>
    </div>

    <form method="post" action="<?=base_url($controller.'/submit_initial_prep')?>" enctype="multipart/form-data"
        id="initialPreparationForm">

        <div id="jobsContainer">
            <?php $this->load->view('initial_preparation/initial_preparation_container', ['jobs'=>$jobs, 'thColor'=>$thColor, 'display_status'=>$test_statuses]); ?>
        
        </div>
        <div class="row justify-content-end mt-3">
            <div class="col-md-12 d-flex justify-content-end gap-2">
                <button type="button" id="saveBtnInitial" class="btn btn-success mr-2">Save</button>
                <button type="button" class="btn btn-danger"
                    onclick="window.location.href='<?= base_url('initialpreparation') ?>';">Cancel</button>
            </div>
        </div>

    </form>

    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            No Initial Preparation data
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="confirmModalInitial" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="confirmModalLabel">
                    <i class="fas fa-exclamation-circle mr-2"></i> Confirm Initial Preparation
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center font-weight-bold mb-3 text-dark">Pre Analytical Process</p>

                <div class="status-summary">
                    <div class="status-box status-passed">
                        <div><i class="fas fa-check-circle fa-lg mb-1"></i></div>
                        <div>Homogenizing</div>
                        <div id="countHomogenizing" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-hold">
                        <div><i class="fas fa-pause-circle fa-lg mb-1"></i></div>
                        <div>Grinding</div>
                        <div id="countGrinding" style="font-size: 1.4rem;">0</div>
                    </div>
                    <div class="status-box status-failed">
                        <div><i class="fas fa-times-circle fa-lg mb-1"></i></div>
                        <div>As Is</div>
                        <div id="countAsIs" style="font-size: 1.4rem;">0</div>
                    </div>
                </div>

                <p class="confirm-text mt-4">
                    Are you sure you want to proceed with Initial Preparation?
                </p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" id="confirmInitialSubmit" class="btn btn-success px-4">
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