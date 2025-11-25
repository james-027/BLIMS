<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>

<div class="page-inner animated fadeInRightBig">

    <?=$breadcrumbs?>

    <div class="row justify-content-center mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                    <h4 class="mb-0"><?php echo $title ?></h4>
                </div>
                <div class="card-body">

                    <form method="post" action="<?=base_url($controller.'/submit_registration')?>" enctype="multipart/form-data"
                        id="registrationForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="internalFeedmill">Internal Feedmill<span class="text-danger"> *</span></label>
                                    <select class="form-control dynamic_dropdown" id="internalFeedmill" name="internalFeedmill" required>
                                        <option value="">Select Feedmill</option>
                                        <?php foreach($internalFeedmills as $feedmill): ?>
                                        <option value="<?= $feedmill->id ?>"><?= $feedmill->feedmill_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="commercialFeed">Commercial Feedmill</label>
                                    <select class="form-control" id="commercialFeed" name="commercialFeed" required
                                        disabled>
                                        <option value="">Select Feed</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        placeholder="Enter address" required>
                                </div>

                                <div class="form-group">
                                    <label>External Client</label>
                                    <a href="#" id="externalClientToggle" class="text-warning">
                                        <span class="fas fa-toggle-off fa-lg"></span>
                                    </a>
                                    <input type="text" class="form-control mt-2" id="externalClientName"
                                        name="externalClientName" placeholder="Enter External Client Name"
                                        style="display:none;">
                                </div>
                                <div class="form-group">
                                    <label for="contactNumber">Contact Number<span class="text-danger"> *</span></label>
                                    <input type="tel" class="form-control" id="contactNumber" name="contactNumber"
                                        placeholder="0917 123 4567" pattern="^(09|\+639)\d{9}$" maxlength="13" required>
                                </div>
                                <div class="form-group">
                                    <label for="nutritionist">Nutritionist<span class="text-danger"> *</span></label>
                                    <select class="form-control dynamic_dropdown" id="nutritionist" name="nutritionist" required>
                                        <option value="">Select Nutritionist</option>
                                        <?php foreach($nutritionists as $nutri): ?>
                                        <option value="<?= $nutri->id ?>"><?= $nutri->nutritionist_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="labLocation">Laboratory Location<span class="text-danger"> *</span></label></label>
                                    <select class="form-control " id="labLocation" name="labLocation" required>
                                        <option value="">Select Location</option>
                                        <?php foreach($laboratories as $lab): ?>
                                        <option value="<?= $lab->id ?>"><?= $lab->laboratory_name ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Type</label><br>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deliveryType" id="courier"
                                            value="Courier" checked>
                                        <label class="form-check-label" for="courier">Courier</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="deliveryType" id="internal"
                                            value="Internal">
                                        <label class="form-check-label" for="internal">Internal Handling</label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="wayBillNumber">Way Bill Number</label>
                                    <input type="text" class="form-control" id="wayBillNumber" name="wayBillNumber"
                                        placeholder="Enter Way Bill Number" required>
                                </div>

                                <div id="driverGroup" class="form-group">
                                    <label for="driverName">Driver Name</label>
                                    <input type="text" class="form-control" id="driverName" name="driverName" placeholder="Enter Driver Name" required>
                                </div>

                                <div class="form-group" id="plateNumberGroup" style="display:none;">
                                    <label for="plateNumber">Plate Number</label>
                                    <input type="text" class="form-control" id="plateNumber" name="plateNumber"
                                        placeholder="Enter Plate Number">
                                </div>

                                <div class="form-group">
                                    <label for="submittedBy">Submitted By</label>
                                    <input type="text" class="form-control" id="submittedBy" name="submittedBy"
                                        value="<?=$submittedBy?>" placeholder="Enter your name" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="dateSubmitted">Date Submitted</label>
                                    <input type="text" class="form-control" id="dateSubmitted" name="dateSubmitted"
                                        value="<?=date('Y-m-d')?>" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="remarks">Remarks</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="2"
                                        placeholder="Enter any remarks"></textarea>
                                </div>
                                 <div class="form-group">
                                    <label for="attachFile">Attach File</label>
                                    <input type="file" class="form-control-file" id="attachFile" name="attachFile">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5>Sample Details<span class="text-danger"> *</span></label></h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="sampleDetailsTable">
                                    <thead class="thead-light text-center">
                                        <tr>
                                            <th style="min-width:140px;">Sample Name</th>
                                            <th style="min-width:140px;">Type of Sample</th>
                                            <th style="min-width:180px;">Test Code</th>
                                            <th style="min-width:140px;">Production/Delivery Date</th>
                                            <th style="min-width:180px;">Shipment/Supplier</th>
                                            <th style="min-width:140px;">Plate/Van Number</th>
                                            <th style="min-width:100px;">Batch/Lot Number</th>
                                            <th style="min-width:140px;">Type of Lead Time</th>
                                            <th style="min-width:20px;">COA</th>
                                            <th style="min-width:80px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><select name="sampleName[]" class="form-control" required>
                                                    <option value="">Sample</option>
                                                    <?php foreach($samples as $sample): ?>
                                                    <option value="<?= $sample->id ?>"><?= $sample->sample_name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select></td>
                                            <td><select name="typeOfSample[]" class="form-control" required>
                                                    <option value="">Type of Sample</option>
                                                </select></td>
                                            <td><select name="laboratoryTests[]" class="form-control" required>
                                                    <option value="">Test Code</option>
                                                </select></td>
                                            <td><input type="date" name="productionDate[]" class="form-control"
                                                    required></td>
                                            <td><select name="shipmentSupplier[]" class="form-control" required>
                                                    <option value="">Supplier</option>
                                                    <?php foreach($suppliers as $supplier): ?>
                                                    <option value="<?= $supplier->id ?>"><?= $supplier->supplier_name ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select></td>
                                            <td>

                                                <select name="plateVanNumber[]" class="form-control plate-select"
                                                    required>
                                                    <option value="">Plate/Van</option>
                                                    <?php foreach($plate_numbers as $pm): ?>
                                                    <option value="<?= $pm->plate_number  ?>"><?= $pm->plate_number ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="batchLotNumber[]" class="form-control batch-select"
                                                    required>
                                                    <option value="">Batch/Lot Number</option>
                                                    <?php foreach($batches as $batch): ?>
                                                    <option value="<?= $batch->batch_number ?>"><?= $batch->batch_number ?>
                                                    </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>

                                            <td><select name="leadTimeType[]" class="form-control" required>
                                                    <option value="">Select Lead Time</option>
                                                </select></td>
                                            <td class="text-center"><input type="checkbox" name="coaRequired[0]"></td>
                                            <td class="text-center">
                                                <span class="addRow"
                                                    style="cursor:pointer; color:green; font-size:1.2rem; margin-right:5px;"><i
                                                        class="fas fa-plus-circle"></i></span>
                                                <span class="removeRow"
                                                    style="cursor:pointer; color:red; font-size:1.2rem;"><i
                                                        class="fas fa-minus-circle"></i></span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-right mt-3">
                                <button type="submit" class="btn btn-success mr-2">Save</button>
                                <button type="button" class="btn btn-danger"
                                    onclick="window.history.back();">Cancel</button>
                            </div>
                            
                        </div>


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let baseUrl = '<?= base_url() ?>';
let controllerName = '<?= $controller ?>';
let plateNumbers = <?= json_encode($plate_numbers); ?>;
let batchNumbers = <?= json_encode($batches); ?>;
let suppliers = <?= json_encode($suppliers); ?>;
let samples = <?= json_encode($samples); ?>;
</script>