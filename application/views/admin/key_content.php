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
                        <table class="table table-striped table-hover dt-responsive nowrap" style="width:100%" id="tbl-key">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    
                                    <th>System Key ID</th>
                                    <th>System Key Code</th>
                                    <th>Suffix Code</th>
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
<div class="modal fade animated bounceInDown" id="modal-add-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add System Key</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-key">
                
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Key Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="keyCode" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Suffix Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="keyCode2" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Business Center:</label>
                        <label for="" class="input-group">
                            <select name="bcID" class="form-control form-control-md dynamic_dropdown_no_order" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($bc as $row):?>
                                    <option value="<?=encode($row->bcID)?>"><?=$row->bcName?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Apply to User Roles:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" class="form-control form-control-md basic_dropdown uType users-role-for-key" multiple="multiple">
                                
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
                        <label>Apply Changes to Users:</label>
                        <label for="" class="input-group">
                            <select name="userID[]" class="form-control form-control-md dynamic_dropdown users-list" multiple="multiple">
                                
                            </select>
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

<div class="modal fade animated bounceInDown" id="modal-edit-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update System Key</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-key">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Key Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="keyCode" id="keyCode" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Suffix Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="keyCode2" id="keyCode2" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Business Center:</label>
                        <label for="" class="input-group">
                            <select name="bcID" id="bcID" class="form-control form-control-md dynamic_dropdown_no_order" required="true">
                                <option value=""> Select...</option>
                                
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Apply to User Roles:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" class="form-control form-control-md basic_dropdown uType users-role-for-key-update" multiple="multiple">
                                
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
                        <label>Apply Changes to Users:</label>
                        <label for="" class="input-group">
                            <select name="userID[]" class="form-control form-control-md dynamic_dropdown users-list" multiple="multiple">
                                
                            </select>
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

<div class="modal fade animated bounceInDown" id="modal-active-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate System Key</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-key">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="keyID" id="keyID">
                    <p class="text-center"><strong>Are you sure to activate this system key?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate System Key</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-key">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="keyID" id="keyID">
                    <p class="text-center"><strong>Are you sure to deactivate this system key?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-clear-trans"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Clear Data on Current System Key</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            <div class="modal-body">
                
                <div class="row">
                    <div class="col-12">
                        <a href="#" class="clear-trans-employees card-link text-danger mt-2" data-doctype="3" data-transtype="1">
                            <span class="fa-stack fa-xs">
                                <i class="fa fa-circle fa-stack-2x"></i>
                                <i class="fas fa-broom fa-stack-1x fa-inverse"></i>
                            </span>Clear Employees
                        </a>
                        <br>
                        
                    </div>
                    


                </div>

            </div>
        
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                
            </div>
            
        </div>
    </div>
</div>