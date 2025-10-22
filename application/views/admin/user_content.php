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
                    <input type="hidden" id="userStatusID" value="<?=$userStatusID?>">
                    <input type="hidden" id="userType" value="<?=$userType?>">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-user">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Emp. No</th>
                                    <th>User Role</th>
                                    <th>Imm. Sup.</th>
                                    <th>Email Notif</th>
                                    <th>Last Login</th>
                                    <th>Mobile Number</th>
                                    <th>Agency</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-user"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/add-user')?>" id="add-user-form">
                <input type="hidden" name="userStatusID" value="<?=$userStatusID?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user-title">Title: </label>
                        <label for="" class="input-group">
                            <input type="text" name="user-title" class="form-control form-control-md" placeholder="">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">First Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="user-fname" class="form-control form-control-md" required="true" placeholder="">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Last Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="user-lname" class="form-control form-control-md" required="true" placeholder="">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <label for="" class="input-group">
                            <input type="text" class="form-control form-control-md" name="user-email" required="true" />
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Employee No.</label>
                        <label for="" class="input-group">
                            <input type="text" class="form-control form-control-md" name="user-employee-no" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <label for="" class="input-group">
                            <input type="password" class="form-control form-control-md" name="user-password" required="true">
                        </label>
                    </div>

                    <?php if($haveGcash): ?>
                    <div class="form-group">
                        <label>GCash #</label>
                        <label for="" class="input-group">
                            <input type="text" class="form-control form-control-md" name="mobile-number" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Agency:</label>
                        <label for="" class="input-group">

                            <select name="agency-id" class="form-control form-control-md dynamic_dropdown_no_order" required="true">
                                <?php foreach($agency as $row):
                                        
                                ?>
                                    <option value="<?=encode($row->agency_id)?>"><?=$row->agency_name?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>
                    <?php endif; ?>

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
                        <label>Immediate Superior:</label>
                        <label for="" class="input-group">

                            <select name="upline-id" class="form-control form-control-md dynamic_dropdown_no_order upline" required="true">
                                <?php foreach($upline as $row):
                                        $uplineName = $row->userID == 1 ? ' NOT AVAILABLE' : $row->userFirstName . '&nbsp;' . $row->userLastName;
                                ?>
                                    <option value="<?=encode($row->userID)?>"><?=$uplineName?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label>User Role:</label>
                        <label for="" class="input-group">
                            <select name="uType-id" id="uType-id" class="form-control form-control-md basic_dropdown uType" required="true">
                                <option value="-1"> Select Role</option>
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
                            <select name="bc-id[]" class="form-control form-control-md dynamic_dropdown_no_order bc user-bc-id" required="true" multiple="multiple">
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
                        
                        <br>
                        <div class="custom-control custom-checkbox medium">
                            <input type="checkbox" class="custom-control-input" id="customCheck" name="send-email-notif" checked value="<?=encode(1)?>" >
                            <label class="custom-control-label" for="customCheck">Send Email Notif</label>
                        </div>
                        
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

<div class="modal fade animated bounceInDown" id="modal-edit-user"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="" id="update-user">
                <input type="hidden" name="id" id="id">
                <input type="hidden" name="userStatusID" value="<?=$userStatusID?>">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="user-title">Title: </label>
                        <label for="" class="input-group">
                            <input type="text" name="user-title" id="user-title" class="form-control form-control-md" placeholder="">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">First Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="user-fname" id="user-fname" class="form-control form-control-md" required="true" placeholder="">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Last Name: </label>
                        <label for="" class="input-group">    
                            <input type="text" name="user-lname" id="user-lname" class="form-control form-control-md" required="true" placeholder="">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <label for="" class="input-group">
                            <input type="text" name="user-email" id="user-email"class="form-control form-control-md">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Employee No.</label>
                        <label for="" class="input-group">
                            <input type="text" name="user-employee-no" class="form-control form-control-md" id="user-employee-no">
                        </label>
                    </div>

                    <div class="form-group user-password-group">
                        <label>Password</label>
                        <label for="" class="input-group">
                            <input type="password" class="form-control form-control-md user-password" name="user-password">
                        </label>
                    </div>

                    <div class="form-group user-gcash-group">
                        <label>GCash #</label>
                        <label for="" class="input-group">
                            <input type="text" class="form-control form-control-md" name="mobile-number" id="mobile-number" required="true">
                        </label>
                    </div>

                    <div class="form-group user-agency-group">
                        <label>Agency:</label>
                        <label for="" class="input-group">

                            <select name="agency-id" id="agency-id" class="form-control form-control-md dynamic_dropdown_no_order" required="true">
                                
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">System Key: </label>
                        <label for="" class="input-group">
                            <select name="key-id[]" id="key-id" class="form-control form-control-md dynamic_dropdown key" required="true" multiple="multiple">
                            </select>
                        </label>
                    </div>
                        
                    

                    <div class="form-group">
                        <label>Immediate Superior:</label>
                        <label for="" class="input-group">
                            <select name="upline-id" id="upline-id" class="form-control form-control-md basic-dropdown upliine" required="true">
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
                        <label>Data Access:</label>
                        <label for="" class="input-group">
                            <select name="bc-id[]" class="form-control form-control-md dynamic_dropdown_no_order bc user-bc-id" required="true" multiple="multiple">
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
                    <button type="submit" id="user-update-btn" class="btn btn-<?=$btnColor?> btn-md btn-round"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-active-user"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/activate-user')?>" id="activate-user">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="userStatusID" value="<?=$userStatusID?>">
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

<div class="modal fade animated bounceInDown" id="modal-deactivate-user"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/deactivate-user')?>" id="deactivate-user">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="userStatusID" value="<?=$userStatusID?>">
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

<div id="modal-reset-user" class="modal fade animated bounceInDown" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Reset User</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="<?=base_url('admin/update-password')?>" enctype="multipart/form-data" id="update-password">
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" name="userStatusID" value="<?=$userStatusID?>">
                    <div class="form-group">
                        <label>Temporary pasword</label>
                        <label for="" class="input-group">
                            <input type="password" class="form-control form-control-md password" minlength="7" required="true" name="password">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>Retype pasword</label>
                        <label for="" class="input-group">
                            <input type="password" class="form-control form-control-md password" minlength="7" required="true" name="password2">
                        </label>
                    </div>

                    <div class="form-group">
                        <div class="custom-control custom-checkbox medium">
                            <input type="checkbox" class="custom-control-input" id="show_password">
                            <label class="custom-control-label" for="show_password">Show Passwords</label>
                        </div>
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

<div class="modal fade animated bounceInDown" id="modal-upload-user"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Upload Users</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="upload-user" data-url="<?=$controller.'/upload-users'?>" >
                <div class="modal-body">
                    <div class="row col-lg-12">
                        Need Upload Template ?&nbsp;<a class="card-link" href="<?=base_url($controller.'/download-user-template')?>"><span class="fas fa-download"></span>&nbsp;Download here</a>
                    </div>
                    <hr class="border-<?=$btnColor?>">

                    
                    <div class="row col-lg-12">
                        <div class="form-group">
                            <label>Select Excel File</label><br>
                            <label for="" class="input-group">
                                <input type="file" name="upload-temp-file" class="form-contol-md form-control-file" required />
                            </label>
                        </div>
                    </div>
                    
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-import-result"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl2" role="document" id="long-modal-dialog">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title font-weight-bold"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body" id="long-modal-body">
                
                <div class="col-lg-12">
                    <div class="form-group">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover dt-responsive nowrap tbl-import-result" style="width:100%">
                                <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                    <tr>
                                               
                                        <th>Title</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>

                                        <th>Employee No.</th>
                                        <th>System Key</th>
                                        <th>User Role</th>
                                        
                                        <th>Mobile Number</th>
                                        <th>Agency</th>

                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
            </div>
            
        </div>
    </div>
</div>