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
                    
                    <?=$new_button?>
                    <div class="table-responsive">
                        
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-province">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Province Code</th>
                                    <th>Province</th>
                                    <th>Business Center</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<div class="modal fade animated bounceInDown" id="modal-add-province"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Province</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-province">
                <div class="modal-body">
                    
                    <div class="form-group">

                        <label>Business Center:</label>
                        <label for="" class="input-group">
                            <select name="bc-id-for-province" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($bc as $row):?>
                                    <option value="<?=$row->bcID?>"><?=$row->bcName?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Province Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="province-sDesc" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Province Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="province-lDesc" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-edit-province"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Province</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-province">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label>Business Center:</label>
                        <label for="" class="input-group">
                            <select name="bc-id-for-province" id="bc-id" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($bc as $row):?>
                                    <option value="<?=$row->bcID?>"><?=$row->bcName?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Province Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="province-sDesc" id="province-sDesc" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Province Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="province-lDesc" id="province-lDesc" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-active-province"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Province</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-province">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this province?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-province"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Province</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-province">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this province?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
        