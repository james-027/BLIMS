<div id="loader-div">
    <div id="loader-wrapper">
        <div id="loader"></div>
    </div>
</div>
<div class="page-inner animated fadeInRightBig">

    <?=$breadcrumbs?>

    <?php
        if($this->session->flashdata('message') != "" ){
            echo $this->session->flashdata('message');
        }
    ?>
    <div class="row">
        <div class="col-sm-6 col-md-6">
            <div class="card card-round animated bounceIn">
                <form method="POST" action="<?=base_url('login/add-user-rating')?>" id="add-user-rating">
                    <div class="card-header">
                        <h4 class="card-title">Share your thoughts.</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <input type="hidden" name="current_controller"
                                    value="<?=encode($this->uri->segment(1));?>">
                                <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">

                                <div class="form-group">
                                    <label class="form-label">How would you rate the system?</label><br>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="rating" value="<?=encode('good')?>"
                                                class="selectgroup-input form-control rating-btn-good">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-thumbs-up"></i></span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="rating" value="<?=encode('bad')?>"
                                                class="selectgroup-input form-control rating-btn-bad"="">
                                            <span class="selectgroup-button selectgroup-button-icon"><i
                                                    class="fas fa-thumbs-down"></i></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group feedback-group">
                                    <label class="form-label feedback-group-label"></label><br>
                                    <div class="selectgroup selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="user-feedback[]" value="functionality"
                                                class="selectgroup-input form-control"="">
                                            <span class="selectgroup-button">Functionality</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="user-feedback[]" value="ease of use"
                                                class="selectgroup-input form-control">
                                            <span class="selectgroup-button">Ease of use</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="user-feedback[]" value="has usual support"
                                                class="selectgroup-input form-control">
                                            <span class="selectgroup-button">Has usual support</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="user-feedback[]" value="completeness"
                                                class="selectgroup-input form-control">
                                            <span class="selectgroup-button">Completeness</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="checkbox" name="user-feedback[]" value="efficient"
                                                class="selectgroup-input form-control">
                                            <span class="selectgroup-button">Efficient</span>
                                        </label>

                                    </div>
                                </div>



                                <div class="form-group">
                                    <label for="">Comments</label>
                                    <label for="" class="input-group">
                                        <textarea name="user-comment" class="form-control form-control-md"
                                            rows="4"> </textarea>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label class="col-form-label text-gray-500">Your feedback will be
                                        confidential</label>
                                </div>




                            </div>

                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group text-center">
                            <?=$save_button?>
                        </div>
                    </div>
                </form>
            </div>


            <div class="card card-round" id="change-pass">
                <form method="POST" action="<?=base_url('login/edit-user-password')?>" id="update-password-form">
                    <div class="card-header">
                        <h4 class="card-title">Update Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <input type="hidden" name="current_controller"
                                    value="<?=encode($this->uri->segment(1));?>">
                                <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">
                                <div class="form-group">
                                    <label for="">Current Password</label>
                                    <label for="" class="input-group">
                                        <input type="password" name="current-pass"
                                            class="form-control form-control-md password" minlength="7" required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">New Password</label>
                                    <label for="" class="input-group">
                                        <input type="password" name="new-pass"
                                            class="form-control form-control-md password" minlength="7" placeholder=""
                                            required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">Confirm Password </label>
                                    <label for="" class="input-group">
                                        <input type="password" name="confirm-pass"
                                            class="form-control form-control-md password" minlength="7" placeholder=""
                                            required="true">
                                    </label>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox medium">
                                        <input type="checkbox" class="custom-control-input" id="show_password">
                                        <label class="custom-control-label" for="show_password">Show Passwords</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group text-center">
                            <?=$save_button?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card card-round" id="contact-us">
                <div class="card-header">
                    <h4 class="card-title">Contact Us</h4>
                </div>
                <div class="card-body">


                    <div class="form-group text-center">

                        <div class="form-group">
                            <label for="">James Paul F. Dimaculangan</label><br>
                            <label for="">Information System Jr. Programmer</label><br>
                            <label for="">(+63) 920 925 5430</label>

                        </div>
                    </div>




                </div>


            </div>

        </div>

        <div class="col-sm-6 col-md-6">
            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">Update Profile Picture</h4>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12 text-center ">

                            <!-- <div class="avatar avatar-xxl">
                                <img src="<?=@$profile['profile_img_link']?>" alt="..." class="avatar-img rounded-circle">
                            </div> -->
                            <img class="rounded-circle img-responsive mx-auto"
                                src="<?=@$profile['profile_img_link']?>"><br>
                            <br>


                        </div>


                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-<?=$btnColor?> btn-md btn-round " href="#"
                            data-toggle="modal" data-target="#uploadPhotoModal">
                            <i class="flaticon-picture mr-1"></i>
                            <font class="font-weight-bolder">Change Photo </font>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">E-Signature</h4>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($profile['userEsign'])): ?>
                    <img src="<?=@$profile['userEsign']?>" alt="E-signature"
                        style="width:300px; border:1px solid #ccc; background:white;">
                    <?php else: ?>
                    <p class="text-muted">No E-signature uploaded yet.</p>
                    <?php endif; ?>
                </div>
                <div class="card-footer text-center">
                    <button type="button" class="btn btn-<?= $btnColor ?> btn-md btn-round" data-toggle="modal"
                        data-target="#esignModal">
                        <i class="flaticon-pencil mr-1"></i> <b>Update E-Signature</b>
                    </button>
                </div>
            </div>

            <div class="card card-round">
                <div class="card-header">
                    <h4 class="card-title">User Manual</h4>
                </div>

                <div class="card-body text-center">
                    <p class="text-muted mb-2">
                        Download the system user manual for guidance on using the system.
                    </p>

                    <i class="fa fa-file-pdf-o fa-3x text-danger mb-3"></i>
                </div>

                <div class="card-footer text-center">
                    <a href="<?= base_url('uploads/user_manual/BLIMS_User_Manualv1.pdf') ?>"
                        class="btn btn-<?= $btnColor ?> btn-md btn-round" target="_blank" download>
                        <i class="flaticon-download mr-1"></i> <b>Download Manual (PDF)</b>
                    </a>
                </div>
            </div>



            <div class="card card-round">
                <form method="POST" action="<?=base_url('login/edit-user-profile')?>" id="update-profile-form">
                    <div class="card-header">
                        <h4 class="card-title">Update Basic Info</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">

                                <!-- <div class="form-group">
                                    <a class="btn btn-<?=@$btnColor?> btn-md btn-round btn-border float-right" href="#" id="userGuide">
                                        View User Guide
                                    </a>
                                </div>
                                <br><br> -->


                                <input type="hidden" name="current_controller"
                                    value="<?=encode($this->uri->segment(1));?>">
                                <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-title" class="form-control form-control-md"
                                            value="<?=@$profile['userTitle']?>" minlength="2"
                                            placeholder="Mr., Ms., Dr., etc..">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-fname" class="form-control form-control-md"
                                            value="<?=@$profile['firstName']?>" minlength="2" placeholder=""
                                            required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-lname" class="form-control form-control-md"
                                            value="<?=@$profile['lastName']?>" minlength="2" placeholder=""
                                            required="true">
                                    </label>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="form-group text-center">
                            <?=$save_button?>
                        </div>
                    </div>

                </form>
            </div>
        </div>


    </div>


</div>

</div>

<div class="modal fade" id="esignModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Draw Your E-Signature</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <form action="<?=base_url('login/save_esign')?>" method="POST" id="esignForm">
                <div class="modal-body text-center">

                    <canvas id="esign-pad" width="500" height="200"
                        style="border:1px solid #333; background:white;"></canvas>

                    <input type="hidden" name="userEsign" id="esign-data">

                    <br><br>
                    <button type="button" id="clearEsign" class="btn btn-danger btn-round">Clear</button>
                </div>

                <div class="modal-footer">
                    <button type="submit" id="saveEsignButton" class="btn btn-<?= $btnColor ?> btn-round">
                        Save E-Signature
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>