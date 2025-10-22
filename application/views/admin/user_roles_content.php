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
                    <?php
                        if($this->session->flashdata('message') != "" ){
                            echo $this->session->flashdata('message');
                        }
                    ?>
                
                    <?=$new_button?>
                
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-user-roles">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>User Role ID</th>
                                    <th>User Role</th>
                                    <th>Level</th>
                                    <th>Created Time</th>
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
<div class="modal fade animated bounceInDown" id="modal-add-user-role"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add User Roles</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-user-role-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label>User Roles:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" id="user-role" class="form-control form-control-md basic_dropdown uType" required="true">
                            
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
                        <label>Data Access:</label>
                        <label for="" class="input-group">
                            <select name="bc-id[]" class="form-control form-control-md dynamic_dropdown_no_order bc" required="true" multiple="multiple">
                                
                                <option value="-1" > Select All</option>
                                <?php 
                                    
                                    foreach($bc as $row):
                                ?>
                                    <option value="<?=encode($row->bcID)?>" ><?=$row->bcName?></option>
                                <?php 
                                    endforeach;
                                ?>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Copy From:</label>
                        <label for="" class="input-group">
                            <select class="form-control form-control-md dynamic_dropdown_no_order user-role-copy-for-preset">
                                <option value="" >Select...</option>
                                <?php 
                                    
                                    foreach($uTypeForCopy as $row):
                                ?>
                                    <option value="<?=encode($row->userTypeID)?>" ><?=$row->userTypeName?></option>
                                <?php 
                                    endforeach;
                                ?>
                            </select>
                        </label>
                    </div>


                    <br>
                    <div class="form-group">
                        <table class="table table-bordered table-striped table-hover nowrap" style="width:100%" id="tbl-module-access">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Module</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Act</th>
                                    <th>Post</th>
                                    <th>Canc</th>
                                    <th>Prnt</th>
                                    <th>ULod</th>
                                    <th>DLod</th>
                                    <th>Clear</th>
                                    <th>Appr</th>
                                </tr>
                            </thead>
                        </table>
                        
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

<div class="modal fade animated bounceInDown" id="modal-edit-user-role"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-user-role">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>User Role:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" id="user-role-fetch-user" class="form-control form-control-md dynamic_dropdown uType" required="true">
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Data Access:</label>
                        <label for="" class="input-group">
                            <select name="bc-id[]" id="bc-id-preset" class="form-control form-control-md dynamic_dropdown_no_order bc" required="true" multiple="multiple">
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Copy From:</label>
                        <label for="" class="input-group">
                            <select class="form-control form-control-md dynamic_dropdown_no_order user-role-copy-for-preset">
                                <option value="" >Select...</option>
                                
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Apply Changes to Users:</label>
                        <label for="" class="input-group">
                            <select name="userID[]" id="user-ids" class="form-control form-control-md dynamic_dropdown users-list" multiple="multiple">
                            </select>
                        </label>
                    </div>
                        
                    <div class="form-group">
                        <div class="custom-control custom-checkbox medium">
                            <input type="checkbox" class="custom-control-input" id="customCheck" name="apply-changes-to-user" value="<?=encode(1)?>" >
                            <label class="custom-control-label" for="customCheck">Apply Changes to Users Only</label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox medium">
                            <input type="checkbox" class="custom-control-input" id="customCheckDataAccess" name="neglect-data-access-change" value="<?=encode(1)?>" >
                            <label class="custom-control-label" for="customCheckDataAccess">Don't apply data access to Users</label>
                        </div>
                    </div>
                    <br>

                    <div class="form-group">
                        <table class="table table-bordered table-striped table-hover nowrap tbl-user-module-access" style="width:100%" id="">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Module</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                    <th>Act</th>
                                    <th>Post</th>
                                    <th>Canc</th>
                                    <th>Prnt</th>
                                    <th>ULod</th>
                                    <th>DLod</th>
                                    <th>Clear</th>
                                    <th>Appr</th>
                                </tr>
                            </thead>
                        </table>
                        
                        
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" id="user-role-update-btn" class="btn btn-<?=$btnColor?> btn-md btn-round"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-activate-user-role"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate User Role</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-user-role">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-user-role"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate User Role</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-user-role">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>