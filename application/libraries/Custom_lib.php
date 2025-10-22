<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_lib {

    public function _get_access ($filter, $column) {
    	$ci =& get_instance();

		$check_bc_access = $ci->main->check_data('key', $filter, true, false, false);
		
		if($check_bc_access['result']){
			if($column == 'bcID'){
				return $check_bc_access['info']->bcID;
			} else if ($column == 'keyID'){
				return $check_bc_access['info']->keyID;
			} else if ($column == 'keyCode'){
				return $check_bc_access['info']->keyCode;
			} else if ($column == 'keyCode2'){
				return $check_bc_access['info']->keyCode2;
			}
		} else {
			return NULL;
		}
	}

	public function _get_data_access ($filter, $column=null) {
    	$ci =& get_instance();
		$filter = 
		$check_bc_access = $ci->main->get_data('userbc', $filter, false, false, false);
		
		if(!empty($check_bc_access)){
			foreach ($check_bc_access as $r) {
				$bc_arr[] = $r->bcID;
			}
			return $bc_arr;
		} else {
			return NULL;
		}
	}

	public function _get_sloc_access($filter){
		$ci =& get_instance();

		$join = array(
					'storagelocation b' => 'a.slocID = b.slocID and a.statusID = 1',
					'cgdtl c' => 'b.slocID = c.slocID',
					'vet d' => 'c.vetID = d.vetID',
					);
		$check_module_access = $ci->main->get_join('usersloc a', $join, false, false, false, false, $filter, false, false, false, false, false);

		
		if(!empty($check_module_access)){
			return $check_module_access;
		} else {
			return false;
		}
	}


	public function _get_available_access($filter){
		$ci =& get_instance();

		$join = array(
					'key b' => 'a.keyID = b.keyID and a.statusID = 1',
					'businesscenter c' => 'b.bcID = c.bcID',
					'businessunit d' => 'b.buID = d.buID'
					);
		$check_available_access = $ci->main->get_join('userkey a', $join, false, false, false, false, $filter, false, false, false, false, false);
		
		if(!empty($check_available_access)){
			return $check_available_access;
		} else {
			return false;
		}
	}

	// Additional function from controller to reduce redundant code
	public function _require_login($empID=null)
	{
		// $loginLocal = $this->_check_local_access();
		$loginLocal = FALSE;
		if(!$loginLocal){
			$CI =& get_instance();
			$login = $CI->session->userdata(APP_SESS_NAME);
			
			if(isset($login)){
				$user_type = decode($login['userTypeID']);
				if(decode($login['userReset']) != 1){
					if($user_type){
						return $login;
					}else{
						$CI->session->unset_userdata(APP_SESS_NAME);
						redirect('login');
					}
				}else{
					$msg = '<div class="alert alert-success">Atleast 7 characters in length.</div>';
					$CI->session->set_flashdata('message', $msg);
					redirect('login/change-password/' . $login['userID'].'/'.$empID);
					$CI->session->unset_userdata(APP_SESS_NAME);
				}
			}else{
				$CI->session->unset_userdata(APP_SESS_NAME);
				redirect('login');
			}
		}
	}

	public function _get_profile() 
	{
		$CI =& get_instance();
		$info = $this->_require_login();
		$themeID = 0;

		$path = 'uploads/thumbnail/';
		$path .= 'PROFPIC-IMG-'.decode($info['userID']).'/';
		$filter = array('userID' => decode($info['userID']));
		$check_user = $CI->main->get_data('users', $filter, true, 'profilePicName');
		$profile_img_link = base_url('assets/img/undraw_profile.svg');
		if ($check_user->profilePicName) {
			$profile_img_link = base_url($path.$check_user->profilePicName);
		}
		
		$filter = array('statusID'	=>	1);
		$check_themes = $CI->main->get_data('themes', $filter, false, false, 'themeName');
			
		$item = '';
		if(!empty($check_themes)){
			foreach ($check_themes as $r) {
				$selected = '';
				if($themeID == $r->themeID){
					$selected = 'selected';
				}
				$item .= '<option '.$selected.' value="' . encode($r->themeID) . '">' . $r->themeName . '</option>';
			}
			return array(
				'userID'	=>	$info['userID'],
				'firstName'	=>	$info['userFirstName'],
				'lastName'	=>	$info['userLastName'],
				'userTitle'	=>	$info['userTitle'],
				'themes'	=>	$item,
				'profile_img_link' => $profile_img_link
			);
		} else {
			return array(
				'userID'	=>	$info['userID'],
				'firstName'	=>	$info['userFirstName'],
				'lastName'	=>	$info['userLastName'],
				'userTitle'	=>	$info['userTitle'],
				'themes'	=>	'',
				'profile_img_link' => $profile_img_link
			);
		}
	}

	public function _get_notifications($notifTypeID = 1)
	{
		$CI =& get_instance();
		$info    = $this->_require_login();
		$profile = $this->_get_profile();
		//$menuColor = get_user_theme(array('a.userID' => decode($info['userID'])), true)->menuColor;
	    //$tableColor = get_user_theme(array('a.userID' => decode($info['userID'])), true)->tableColor;
	    //$thColor = get_user_theme(array('a.userID' => decode($info['userID'])), true)->thColor;
	    $btnColor = get_user_theme(array('a.userID' => decode($info['userID'])), true)->btnColor;
		

		$filter = array(
			'a.keyID' => decode($info['current_keyID'])
		);
		/* $key_access = $CI->custom_lib->_get_available_access( array('userID' => decode($info['userID'])) );
		if(!empty($key_access)){
			$key_field = 'a.keyID';
			$key_arr = array();
			foreach ($key_access as $r) {
				$key_arr[] = $r->keyID;
			}
		} */
		$key_arr = false;
		$key_field = false;
		$join = array(
					'users c' =>	'a.createdBy = c.userID',
					'usernotif d' =>	'a.notifID = d.notifID and d.userID = '.decode($info['userID']).' and d.statusID != 15',
				);
		$order_by = 'a.tsCreated DESC';
		$limit = 40;
		$recFound = $CI->main->get_join_datatables('notification a', $join, false, $order_by, false, '*, a.createdBy', $filter, $key_field, $key_arr, false, $limit);

		$item = '';
		$counter = 0;
		$line = 0;
		if(!empty($recFound)){
			foreach ($recFound->result() as $r) {
				$date1 = date_create($r->tsCreated);
				$date2 = date_create(date_now());
				if(datediff('y', $date1, $date2) >= 1){
					$diff = intval(datediff('y', $date1, $date2)).' year(s) ago';
				}elseif(datediff('m', $date1, $date2) >= 1){
					$diff = intval(datediff('m', $date1, $date2)).' month(s) ago';
				}elseif(datediff('d', $date1, $date2) >= 1){
					$diff = intval(datediff('d', $date1, $date2)).' day(s) ago';
				}elseif(datediff('h', $date1, $date2) >= 1){
					$diff = intval(datediff('h', $date1, $date2)).' hour(s) ago';
				}elseif(datediff('i', $date1, $date2) >= 1){
					$diff = intval(datediff('i', $date1, $date2)).' minutes(s) ago';
				}elseif(datediff('s', $date1, $date2) >= 1){
					$diff = intval(datediff('s', $date1, $date2)).' second(s) ago';
				}
				
				if($r->statusID == 1){
					$counter++;
				}
				if($r->statusID == 1 || $r->statusID == 13){
					$addtl_class = 'border-left-'.$btnColor.' bg-gray-200';
					$addtl_class2 = 'font-weight-bold';
				} else {
					$addtl_class2 = '';
					$addtl_class = '';
				}
				if($r->notifTypeID == 1){
					$icon_class = 'fas fa-fw fa-industry';
				} elseif($r->notifTypeID == 2){
					$icon_class = 'fas fa-fw fa fa-folder-open-o';
				} elseif($r->notifTypeID == 3){
					$icon_class = 'fas fa-drumstick-bite';
				}
	
				if($r->createdBy == 0){
					$creator = $r->mobileFullName;
				} else {
					$creator = $r->userFirstName.' '.$r->userLastName;
				}
	
				/* $item .= '
				<a data-name="'.$r->notifName.'" data-id="'.$r->notifID.'" data-typeid="'.$r->notifTypeID.'" class="notif-item dropdown-item d-flex align-items-center '.$addtl_class.'" href="#">
					<div class="mr-3">
						<div class="icon-circle>
							<i class="'.$icon_class.' text-white"></i>
						</div>
					</div>
					
					<div>
						<div class="small text-gray-500">'.$diff.' | '.$creator.'</div>
						<span class="'.$addtl_class2.'">'.$r->notifName.'</span><br>
						<span class="small '.$addtl_class2.'">'.$r->notif.'</span>
					</div>
				</a>'; */
				
				$item .= '
				<a href="#" class="'.$addtl_class.' notif-item" data-name="'.$r->notifName.'" data-id="'.$r->notifID.'" data-typeid="'.$r->notifTypeID.'" data-ref-id="'.encode($r->refID).'" data-transtype="'.encode(1).'">
					<div class="notif-icon notif-'.@$btnColor.'"> <i class="'.$icon_class.'"></i> </div>
					<div class="notif-content">
						<span class="subject">'.$r->notifName.'</span>
						<span class="block '.$addtl_class2.'">
							'.$r->notif.'
						</span>
						<span class="time">'.$diff.'</span> 
					</div>
				</a>';
				$line++;
			}
		}
		if($line > 0){
			//$item .= '<a class="notif-item dropdown-item text-center small text-gray-900" href="#">Show All</a>';
		} else {
			//$item .= '<a class="dropdown-item text-center small text-gray-500" href="#"><img class="dropdown-list-image rounded-circle" src="'.base_url().'assets/img/undraw_rocket.svg"> Hurray, Nap time....</a>';
			$item = '
			<a href="#" class="notif-item">

				<div class="notif-content">
					<span class="subject"> <font class="text-gray-500"> <i class="fas fa-check-circle"></i> Hurray, No notifications....</font></span>
					
				</div>
			</a>';
		}
		/**/
		$data = array(
			'item' => $item,
			'counter' => $counter
		);
		$data = (object) $data;
		return $data;
	}

    public function module_access($alias = ''){
		$CI =& get_instance();
		return get_module_access(array('alias' => $alias, 'a.userID' => decode($CI->session->userdata[APP_SESS_NAME]['userID']) ), true);
	}

	public function _check_access_role($role='', $alias=''){
		$module_access = $this->module_access($alias);
		switch ($role) {
			case 'edit':
				if(!$module_access->edit ){
					$data['success'] = 0;
					$data['msg'] = 'Opps, Access Denied.';
					echo json_encode($data);
					exit;
				}
				break;
			case 'add':
				if(!$module_access->add ){
					$data['success'] = 0;
					$data['msg'] = 'Opps, Access Denied.';
					echo json_encode($data);
					exit;
				}
				break;
			case 'act':
				if(!$module_access->act ){
					$data['success'] = 0;
					$data['msg'] = 'Opps, Access Denied.';
					echo json_encode($data);
					exit;
				}
				break;
			
			default:
				# code...
				break;
		}
	}


	function _check_local_access()
	{
		$CI =& get_instance();
		$config = $CI->main->get_data('configs_tbl a', ['config_id' => 1, 'config_is_centralize' => 'NO']);

		if ($config) {
			
			$login = $CI->session->userdata(APP_SESS_NAME);

			if (isset($login)) {
				$CI->session->unset_userdata(APP_SESS_NAME);
			}

			$redirect = '';
			$join = array(
						'userkey b'        => 'a.userID = b.userID and b.statusID = 1 and (a.userID = 2)',
						'key c'            => 'b.keyID = c.keyID',
						'businesscenter d' => 'c.bcID = d.bcID',
						'businessunit e'   => 'c.buID = e.buID',
						'usertype g'       => 'a.userTypeID = g.userTypeID',
						'themes h'         => 'a.themeID = h.themeID',
						'usertheme f'      => 'a.userID = f.userID'
					);
			$check_login   = $CI->main->check_join('users a', $join, TRUE, 'b.current DESC, b.keyID', FALSE, FALSE);
			$empID         = '';
			$data['empID'] = $empID;
			
			if($check_login['result'] == TRUE){
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
					$set    = array('current' => 1);
					$CI->main->update_data('userkey', $set, $filter );	
				}

				$filter = array('userID' => $check_login['info']->userID);
				$set    = array('lastLoginTS' => date_now(), 'isLogout' => 0);
				$CI->main->update_data('users', $set, $filter );
				
				if($check_login['info']->statusID == 1){
					$login = $CI->session->set_userdata(APP_SESS_NAME, $session);
					if($redirect == ''){
						//INSERT USER LOGS
						$user_logs = array(
							'userID'       => $check_login['info']->userID,
							'userFullName' => $check_login['info']->userFirstName.' '.$check_login['info']->userLastName,
							'page'         => 'Login/login_verification',
							'logTS'        => date_now(),
							'logDetail'    => 'Successfully login'
						);
						$CI->main->user_logs($user_logs);

						if($check_login['info']->userTypeID >= 1){
							return $login;
							// redirect('admin/index/'.$empID);
						}elseif($check_login['info']->userTypeID >= 2){
							// redirect('admin/index/'.$empID);
						}else{
							$CI->session->unset_userdata(APP_SESS_NAME);
							$CI->session->sess_destroy();
							redirect('login');
						}
					}else{
						//redirect($redirect);
					}
				}elseif($check_login['info']->statusID == 2){
					$msg = '<div class="alert alert-danger">Your account has been deactivated contact your administrator.</div>';
					$CI->session->set_flashdata('message', $msg);
					$CI->load->view('login/login_content', $data);
				}else{
					$msg = '<div class="alert alert-danger">Error please contact your administrator.</div>';
					$CI->session->set_flashdata('message', $msg);
					$CI->load->view('login/login_content', $data);
				}
			}else{
				$msg = '<div class="alert alert-danger">Error please contact your administrator.</div>';
				$CI->session->set_flashdata('message', $msg);
				$CI->load->view('login/login_content', $data);
			}
			return;	
		} else {
			
			// $data['empID'] = '';
			// $CI->session->unset_userdata(APP_SESS_NAME);
			// $msg = '<div class="alert alert-danger">Config Error! please contact POS Support personnel.</div>';
			// $CI->session->set_flashdata('message', $msg);
			// $CI->load->view('login/login_content', $data);
			
		}
			
	}
}



?>
