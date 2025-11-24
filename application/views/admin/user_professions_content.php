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
                            id="tbl-user-professions">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>User Name</th>
                                    <th>Professions</th>
                                    <th>License No.</th>
                                    <th>Valid Until.</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-user-professions" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add User Professions</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-user-professions">
                <div class="modal-body">
                          <div class="form-group">
                        <label for="professionID">Select Profession:</label>
                        <select name="professionID" class="form-control form-control-md dropdown" required="true">
                            <option value="">-- Profession--</option>
                            <?php foreach($professions as $profession): ?>
                            <option value="<?= $profession->id ?>"><?= $profession->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="userID">Select Users:</label>
                        <select name="userID" class="form-control form-control-md dynamic_dropdown" required="true">
                            <option value="">--User Name--</option>
                            <?php foreach($users as $user): ?>
                            <option value="<?= $user->userID ?>"><?= $user->userFirstName . " " . $user->userLastName ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="exampleInputEmail1">License No.: </label>
                        <label for="" class="input-group">
                            <input type="text" name="LicenseNo" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Valid Until: </label>
                        <label for="" class="input-group">
                            <input type="date" name="ValidUntil" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    
                    <div class="form-group">
                       <label>Assign as Signatory to Laboratories:</label>
                        <div style="max-height:150px; overflow-y:auto; border:1px solid #ddd; padding:10px; border-radius:4px;">
                            <?php if(!empty($laboratories)): ?>
                                <?php foreach($laboratories as $lab): ?>
                                    <label style="display:block; margin-bottom:5px;">
                                        <input type="checkbox" name="laboratories[]" value="<?= $lab->id ?>">
                                        <?= $lab->laboratory_name ?>
                                    </label>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p>No laboratories found.</p>
                            <?php endif; ?>
                        </div>
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

<div class="modal fade animated bounceInDown" id="modal-edit-user-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Professions</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="update-user-professions">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="professionID">Select Professions:</label>
                        <select name="professionID" id="professionID" class="form-control form-control-md dropdown" required>
                            <option value="">-- Select Professions --</option>
                            <?php foreach($professions as $profession): ?>
                            <option value="<?= $profession->id ?>"><?= $profession->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="userID">Select Users:</label>
                  <select name="userID" id="edit_userID" class="form-control form-control-md dynamic_dropdown" required>

                            <option value="">-- Select Users --</option>
                            <?php foreach($users as $user): ?>
                                <option value="<?= $user->userID ?>"><?= $user->userFirstName . " " . $user->userLastName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>


                    <div class="form-group">
                        <label for="exampleInputEmail1">License No.: </label>
                        <label for="" class="input-group">
                            <input type="text" name="LicenseNo" id = "LicenseNo" class="form-control form-control-md" required="true">
                        </label>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Valid Until: </label>
                        <label for="" class="input-group">
                            <input type="date" name="ValidUntil" id = "ValidUntil" class="form-control form-control-md" required="true">
                        </label>
                    </div>

    <div class="form-group">
    <label>Assign as Signatory to Laboratories:</label>
    <div style="max-height:150px; overflow-y:auto; border:1px solid #ddd; padding:10px; border-radius:4px;">
        <?php if(!empty($laboratories)): ?>
            <?php foreach($laboratories as $lab): ?>
                <label style="display:block; margin-bottom:5px;">
                    <input type="checkbox" name="laboratories[]" value="<?= $lab->id ?>" class="lab-checkbox">
                    <?= $lab->laboratory_name ?>
                </label>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No laboratories found.</p>
        <?php endif; ?>
    </div>
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


<div class="modal fade animated bounceInDown" id="modal-active-user-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>User Profession</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-user-professions">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this User Profession?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade animated bounceInDown" id="modal-deactivate-user-professions"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate User Profession</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-user-professions">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this User Profession?</strong></p>

                    <p class="text-center">
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                        <button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">No</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div> 