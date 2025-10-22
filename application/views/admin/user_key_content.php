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
                    <input type="hidden" id="user-key-user-id" value="<?=$userID?>">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-user-key">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Full Name</th>
                                    <th>System Key</th>
                                    <th>User Role</th>
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


    
</div>	


<div class="modal fade animated bounceInDown" id="modal-edit-user-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="update-user-key">
                <input type="hidden" name="userID" id="user-id">
                <input type="hidden" name="hasFilter" id="has-filter">
                
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">System Key: </label>
                        <label for="" class="input-group">
                            <select name="key-id" id="key-id" class="form-control form-control-md dynamic_dropdown user-mod-key">
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>User Role:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" id="uType2-id" class="form-control form-control-md dynamic_dropdown uType" required="true">
                            </select>
                        </label>
                    </div>

                    

                    <div class="form-group">
                        <br>
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
                    <button type="submit" id="user-update-btn" class="btn btn-<?=$btnColor?> btn-md btn-round">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade animated bounceInDown" id="modal-active-user-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/activate-user')?>" id="activate-user-key">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    
                    <p class="text-center"><strong>Are you sure to activate <br><span id ="val"></span>?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-user-key"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/deactivate-user')?>" id="deactivate-user-key">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    
                    <p class="text-center"><strong>Are you sure to deactivate <br><span id ="val"></span>?</strong></p>

                    <p class="text-center">
                        
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
