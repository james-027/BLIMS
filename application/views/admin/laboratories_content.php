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

                        <table class="table table-striped table-hover dt-responsive nowrap " style="width:100%"
                            id="tbl-laboratories">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Identifier Code</th>
                                    <th>Laboratories Name</th>
                                    <th>Address</th>
                                    <th>COA Header</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-laboratories" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Laboratories</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-laboratories">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Identifier Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="identifierCode" class="form-control form-control-md"
                                required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Laboratory Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="laboratoryName" class="form-control form-control-md"
                                required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Address: </label>
                        <label for="" class="input-group">
                            <input type="text" name="addressName" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label>COA Header:</label>
                        <textarea name="coaHeader" id="coaHeader" class="form-control form-control-md" rows="2"
                            required></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-edit-laboratories" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Laboratory</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="update-laboratories">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Identifier Code: </label>
                        <label for="" class="input-group">
                            <input type="text" id="identifierCode" name="identifierCode"
                                class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Laboratory Name: </label>
                        <label for="" class="input-group">
                            <input type="text" id="laboratoryName" name="laboratoryName"
                                class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Address: </label>
                        <label for="" class="input-group">
                            <input type="text" id="addressName" name="addressName" class="form-control form-control-md"
                                required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label>COA Header:</label>
                        <textarea name="coaHeader" id="coaHeader" class="form-control form-control-md" rows="2"
                            required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round"
                        data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade animated bounceInDown" id="modal-active-laboratories" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Laboratory</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-laboratories">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Laboratory?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round"
                            data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-laboratories" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Laboratory</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-laboratories">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Laboratory?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round"
                            data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>