<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incentives extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'incentives_tbl';
		$this->alias = 'incentives';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
	}


    /*  
	module: Incentive Controller
	desc: CRUD of incentive
	date created: 2023-03-22
	created by: Aljune
	Change Management #1
		Date: 2020-06-03
		Description: Continuation of CRUD (Add, Edit, Deactivation, & Activation)
		Modified By: Aljune
	*/

	public function index(){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$data['js_file'] = 'assets/js/dynamic-generic.js?v=1.0';
		$data['profile'] = $this->custom_lib->_get_profile();
		$data['menuColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->menuColor;
	    $data['tableColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->tableColor;
	    $data['thColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->thColor;
	    $data['btnColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->btnColor;
	    
	    $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;

	    $keyID = decode($info['current_keyID']);
	    $userID = decode($info['userID']);
		
		$data['available_access'] = $this->custom_lib->_get_available_access( array('userID' => decode($info['userID'])) );
		
		$module_access = $this->custom_lib->module_access($alias);
		$data['new_button'] = '<div class="row col-lg-12">';

		$btn_class = 'btn btn-icon btn-sm btn-round btn-'.$data['btnColor'].' mr-2 mb-2';
		if($module_access->add){
			
			$data['new_button'] .= '
			
				<button type="button" data-url="'.$this->controller.'/modal" class="add-form '.$btn_class.'"><span class="fas fa-plus"></span></button>';
		}

        $data['new_button'] .= '<button type="button" class="refresh-dt '.$btn_class.'"><span class="fa fa-refresh"></span></button>';

		if($module_access->dlod){
			$data['new_button'] .= '<button type="button" class="print-dt '.$btn_class.'"><span class="fas fa-file-excel"></span></button>';
			
		}
		$data['new_button'] .= '</div>';
		
		
		if(!$module_access->view){redirect('admin');}
		

		$data['title'] = 'Incentive';
		$data['menu_title'] = '';
		$data['parent_title'] = 'Transactional';
		$data['controller']   = $this->controller;


		$data['userID'] = decode($info['userID']);
		$data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);

		$data['content'] = $this->load->view($this->controller.'/incentives_content', $data , TRUE);
		
		$this->load->view('admin/templates', $data);
	}

	public function data_grid(){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$keyID = decode($info['current_keyID']);
		$userID = decode($info['userID']);
		
        $module_access = $this->custom_lib->module_access($alias);
		
		$bc_access = $this->custom_lib->_get_data_access( array('userID' => $userID, 'statusID' => 1));
		$where_in_field = FALSE;
		$where_in = FALSE;
		

		$draw = intval($this->input->get("draw")); 
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$data = array();
		$join = array(
					'users b' => array('a.incentive_added_by = b.userID' => 'INNER'),
					'status_tbl c' => array('a.incentive_status = c.status_id' => 'INNER'),
                    'users d' => array('a.incentive_modified_by = d.userID' => 'LEFT'),
                    'stores_tbl e' => array('a.store_id = e.store_id' => 'INNER'),
					'incentive_hurdles_tbl f' => array('a.incentive_id = f.incentive_id' => 'INNER'),
					'material_groups_tbl g' => array('f.mat_group_id = g.mat_group_id' => 'INNER'),

		);
		$recFound = $this->main->get_join_datatables($this->db_tbl.' a', $join, false, 'a.incentive_code', false, 'a.*, c.*, CONCAT(b.userFirstName," ",b.userLastName) as userFullName, CONCAT(d.userFirstName," ",d.userLastName) as userFullNameModifier, e.store_ifs_code, e.store_name, f.incentive_hurdle_qty, f.incentive_hurdle_sales_qty, f.incentive_hurdle_is_qualified', false, $where_in_field, $where_in);
		$toggle = '';
		$primary_action = '';
		foreach ($recFound->result() as $r) {
			$badge = '';
			if($r->incentive_status == 3){
		        $badge = '<span class="badge badge-success">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="" class="toggle-active text-success" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-on fa-lg"></a></span>';
		        	$toggle = '';
		        }
		    }elseif($r->incentive_status == 4){
		        $badge = '<span class="badge badge-danger">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="#" class="toggle-inactive text-warning" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-off fa-lg"></span></a>';
					$toggle = '';
		        }
		    }
		    if($module_access->view){
		    	$primary_action = '<a href="'.base_url('admin/dashboard/'.encode($r->incentive_id)).'" class="" data-id="'.encode($r->incentive_id).'"><span class="fas fa-eye fa-md"></span></a>';
		    }

            $createdBy = $r->userFullName;
			$modifiedOn = $r->incentive_modified_by == '' ? '' : time_stamp_display($r->incentive_modified_date);
            $modifiedBy = $r->incentive_modified_by == '' ? '' : $r->userFullNameModifier;

			$data[] = array(
				$r->incentive_code,
				picker_date($r->incentive_date),
				$r->store_ifs_code.' - '.$r->store_name,
				decimal_format($r->incentive_hurdle_qty),
				$r->incentive_hurdle_is_qualified,
				decimal_format($r->incentive_store_total, 2),
				decimal_format($r->incentive_rsl_total, 2),
				decimal_format($r->incentive_overall_total, 2),
                
				time_stamp_display($r->incentive_added_date),
				$modifiedOn,
				$badge,
				$primary_action.'&nbsp;'.$toggle
			);
		}

		$output = array(
			 "draw" => $draw,
			 "recordsTotal" => $recFound->num_rows(),
			 "recordsFiltered" => $recFound->num_rows(),
			 "data" => $data
		);
		echo json_encode($output);
		exit();
	}

	

	// END OF Incentive CONTROLLER

	

	

}
