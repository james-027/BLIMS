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
                            id="tbl-lab-test">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>Test Code</th>
                                    <th>Laboratory Test</th>
                                    <th>Test Parameter</th>
                                    <th>Analyst</th>
                                    <th>Test Method</th>
                                    <th>Type of Sample</th>
                                    <th>Lead Time (Regular)</th>
                                    <th>Lead Time (Rush)</th>
                                    <th>Reference Method</th>
                                    <th>Laboratory Location</th>
                                    <th>Test Group</th>
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

<div class="modal fade animated bounceInDown" id="modal-add-lab-test" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Add Laboratory Test</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="add-lab-test">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="labtestGroup">Select Laboratory Test Group:</label>
                        <select name="labtestGroup" id="labtestGroup" class="form-control form-control-md" required>
                            <option value="">-- Select Laboratory Test Group --</option>
                            <?php foreach($lab_test_groupings as $group): ?>
                            <option value="<?= $group->id ?>"><?= $group->group_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="testCode">Select Test Code :</label>
                        <select name="testCode" id="testCode" class="form-control form-control-md" required>
                            <option value="">-- Select Test Code --</option>
                            <?php foreach($tests as $test): ?>
                            <option value="<?= $test->id ?>"><?= $test->test_code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paramName">Select Test Parameters :</label>
                        <select name="paramName" id="paramName" class="form-control form-control-md" required>
                            <option value="">-- Select Test Parameters --</option>
                            <?php foreach($test_parameters as $parameter): ?>
                            <option value="<?= $parameter->id ?>"><?= $parameter->param_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="analystName">Select Analyst :</label>
                        <select name="analystName" id="analystName" class="form-control form-control-md" required>
                            <option value="">-- Select Analyst --</option>
                            <?php foreach($analysts as $analyst): ?>
                            <option value="<?= $analyst->id ?>"><?= $analyst->analyst_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="methodName">Select Test Method :</label>
                        <select name="methodName" id="methodName" class="form-control form-control-md" required>
                            <option value="">-- Select Test Method --</option>
                            <?php foreach($test_methods as $method): ?>
                            <option value="<?= $method->id ?>"><?= $method->method_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sampleTypes">Select Sample Types :</label>
                        <select name="sampleTypes" id="sampleTypes" class="form-control form-control-md" required>
                            <option value="">-- Select Sample Types --</option>
                            <?php foreach($sample_types as $sample_type): ?>
                            <option value="<?= $sample_type->id ?>"><?= $sample_type->sample_type_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="refMethod">Select Reference Method :</label>
                        <select name="refMethod" id="refMethod" class="form-control form-control-md" required>
                            <option value="">-- Select Reference Method --</option>
                            <?php foreach($ref_methods as $ref_method): ?>
                            <option value="<?= $ref_method->id ?>"><?= $ref_method->method_name ?></option>
                            <?php endforeach; ?>
                        </select>
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
                    <div class="form-group">
                        <label for="leadRegular">Lead Time (Regular):</label>
                        <input type="number" name="leadRegular" id="leadRegular" class="form-control form-control-md"
                            placeholder="Enter regular lead time (days)" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="leadRush">Lead Time (Rush):</label>
                        <input type="number" name="leadRush" id="leadRush" class="form-control form-control-md"
                            placeholder="Enter rush lead time (days)" min="0" require>
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

<div class="modal fade animated bounceInDown" id="modal-edit-lab-test" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Update Laboratory Test</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="POST" id="update-lab-test">
                <input type="hidden" name="id" id="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="labtestGroup">Select Laboratory Test Group:</label>
                        <select name="labtestGroup" id="labtestGroup" class="form-control form-control-md" required>
                            <option value="">-- Select Laboratory Test Group --</option>
                            <?php foreach($lab_test_groupings as $group): ?>
                            <option value="<?= $group->id ?>"><?= $group->group_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="testCode">Select Test Code :</label>
                        <select name="testCode" id="testCode" class="form-control form-control-md" required>
                            <option value="">-- Select Test Code --</option>
                            <?php foreach($tests as $test): ?>
                            <option value="<?= $test->id ?>"><?= $test->test_code ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="paramName">Select Test Parameters :</label>
                        <select name="paramName" id="paramName" class="form-control form-control-md" required>
                            <option value="">-- Select Test Parameters --</option>
                            <?php foreach($test_parameters as $parameter): ?>
                            <option value="<?= $parameter->id ?>"><?= $parameter->param_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="analystName">Select Analyst :</label>
                        <select name="analystName" id="analystName" class="form-control form-control-md" required>
                            <option value="">-- Select Analyst --</option>
                            <?php foreach($analysts as $analyst): ?>
                            <option value="<?= $analyst->id ?>"><?= $analyst->analyst_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="methodName">Select Test Method :</label>
                        <select name="methodName" id="methodName" class="form-control form-control-md" required>
                            <option value="">-- Select Test Method --</option>
                            <?php foreach($test_methods as $method): ?>
                            <option value="<?= $method->id ?>"><?= $method->method_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="sampleTypes">Select Sample Types :</label>
                        <select name="sampleTypes" id="sampleTypes" class="form-control form-control-md" required>
                            <option value="">-- Select Sample Types --</option>
                            <?php foreach($sample_types as $sample_type): ?>
                            <option value="<?= $sample_type->id ?>"><?= $sample_type->sample_type_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="refMethod">Select Reference Method :</label>
                        <select name="refMethod" id="refMethod" class="form-control form-control-md" required>
                            <option value="">-- Select Reference Method --</option>
                            <?php foreach($ref_methods as $ref_method): ?>
                            <option value="<?= $ref_method->id ?>"><?= $ref_method->method_name ?></option>
                            <?php endforeach; ?>
                        </select>
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
                    <div class="form-group">
                        <label for="leadRegular">Lead Time (Regular):</label>
                        <input type="number" name="leadRegular" id="leadRegular" class="form-control form-control-md"
                            placeholder="Enter regular lead time (days)" min="0" required>
                    </div>
                    <div class="form-group">
                        <label for="leadRush">Lead Time (Rush):</label>
                        <input type="number" name="leadRush" id="leadRush" class="form-control form-control-md"
                            placeholder="Enter rush lead time (days)" min="0" require>
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


<div class="modal fade animated bounceInDown" id="modal-active-lab-test" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Laboratory Test</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="activate-lab-test">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to activate this Laboratory Test?</strong></p>

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

<div class="modal fade animated bounceInDown" id="modal-deactivate-lab-test" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Laboratory Test</strong></h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" id="deactivate-lab-test">
                <div class="modal-body">
                    <input type="hidden" name="id" id="id">
                    <p class="text-center"><strong>Are you sure to deactivate this Laboratory Test?</strong></p>

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