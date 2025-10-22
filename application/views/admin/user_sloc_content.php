<div class="page-inner">
    <div id="loader-div">
        <div id="loader-wrapper">
            <div id="loader"></div>
        </div>
    </div>
    
    <?=$breadcrumbs?>
        
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <?php
                        if($this->session->flashdata('message') != "" ){
                            echo $this->session->flashdata('message');
                        }
                    ?>
                
                    <?=$new_button?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-user-sloc">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Storage Location</th>
                                    <th>Created On</th>
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


    <div class="modal fade animated--grow-in" id="modal-add-user-sloc"   user-sloc="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" user-sloc="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Add User SLoc</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="add-user-sloc-form">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>User Roles:</label>
                            <label for="" class="input-group">

                                <select name="uType-id" class="form-control form-control-md basic_dropdown uType users-role-for-key" multiple="multiple" required="true">
                                    
                                    <option value="-1"> Select All</option>
                                    <?php 
                                        
                                        foreach($uType as $row):
                                    ?>
                                        <option value="<?=encode($row->userTypeID)?>" ><?=$row->userTypeName?></option>
                                    <?php 
                                        endforeach;
                                    ?>
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Users:</label>
                            <label for="" class="input-group">
                                <select name="userID[]" class="form-control form-control-md dynamic_dropdown users-list" required="true" multiple="multiple">
                                    
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">System Key: </label>
                            <label for="" class="input-group">
                                <select name="key-id[]" class="form-control form-control-md dynamic_dropdown key" required="true" multiple="multiple">
                                    <option value="-1"> Select All</option>
                                    <?php foreach($key as $row):?>
                                        <option value="<?=encode($row->keyID)?>"><?=$row->keyCode . ' : ' . $row->coSDesc . ' - '. $row->buSDesc . ' [' . $row->bcCode .']' ?></option>
                                    <?php endforeach;?>
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label>Storage Location Access:</label>
                            <label for="" class="input-group">
                                <select name="sLoc-id[]" id="sLoc~id" class="form-control form-control-md dynamic_dropdown sLoc" required="false" multiple="multiple">
                                    <option value=""> Select System Key First</option>
                                </select>
                            </label>
                        </div>
                            
                        
                        <div class="form-group">
                            <div class="custom-control custom-checkbox medium">
                                <input type="checkbox" class="custom-control-input" id="customCheckOverWriteKey" name="overwrite-key" value="<?=encode(1)?>" >
                                <label class="custom-control-label" for="customCheckOverWriteKey">Overwrite Key</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox medium">
                                <input type="checkbox" class="custom-control-input" id="customCheckApplyKey" name="apply-changes-to-addtl-key" value="<?=encode(1)?>" checked>
                                <label class="custom-control-label" for="customCheckApplyKey">Apply Changes to Additional Key only</label>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox medium">
                                <input type="checkbox" class="custom-control-input" id="customCheckOverWriteSloc" name="overwrite-sloc" value="<?=encode(1)?>" >
                                <label class="custom-control-label" for="customCheckOverWriteSloc">Overwrite SLoc</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox medium">
                                <input type="checkbox" class="custom-control-input" id="customCheck" name="apply-changes-to-addtl-sloc" value="<?=encode(1)?>" checked>
                                <label class="custom-control-label" for="customCheck">Apply Changes to Additional SLoc only</label>
                            </div>
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    

    <div class="modal fade animated--grow-in" id="modal-activate-user-sloc"   user-sloc="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" user-sloc="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Activate User SLoc</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="activate-user-sloc">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <p class="text-center"><strong>Are you sure to activate?</strong></p>

                        <p class="text-center">
                            <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                            <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">No</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade animated--grow-in" id="modal-deactivate-user-sloc"   user-sloc="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" user-sloc="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate User SLoc</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="deactivate-user-sloc">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <p class="text-center"><strong>Are you sure to deactivate?</strong></p>

                        <p class="text-center">
                            <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                            <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">No</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>