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
                        
                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%" id="tbl-professions">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Profession</th> 
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

<div class="modal fade animated bounceInDown" id="modal-add-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Profession</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-professions">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Profession: </label>
                        <label for="" class="input-group">
                            <input type="text" name="professionName" class="form-control form-control-md" required="true">
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

<div class="modal fade animated bounceInDown" id="modal-edit-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Professiion</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="update-professions">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Profession: </label>
                        <label for="" class="input-group">
                            <input type="text" name="professionName" id="professionName" class="form-control form-control-md" required="true">
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


<div class="modal fade animated bounceInDown" id="modal-active-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Profession </strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-professions">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Profession?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Profession</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-professions">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Profession?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
        