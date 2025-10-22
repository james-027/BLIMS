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
                <form  method="POST" action="<?=base_url('login/add-user-rating')?>" id="add-user-rating">
                    <div class="card-header">
                        <h4 class="card-title">Share your thoughts.</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-12" >
                                    <input type="hidden" name="current_controller" value="<?=encode($this->uri->segment(1));?>">
                                    <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                    <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">

                                    <div class="form-group">
                                        <label class="form-label">How would you rate the system?</label><br>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="radio" name="rating" value="<?=encode('good')?>" class="selectgroup-input form-control rating-btn-good">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-thumbs-up"></i></span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="radio" name="rating" value="<?=encode('bad')?>" class="selectgroup-input form-control rating-btn-bad"="">
                                                <span class="selectgroup-button selectgroup-button-icon"><i class="fas fa-thumbs-down"></i></span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group feedback-group">
                                        <label class="form-label feedback-group-label"></label><br>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="user-feedback[]" value="functionality" class="selectgroup-input form-control"="">
                                                <span class="selectgroup-button">Functionality</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="user-feedback[]" value="ease of use" class="selectgroup-input form-control">
                                                <span class="selectgroup-button">Ease of use</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="user-feedback[]" value="has usual support" class="selectgroup-input form-control">
                                                <span class="selectgroup-button">Has usual support</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="user-feedback[]" value="completeness" class="selectgroup-input form-control">
                                                <span class="selectgroup-button">Completeness</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="user-feedback[]" value="efficient" class="selectgroup-input form-control">
                                                <span class="selectgroup-button">Efficient</span>
                                            </label>
                                            
                                        </div>
                                    </div>

                                    

                                    <div class="form-group">
                                        <label for="">Comments</label>
                                        <label for="" class="input-group">
                                            <textarea name="user-comment" class="form-control form-control-md" rows="4" > </textarea>
                                        </label>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-form-label text-gray-500">Your feedback will be confidential</label>
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
                <form  method="POST" action="<?=base_url('login/edit-user-password')?>" id="update-password-form">
                    <div class="card-header">
                        <h4 class="card-title">Update Password</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            
                            <div class="col-md-12" >
                                <input type="hidden" name="current_controller" value="<?=encode($this->uri->segment(1));?>">
                                <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">
                                <div class="form-group">
                                    <label for="">Current Password</label>
                                    <label for="" class="input-group">
                                        <input type="password" name="current-pass" class="form-control form-control-md password" minlength="7" required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">New Password</label>
                                    <label for="" class="input-group">
                                        <input type="password" name="new-pass" class="form-control form-control-md password" minlength="7" placeholder="" required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">Confirm Password </label>
                                    <label for="" class="input-group">
                                        <input type="password" name="confirm-pass" class="form-control form-control-md password" minlength="7" placeholder="" required="true">
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
                        
                        <img class="rounded-circle img-responsive mx-auto" src="<?=base_url('uploads/contact-us/contact-us-1.jpg')?>"><br>
                        <div class="form-group">
                            <label for="">Aljune K. Atok</label><br>
                            <label for="">Information System Sr. Programmer</label><br>
                            <label for="">(+63) 917 879 4634</label>
                            
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
                        
                        <div class="col-md-12 text-center " >
                            
                            <!-- <div class="avatar avatar-xxl">
                                <img src="<?=@$profile['profile_img_link']?>" alt="..." class="avatar-img rounded-circle">
                            </div> -->
                            <img class="rounded-circle img-responsive mx-auto" src="<?=@$profile['profile_img_link']?>"><br>
                            <br>
                            
                            
                        </div>

                        
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form-group text-center">
                        <button type="button" class="btn btn-<?=$btnColor?> btn-md btn-round " href="#" data-toggle="modal" data-target="#uploadPhotoModal">
                            <i class="flaticon-picture mr-1"></i> <font class="font-weight-bolder">Change Photo </font>
                        </button>
                    </div>
                </div>
                
            </div>
            
            <div class="card card-round">
                <form  method="POST" action="<?=base_url('login/edit-user-profile')?>" id="update-profile-form">
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
                                
                                
                                <input type="hidden" name="current_controller" value="<?=encode($this->uri->segment(1));?>">
                                <input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
                                <input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-title" class="form-control form-control-md" value="<?=@$profile['userTitle']?>" minlength="2" placeholder="Mr., Ms., Dr., etc..">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="">First Name</label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-fname" class="form-control form-control-md" value="<?=@$profile['firstName']?>" minlength="2" placeholder="" required="true">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Last Name </label>
                                    <label for="" class="input-group">
                                        <input type="text" name="user-lname" class="form-control form-control-md" value="<?=@$profile['lastName']?>" minlength="2" placeholder="" required="true">
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
                </div>
            </div>

            
        </div>

        
    </div>

</div>