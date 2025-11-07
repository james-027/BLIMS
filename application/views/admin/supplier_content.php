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
                            id="tbl-supplier">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Supplier Name</th>
                                    <th>Laboratory</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-supplier" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Supplier</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-supplier">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Supplier Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="supplierName" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="labName">Select Laboratories :</label>
                        <select name="labName" id="labName" class="form-control form-control-md" required>
                            <option value="">-- Select Laboratories --</option>
                            <?php foreach($laboratories as $laboratory): ?>
                            <option value="<?= $laboratory->id ?>"><?= $laboratory->laboratory_name ?></option>
                            <?php endforeach; ?>
                        </select>
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

<div class="modal fade animated bounceInDown" id="modal-upload-supplier" role="dialog" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="uploadModalLabel"><strong>Upload Supplier File</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="upload-supplier-form" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="uploadFile">Select File to Upload:</label>
                        <div class="input-group">
                            <input type="file" name="supplier_file" id="uploadFile" class="form-control form-control-md" accept=".csv" required>

                        </div>
                        <small class="form-text text-muted mt-2">
                            Allowed file types: <strong>.csv only</strong>
                        </small>
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


<div class="modal fade animated bounceInDown" id="modal-edit-supplier" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Supplier</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-supplier">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Supplier Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="supplierName" id="supplierName"
                                class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="labName">Select Laboratories :</label>
                        <select name="labName" id="labName" class="form-control form-control-md" required>
                            <option value="">-- Select Laboratories --</option>
                            <?php foreach($laboratories as $laboratory): ?>
                            <option value="<?= $laboratory->id ?>"><?= $laboratory->laboratory_name ?></option>
                            <?php endforeach; ?>
                        </select>
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

<div class="modal fade animated bounceInDown" id="modal-active-supplier" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Supplier</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-supplier">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this supplier?</strong></p>

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

<div class="modal fade animated bounceInDown" id="modal-deactivate-supplier" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Supplier</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-supplier">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this supplier?</strong></p>

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