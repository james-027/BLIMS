<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">
    <?=$breadcrumbs?>

    <div class="row align-items-center justify-content-between mt-3">
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


    <div id="jobsContainer">
            <?php $this->load->view('verification/sub_verification_container', ['jobs'=>$verification_jobs, 'thColor'=>$thColor, 'display_status'=>$display_status]); ?>

      
    </div>
    <?php else: ?>
    <div class="row justify-content-center mt-4">
        <div class="col-md-12 text-center text-muted">
            <h4>No failed verifications found.</h4>
        </div>
    </div>
    <?php endif; ?>


</div>



</div>




<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
</script>