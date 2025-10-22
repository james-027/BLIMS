<div class="page-inner">
    <div id="loader-div">
        <div id="loader-wrapper">
            <div id="loader"></div>
        </div>
    </div>
    <div class="page-header">
        <h4 class="page-title"><?=@$title?></h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="<?=base_url('admin/')?>">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?=@$parent_title?></a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#"><?=@$title?></a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                
                <div class="card-body">
                    <?=$new_button?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover dt-responsive nowrap full-width" style="width:100%" id="tbl-sLoc">
                            <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                <tr>
                                    <th>SLoc. Code</th>
                                    <th>SLoc. Name</th>
                                    <th>SLoc. Address</th>
                                    <th>SLoc. Type</th>
                                    <th>Business Center</th>
                                    <th>Full Name</th>
                                    <th>Farm Name</th>
                                    <th>Farm</th>
                                    <th>Date Started</th>
                                    <th>Litter Material</th>
                                    <th>Heat Source</th>
                                    <th>Farm Type</th>
                                    <th>Capacity</th>
                                    <th>No. of House</th>
                                    <th>Farm Coordinates</th>
                                    <th>Province</th>
                                    <th>CG Personnel</th>
                                    <th>Home Address</th>
                                    <th>CP No.</th>
                                    <th>Email</th>
                                    <th>Farm Changes</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade animated--grow-in" id="modal-add-sLoc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Add Contract Grower</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="add-sLoc">
                    <div class="modal-body">
                        <div class="row">
                            
                                
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Business Center:</label>
                                    <label for="" class="input-group">
                                        <select name="bc-id-for-sLoc" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($bc as $row):?>
                                                <option value="<?=$row->bcID?>"><?=$row->bcName?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Type of Storage Location:</label>
                                    <label for="" class="input-group">
                                        <select name="sLocType-id-for-sLoc" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($sLocType as $row):?>
                                                <option value="<?=$row->slocTypeID?>"><?=$row->slocTypeLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Storage Location Code: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="sLoc-code" class="form-control form-control-md" required="true" placeholder="Registered SAP SLoc Code">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Storage Location Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="sLoc-name" class="form-control form-control-md" required="true" placeholder="Registered SAP SLoc Name">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm Address: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="sLoc-addr" class="form-control form-control-md" required="true" placeholder="Registered SAP SLoc Address">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="firstName" class="form-control form-control-md" required="true" placeholder="CG First Name">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="surName" class="form-control form-control-md" required="true" placeholder="CG Last Name">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farmName" class="form-control form-control-md" required="true" placeholder="Farm Name">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farm" class="form-control form-control-md" placeholder="Farm (A, B, C, etc.)">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Date Started:</label><br>
                                    <label for="" class="input-group">
                                        <div class="datepicker date input-group">
                                            <input type="text" placeholder="Pick a date" class="form-control form-control-md col-md-6" name="dateStarted" required="true" value="<?=date("m/d/Y");?>">
                                            <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar"></i></span></div>
                                        </div>
                                    </label>
                                </div>
                                    

                                <div class="form-group">
                                    <label>Litter Material:</label>
                                    <label for="" class="input-group">
                                        <select name="litterMaterialID" class="form-control form-control-md basic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($littermaterial as $row):?>
                                                <option value="<?=$row->litterMaterialID?>"><?=$row->litterMaterialLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Heat Source:</label>
                                    <label for="" class="input-group">
                                        <select name="heatSourceID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($heatsource as $row):?>
                                                <option value="<?=$row->heatSourceID?>"><?=$row->heatSourceLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Farm Type:</label>
                                    <label for="" class="input-group">

                                        <select name="farmTypeID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($farmtype as $row):?>
                                                <option value="<?=$row->farmTypeID?>"><?=$row->farmTypeLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>


                                <div class="form-group">
                                    <label for="exampleInputEmail1">Capacity: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="capacity" class="form-control form-control-md numeric" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">No. of House: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="noOfHouse" class="form-control form-control-md numeric" required="true">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm Coordinates: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farmCoordinates" class="form-control form-control-md" required="true">
                                    </label>
                                </div>

                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">CP Number 1: </label>
                                            <label for="" class="input-group">
                                                <input type="text" name="cpNo1" placeholder="9171234567" class="form-control form-control-md numeric" pattern="[789][0-9]{9}" required="true">
                                            </div>
                                        </div>
                                    </label>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">CP Number 2: </label>
                                            <label for="" class="input-group">
                                                <input type="text" name="cpNo2" placeholder="9181234568" class="form-control form-control-md numeric" pattern="[789][0-9]{9}">
                                            </div>
                                        </div>
                                    </label>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">CP Number 3: </label>
                                            <label for="" class="input-group">
                                                <input type="text" name="cpNo3" placeholder="9191234569" class="form-control form-control-md numeric" pattern="[789][0-9]{9}">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                        
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="email" class="form-control form-control-md" required="true">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Home Address: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="homeAddress" class="form-control form-control-md" required="true">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>Province:</label>
                                    <label for="" class="input-group">
                                        <select name="provinceID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($province as $row):?>
                                                <option value="<?=$row->provinceID?>"><?=$row->provinceLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label>CG Personnel:</label>
                                    <label for="" class="input-group">
                                        <select name="vetID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($vet as $row):?>
                                                <option value="<?=$row->vetID?>"><?=$row->firstName.' '.$row->surName.' ['.$row->bcName.']'?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>

                                
                            </div>
                                
                            
                        </div>
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade animated--grow-in" id="modal-upload-sLoc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Upload Contract Grower</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="upload-sLoc">
                    <div class="modal-body">
                        <div class="row col-lg-12">
                            Need Upload Template ?&nbsp;<a class="card-link" href="<?=base_url('admin/download-sloc-template')?>"><span class="fas fa-download"></span>&nbsp;Download here</a>
                        </div>
                        <hr>
                        <div class="row col-lg-12">
                            <div class="form-group">
                                <label>Select Excel File</label><br>
                                <input type="file" name="sloc-file" class="form-contol-md form-control-file" required accept=".xlsx" />
                            </div>
                        </div>
                        
                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade animated--grow-in" id="modal-edit-sLoc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Update Contract Grower</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="update-sLoc">
                    <input type="hidden" name="id" id="id">
                    <div class="modal-body">
                        
                        <div class="row col-md-12">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox medium">
                                        <input type="checkbox" class="custom-control-input" id="customCheck" name="history-on" value="1" checked>
                                        <label class="custom-control-label" for="customCheck">Store update to Farm changes/history</label>
                                    </div>
                                </div>
                                <hr class="border-<?=$btnColor?>">
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    
                                    <label>Business Center:</label>
                                    <label class="input-group">
                                        <select name="bc-id" id="bc-id" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Type of Storage Location:</label><br>
                                    <label class="input-group">
                                        <select name="sLocType-id" id="sLocType-id" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Storage Location Code: </label><br>
                                    <label class="input-group">
                                        <input type="text" name="sLoc-code" id="sLoc-code" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">Storage Location Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="sLoc-name" id="sLoc-name" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">

                                    <label for="">Farm Address: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="sLoc-addr" id="sLoc-addr" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">First Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="firstName" id="firstName" class="form-control form-control-md" required="true" placeholder="CG First Name">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="surName" id="surName" class="form-control form-control-md" required="true" placeholder="CG Last Name">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm Name: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farmName" id="farmName" class="form-control form-control-md" required="true" placeholder="Farm Name">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farm" id="farm" class="form-control form-control-md" placeholder="Farm (A, B, C, etc.)">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>Date Started:</label><br>
                                    <label for="" class="input-group">
                                        <div class="datepicker date input-group p-0" id="dateStartedParent">
                                            <input type="text" placeholder="Pick a date" class="form-control form-control-md col-md-6" name="dateStarted" id="dateStarted" required="true">
                                            <div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar"></i></span></div>
                                        </div>
                                    </label>
                                </div>
                                    
                                    
                                    
                                <div class="form-group">
                                    <label>Litter Material:</label>
                                    <label for="" class="input-group">
                                        <select name="litterMaterialID" id="litterMaterialID" class="form-control form-control-md basic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">

                                    <label>Heat Source:</label>
                                    <label for="" class="input-group">
                                        <select name="heatSourceID" id="heatSourceID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($heatsource as $row):?>
                                                <option value="<?=$row->heatSourceID?>"><?=$row->heatSourceLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label>Farm Type:</label>
                                    <label for="" class="input-group">
                                        <select name="farmTypeID" id="farmTypeID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($farmtype as $row):?>
                                                <option value="<?=$row->farmTypeID?>"><?=$row->farmTypeLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Capacity: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="capacity" id="capacity" class="form-control form-control-md numeric" required="true">
                                    </label>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="exampleInputEmail1">No. of House: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="noOfHouse" id="noOfHouse" class="form-control form-control-md numeric" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Farm Coordinates: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="farmCoordinates" id="farmCoordinates" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>Province:</label>
                                    <label for="" class="input-group">
                                        <select name="provinceID" id="provinceID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($province as $row):?>
                                                <option value="<?=$row->provinceID?>"><?=$row->provinceLDesc?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label>CG Personnel:</label>
                                    <label for="" class="input-group">
                                        <select name="vetID" id="vetID" class="form-control form-control-md dynamic_dropdown" required="true" required="true">
                                            <option value=""> Select...</option>
                                            <?php foreach($vet as $row):?>
                                                <option value="<?=$row->vetID?>"><?=$row->firstName.' '.$row->surName?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Home Address: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="homeAddress" id="homeAddress" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email: </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="email" id="email" class="form-control form-control-md" required="true">
                                    </label>
                                </div>
                                
                                <div class="form-group">    
                                    <a href="<?=base_url('admin/mobile')?>" class="card-link form-label">Mobile Numbers</a>
                                    
                                    <div id="mobbilenumberslabel"></div>
                                    <label>Do you want to manage mobile numbers? Click </label>&nbsp;<a href="#" class="card-link form-label mobile-redirect"> here</a>
                                </div>
                            </div>
                        </div>
                                
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade animated--grow-in" id="modal-active-sLoc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Activate Contract Grower</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="activate-sLoc">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <p class="text-center"><strong>Are you sure to activate <br><span id ="val"></span>?</strong><hr class="bg-<?=$btnColor?>"><span id ="substat-val"></span></p>
                        <div class="form-group">
                            <label>Reason: </label>
                            
                            <textarea placeholder="Reason for activation" class="form-control form-control-md" required="true" name="reason" rows="2"></textarea>
                        </div>

                        <p class="text-center">
                            <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                            <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">No</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade animated--grow-in" id="modal-deactivate-sLoc"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Deactivate Contract Grower</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="deactivate-sLoc">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <p class="text-center"><strong>Are you sure to deactivate <br><span id ="val"></span>?</strong></p>
                            <label>Reason for deactivation:</label>
                            <select name="statusID" class="form-control form-control-md dynamic_dropdown" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($stats as $row):?>
                                    <option value="<?=$row->statusID?>"><?=$row->statDesc?></option>
                                <?php endforeach;?>
                            </select>

                            <label class="mt-1 mb-2">Due to:</label>
                            <a href="#" title="Add Supplementary Reason" class="btn btn-<?=$btnColor?> btn-round btn-xs float-right mt-1 mb-3 add-substat">
                                <i class="fas fa-plus"></i>
                            </a>
                            <select name="subStatusID" class="form-control form-control-md dynamic_dropdown dynamic-substatus" required="true">
                                <option value=""> Select...</option>
                                <?php foreach($substat as $row):?>
                                    <option value="<?=$row->subStatusID?>"><?=$row->subStatDesc?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <p class="text-center">
                            <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Yes</button>&nbsp;
                            <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">No</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade animated--grow-in" id="modal-view-history-cg"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title font-weight-bold"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button type="button" class="btn btn-<?=@$btnColor?> btn-sm refresh-dt btn-to-hide"><span class="fa fa-refresh"></span></button>&nbsp;
                            
                            <table class="table  tbl-view-history-cg" style="width:100%">
                                <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                    <tr>
                                        <th>ID</th>
                                        <th>Logs</th>
                                        <th>Registered Time</th>
                                        <th>User</th>
                                    </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                </div>
                
            </div>
        </div>
    </div>


    <div class="modal fade animated--grow-in" id="modal-import-result"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl2" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title font-weight-bold"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    
                    <div class="col-lg-12">
                        <div class="form-group">
                            
                            <table class="table  dt-responsive nowrap tbl-import-result" style="width:100%">
                                <thead class="bg-<?=$thColor?> <?=expColor($thColor)->fontColor?>">
                                    <tr>
                                        <th>Business Center</th>
                                        <th>SLoc Code</th>
                                        <th>SLoc Name</th>
                                        <th>SLoc Address</th>
                                        <th>CG Full Name</th>
                                        <th>Farm Name</th>
                                        <th>Farm</th>
                                        <th>Date Started</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                </div>
                
            </div>
        </div>
    </div>
    

    <div class="modal fade animated--grow-in" id="modal-add-substat"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=$btnColor?>">
                    <h6 class="modal-title" id="exampleModalLabel"><strong>Add Supplementary Reason</strong></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" id="add-substat">
                    <div class="modal-body">
                        
                        <div class="form-group">

                            <label for="exampleInputEmail1">Supplementary Reason: </label>
                            <input type="text" name="subStatDesc" class="form-control form-control-md" required="true">

                        </div>

                    </div>
                
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-md btn-round" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-<?=$btnColor?> btn-md btn-round">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>