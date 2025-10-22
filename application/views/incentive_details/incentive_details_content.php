<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>
<div class="page-inner animated fadeInRightBig">

    <?=$breadcrumbs?>
        
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-xl-4">
                            <?=$table?>
                        </div>
                        <div class="col-xl-8">
                            <?=$tableCrew?>
                            <?=$card?>
                        </div>
                        
                        <div class="col-xl-12">
                            <?=$table2?>
                        </div>
                        
                    </div>
                    
                    <hr class="border-<?=$btnColor?>">
                    

                    <ul class="nav nav-line nav-color-<?=$thColor?> nav-line-no-bd mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="store-sales-tab" data-toggle="pill" href="#store-sales" role="tab" aria-controls="store-sales" aria-selected="true">Other Store Sales Incentive</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="rsl-sales-tab" data-toggle="pill" href="#rsl-sales" role="tab" aria-controls="rsl-sales" aria-selected="false">RSL Sales Incentive</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="hurdle-sales-tab" data-toggle="pill" href="#hurdle-sales" role="tab" aria-controls="hurdle-sales" aria-selected="false">Hurdle Sales Qty</a>
                        </li>
                    </ul>
                    <div class="tab-content mb-3" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="store-sales" role="tabpanel" aria-labelledby="store-sales-tab">
                            <?=$new_button?>

                            <div class="table-responsive">
                                
                                <table class="table table-striped table-hover nowrap my-datatables-2" data-url="<?=$controller.'/data-grid/'.$incentive_id.'/'.encode(1)?>" style="width:100%">
                                    <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                        <tr>
                                            
                                            <th class="bg-<?=$thColor?>">SAP Code</th>
                                            <th class="bg-<?=$thColor?>">ERP Code</th>
                                            <th>Mat. Group</th>
                                            <th>Material</th>
                                            <th>Incentive Type</th>
                                            <th>Sales Qty</th>
                                            <th>Scheme Amount</th>
                                            <th>Incentive Amount</th>
                                            <th>Status</th>
                                            
                                            <th>Created On</th>
                                            <th>Modified On</th>
                                            
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th colspan="5"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="rsl-sales" role="tabpanel" aria-labelledby="rsl-sales-tab">
                            <?=$new_button?>

                            <div class="table-responsive">
                                
                                <table class="table table-striped table-hover nowrap my-datatables-2" data-url="<?=$controller.'/data-grid/'.$incentive_id.'/'.encode(2)?>" style="width:100%">
                                    <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                        <tr>
                                            
                                            <th class="bg-<?=$thColor?>">SAP Code</th>
                                            <th class="bg-<?=$thColor?>">ERP Code</th>
                                            <th>Mat. Group</th>
                                            <th>Material</th>
                                            <th>Incentive Type</th>
                                            <th>Sales Qty</th>
                                            <th>Scheme Amount</th>
                                            <th>Incentive Amount</th>
                                            <th>Status</th>
                                            
                                            <th>Created On</th>
                                            <th>Modified On</th>
                                            
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th colspan="5"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="hurdle-sales" role="tabpanel" aria-labelledby="hurdle-sales-tab">
                            <?=$new_button?>

                            <div class="table-responsive">
                                
                                <table class="table table-striped table-hover nowrap my-datatables-2" data-url="<?=$controller.'/data-grid-hurdle/'.$incentive_id?>" style="width:100%">
                                    <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                        <tr>
                                            
                                            <th class="bg-<?=$thColor?>">SAP Code</th>
                                            <th class="bg-<?=$thColor?>">ERP Code</th>
                                            <th>Mat. Group</th>
                                            <th>Material</th>
                                            <th>Incentive Type</th>
                                            <th>Sales Qty</th>
                                            <th>Scheme Amount</th>
                                            <th>Incentive Amount</th>
                                            <th>Status</th>
                                            
                                            <th>Created On</th>
                                            <th>Modified On</th>
                                            
                                        </tr>
                                    </thead>

                                    <tfoot>
                                        <tr>
                                            <th colspan="5"></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th colspan="2"></th>
                                        </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>

                    
                </div>
            </div>
        </div>
    </div>

    
</div>


<div class="modal fade animated bounceInDown" id="modal-get-form"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Incentives Record</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                <table class="table table-bordered table-striped table-hover nowrap tbl-user-module-access" style="width:100%" id="">
                    <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                        <tr>
                            <th>Incentive Code</th>
                            <th>Incentive Date</th>
                            <th>Store</th>
                            <th>Store Hurdle</th>
                            <th>Incentive Qualified</th>
                            <th>Store Incentives</th>
                            <th>Reseller Incentives</th>
                            <th>Overall Incentives</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Update</button>
            </div>
            
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-activate-form"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Incentives</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-form" data-url="<?=$controller.'/activate'?>" >
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Incentives?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-form"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Incentives</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-form" data-url="<?=$controller.'/deactivate'?>" >
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Incentives?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
        