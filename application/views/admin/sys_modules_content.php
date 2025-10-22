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
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-sys-modules">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Module ID</th>
                                    <th>Module Name</th>
                                    <th>Alias</th>
                                    <th>Link</th>
                                    <th>Link Name</th>
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
<div class="modal fade animated bounceInDown" id="modal-add-sys-module"   sys-module="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" sys-module="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add System Module</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-sys-module-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">System Module Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="module-desc" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">System Module Alias: </label>
                        <label for="" class="input-group">
                            <input type="text" name="alias" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Link: </label>
                        <label for="" class="input-group">
                            <input type="text" name="link" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Link Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="link-name" class="form-control form-control-md" placeholder="" required="true">
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

<div class="modal fade animated bounceInDown" id="modal-edit-sys-module"   sys-module="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md" sys-module="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title font-weight-bold" id="exampleModalLabel"></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-sys-module">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">System Module Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="module-desc" id="module-desc" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">System Module Alias: </label>
                        <label for="" class="input-group">
                            <input type="text" name="alias" id="alias" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Link: </label>
                        <label for="" class="input-group">
                            <input type="text" name="link" id="link" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Link Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="link-name" id="link-name" class="form-control form-control-md" placeholder="" required="true">
                        </label>
                    </div>
                </div>
            
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
                    <button type="submit" id="sys-module-update-btn" class="btn btn-<?=$btnColor?> btn-md btn-round"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-activate-sys-module"   sys-module="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" sys-module="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate System Module</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-sys-module">
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

<div class="modal fade animated bounceInDown" id="modal-deactivate-sys-module"   sys-module="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" sys-module="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate System Module</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-sys-module">
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