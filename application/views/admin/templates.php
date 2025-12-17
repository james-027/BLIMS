<?php include_once('templates_cond.php');?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<meta name="description" content="BAVI-IPPS">
    <meta name="author" content="BAVI-IPPS">
	<title><?=SYS_NAME.' - '.@$title?></title>
	<link rel="icon" href="<?= base_url('assets/img/hr-icon.ico') ?>" type="image/x-icon" />
	<link rel="stylesheet" href="<?=base_url('assets/js/lobibox-master/dist/css/lobibox.min.css')?>">

	<!-- Fonts and icons -->
	<script src="<?=base_url()?>assets/js/plugin/webfont/webfont.min.js"></script>
	
	<link rel="stylesheet" href="<?=base_url()?>assets/css/fonts.min.css">
	<link href="<?=base_url()?>/assets/vendor/fontawesome-free-5.15.3/css/all.css" rel="stylesheet" type="text/css">
	
	<!-- CSS Files -->
	<link rel="stylesheet" href="<?=base_url()?>assets/css/bootstrap.css">
	


	<!-- Additional files -->
	<link rel="stylesheet" href="<?=base_url('assets/css/dataTables.bootstrap4.min.css')?>">
	<link rel="stylesheet" href="<?=base_url('assets/css/responsive.bootstrap4.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/select.dataTables.min.css')?>">
    
	<link rel="stylesheet" href="<?=base_url('assets/css/fixedColumns.bootstrap4.min.css')?>">
	<link href="<?=base_url('assets/css/buttons.dataTables.min.css')?>" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?=base_url('assets/css/select2.css')?>"/>
    <link rel="stylesheet" href="<?=base_url('assets/css/select2-bootstrap4.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/scroller.dataTables.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap-datepicker3.css')?>">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	
	<link rel="stylesheet" href="<?=base_url()?>assets/css/atlantis.css?v=2.11">
	<link rel="stylesheet" href="<?=base_url()?>assets/css/demo.css">
	
	
</head>
<body data-background-color="<?=$backgroundColor?>">
	<input type="hidden" value="<?=base_url()?>" id="base_url">
	<input type="hidden" value="<?=SYS_NAME?>" id="sys-name">
	<input type="hidden" id="expThColor" value="<?=$expThColor?>">
	<input type="hidden" id="expFontColor" value="<?=$expFontColor?>">
	<input type="hidden" id="expDtColor" value="<?=$expDtColor?>">
	<input type="hidden" id="sideBarColorVal" value="<?=$sideBarColor?>">
	<input type="hidden" id="btnColorVal" value="<?=$btnColor?>">
	<input type="hidden" id="userRatingInd" value="<?=$userRatingInd?>">
	<input type="hidden" id="miniSideBarConf" value="<?=@$miniSideBarConf?>">
	
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<div class="logo-header" data-background-color="<?=$logoHeaderColor?>">
				
				<a href="#" class="logo">
					<div class="navbar-brand font-weight-bold <?=$logo_added_class?>" data-toggle="tooltip" data-placement="bottom" title="<?=SYS_FULL_NAME?>"> <i class="flaticon-interface-6"></i>&nbsp;<?=SYS_NAME?></div>
					<!-- <img src="<?=base_url()?>assets/img/logo.svg" alt="navbar brand" class="navbar-brand"> -->
				</a>
				<button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon">
						<i class="icon-menu"></i>
					</span>
				</button>
				<button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
				<div class="nav-toggle">
					<button class="btn btn-toggle toggle-sidebar">
						<i class="icon-menu"></i>
					</button>
				</div>
			</div>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<nav class="navbar navbar-header navbar-expand-lg" data-background-color="<?=$topBarColor?>">
				
				<div class="container-fluid">
					<!-- <div class="collapse" id="search-nav">
						<form class="navbar-left navbar-form nav-search mr-md-3">
							<div class="input-group">
								<div class="input-group-prepend">
									<button type="submit" class="btn btn-search pr-1">
										<i class="fa fa-search search-icon"></i>
									</button>
								</div>
								<input type="text" placeholder="Search ..." class="form-control">
							</div>
						</form>
					</div> -->
					<!-- <h2 class="font-weight-bold mt-2"><?=@$title?></h2> -->
					<!-- <div class="navbar-left navbar-nav topbar-nav mr-md-auto">
						<li class="nav-item dropdown hidden-caret">
							<a href="#" class="nav-link change-access" data-toggle="tooltip" data-placement="bottom" title="Change system account access">
								<i class="flaticon-shapes mr-1"></i> <font class="font-weight-bolder"> <?=$keyAccess?> </font>
							</a>
						</li>
					</div> -->

					
					
					<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
						

						<li class="nav-item dropdown hidden-caret">
							<a href="#" class="nav-link change-access" data-toggle="tooltip" data-placement="bottom" title="Change system account access">
								<i class="flaticon-shapes mr-1"></i> <font class="font-weight-bolder"> <?=$keyAccess?> </font>
							</a>
						</li>
						
					
						<li class="nav-item dropdown hidden-caret">
							<a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="clearNotif();">
								<i class="fa fa-bell"></i>
								<span class="<?=$notif_bell_class?>" id="notif-counter"><?=@$notif_counter > 0 ? @$notif_counter : '';?></span>
							</a>
							<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown" >
								<li>
									<div class="dropdown-title" id="notif-dropdown-title"></div>
								</li>
								<li>
									<div class="notif-scroll scrollbar-outer">
										<div class="notif-center" id="announcement-notif">
											<?=@$notif_item?>
										</div>
									</div>
								</li>
								<li>
									<a class="see-all notif-item" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i> </a>
								</li>
							</ul>
						</li>
						
						<li class="nav-item dropdown hidden-caret">
							<a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
								<div class="avatar-sm">
									<img src="<?=@$profile['profile_img_link']?>" alt="..." class="avatar-img rounded-circle">
								</div>
							</a>
							<ul class="dropdown-menu dropdown-user animated fadeIn">
								<div class="dropdown-user-scroll scrollbar-outer">
									<li>
										<div class="user-box">
											<div class="avatar-lg"><img src="<?=@$profile['profile_img_link']?>" alt="image profile" class="avatar-img rounded"></div>
											<div class="u-text">
												<h4><?=$this->session->userdata[APP_SESS_NAME]['userFirstName']?></h4>
												<p class="text-muted"> <?=$this->session->userdata[APP_SESS_NAME]['userTypeName']?> </p><a href="<?=base_url('admin/my-profile')?>" class="btn btn-xs btn-<?=$btnColor?> btn-sm">View Profile</a>
											</div>
										</div>
									</li>
									<li>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?=base_url('admin/activity-logs')?>">Activity Logs</a>
										
										<div class="dropdown-divider"></div>
										<a class="dropdown-item change-access" href="#">Access Setting</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="<?=base_url('admin/my-profile')?>">Rate the System&nbsp;<span class="badge badge-<?=$btnColor?>">New</span></a> 
										<div class="dropdown-divider"></div>
										<a class="dropdown-item" href="#" data-toggle="modal" id="logoutAct">Logout</a>
									</li>
								</div>
							</ul>
						</li>
					</ul>

					
				</div>
			</nav>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<?php $this->load->view('admin/side_menu')?>
		<!-- End Sidebar -->

		<div class="main-panel">
			
			<div class="content container-fluid">
				<?=@$content?>

				
			</div>
			
			
			
		</div>
		
		<!-- Custom template! -->
		<?php $this->load->view('admin/custom_settings')?>
		<!-- End Custom template -->
		
		
		

		<!-- Settings Access Modal-->
		<div class="modal fade animated bounceInDown" id="chAccessModal"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header <?=expColor($btnColor)->fontColor?> bg-<?=@$btnColor?>">
						<h6 class="modal-title" id="exampleModalLabel"><span class="fa fa-user-circle fa-lg"></span>  <strong>Access Settings</strong></h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="POST" action="<?=base_url('login/change-access')?>" id="change-access-form">
						<div class="modal-body ">
							<div class="form-group">
								
								<input type="hidden" name="current_controller" value="<?=encode($this->uri->segment(1));?>">
								<input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
								<input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">
								<label class="<?=$modal_text?>">Available Access:</label>
								<label for="" class="input-group">
									<select name="userKeyID" id="userKeyID" class="form-control input-md dynamic_dropdown" required="true">
										<option value="">SELECT...</option>
									<?php
									if(is_array(@$available_access)){
										foreach (@$available_access as $row) {
											$selected = '';
											if(decode($this->session->userdata[APP_SESS_NAME]['current_userKeyID']) == $row->userKeyID){
												$selected = 'selected';
											}
											echo "<option ".$selected." value='". encode($row->userKeyID) ."' title='Business-Center: ". $row->bcName ."'>" . $row->keyCode . " - " . $row->bcCode . "</option>";
										}
									}
									?>
									</select>
								</label>
								
							</div>
						</div>
					
						<div class="modal-footer">
							<button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-<?=@$btnColor?> btn-md btn-round">Load</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		

		<!-- Profile Modal-->
		<div class="modal fade animated bounceInDown" id="profileModal"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-sm" role="document">
				<div class="modal-content">
					<div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=@$btnColor?>">
						<h6 class="modal-title" id="exampleModalLabel"><span class="fa fa-user-circle fa-lg"></span> <strong>Profile</strong></h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="POST" action="<?=base_url('login/edit-user-profile')?>">
						<div class="modal-body">
							<div class="form-group">
								<input type="hidden" name="current_controller" value="<?=encode($this->uri->segment(1));?>">
								<input type="hidden" name="current_method" value="<?=encode($this->uri->segment(2));?>">
								<input type="hidden" name="current_param" value="<?=$this->uri->segment(3);?>">

								<img class="rounded-circle img-responsive center-block d-block mx-auto" src="<?=@$profile['profile_img_link']?>">
								<a class="page-link text-center text-<?=@$btnColor?>" href="#" data-toggle="modal" data-target="#uploadPhotoModal">
									Change Photo
								</a>
								<hr class="bg-<?=@$btnColor?>">
								<p class="font-weight-bolder text-<?=@$btnColor?> text-center"><?=@$profile['firstName'].' '.@$profile['lastName']?></p>


								<label for="exampleInputEmail1">First Name: </label>
								<input type="text" name="user-fname" class="form-control form-control-md" value="<?=@$profile['firstName']?>" placeholder="" required="true">

								<label for="exampleInputEmail1">Last Name: </label>
								<input type="text" name="user-lname" class="form-control form-control-md" value="<?=@$profile['lastName']?>" placeholder="" required="true">

								
								<label for="exampleInputEmail1">Colour Scheme: </label>
								<select name="themeID" class="form-control form-control-md basic_dropdown" required="true">
									<?=@$profile['themes']?>
								</select>
								<br>
								<a class="page-link text-center text-<?=@$btnColor?>" href="#" id="userGuide">
									View User Guide
								</a>

								
							</div>
						</div>
					
						<div class="modal-footer">
							<button type="button" class="btn btn-danger btn-border fw-bold btn-sm" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-<?=@$btnColor?> btn-sm">Save</button>
						</div>
					</form>

					
				</div>
			</div>
		</div>

		<div class="modal fade animated bounceInDown" id="uploadPhotoModal"   role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-md" role="document">
				<div class="modal-content">
					<div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=@$btnColor?>">
						<h6 class="modal-title" id="exampleModalLabel"><span class="fas fa-upload fa-lg"></span> <strong>Upload Profile Picture</strong></h6>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form method="POST" id="uploadPhotoForm">
						<div class="modal-body text-white">
							
								
							<div class="row col-lg-12">
								<div class="form-group">
									<label class="<?=$modal_text?>">Select Image</label><br>
									<label for="" class="input-group">
										<input type="file" name="prof-pic-file" class="form-contol-md form-control-file <?=$modal_text?>" required accept=".jpg,.png,.jpeg" />
									</label>
								</div>
							</div>
								
							
						</div>
					
						<div class="modal-footer">
							<button type="button" class="btn btn-danger btn-border fw-bold btn-md btn-round" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-<?=@$btnColor?> btn-md btn-round">Upload</button>
						</div>
					</form>

					
				</div>
			</div>
		</div>
		
		<!-- Logout Modal-->
		<div class="modal fade animated bounceInDown" id="logoutModal" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header <?=expColor($thColor)->fontColor?> bg-<?=@$btnColor?>">
						<h6 class="modal-title" id="exampleModalLabel"><span class="fas fa-exclamation-circle fa-lg"></span> Hang on!</h6>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row col-12">
							<div class="col-3">
								<div class="avatar avatar-lg text-center">
									<img class="avatar-img" src="<?=base_url('assets/img/svg/undraw_feeling_happy.svg')?>" alt="">
								</div>
							</div>
							<div class="col-9 pt-3">
								Are you sure you want to logout?
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-danger btn-border fw-bold btn-md btn-round" type="button" data-dismiss="modal">No</button>
						<a class="btn btn-<?=@$btnColor?> btn-md btn-round" href="<?=base_url('admin/logout/')?>">Yes</a>
					</div>
				</div>
			</div>
		</div>

		<!-- Logout Modal 2-->
		<div class="modal fade animated bounceInDown" id="logoutModal2" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header <?=expColor(@$thColor)->fontColor?> bg-<?=@$btnColor?>">
						<h6 class="modal-title" id="exampleModalLabel"><span class="fas fa-exclamation-circle fa-lg"></span> Hang on!</h6>
						<button class="close" type="button" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row col-12">
							<div class="col-3">
								<div class="avatar avatar-lg text-center">
									<img class="avatar-img" src="<?=base_url('assets/img/svg/undraw_Done.svg')?>" alt="">
								</div>
							</div>
							<div class="col-9">
								How was your experience with the system? Could you please take a moment to rate it.
							</div>
							
						</div>
						
					</div>
					<div class="modal-footer">
						<button class="btn btn-danger btn-border btn-md btn-round" type="button" id="noRateBtn">Not this time</button>
						<a class="btn btn-<?=@$btnColor?> btn-md btn-round" href="<?=base_url('admin/my-profile/')?>">Rate now</a>
					</div>
				</div>
			</div>
		</div>
		

		
	</div>
	<!--   Core JS Files   -->
	<script src="<?=base_url()?>assets/js/core/jquery.3.2.1.min.js"></script>
	<script src="<?=base_url()?>assets/js/core/popper.min.js"></script>
	<script src="<?=base_url()?>assets/js/core/bootstrap.min.js"></script>
	
	<!-- <script src="<?=base_url()?>assets/vendor/jquery/jquery.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script> -->
	

	<!-- jQuery UI -->
	<script src="<?=base_url()?>assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="<?=base_url()?>assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>


	<!-- Chart JS -->
	<script src="<?=base_url()?>assets/js/plugin/chart.js/chart.min.js"></script>
	<script src="<?=base_url()?>assets/vendor/chart.js/Chart.min.js"></script>
	


	<!-- FilePond -->
	<script src="<?= base_url('assets/vendor/filepond/filepond.min.js') ?>"></script>


	<!-- jQuery Sparkline -->
	<script src="<?=base_url()?>assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="<?=base_url()?>assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<!-- <script src="<?=base_url()?>assets/js/plugin/datatables/datatables.min.js"></script> -->

	<!-- Bootstrap Notify -->
	<script src="<?=base_url()?>assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="<?=base_url()?>assets/js/plugin/jqvmap/jquery.vmap.min.js"></script>
	<script src="<?=base_url()?>assets/js/plugin/jqvmap/maps/jquery.vmap.world.js"></script>

	<!-- Sweet Alert -->
	<script src="<?=base_url()?>assets/js/plugin/sweetalert/sweetalert.min.js"></script>

	<!-- Atlantis JS -->
	<script src="<?=base_url()?>assets/js/atlantis.js?v=2.11"></script>

	<!-- Atlantis DEMO methods, don't include it in your project! -->
	<script src="<?=base_url()?>assets/js/setting-demo.js"></script>
	<script src="<?=base_url()?>assets/js/demo.js"></script>


	<!-- ADDITIONAL JS FILES -->
	<script src="<?=base_url('assets/js/jquery.dataTables.min.js')?>"></script>
	<script src="<?=base_url('assets/js/dataTables.bootstrap4.min.js')?>"></script>
	<script src="<?=base_url('assets/js/dataTables.responsive.min.js')?>"></script>
	<script src="<?=base_url('assets/js/dataTables.select.min.js')?>"></script>
	
	<script src="<?=base_url('assets/js/dataTables.buttons.min.js')?>"></script>
	<script src="<?=base_url('assets/js/jszip.min.js')?>"></script>
	<script src="<?=base_url('assets/js/pdfmake.min.js')?>"></script>
	<script src="<?=base_url('assets/js/vfs_fonts.js')?>"></script>
	<script src="<?=base_url('assets/js/buttons.html5.min.js')?>"></script>
	<script src="<?=base_url('assets/js/buttons.print.min.js')?>"></script>
	<script src="<?=base_url('assets/js/buttons.colVis.min.js')?>"></script>
	
	<!-- <script src="<?=base_url('assets/js/responsive.bootstrap4.min.js')?>"></script> -->
	<script src="<?=base_url('assets/js/dataTables.fixedColumns.min.js')?>"></script>
	<script src="<?=base_url('assets/js/dataTables.scroller.min.js')?>"></script>
	
	<script src="<?=base_url('assets/js/ed7ebf3abf.js')?>" crossorigin="anonymous"></script>
	<script src="<?=base_url();?>assets/js/bootstrap-datepicker.min.js"></script>
	
	<script src="<?=base_url('assets/js/lobibox-master/dist/js/lobibox.min.js')?>"></script>
	
	<script src="<?=base_url('assets/js/select2.js')?>"></script>
	<script src="<?=base_url('assets/js/plugin/jquery-validation/jquery.validate.min.js')?>"></script>
	<script src="<?=base_url('assets/js/plugin/jquery-validation/additional-methods.min.js')?>"></script>
	<script src="<?=base_url('assets/js/plugin/signature-pad/signature_pad.umd.min.js')?>"></script>
	<script src="<?=base_url('assets/js/admin.js?v=3.5')?>"></script>
	<?php if (!empty($js_file)): ?>
    <script src="<?= base_url($js_file) ?>"></script>
	<?php endif; ?>

		
	
</body>
</html>