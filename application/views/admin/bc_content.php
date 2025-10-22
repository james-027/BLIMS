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
                    <input type="hidden" id="centerTypeID" value="<?=$centerTypeID?>">
                    <div class="table-responsive">
                        
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-bc">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Center Code</th>
                                    <th>Center</th>
                                    <th>Plant Code</th>
                                    <th>Region</th>
                                    <th>Center Type</th>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Modified By</th>
                                    <th>Modified On</th>
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
<div class="modal fade animated bounceInDown" id="modal-add-bc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Center</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-bc">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label>Region:</label>
                        <label for="" class="input-group">
                            <select name="rg-id" class="form-control form-control-md dynamic_dropdown" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($region as $row):?>
                                    <option value="<?=encode($row->rgID)?>"><?=$row->rgLDesc?></option>
                                <?php endforeach;?>
                            </select>
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputEmail1">Center Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="bc-code" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Center Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="bc-name" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Plant Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="plant-code" class="form-control form-control-md numeric" minlength="4" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Center Type:</label>
                        <label for="" class="input-group">
                            <select name="center-type-id" class="form-control form-control-md dynamic_dropdown" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($centertype as $row):?>
                                    <option value="<?=encode($row->centerTypeID)?>"><?=$row->centerType?></option>
                                <?php endforeach;?>
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

<div class="modal fade animated bounceInDown" id="modal-edit-bc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Center</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-bc">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    
                    <div class="form-group">
                        <label>Region:</label>
                        <label for="" class="input-group">
                            <select name="rg-id" id="rg-id" class="form-control form-control-md dynamic_dropdown" required="true">
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Center Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="bc-code" id="bc-code" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Center Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="bc-name" id="bc-name" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">Plant Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="plant-code" id="plant-code" class="form-control form-control-md numeric"  minlength="4" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>Center Type:</label>
                        <label for="" class="input-group">
                            <select name="center-type-id" id="center-type-id" class="form-control form-control-md dynamic_dropdown" required="true">
                                
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

<div class="modal fade animated bounceInDown" id="modal-active-bc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Center</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-bc">
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

<div class="modal fade animated bounceInDown" id="modal-deactivate-bc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Center</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-bc">
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