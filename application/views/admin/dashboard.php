<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>
<div class="page-inner animated fadeInRightBig">

    <?=$breadcrumbs?>

    

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        
        <h1 class="d-sm-inline-block h6 mb-0 text-gray-800"></h1>
        <button href="#" id="dashboard-filter-btn" class="btn btn-md btn-<?=$btnColor?> btn-round">
            <i class="fas fa-plus mr-1"></i> Apply Filter
        </button>
        
    </div>
    
    
    <input type="hidden" id="dashboard-yearFrom" value="">
    <input type="hidden" id="dashboard-yearTo" value="">
    <div class="row">
        <div class="col-xl-12">
            Coming Soon....
        </div>
    </div>
    
    

    
</div>


<div class="modal fade animated bounceInDown" id="modal-dashboard-filter"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Apply Filter</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/dashboard')?>" id="apply-dashboard-filter">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>From:</label><br>
                                <label for="" class="input-group">
                                    <div class="monthpicker date input-group p-0">
                                        <input type="text" placeholder="Pick a month" class="form-control form-control-md" name="yearFrom" required="true" value="<?=@$yearFrom;?>">

                                        <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar"></i></span></div>
                                    </div>
                                </label>
                            </div>

                            <div class="form-group">
                                <label>To:</label><br>
                                <label for="" class="input-group">
                                    <div class="monthpicker date input-group p-0">
                                        <input type="text" placeholder="Pick a month" class="form-control form-control-md" name="yearTo" required="true" value="<?=@$yearTo;?>">
        
                                        <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar"></i></span></div>
                                    </div>
                                </label>
                            </div>
                            
                        </div>
                    </div>

                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Load</button>
                </div>
            </form>
        </div>
    </div>
</div>