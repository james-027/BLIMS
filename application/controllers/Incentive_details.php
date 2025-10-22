<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Incentive_details extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'incentive_details_tbl';
		$this->alias = 'dashboard';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
	}


    /*  
	module: Incentive Details Controller
	desc: CRUD of incentive Details
	date created: 2023-03-23
	created by: Aljune
	Change Management #1
		
	*/

	public function index_test($incentive_id=null){
		
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$incentive_date = clean_data($this->input->get('inc_date') ? date('Y-m-d', strtotime($this->input->get('inc_date'))) : date('Y-m-d'));;
		$store_id = clean_data($this->input->get('store-id')) ?: 0;

		if(!$incentive_id){

			if($store_id != 'null' && !empty($store_id)){
				// CHECK FILTERS
				$filter = [
					'store_id' => decode($store_id),
					'incentive_date' => $incentive_date
				];
				$incentive_record = $this->main->get_data('incentives_tbl a', $filter, TRUE);
				$incentive_id = !empty($incentive_record) ? encode($incentive_record->incentive_id) : 0;

			} else {
				// CHECK THE FIRST ENTRY OF THE DAY AFTER LOGIN
				$filter = [
					'incentive_date' => $incentive_date,
				];
				$incentive_record = $this->main->get_data('incentives_tbl a', $filter, TRUE);
				$incentive_id = !empty($incentive_record) ? encode($incentive_record->incentive_id) : 0;
				
			}
		}
		

		$data['js_file'] = 'assets/js/dynamic-generic-2.js?v=1.0';
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


		
		$data['incentive_id'] = !$incentive_id ? encode(0) : $incentive_id;

		$join = array(
			'users b' => array('a.incentive_added_by = b.userID' => 'INNER'),
			'status_tbl c' => array('a.incentive_status = c.status_id' => 'INNER'),
			'users d' => array('a.incentive_modified_by = d.userID' => 'LEFT'),
			'stores_tbl e' => array('a.store_id = e.store_id' => 'INNER'),
			'incentive_hurdles_tbl f' => array('a.incentive_id = f.incentive_id' => 'INNER'),
			'material_groups_tbl g' => array('f.mat_group_id = g.mat_group_id' => 'INNER'),
		);
		$select = 'a.*, c.*, e.*, f.*, SUM(a.incentive_hurdle_qty) as incentive_hurdle_qty, SUM(a.incentive_hurdle_sales_qty) as incentive_hurdle_sales_qty, SUM(a.incentive_hurdle_remaining_qty) as incentive_hurdle_remaining_qty, SUM(a.incentive_hurdle_over_qty) as incentive_hurdle_over_qty, SUM(a.incentive_hurdle_amount) as incentive_hurdle_amount, SUM(a.incentive_hurdle_total) as incentive_hurdle_total';
		$incentive_hurdle = $this->main->get_join('incentives_tbl a', $join, $row_type=FALSE, $order=FALSE, $group='a.incentive_id', $select=FALSE, $where='a.incentive_id = '.decode($incentive_id));

		$store_ddown = '';
		if($incentive_id){
			$join = array(
				
				'stores_tbl e' => array('a.store_id = e.store_id' => 'INNER'),
			);
			$stores     = $this->main->get_join('incentives_tbl a', $join, $row_type=FALSE, $order=FALSE, $group='a.store_id', $select=FALSE, $where='e.store_status = 1');
		} else {
			$stores = $this->main->get_data('stores_tbl a', ['store_status' => 1]);
			$store_ddown = '<select id="store_id" class="form-control form-control-md basic_dropdown">';
			// $store_ddown .= '<option value="">Select...</option>';
			// if(!empty($stores)){
			// 	foreach($stores as $r){
			// 		$selected = decode($store_id) == $r->store_id ? 'selected' : '';
			// 		$store_ddown .= '<option value="'.encode($r->store_id).'" '.$selected.'>'.$r->store_name.'</option>';
			// 	}
			// }
			$store_ddown .= '</select>';
		}

		
		


		$table = '<table class="table table-hover nowrap shadow"><tr>';
		$table2 = '<table class="table table-striped table-hover nowrap shadow-lg">';
		$table2 .= '<thead class="bg-'.$data['thColor'].' '.expColor($data['thColor'])->fontColor.'">';
		// echo $incentive_id;
		// exit;
		$card = '';
		
		$incentive_date = $incentive_date;
		$incentive_hurdle_is_qualified = '';
		$incentive_store_total = 0;
		$incentive_rsl_total = 0;
		$incentive_crew_total = 0;
		$incentive_ss_total = 0;
		$incentive_coor_total = 0;
		$incentive_overall_total = 0;
		$incentive_hurdle_amount = 0;
		$incentive_hurdle_ss_amount = 0;
		$incentive_hurdle_coor_amount = 0;
		$incentive_hurdle_total = 0;
		$incentive_hurdle_ss_total = 0;
		$incentive_hurdle_coor_total = 0;
		$other_store_incentive = 0;
		$incentive_code = '';
		$incentive_hurdle_sales_qty = 0;
		$incentive_hurdle_qty = 0;
		$hurdleDesc = 'Hurdle Over Qty';
		$hurdleRemOvrQty = 0;
		foreach($incentive_hurdle as $row){
			$incentive_code = $row->incentive_code;
			$incentive_hurdle_sales_qty = decimal_format($row->incentive_hurdle_sales_qty);
			$incentive_hurdle_qty = decimal_format($row->incentive_hurdle_qty);
			if($row->incentive_hurdle_remaining_qty==0){
				$hurdleDesc = 'Hurdle Over Qty';
				$hurdleRemOvrQty = $row->incentive_hurdle_over_qty;
			} else {
				$hurdleDesc = 'Hurdle Remaining';
				$hurdleRemOvrQty = $row->incentive_hurdle_remaining_qty;
			}
			$hurdleRemOvrQty = decimal_format($hurdleRemOvrQty);
			$incentive_hurdle_amount = decimal_format($row->incentive_hurdle_amount);
			$incentive_hurdle_ss_amount = decimal_format($row->incentive_hurdle_ss_amount);
			$incentive_hurdle_coor_amount = decimal_format($row->incentive_hurdle_coor_amount);
			$incentive_hurdle_total = decimal_format($row->incentive_hurdle_total);
			$incentive_hurdle_ss_total = decimal_format($row->incentive_hurdle_ss_total);
			$incentive_hurdle_coor_total = decimal_format($row->incentive_hurdle_coor_total);
			$other_store_incentive = $row->incentive_crew_total - ($row->incentive_hurdle_total + $row->incentive_rsl_total);

			$store_ddown = '<select id="store_id" class="form-control form-control-md basic_dropdown">';
			if(!empty($stores)){
				foreach($stores as $r){
					$selected = ($row->store_id == $r->store_id) ? 'selected' : '';
					$store_ddown .= '<option value="'.encode($r->store_id).'" '.$selected.'>'.$r->store_ifs_code.' - '.$r->store_name.'</option>';
				}
			}
			$store_ddown .= '</select>';
			$incentive_date = $row->incentive_date;
			$incentive_hurdle_is_qualified = $row->incentive_hurdle_is_qualified;
			$incentive_store_total = $row->incentive_store_total;
			$incentive_rsl_total = $row->incentive_rsl_total;
			$incentive_crew_total = $row->incentive_crew_total;
			$incentive_ss_total = $row->incentive_ss_total;
			$incentive_coor_total = $row->incentive_coor_total;
			$incentive_overall_total = $row->incentive_overall_total;
			
			
		}

		$card = '
		<div class="row row-card-no-pd shadow border-bottom-'.$data['btnColor'].'">
			<div class="col-sm-6 col-md-4">
				<div class="card card-stats card-round">
					<div class="card-body">
						<div class="row">
							<div class="col-3">
								<div class="icon-big text-center">
									<i class="flaticon-chart-pie text-warning"></i>
								</div>
							</div>
							<div class="col-9 col-stats">
								<div class="numbers">
									<p class="card-category font-weight-bold">Store Hurdle</p>
									<h4 class="card-title font-weight-bold">'.decimal_format($incentive_hurdle_qty).'</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="card card-stats card-round">
					<div class="card-body ">
						<div class="row">
							<div class="col-3">
								<div class="icon-big text-center">
									<i class="flaticon-cart-1 text-success"></i>
								</div>
							</div>
							<div class="col-9 col-stats">
								<div class="numbers">
									<p class="card-category font-weight-bold">Hurdle Sales Qty</p>
									<h4 class="card-title font-weight-bold">'.decimal_format($incentive_hurdle_sales_qty).'</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-sm-6 col-md-4">
				<div class="card card-stats card-round">
					<div class="card-body">
						<div class="row">
							<div class="col-3">
								<div class="icon-big text-center">
									<i class="flaticon-success text-primary"></i>
								</div>
							</div>
							<div class="col-9 col-stats">
								<div class="numbers">
									<p class="card-category font-weight-bold">'.$hurdleDesc.'</p>
									<h4 class="card-title font-weight-bold">'.decimal_format($hurdleRemOvrQty).'</h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
		</div>';



		$picker_date = '
		<div class="datepicker-dashboard date input-group p-0">
			<input type="text" placeholder="Pick a date" class="form-control form-control-md col-lg-12 col-md-4" id="incentive_date" value="'.picker_date($incentive_date).'"/>

			<div class="input-group-append"><span class="input-group-text px-4"><i class="fa fa-calendar"></i></span></div>
		</div>';

		$table .= '<td width="35%" class="h5 font-weight-bold align-middle">Incentive Code</td>';
		$table .= '<td width="65%" class="font-weight-bold align-middle">'.$incentive_code.'</td>';
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= '<td class="h5 font-weight-bold align-middle">Incentive Date</td>';
		$table .= '<td class="font-weight-bold">'.$picker_date.'</td>';
		$table .= '</tr>';
		$table .= '<tr>';
		$table .= '<td class="h5 font-weight-bold align-middle">Store</td>';
		// $table .= '<td class="font-weight-bold"><a href="#" class="card-link get-incentives-record">'.$row->store_ifs_code.' - '.$row->store_name.'</a></td>';
		$table .= '<td class="font-weight-bold">'.$store_ddown.'</td>';
		$table .= '</tr>';

		$table .= '<tr>';
		$table .= '<td class="h5 font-weight-bold align-middle">Is Qualified</td>';
		$table .= '<td class="font-weight-bold">'.$incentive_hurdle_is_qualified.'</td>';
		$table .= '</tr>';
		

		$table2 .= '
			<div class="row row-card-no-pd mt--2 shadow border-bottom-'.$data['btnColor'].'">
				<div class="col-12 col-sm-12 col-md-4">
					<div class="card ">
						
						<div class="card-body">
							
							<div class="d-flex justify-content-between">
								<div>
									<h4 class="text-primary"><b><u>Crew Total Incentives</u></b></h4>
								</div>
								<h3 class="text-danger fw-bold">₱'.decimal_format($incentive_crew_total, 2).'</h3>
							</div>
							
							<div class="d-flex justify-content-between">
								<div>
									<h5><b>Hurdle Sales Incentive</b>&nbsp;<i class="icon-information text-primary" data-toggle="tooltip" data-placement="right" title="Hurdle Amt * Hurdle Over Qty = (₱'.decimal_format($incentive_hurdle_amount, 2).' * '.decimal_format($hurdleRemOvrQty).')"></i></h5>
								</div>
								<h5 class="text-primary fw-bold">₱'.decimal_format($incentive_hurdle_total, 2).'</h3>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<h5><b>Other Store Sales Incentives</b></h5>
								</div>
								<h5 class="text-primary fw-bold">₱'.decimal_format($other_store_incentive, 2).'</h3>
							</div>
							<div class="d-flex justify-content-between">
								<div>
									<h5><b>Reseller Sales Incentives</b></h5>
								</div>
								<h5 class="text-primary fw-bold">₱'.decimal_format($incentive_rsl_total, 2).'</h3>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 col-sm-12 col-md-4">
					<div class="card">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<div>
									<h4 class="text-primary"><b><u>SS Incentives</u></b></h4>
								</div>
								<h3 class="text-danger fw-bold">₱'.decimal_format($incentive_ss_total, 2).'</h3>
							</div>
							
							<div class="d-flex justify-content-between">
								<div>
									<h5><b>Hurdle Sales Incentive</b>&nbsp;<i class="icon-information text-primary" data-toggle="tooltip" data-placement="right" title="Hurdle Amt * Hurdle Over Qty = (₱'.decimal_format($incentive_hurdle_ss_amount, 2).' * '.decimal_format($hurdleRemOvrQty).')"></i></h5>
								</div>
								<h5 class="text-primary fw-bold">₱'.decimal_format($incentive_hurdle_ss_total, 2).'</h3>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-12 col-sm-12 col-md-4">
					<div class="card">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<div>
									<h4 class="text-primary"><b><u>Coordinator Incentives</u></b></h4>
								</div>
								<h3 class="text-danger fw-bold">₱'.decimal_format($incentive_coor_total, 2).'</h3>
							</div>
							
							<div class="d-flex justify-content-between">
								<div>
									<h5><b>Hurdle Sales Incentive</b>&nbsp;<i class="icon-information text-primary" data-toggle="tooltip" data-placement="right" title="Hurdle Amt * Hurdle Over Qty = (₱'.decimal_format($incentive_hurdle_coor_amount, 2).' * '.decimal_format($hurdleRemOvrQty).')"></i></h5>
								</div>
								<h5 class="text-primary fw-bold">₱'.decimal_format($incentive_hurdle_coor_total, 2).'</h3>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12 ">
					<div class="text-center">
						<h3 data-toggle="tooltip" data-placement="top" title="Combined incentives of Crew, SS and Coordinator" class="text-danger"><b>Overall Incentives : ₱'.decimal_format($incentive_overall_total, 2).'</b></h3>
					</div>
				</div>

			</div>';



		$data['title'] = 'Dashboard';
		$data['menu_title'] = '';
		$data['parent_title'] = '';
		$data['controller']   = $this->controller;

		$table .= '</table>';
		$table2 .= '</thead></table>';

		$data['table'] = $table;
		$data['table2'] = $table2;
		$data['card'] = $card;


		$tableCrew = '<table class="table table-striped table-hover nowrap shadow"><tr>';
		$tableCrew .= '<tr>';
		$tableCrew .= '<td class="font-weight-bold">Logged Crew</td>';

		$join = array(
			'crews_tbl b' => array('a.crew_id = b.crew_id' => 'INNER')
			
		);
		$logged_crew = $this->main->get_join('incentive_crews_tbl a', $join, FALSE, 'b.crew_lname', 'a.crew_id', false, $where='a.incentive_id = '.decode($incentive_id));

		
		if(!empty($logged_crew)){
			$crew = '';
			foreach ($logged_crew as $r) {
				$crew .= $r->crew_username.' '.$r->crew_fname.' '.$r->crew_lname.'<br>';
				
			}
			$tableCrew .= '<td class="">'.$crew.'</td>';
		}
		$tableCrew .= '</tr></table>';
		$data['tableCrew'] = $tableCrew;


		$data['userID'] = decode($info['userID']);
		$data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);

		$data['content'] = $this->load->view($this->controller.'/incentive_details_content', $data , TRUE);
		
		$this->load->view('admin/templates', $data);
	}

	public function data_grid($incentive_id, $incentive_type_id){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$keyID = decode($info['current_keyID']);
		$userID = decode($info['userID']);

		$incentive_id = decode($incentive_id);
		$incentive_type_id = decode($incentive_type_id);
		
		
        $module_access = $this->custom_lib->module_access($alias);
		
		$bc_access = $this->custom_lib->_get_data_access( array('userID' => $userID, 'statusID' => 1));
		$where_in_field = FALSE;
		$where_in = FALSE;
		

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$data = array();
		$join = array(
					'users b' => array('a.incentive_det_added_by = b.userID' => 'INNER'),
					'status_tbl c' => array('a.incentive_det_status = c.status_id' => 'INNER'),
                    'users d' => array('a.incentive_det_modified_by = d.userID' => 'LEFT'),
                    'materials_tbl e' => array('a.mat_id = e.mat_id' => 'INNER'),
                    'incentive_types_tbl f' => array('a.incentive_type_id = f.incentive_type_id' => 'INNER'),
                    'incentive_based_tbl g' => array('a.incentive_based_id = g.incentive_based_id' => 'INNER'),
                    'material_groups_tbl h' => array('e.mat_group_id = h.mat_group_id' => 'INNER')
		);
		$recFound = $this->main->get_join_datatables($this->db_tbl.' a', $join, false, 'a.incentive_det_added_date DESC', false, 'a.*, c.*, CONCAT(b.userFirstName," ",b.userLastName) as userFullName, CONCAT(d.userFirstName," ",d.userLastName) as userFullNameModifier, e.mat_sap_code, e.mat_name, e.mat_erp_code, f.incentive_type_name, g.incentive_based_name, h.mat_group_name', array('incentive_id' => $incentive_id, 'a.incentive_det_status' => 1, 'a.incentive_type_id' => $incentive_type_id), $where_in_field, $where_in);
		$toggle = '';
		$primary_action = '';
		foreach ($recFound->result() as $r) {
			$badge = '';
			if($r->incentive_det_status == 1){
		        $badge = '<span class="badge badge-success">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="" class="toggle-active text-success" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-on fa-lg"></a></span>';
		        	$toggle = '';
		        }
		    }elseif($r->incentive_det_status == 2){
		        $badge = '<span class="badge badge-danger">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="#" class="toggle-inactive text-warning" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-off fa-lg"></span></a>';
					$toggle = '';
		        }
		    }
		    

            $createdBy = $r->userFullName;
			$modifiedOn = $r->incentive_det_modified_by == '' ? '' : time_stamp_display($r->incentive_det_modified_date);
            $modifiedBy = $r->incentive_det_modified_by == '' ? '' : $r->userFullNameModifier;

			$data[] = array(
				
				$r->mat_sap_code,
				$r->mat_erp_code,
				$r->mat_group_name,
				$r->mat_name,
				$r->incentive_type_name,
				
				decimal_format($r->incentive_det_qty, 2),
				decimal_format($r->incentive_det_amount, 2),
				decimal_format($r->incentive_det_total, 2),
                
				$badge,
				time_stamp_display($r->incentive_det_added_date),
				$modifiedOn
				
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
	
	public function data_grid_hurdle($incentive_id){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$keyID = decode($info['current_keyID']);
		$userID = decode($info['userID']);

		$incentive_id = decode($incentive_id);
		
		
		
        $module_access = $this->custom_lib->module_access($alias);
		
		$bc_access = $this->custom_lib->_get_data_access( array('userID' => $userID, 'statusID' => 1));
		$where_in_field = FALSE;
		$where_in = FALSE;
		

		$draw = intval($this->input->get("draw"));
		$start = intval($this->input->get("start"));
		$length = intval($this->input->get("length"));
		$data = array();
		$join = array(
					'incentive_hurdles_tbl b' => array('a.incentive_id = b.incentive_id' => 'INNER'),
					'incentive_hurdle_details_tbl c' => array('b.incentive_hurdle_id = c.incentive_hurdle_id' => 'INNER'),
					'users d' => array('c.incentive_hurdle_det_added_by = d.userID' => 'INNER'),
					'status_tbl e' => array('c.incentive_hurdle_det_status = e.status_id' => 'INNER'),
                    'users f' => array('c.incentive_hurdle_det_modified_by = f.userID' => 'LEFT'),
                    'materials_tbl g' => array('c.mat_id = g.mat_id' => 'INNER'),
                    'material_groups_tbl h' => array('g.mat_group_id = h.mat_group_id' => 'INNER')
		);
		$recFound = $this->main->get_join_datatables('incentives_tbl a', $join, false, 'c.incentive_hurdle_det_added_date DESC', false, 'a.*, b.*, c.*, CONCAT(d.userFirstName," ",d.userLastName) as userFullName, CONCAT(f.userFirstName," ",f.userLastName) as userFullNameModifier, e.status_name, g.mat_sap_code, g.mat_name, g.mat_erp_code, h.mat_group_name', array('a.incentive_id' => $incentive_id, 'c.incentive_hurdle_det_status' => 1), $where_in_field, $where_in);
		$toggle = '';
		$primary_action = '';
		foreach ($recFound->result() as $r) {
			$badge = '';
			if($r->incentive_hurdle_det_status == 1){
		        $badge = '<span class="badge badge-success">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="" class="toggle-active text-success" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-on fa-lg"></a></span>';
		        	$toggle = '';
		        }
		    }elseif($r->incentive_hurdle_det_status == 2){
		        $badge = '<span class="badge badge-danger">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	// $toggle = '<a href="#" class="toggle-inactive text-warning" data-id="' . encode($r->incentive_id) . '"><span class="fas fa-toggle-off fa-lg"></span></a>';
					$toggle = '';
		        }
		    }
		    

            $createdBy = $r->userFullName;
			$modifiedOn = $r->incentive_hurdle_det_modified_by == '' ? '' : time_stamp_display($r->incentive_hurdle_det_modified_date);
            $modifiedBy = $r->incentive_hurdle_det_modified_by == '' ? '' : $r->userFullNameModifier;

			$incentive_hurdle_det_amount = $r->incentive_hurdle_amount; 
			$incentive_hurdle_det_total = $r->incentive_hurdle_amount * $r->incentive_hurdle_det_qty;
			$data[] = array(
				
				$r->mat_sap_code,
				$r->mat_erp_code,
				$r->mat_group_name,
				$r->mat_name,
				'HURDLE',
				
				decimal_format($r->incentive_hurdle_det_qty, 2),
				decimal_format($incentive_hurdle_det_amount, 2),
				decimal_format($incentive_hurdle_det_total, 2),
                
				$badge,
				time_stamp_display($r->incentive_hurdle_det_added_date),
				$modifiedOn
				
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
