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
                            id="tbl-sample-name">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Sample Name</th>
                                    <th>Sample Code</th>
                                    <th>Sample Type</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-sample-name" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Sample Name</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-sample-name">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sample Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="sampleName" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sample Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="sampleCode" class="form-control form-control-md" required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="sampleType">Select Sample Type:</label>
                        <select name="sampleType" id="sampleType"
                            class="form-control form-control-md dynamic_dropdown" required>
                            <option value="">-- Select Sample Type --</option>
                            <?php foreach($sample_types as $sample_type): ?>
                            <option value="<?= $sample_type->id ?>"><?= $sample_type->sample_type_name ?></option>
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

<div class="modal fade animated bounceInDown" id="modal-edit-sample-name" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Sample Name</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="update-sample-name">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sample Name: </label>
                        <label for="" class="input-group">
                            <input type="text" name="sampleName" id="sampleName" class="form-control form-control-md"
                                required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Sample Code: </label>
                        <label for="" class="input-group">
                            <input type="text" name="sampleCode" id="sampleCode" class="form-control form-control-md"
                                required="true">
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="sampleType">Select Sample Type:</label>
                        <select name="sampleType" id="editsampleType"
                            class="form-control form-control-md dynamic_dropdown" required>
                            <option value="">-- Select Sample Type --</option>
                            <?php foreach($sample_types as $sample_type): ?>
                            <option value="<?= $sample_type->id ?>"><?= $sample_type->sample_type_name ?></option>
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

<div class="modal fade animated bounceInDown" id="modal-active-sample-name" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Sample Name</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-sample-name">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Sample Name?</strong></p>

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

<div class="modal fade animated bounceInDown" id="modal-deactivate-sample-name" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Sample Name</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-sample-name">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Sample Name?</strong></p>

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