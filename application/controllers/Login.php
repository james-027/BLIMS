<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
    	parent::__construct();
    	$this->load->model('main_model', 'main');
		$this->load->library('custom_lib');
	}

	public function _require_login($empID=null){

		// $loginLocal = $this->custom_lib->_check_local_access('during-login');
		$loginLocal = FALSE;
		if(!$loginLocal){

			$login = $this->session->userdata(APP_SESS_NAME);
			
			if(isset($login)){
				$user_type = decode($login['userTypeID']);
				//if(TRUE){
				if(decode($login['userReset']) != 1){
					if($user_type){
						//redirect('admin');
						return $login;
					}else{
						$this->session->unset_userdata(APP_SESS_NAME);
						$this->session->sess_destroy();
					}
				}else{
					$this->session->unset_userdata(APP_SESS_NAME);
					$this->session->sess_destroy();
					redirect('login/change-password/' . $login['userID'].'/'.$empID);
				}
			}else{
				$this->session->unset_userdata(APP_SESS_NAME);
				//$this->session->sess_destroy();
			}
		}
		
		

	}

	public function index($empID=null){
		$info = $this->_require_login($empID);
		
		if(!empty($info)){
			redirect('admin');
		}
		$data['empID'] = $empID;
		$this->load->view('login/login_content', $data);		

	}

	public function login_verification(){
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			$email = clean_data($this->input->post('email'));
			$empNo = clean_data($this->input->post('email'));
			$password = clean_data($this->input->post('password'));
			$empID = clean_data($this->input->post('empID'));

		

			$where = array('userEmail' => $email);
			$redirect = '';
			$join = array(
						'userkey b' => 'a.userID = b.userID and b.statusID = 1 and (a.userEmail = "'.$email.'" or a.employeeNo = "'.$empNo.'")',
						'key c' => 'b.keyID = c.keyID',
						'businesscenter d' => 'c.bcID = d.bcID',
						'businessunit e' => 'c.buID = e.buID',
						'usertype g' => 'a.userTypeID = g.userTypeID',
						'themes h' => 'a.themeID = h.themeID',
						'usertheme f' => 'a.userID = f.userID'
					);
			$check_login = $this->main->check_join('users a', $join, TRUE, 'b.current DESC, b.keyID', FALSE, FALSE);
			$data['empID'] = $empID;
			





			if($check_login['result'] == TRUE){

				

				if($check_login['info']->lastLoginTS != '' && $check_login['info']->isLogout != 1){
					$msg = "You've been automatically logged out because you did not log out last time. Please login again.";
					//$this->session->set_flashdata('message', $msg);
					redirect('admin/logout/'.encode($msg).'/'.$check_login['info']->userID.'/'.$empID);
				}

	

				
				
				if(decode($check_login['info']->password) == $password){
					$session = array(
						'userID'			=>	encode($check_login['info']->userID),
						'userEmail'			=>	encode($check_login['info']->userEmail),
						'userTypeID'		=>	encode($check_login['info']->userTypeID),
						'userTypeName'		=>	$check_login['info']->userTypeName,
						'userTypeLevel'		=>	encode($check_login['info']->userTypeLevel),
						'userReset'			=>	encode($check_login['info']->userReset),
						'userTitle'			=>	$check_login['info']->userTitle,
						'userFullName'		=>	$check_login['info']->userFirstName.' '.$check_login['info']->userLastName,
						'userFirstName'		=>	$check_login['info']->userFirstName,
						'userLastName'		=>	$check_login['info']->userLastName,
						'current_keyID'		=>	encode($check_login['info']->keyID),
						'current_userKeyID'	=>	encode($check_login['info']->userKeyID),
						'buLDesc'			=>	$check_login['info']->buLDesc,
						'keyCode'			=>	$check_login['info']->keyCode,
						'bcName'			=>	$check_login['info']->bcName,
						'bcCode'			=>	$check_login['info']->bcCode,
						'backgroundColor'	=>	$check_login['info']->backgroundColor,
						'sideBarColor'		=>	$check_login['info']->sideBarColor,
						'topBarColor'		=>	$check_login['info']->topBarColor,
						'logoHeaderColor'	=>	$check_login['info']->logoHeaderColor,
						'themeID'			=>	$check_login['info']->themeID,
						'menuColor'			=>	$check_login['info']->menuColor,
						'thColor'			=>	$check_login['info']->thColor,
						'btnColor'			=>	$check_login['info']->btnColor,
						'tableColor'		=>	$check_login['info']->tableColor
					);

					if($check_login['info']->current != 1){
						$filter = array('userKeyID' => $check_login['info']->userKeyID);
						$set = array('current' => 1);
						$this->main->update_data('userkey', $set, $filter );	
					}

					$filter = array('userID' => $check_login['info']->userID);
					$set = array('lastLoginTS' => date_now(), 'isLogout' => 0);
					$this->main->update_data('users', $set, $filter );
					
					if($check_login['info']->statusID == 1){
						$this->session->set_userdata(APP_SESS_NAME, $session);
						if($redirect == ''){

							//INSERT USER LOGS
							$user_logs = array(
								'userID'	=>	$check_login['info']->userID,
								'userFullName' =>	$check_login['info']->userFirstName.' '.$check_login['info']->userLastName,
								'page'	=>	'Login/login_verification',
								'logTS'	=>	date_now(),
								'logDetail'	=>	'Successfully login'
							);
			                $this->main->user_logs($user_logs);

							if($check_login['info']->userTypeID == 1){
								redirect('admin/index/'.$empID);
							}elseif($check_login['info']->userTypeID >= 2){
								redirect('admin/index/'.$empID);
							}else{
								$this->session->unset_userdata(APP_SESS_NAME);
								$this->session->sess_destroy();
								redirect('login');
							}
						}else{
							//redirect($redirect);
						}
					}elseif($check_login['info']->statusID == 2){
						$msg = '<div class="alert alert-danger">Your account has been deactivated contact your administrator.</div>';
						$this->session->set_flashdata('message', $msg);
						$this->load->view('login/login_content', $data);
					}else{
						$msg = '<div class="alert alert-danger">Error please contact your administrator.</div>';
						$this->session->set_flashdata('message', $msg);
						$this->load->view('login/login_content', $data);
					}
				}else{
					$msg = '<div class="alert alert-danger">Invalid email/employee no. or password.</div>';
					$this->session->set_flashdata('message', $msg);
					$this->load->view('login/login_content', $data);
				}
			
			}else{
				$msg = '<div class="alert alert-danger">Invalid email/employee no. and password.</div>';
				$this->session->set_flashdata('message', $msg);
				$this->load->view('login/login_content', $data);
			}
		}else{
			redirect('');
		}
	}


	public function change_password($id = null, $empID=null){
		$userID = decode($id);
		if(!empty($userID)){
			$check_id = $this->main->check_data('users', array('userID' => $userID, 'userReset' => 1));

			if($check_id == TRUE){
				$data['userID'] = encode($userID);
				$data['empID'] = encode($empID);
				
				$this->load->view('login/change_password_content', $data);
			}else{
				redirect(base_url());
			}
		}else{
			redirect(base_url());
		}
	}

	public function reset_process(){

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$userID = clean_data(decode($this->input->post('id')));
			$empID = clean_data($this->input->post('empID'));
			$password = clean_data($this->input->post('new-password'));
			$cpassword = clean_data($this->input->post('confirm-password'));

			$data['empID'] = $empID;

			$this->form_validation->set_rules('new-password', 'New Password', 'trim|required|min_length[7]');

			if ($this->form_validation->run() == false) {
				$msg = '<div class="alert alert-danger">'.validation_errors().'</div>';
				$this->session->set_flashdata('message', $msg);
				redirect('login/change_password/'. $this->input->post('id'));
			}

			$check_id = $this->main->check_data('users', array('userID' => $userID, 'userReset' => 1));

			if($check_id == TRUE){
				if($password == $cpassword){

					$set = array(
						'password' => encode($password),
						'userReset' => 0,
						'isLogout'	=> 1
					);

					$where = array('userID' => $userID);
					$result = $this->main->update_data('users', $set, $where);

					if($result == TRUE){
						$msg = '<div class="alert alert-success">Login now with your new password.</div>';
						$this->session->set_flashdata('message', $msg);
						$this->load->view('login/login_content', $data);
					}else{
						$msg = '<div class="alert alert-danger">Error please try again.</div>';
						$this->session->set_flashdata('message', $msg);
						redirect('login');
					}
				}else{
					$msg = '<div class="alert alert-danger">Password not match. Please try again!</div>';
					$this->session->set_flashdata('message', $msg);
					redirect('login/change-password/' . encode($userID).'/'.$empID);
				}
			}else{
				redirect();	
			}
		}else{
			redirect(base_url());
		}
	}

	public function change_access(){
		
		$info = $this->_require_login();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$userKeyID = clean_data(decode($this->input->post('userKeyID')));
			$current_method = clean_data(decode($this->input->post('current_method')));
			$current_controller = clean_data(decode($this->input->post('current_controller')));
			$current_param = clean_data($this->input->post('current_param'));


			$join = array(
						'key b' => 'a.keyID = b.keyID',
						'businesscenter c' => 'b.bcID = c.bcID',
						'businessunit d' => 'b.buID = d.buID'
					);
			$check_id = $this->main->get_join('userkey a', $join, true, FALSE, FALSE, FALSE, array('a.userKeyID' => $userKeyID, 'a.statusID' => 1) );

			if(!empty($check_id)){

				$set = array('current' => 0);
				$this->main->update_data('userkey', $set, array('userID' => decode($info['userID']) ));

				$set = array('current' => 1);
				$this->main->update_data('userkey', $set, array('userkeyID' => $userKeyID ));

				$session = array(
					'userID'			=>	$info['userID'],
					'userEmail'				=>	$info['userEmail'],
					'userTypeID'		=>	$info['userTypeID'],
					'userTypeName'		=>	$info['userTypeName'],
					'userTypeLevel'		=>	$info['userTypeLevel'],
					'userReset'			=>	$info['userReset'],
					'userTitle'			=>	$info['userTitle'],
					'userFullName'		=>	$info['userFullName'],
					'userFirstName'		=>	$info['userFirstName'],
					'userLastName'		=>	$info['userLastName'],
					'current_keyID'		=>	encode($check_id->keyID),
					'current_userKeyID'	=>	encode($check_id->userKeyID),
					'buLDesc'			=>	$check_id->buLDesc,
					'keyCode'			=>	$check_id->keyCode,
					'bcName'			=>	$check_id->bcName,
					'bcCode'			=>	$check_id->bcCode,
					'backgroundColor'	=>	$info['backgroundColor'],
					'sideBarColor'		=>	$info['sideBarColor'],
					'topBarColor'		=>	$info['topBarColor'],
					'logoHeaderColor'	=>	$info['logoHeaderColor'],
					'themeID'			=>	$info['themeID'],
					'menuColor'			=>	$info['menuColor'],
					'thColor'			=>	$info['thColor'],
					'btnColor'			=>	$info['btnColor'],
					'tableColor'		=>	$info['tableColor'],
				);

				$this->session->set_userdata(APP_SESS_NAME, $session);
				$msg = '<div class="alert alert-success">Your system access has been changed.</div>';
				
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
			}else{
				$msg = '<div class="alert alert-danger">The selected access was inactive for your account! Contact System administrator.</div>';
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
			}
		}else{
			redirect(base_url());
		}
	}


	public function edit_user_profile(){
		
		$info = $this->_require_login();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$current_method = clean_data(decode($this->input->post('current_method')));
			$current_controller = clean_data(decode($this->input->post('current_controller')));
			$current_param = clean_data($this->input->post('current_param'));
			$firstName = clean_data(strtoupper($this->input->post('user-fname')));
			$lastName = clean_data(strtoupper($this->input->post('user-lname')));
			$userTitle = clean_data(strtoupper($this->input->post('user-title')));
			$themeID = clean_data(decode($this->input->post('themeID')));
			

			

			if($firstName && $lastName){

				$set = array(
					'userTitle' 	=>	$userTitle,
					'userFirstName' =>	$firstName,
					'userLastName'	=>	$lastName
				);
				$this->main->update_data('users', $set, array('userID' => decode($info['userID']) ));

				$session = array(
					'userID'			=>	$info['userID'],
					'userEmail'			=>	$info['userEmail'],
					'userTypeID'		=>	$info['userTypeID'],
					'userTypeName'		=>	$info['userTypeName'],
					'userTypeLevel'		=>	$info['userTypeLevel'],
					'userReset'			=>	$info['userReset'],
					'userTitle'			=>	$userTitle,
					'userFullName'		=>	$firstName.' '.$lastName,
					'userFirstName'		=>	$firstName,
					'userLastName'		=>	$lastName,
					'current_keyID'		=>	$info['current_keyID'],
					'current_userKeyID'	=>	$info['current_userKeyID'],
					'buLDesc'			=>	$info['buLDesc'],
					'keyCode'			=>	$info['keyCode'],
					'bcName'			=>	$info['bcName'],
					'bcCode'			=>	$info['bcCode'],
					'backgroundColor'	=>	$info['backgroundColor'],
					'sideBarColor'		=>	$info['sideBarColor'],
					'topBarColor'		=>	$info['topBarColor'],
					'logoHeaderColor'	=>	$info['logoHeaderColor'],
					'themeID'			=>	$info['themeID'],
					'menuColor'			=>	$info['menuColor'],
					'thColor'			=>	$info['thColor'],
					'btnColor'			=>	$info['btnColor'],
					'tableColor'		=>	$info['tableColor']
				);

				$this->session->set_userdata(APP_SESS_NAME, $session);
				$msg = '<div class="alert alert-success">Your profile has been updated.</div>';
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
				
			}else{
				$msg = '<div class="alert alert-danger">The selected access was inactive for your account! Contact System administrator.</div>';
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
			}
		}else{
			redirect(base_url());
		}
	}

	public function edit_user_password(){
		
		$info = $this->_require_login();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$current_method = clean_data(decode($this->input->post('current_method')));
			$current_controller = clean_data(decode($this->input->post('current_controller')));
			$current_param = clean_data($this->input->post('current_param'));
			$current_pass = clean_data($this->input->post('current-pass'));
			$new_pass = clean_data($this->input->post('new-pass'));
			$confirm_pass = clean_data($this->input->post('confirm-pass'));
			

			$get_user_record = $this->main->check_data('users', array('userID' =>  decode($info['userID'])), TRUE);
			if($get_user_record['result']){
				$password = $get_user_record['info']->password;
				if($current_pass != decode($password)){
					$msg = '<div class="alert alert-danger">Error, Current password does not match with the record</div>';
					$this->session->set_flashdata('message', $msg);
					if($current_param){
						redirect($current_controller.'/'.$current_method.'/'.$current_param);
					} else {
						redirect($current_controller.'/'.$current_method);
					}
				}
			}

			

			if($current_pass && $new_pass && $confirm_pass){
				
				$set = array(
					'password' 	=>	encode($confirm_pass)
				);
				$result = $this->main->update_data('users', $set, array('userID' => decode($info['userID']) ));

				$msg = '';
				if($result){
					$msg = '<div class="alert alert-success">Your password has been updated.</div>';
				}
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
				
			}else{
				$msg = '<div class="alert alert-danger">'.ERROR_REQ_FIELDS.'</div>';
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
			}
		}else{
			redirect(base_url());
		}
	}







	public function save_esign()
	{
		$info = $this->custom_lib->_require_login();
		$userID = decode($info['userID']);

		// Only allow POST requests
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			echo json_encode(['success' => false, 'message' => 'Invalid request']);
			return;
		}

		$esign = $this->input->post('userEsign');
		if (empty($esign)) {
			$this->session->set_flashdata('error', 'Please draw your signature before saving.');
			redirect('admin/my_profile');
		}

		$get_user_record = $this->main->check_data('users', ['userID' => $userID], TRUE);
		$ctr = 1;
		if ($get_user_record['result']) {
			$esignName = $get_user_record['info']->userEsign;
			if ($esignName) {
				$prevFile = decode($esignName);
				$slice = explode(".", basename($prevFile));
				$arr = explode("-", $slice[0]);
				$current_ctr = isset($arr[3]) ? intval($arr[3]) : 0;
				$ctr = $current_ctr + 1;
			}
		}

		$real_file_name = 'ESIGN-IMG-' . $userID . '-' . $ctr . '.png';

		$path = 'uploads/esign/';
		if (!is_dir($path)) {
			if (!mkdir($path, 0777, true)) {
				$this->session->set_flashdata('error', 'Failed to create directory.');
				redirect('admin/my_profile');
			}
		}

		$encrypted_file_name = encode($real_file_name);

		$encrypted_file_name = str_replace(
			['/', '\\', ':', '*', '?', '"', '<', '>', '|', '+', '='],
			'_',
			$encrypted_file_name
		) . '.png';

		$data = explode(',', $esign);
		if (!isset($data[1]) || empty($data[1])) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Invalid Signature Data.</div>');
			redirect('admin/my_profile');
		}

		$decodedData = base64_decode($data[1]);
		if ($decodedData === false) {
			$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to decode signature.</div>');
			
			redirect('admin/my_profile');
		}

		$fullPath = FCPATH . $path . $encrypted_file_name;
		if (file_put_contents($fullPath, $decodedData) === false) {
						$this->session->set_flashdata('message', '<div class="alert alert-danger">Failed to save Signature File.</div>');

			redirect('admin/my_profile');
		}

		$update_data = ['userEsign' => $encrypted_file_name];
		$result = $this->main->update_data('users', $update_data, ['userID' => $userID]);

		$msg = '';
			if($result){
				$msg = '<div class="alert alert-success">Your E-Signature has been updated.</div>';
			}

		$this->session->set_flashdata('message', $msg);
		redirect('admin/my_profile');
	}



	public function add_user_rating(){
		
		$info = $this->_require_login();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			
			
			$current_method = clean_data(decode($this->input->post('current_method')));
			$current_controller = clean_data(decode($this->input->post('current_controller')));
			$current_param = clean_data($this->input->post('current_param'));
			
			$rating = clean_data(decode($this->input->post('rating')));
			$userFeedback = clean_data($this->input->post('user-feedback'));
			$userFeedback = !empty($userFeedback) ? join(', ', $userFeedback) : '';
			//$userFeedback2 = clean_data(decode($this->input->post('user-feedback2')));
			//$userFeedback3 = clean_data(decode($this->input->post('user-feedback3')));
			//$userFeedback4 = clean_data(decode($this->input->post('user-feedback4')));
			//$userFeedback5 = clean_data(decode($this->input->post('user-feedback5')));
			$userComment = clean_data($this->input->post('user-comment'));


			if($userComment && $userFeedback && $rating){
				
				$set = array(
					'rating' 		=>	strtoupper($rating),
					'userFeedback' 	=>	strtoupper($userFeedback),
					'userComment' 	=>	strtoupper($userComment),
					'userID' 		=>	decode($info['userID']),
					'statusID'		=>	1
				);

				
				
				$result = $this->main->insert_data('userrating', $set, TRUE);

				$msg = '';
				if($result['result']){
					$msg = '<div class="alert alert-success">Your feedback has been added.</div>';
				}
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
				
			}else{
				$msg = '<div class="alert alert-danger">'.ERROR_REQ_FIELDS.'</div>';
				$this->session->set_flashdata('message', $msg);
				if($current_param){
					redirect($current_controller.'/'.$current_method.'/'.$current_param);
				} else {
					redirect($current_controller.'/'.$current_method);
				}
			}
		}else{
			redirect(base_url());
		}
	}

	public function update_profile_pic(){
		
		$info = $this->_require_login();
		
		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$userID = decode($info['userID']);
			$ctr = 1;
			$get_user_record = $this->main->check_data('users', array('userID' =>  $userID), TRUE);
			if($get_user_record['result']){
				$profilePicName = $get_user_record['info']->profilePicName;
				$profPicName_slice = explode(".", $profilePicName);
				$profPicName_arr = explode("-", $profPicName_slice[0]);
				$current_ctr = @$profPicName_arr[3];
				if($current_ctr){
					
					$ctr = $current_ctr *1+1;
				}
			}
			

			$new_file_name = 'PROFPIC-IMG-'.$userID.'-'.$ctr;
			$path = 'uploads/profilepic/';
			$target_dir = 'PROFPIC-IMG-'.$userID.'/';
			if (!is_dir($path)) {
			    mkdir($path, 0777, TRUE);
			}
			/*$filter = array('userID' => $userID);
	        $check_old_file = $this->main->check_data('users', $filter, TRUE, 'profilePicName');
	        $old_file_name = '';
	        if($check_old_file['result']){
	            $old_file_name = $check_old_file['info']->profilePicName;
	            if($old_file_name){
					unlink(FCPATH.$path.$old_file_name);
					unlink(FCPATH.'/uploads/thumbnail/'.$old_file_name);
	            }
	        }*/

			$config['upload_path'] = $path;
			$config['file_name'] = $new_file_name;
			$config['allowed_types'] = 'jpeg|jpg|png';
			$config['max_size'] = 5000;
	        $config['max_width'] = 5000;
	        $config['max_height'] = 10000;
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = true;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('prof-pic-file')) {
				$error = array('error' => $this->upload->display_errors());
			} else {

				$data = array('upload_data' => $this->upload->data());
			}

			if(empty($error)){


				if (!empty($data['upload_data']['file_name'])) {

					$import_img_file = $data['upload_data']['file_name'];
				} else {
					$import_img_file = 0;
					
		            redirect(base_url());
				}
				$inputFileName = $path . $import_img_file;

				$this->resizeImage($import_img_file, $path, $target_dir);

				$update_data = array(
					 'profilePicName'		=>     $import_img_file
		        );
		        $filter = array ('userID' => $userID);
		        $result = $this->main->update_data('users', $update_data, $filter);
		        
				unlink(FCPATH.$inputFileName);

		        echo json_encode(array(
				  'success' => true,
				  'successMsg' => 'Uploaded successfully'//$error['error']
				));
	
			} else {
				//$msg = '<div class="alert alert-danger">'.$this->upload->display_errors().'</div>';
				//$this->session->set_flashdata('message', $msg);
				//redirect(base_url('admin/dashboard'));
				$update_data = array(
					 'profilePicName'		=>     NULL
		        );
		        $filter = array ('userID' => $userID);
		        $result = $this->main->update_data('users', $update_data, $filter);
				echo json_encode(array(
				  'success' => false,
				  'successMsg' => $this->upload->display_errors()//$error['error']
				));
			}
		}else{
			echo json_encode(array(
			  'success' => false,
			  'successMsg' => 'Please try again'//$error['error']
			));
		}
	}

	public function resizeImage($filename, $source_path, $target_dir = false)
	{	
		

		$source_path = FCPATH . $source_path . $filename;
		$target_path = FCPATH . '/uploads/thumbnail/';
		if($target_dir){
			$target_path = $target_path.$target_dir;
		}
		if (!is_dir($target_path)) {
			mkdir($target_path, 0777, TRUE);
		}
		$config_manip = array(
			'image_library' => 'gd2',
			'source_image' => $source_path,
			'new_image' => $target_path,
			'maintain_ratio' => TRUE,
			'create_thumb' => TRUE,
			'thumb_marker' => '',
			'width' => 250,
			'height' => 250,
			'overwrite' => true
		);


		$this->load->library('image_lib', $config_manip);
		if (!$this->image_lib->resize()) {
			echo json_encode(array(
				'success' => false,
				'successMsg' => $this->image_lib->display_errors()
			));
		}


		$this->image_lib->clear();
	}
}
