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
                            id="tbl-animal-nutritionist">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>

                                    <th>Animal Feed</th>
                                    <th>Location</th>
                                    <th>Assigned Nutrionist</th>
                                    <th>Email Address</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-animal-nutritionist" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Animal Nutritionist</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-animal-nutritionist">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="animalFeed">Select Animal Feed:</label>
                        <select name="animalFeed" id="animalFeed" class="form-control form-control-md" required>
                            <option value="">-- Select Animal Feed --</option>
                            <?php foreach($animal_feeds as $animal_feed): ?>
                            <option value="<?= $animal_feed->id ?>"><?= $animal_feed->feeds_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="locationName">Select Location :</label>
                        <select name="locationName" id="locationName" class="form-control form-control-md" required>
                            <option value="">-- Select Location --</option>
                            <?php foreach($regions as $region): ?>
                            <option value="<?= $region->rgID ?>"><?= $region->rgLDesc ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nutritionistName">Select Nutritionist :</label>
                        <select name="nutritionistName" id="nutritionistName" class="form-control form-control-md" required>
                            <option value="">-- Select Nutritionist --</option>
                            <?php foreach($nutritionists as $nutritionist): ?>
                            <option value="<?= $nutritionist->id ?>"><?= $nutritionist->nutritionist_name ?></option>
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

<div class="modal fade animated bounceInDown" id="modal-edit-animal-nutritionist" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Animal Nutritionist</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="update-animal-nutritionist">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="animalFeed">Select Animal Feed:</label>
                        <select name="animalFeed" id="animalFeed" class="form-control form-control-md" required>
                            <option value="">-- Select Animal Feed --</option>
                            <?php foreach($animal_feeds as $animal_feed): ?>
                            <option value="<?= $animal_feed->id ?>"><?= $animal_feed->feeds_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="locationName">Select Location :</label>
                        <select name="locationName" id="locationName" class="form-control form-control-md" required>
                            <option value="">-- Select Location --</option>
                            <?php foreach($regions as $region): ?>
                            <option value="<?= $region->rgID ?>"><?= $region->rgLDesc ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nutritionistName">Select Nutritionist :</label>
                        <select name="nutritionistName" id="nutritionistName" class="form-control form-control-md" required>
                            <option value="">-- Select Nutritionist --</option>
                            <?php foreach($nutritionists as $nutritionist): ?>
                            <option value="<?= $nutritionist->id ?>"><?= $nutritionist->nutritionist_name ?></option>
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


<div class="modal fade animated bounceInDown" id="modal-active-animal-nutritionist" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Animal Nutritionist</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-animal-nutritionist">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Animal Nutritionist?</strong></p>

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

<div class="modal fade animated bounceInDown" id="modal-deactivate-animal-nutritionist" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Animal Nutritionist</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-animal-nutritionist">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Animal Nutritionist?</strong></p>

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