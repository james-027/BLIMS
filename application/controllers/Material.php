<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Material extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'materials_tbl';
		$this->alias = 'material';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
	}

	public function _remove_invalid_chars( $text) {
		$regex = '/( [\x00-\x7F] | [\xC0-\xDF][\x80-\xBF] | [\xE0-\xEF][\x80-\xBF]{2} | [\xF0-\xF7][\x80-\xBF]{3} ) | ./x';
		return preg_replace($regex, '$1', $text);
	}


    /*  
	module: Material Controller
	desc: CRUD of Material
	date created: 2023-03-16
	created by: Aljune
	Change Management #1
		Date: 2020-06-03
		Description: Continuation of CRUD (Add, Edit, Deactivation, & Activation)
		Modified By: Aljune
	*/

	public function index(){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		
		$data['profile'] = $this->custom_lib->_get_profile();
		$data['js_file'] = 'assets/js/dynamic-generic.js?v=1.0';
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

        $data['new_button'] .= '<button type="button" class="refresh-dt '.$btn_class.'"><span class="fas fa-sync"></span></button>';

		
		if($module_access->dlod){
			$data['new_button'] .= '<button type="button" class="print-dt '.$btn_class.'"><span class="fas fa-file-excel"></span></button>';
			
		}
		if($module_access->ulod){
			$data['new_button'] .= '<button type="button" data-toggle="tooltip" data-placement="bottom" title="Upload Data" class="upload-btn '.$btn_class.'"><span class="fas fa-upload"></span></button>';
		}
		$data['new_button'] .= '</div>';
		
		
		if(!$module_access->view){redirect('admin');}

		$data['title'] = 'Material';
		$data['menu_title'] = 'Master data';
		$data['parent_title'] = 'Material Config';
		$data['controller']   = $this->controller;


		$data['userID'] = decode($info['userID']);
		$data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);

		$data['content'] = $this->load->view($this->controller.'/material_content', $data , TRUE);
		
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
					'users b' => array('a.mat_added_by = b.userID' => 'INNER'),
					'status_tbl c' => array('a.mat_status = c.status_id' => 'INNER'),
                    'users d' => array('a.mat_modified_by = d.userID' => 'LEFT'),
                    'material_groups_tbl e' => array('a.mat_group_id = e.mat_group_id' => 'INNER'),
		);
		$recFound = $this->main->get_join_datatables($this->db_tbl.' a', $join, false, 'a.mat_name', false, 'a.*, c.*, CONCAT(b.userFirstName," ",b.userLastName) as userFullName, CONCAT(d.userFirstName," ",d.userLastName) as userFullNameModifier, e.mat_group_name', false, $where_in_field, $where_in);
		$toggle = '';
		$primary_action = '';
		foreach ($recFound->result() as $r) {

			if($r->mat_status == 1){
		        $badge = '<span class="badge badge-success">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	$toggle = '<a href="" class="toggle-active text-success" data-id="' . encode($r->mat_id) . '"><span class="fas fa-toggle-on fa-lg"></a></span>';
		        }
		    }elseif($r->mat_status == 2){
		        $badge = '<span class="badge badge-warning">'.$r->status_name.'</span>';
		        if($module_access->act){
		        	$toggle = '<a href="#" class="toggle-inactive text-warning" data-id="' . encode($r->mat_id) . '"><span class="fas fa-toggle-off fa-lg"></span></a>';
		        }
		    }
		    if($module_access->edit){
		    	$primary_action = '<a href="" data-url="'.$this->controller.'/modal" class="edit-form" data-id="'.encode($r->mat_id).'"><span class="fas fa-pencil-alt fa-md"></span></a>';
		    }

            // $createdBy = $r->userFullName .' | '.time_stamp_display($r->mat_added_date);
            $createdBy = $r->userFullName;
            $modifiedOn = $r->mat_modified_by == '' ? '' : time_stamp_display($r->mat_modified_date);
            $modifiedBy = $r->mat_modified_by == '' ? '' : $r->userFullNameModifier;

			$data[] = array(
				$r->mat_sap_code,
				$r->mat_erp_code,
				$r->mat_short_name,
				$r->mat_name,
				$r->mat_group_name,
				$r->mat_weight,
				$r->mat_sales_unit,
                $createdBy,
				time_stamp_display($r->mat_added_date),
                $modifiedBy,
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

	public function store(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		$this->custom_lib->_check_access_role('add', $alias);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			
			$mat_name = clean_data($this->input->post('mat_name'));
			$mat_sap_code = clean_data($this->input->post('mat_sap_code'));
			$mat_erp_code = clean_data($this->input->post('mat_erp_code'));
			$mat_short_name = clean_data($this->input->post('mat_short_name'));
			$mat_group_id = clean_data(decode($this->input->post('mat_group_id')));
			$mat_weight = clean_data($this->input->post('mat_weight'));
			$mat_sales_unit = clean_data($this->input->post('mat_sales_unit'));
			

			if(
				!empty($mat_name) &&
				!empty($mat_sap_code) &&
				!empty($mat_erp_code) &&
				!empty($mat_short_name) &&
				!empty($mat_group_id) &&
				$mat_weight != '' &&
				$mat_sales_unit != ''
			){

				$check_mat_name = $this->main->check_data($this->db_tbl, array('mat_name' =>  trim($mat_name) ));
				if($check_mat_name == FALSE){

					$check_mat_code = $this->main->check_data($this->db_tbl, array('mat_sap_code' =>  trim($mat_sap_code) ));
					if($check_mat_code == FALSE){

						$check_mat_erp_code = $this->main->check_data($this->db_tbl, array('mat_erp_code' =>  trim($mat_erp_code) ));
						if($check_mat_erp_code){
							echo json_encode(array(
								'success'       =>    false,
								'msg'    =>    'Material ERP Code already exist.'
							));
							exit;
						}

						$set = array(
							
							'mat_name' => trim(strtoupper($mat_name)),
							'mat_sap_code' => trim($mat_sap_code),
							'mat_erp_code' => trim(strtoupper($mat_erp_code)),
							'mat_short_name' => trim(strtoupper($mat_short_name)),
							'mat_group_id' => trim($mat_group_id),
							'mat_weight' => trim($mat_weight),
							'mat_sales_unit' => trim($mat_sales_unit),
                            'mat_added_by'    => decode($info['userID']),
                            'mat_added_date'  => date_now(),
                            'mat_modified_date'  => date_now(),
							'mat_status' => 1
						);

						$result = $this->main->insert_data($this->db_tbl, $set, TRUE);

						if($result['id']){
							$user_logs = array(
								'userID'	=>	decode($info['userID']),
								'userFullName' =>	$info['userFullName'],
								'logTS'	=>	date_now(),
								'page'	=>	$this->controller.'/add',
								'logDetail'	=>	'Successfully added Material ID:'.@$result['id']
							);
					        $this->main->user_logs($user_logs);

							echo json_encode(array(
			                	'success'       =>    true,
			                	'msg'    =>    'Material added successfully.',
			                	'tgID' => $result['id'],
			                	'tgLDesc' => trim(strtoupper($mat_name))
			                ));
						}
					}else{
						echo json_encode(array(
		                	'success'       =>    false,
		                	'msg'    =>    'Material SAP Code already exist.'
		                ));
					}
				}else{
					echo json_encode(array(
	                	'success'       =>    false,
	                	'msg'    =>    'Material name already exist.'
	                ));
				}
				
			}else{
				echo json_encode(array(
                	'success'       =>    false,
                	'msg'    =>    'Make sure all fields are filled.'
                ));
			}
		}else{
			echo json_encode(array(
            	'success'       =>    false,
            	'msg'    =>    'Please contact your administrator.'
            ));
		}	
	}

	public function modal(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		
		
		$keyID = decode($info['current_keyID']);
		// $bc_access = $this->custom_lib->_get_access( array('keyID' => $keyID, 'statusID' => 1), 'bcID' );
		
		$data['material_groups'] = $this->main->get_data('material_groups_tbl a', ['mat_group_status' => 1]);

		$id = decode($this->input->post('id'));
		if($id != 0 || $id){
			$this->custom_lib->_check_access_role('edit', $alias);

			$join = array(
				'status_tbl b' => 'a.mat_status = b.status_id and a.mat_id = "'.$id.'"',
			);
			$check_record = $this->main->check_join($this->db_tbl.' a', $join, true);
			

			if($check_record['result']){
				$data['success'] = 1;
				$data[$this->controller] = $check_record['info'];

			}
		} else {
			$this->custom_lib->_check_access_role('add', $alias);

			$data['success'] = 1;
			$data[$this->controller] = NULL;
		}


		$data['html'] = $this->load->view($this->controller.'/modal_content', $data, TRUE);

		echo json_encode($data);
	}

	public function update(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		$this->custom_lib->_check_access_role('edit', $alias);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$mat_id = decode($this->input->post('id'));
			$mat_name = clean_data($this->input->post('mat_name'));
			$mat_sap_code = clean_data($this->input->post('mat_sap_code'));
			$mat_erp_code = clean_data($this->input->post('mat_erp_code'));
			$mat_short_name = clean_data($this->input->post('mat_short_name'));
			$mat_group_id = clean_data(decode($this->input->post('mat_group_id')));
			$mat_weight = clean_data($this->input->post('mat_weight'));
			$mat_sales_unit = clean_data($this->input->post('mat_sales_unit'));
			

			if(
				!empty($mat_id) &&
				!empty($mat_name) &&
				!empty($mat_sap_code) &&
				!empty($mat_erp_code) &&
				!empty($mat_short_name) &&
				!empty($mat_group_id) &&
				$mat_weight != '' &&
				$mat_sales_unit != ''
			){

				$check_mat_name = $this->main->check_data($this->db_tbl, array('mat_name' =>  $mat_name, 'mat_id !=' => $mat_id));
				if($check_mat_name == FALSE){
					$check_mat_code = $this->main->check_data($this->db_tbl, array('mat_sap_code' =>  trim($mat_sap_code), 'mat_id !=' => $mat_id));
					if($check_mat_code == FALSE){

						$check_mat_erp_code = $this->main->check_data($this->db_tbl, array('mat_erp_code' =>  trim($mat_erp_code), 'mat_id !=' => $mat_id ));
						if($check_mat_erp_code){
							echo json_encode(array(
								'success'       =>    false,
								'msg'    =>    'Material ERP Code already exist.'
							));
							exit;
						}

						$set = array(
							'mat_name' => trim(strtoupper($mat_name)),
							'mat_sap_code' => trim($mat_sap_code),
							'mat_erp_code' => trim(strtoupper($mat_erp_code)),
							'mat_short_name' => trim(strtoupper($mat_short_name)),
							'mat_group_id' => trim($mat_group_id),
							'mat_weight' => trim($mat_weight),
							'mat_sales_unit' => trim($mat_sales_unit),
                            'mat_modified_by' => decode($info['userID']),
                            'mat_modified_date'   => date_now()
						);

						$result = $this->main->update_data($this->db_tbl, $set, array('mat_id' => $mat_id));
						if($result == TRUE){
							$user_logs = array(
								'userID'	=>	decode($info['userID']),
								'userFullName' =>	$info['userFullName'],
								'logTS'	=>	date_now(),
								'page'	=>	$this->controller.'/update',
								'logDetail'	=>	'Successfully updated Material ID:'.@$mat_id
							);
					        $this->main->user_logs($user_logs);
							echo json_encode(array(
				            	'success'       =>    true,
				            	'msg'    =>    'Material successfully updated.'
				            ));
						}else{
							echo json_encode(array(
				            	'success'       =>    false,
				            	'msg'    =>    'Opps. Please try again.'
				            ));
						}
					}else{
						echo json_encode(array(
			            	'success'       =>    false,
			            	'msg'    =>    'Opps. Material SAP Code already exist.'
			            ));
					}
				}else{
					echo json_encode(array(
		            	'success'       =>    false,
		            	'msg'    =>    'Opps. Material name already exist.'
		            ));
				}
			}else{
				echo json_encode(array(
	            	'success'       =>    false,
	            	'msg'    =>    'Opps. Please make sure all required fields are filled.'
	            ));
			}
		}else{
			echo json_encode(array(
            	'success'       =>    false,
            	'msg'    =>    'Opps. Please contact system administrator.'
            ));
		}
	}

	public function deactivate(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		$this->custom_lib->_check_access_role('act', $alias);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$mat_id = decode(clean_data($this->input->post('id')));
			if(!empty($mat_id)){
				$check = $this->main->check_data($this->db_tbl, array('mat_id' => $mat_id, 'mat_status' => 2), true);
				if($check['result'] == FALSE){

					$set = array(
                        'mat_status' => 2,
                        'mat_modified_by' => decode($info['userID']),
                        'mat_modified_date'   => date_now()
                    );
					$where = array('mat_id' => $mat_id);
					$result = $this->main->update_data($this->db_tbl, $set, $where);

					if($result == TRUE){
			            $user_logs = array(
							'userID'	=>	decode($info['userID']),
							'userFullName' =>	$info['userFullName'],
							'logTS'	=>	date_now(),
							'page'	=>	$this->controller.'/deactivate',
							'logDetail'	=>	'Successfully deactivated Material ID:'.@$mat_id
						);
				        $this->main->user_logs($user_logs);

						echo json_encode(array(
			            	'success'       =>    true,
			            	'msg'    =>    'Material successfully deactivated.'
			            ));
					}else{
						echo json_encode(array(
			            	'success'       =>    false,
			            	'msg'    =>    'Opps. Please try again.'
			            ));
					}
				}else{
					echo json_encode(array(
		            	'success'       =>    false,
		            	'msg'    =>    'Opps. Material already inactive.'
		            ));
				}
			}else{
				echo json_encode(array(
	            	'success'       =>    false,
	            	'msg'    =>    'Opps. Material ID required.'
	            ));
			}
		}else{
			echo json_encode(array(
            	'success'       =>    false,
            	'msg'    =>    'Opps. Please contact system administrator.'
            ));
		}
	}

	public function activate(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		$this->custom_lib->_check_access_role('act', $alias);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$mat_id = decode(clean_data($this->input->post('id')));
			if(!empty($mat_id)){
				$check = $this->main->check_data($this->db_tbl, array('mat_id' => $mat_id, 'mat_status' => 1), true);
				if($check['result'] == FALSE){

					$set = array(
                        'mat_status' => 1,
                        'mat_modified_by' => decode($info['userID']),
                        'mat_modified_date'   => date_now()
                    );
					$where = array('mat_id' => $mat_id);
					$result = $this->main->update_data($this->db_tbl, $set, $where);

					if($result == TRUE){
			            $user_logs = array(
							'userID'	=>	decode($info['userID']),
							'userFullName' =>	$info['userFullName'],
							'logTS'	=>	date_now(),
							'page'	=>	$this->controller.'/activate',
							'logDetail'	=>	'Successfully activated Material ID:'.@$mat_id
						);
				        $this->main->user_logs($user_logs);

						echo json_encode(array(
			            	'success'       =>    true,
			            	'msg'    =>    'Material successfully activated.'
			            ));
					}else{
						echo json_encode(array(
			            	'success'       =>    false,
			            	'msg'    =>    'Opps. Please try again.'
			            ));
					}
				}else{
					echo json_encode(array(
		            	'success'       =>    false,
		            	'msg'    =>    'Opps. Material already active.'
		            ));
				}
			}else{
				echo json_encode(array(
	            	'success'       =>    false,
	            	'msg'    =>    'Opps. Material ID required.'
	            ));
			}
		}else{
			echo json_encode(array(
            	'success'       =>    false,
            	'msg'    =>    'Opps. Please contact system administrator.'
            ));
		}
	}

	public function download_template(){
		$info = $this->custom_lib->_require_login();
		$data['thColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->thColor;
		$expThColor = expColor($data['thColor'])->expThColor;
        $expFontColor = expColor($data['thColor'])->expFontColor;
		
		//$path = 'uploads/';
		require_once( APPPATH . "/third_party/PHPExcel-1.8/Classes/PHPExcel.php" );
		$object_excel = new PHPExcel(); // new object for PHPExcel

		$templateTitle = 'Material Data Upload Template';

			//SHEET 1
		$object_excel->setActiveSheetIndex(0) // Create new worksheet
			->setTitle($templateTitle);
		$object_excel->getActiveSheet()
					->setCellValue("A1", "Columns with (*) are required.")
					->mergeCells('A1:G1');
		$table_head = array(
			'* SAP Code',
			'* ERP Code',
			'* Material Abbr',
			'* Material Name',
			'* Material Group',
			'* Weight',
			'* Sales Unit'
		);

		$head = 0;
		foreach($table_head as $value)
		{
			$object_excel->getActiveSheet()->setCellValueByColumnAndRow($head, 2, $value);
			$head++;
		}

		$cellsTitle4 = 'A2:G2';
		$styleArray = array(
		    'font'  => array(
		        'bold'  => true,
		        'color' => array('rgb' => $expFontColor)
		    ),
		    'fill'	=> array(
		    	'type' => PHPExcel_Style_Fill::FILL_SOLID,
				'startcolor' => array(
					'rgb' => $expThColor
		    	)
		    )
		);
		$object_excel->getActiveSheet()->getStyle($cellsTitle4)->applyFromArray($styleArray);
		for ($i = 'A'; $i <=  $object_excel->getActiveSheet()->getHighestColumn(); $i++) {
		    $object_excel->getActiveSheet()->getColumnDimension($i)->setAutoSize( true );
		}

		
		$object_excel->getActiveSheet()->getComment('A2')->getText()->createTextRun('Registered SAP Material Code');
		$object_excel->getActiveSheet()->getComment('B2')->getText()->createTextRun('Registered ERP Code');

		$object_excel->setActiveSheetIndex(0);
		ob_end_clean();
		ob_start();
		
		$filename = $templateTitle." ". date('mdy').".xlsx";
		header('Content-Type: application/vnd.ms-excel'); 
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		$object_excel_writer = PHPExcel_IOFactory::createWriter($object_excel, 'Excel2007');
		$object_excel_writer->save('php://output');
	}

	public function upload(){
		$info = $this->custom_lib->_require_login();
		$alias = $this->alias;
		$this->custom_lib->_check_access_role('add', $alias);

		ini_set('max_execution_time', 0);
		ini_set('memory_limit','2048M');

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$path = 'uploads/'.$this->controller.'/'.$info['keyCode'].'/'.$info['bcCode'].'/';
			if (!is_dir($path)) {
			    mkdir($path, 0777, TRUE);
			}
			require_once( APPPATH . "/third_party/PHPExcel-1.8/Classes/PHPExcel.php" );
			
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'xlsx|xls';
			$config['remove_spaces'] = TRUE;
			$config['overwrite'] = true;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);            
			if (!$this->upload->do_upload('upload-temp-file')) {
				$error = array('error' => $this->upload->display_errors());
			} else {
				$data = array('upload_data' => $this->upload->data());
			}

			if(empty($error)){
				if (!empty($data['upload_data']['file_name'])) {
					$import_xls_file = $data['upload_data']['file_name'];
				} else {
					$import_xls_file = 0;
				}
				$inputFileName = $path . $import_xls_file;
				try {
					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objPHPExcel = $objReader->load($inputFileName);
					$allDataInSheet = $objPHPExcel->getActiveSheet()->toArray(null, true, false, true);
					$flag = true;
					$i=2; //dating 4
					$excel_line = 3;
					$hdr_saved = 0;
					$hdr_added = 0;
					$added = 0;

					$import_table = '';
					
		        	$totalKgs = 0;
		        	$overAllTotal = 0;

		        	$docTextIDInserted = false;
		        	$noOfHouse = NULL;
					$hasFailed = false;
		        	foreach ($allDataInSheet as $value) {
	        			if($added < 2){ //dating 3
		                  	goto end_here;
		                }

						
		                // $periodFrom		= $value['A'] ? clean_data( trim(date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP( $value['A'] ))) ) : NULL;
		                // $periodTo		= $value['B'] ? clean_data( trim(date('Y-m-d', PHPExcel_Shared_Date::ExcelToPHP( $value['B'] ))) ) : NULL;
			        	$mat_sap_code			= clean_data(trim(@$value['A']));
			        	$mat_erp_code			= strtoupper(clean_data(trim(@$value['B'])));
			        	$mat_short_name			= strtoupper(clean_data(trim(@$value['C'])));
			        	$mat_name				= strtoupper(clean_data(trim(@$value['D'])));
			        	$mat_group_name			= strtoupper(clean_data(trim(@$value['E'])));
			        	$mat_weight				= strtoupper(clean_data(trim(@$value['F'])));
			        	$mat_sales_unit			= strtoupper(clean_data(trim(@$value['G'])));
						$mat_id					= NULL;

						
						$check_mat_group		= $this->main->check_data('material_groups_tbl', array('mat_group_name' =>  $mat_group_name, 'mat_group_status' => 1), true);
						$mat_group_id			= $check_mat_group['result'] ? $check_mat_group['info']->mat_group_id : FALSE;
						$mat_group_msg			= $mat_group_id == FALSE ? 'Material Group ('.$mat_group_name.') not found.<br>' : NULL;

						$mat_sap_code_msg		= NULL;
						$check_mat_sap_code		= $this->main->check_data($this->db_tbl, array('mat_sap_code' =>  $mat_sap_code), true);
						//$mat_sap_code_msg		= $check_mat_sap_code['result'] ? 'Material SAP Code ('.$mat_sap_code.') already exist.<br>' : NULL;
						$mat_id					= $check_mat_sap_code['result'] ? $check_mat_sap_code['info']->mat_id : NULL;
						
						$mat_erp_code_msg		= NULL;
						$check_mat_erp_code		= $this->main->check_data($this->db_tbl, array('mat_erp_code' =>  $mat_erp_code), true);
						//$mat_erp_code_msg		= $check_mat_erp_code['result'] ? 'Material ERP Code ('.$mat_erp_code.') already exist.<br>' : NULL;
						$mat_id					= $check_mat_erp_code['result'] ? $check_mat_erp_code['info']->mat_id : NULL;
						
						
						
						if(
							!empty($mat_sap_code) &&
							!empty($mat_erp_code) &&
							!empty($mat_short_name) &&
							!empty($mat_name) &&
							!empty($mat_group_name) &&
							$mat_weight != '' &&
							$mat_sales_unit != '' &&
							empty($mat_group_msg) &&
							empty($mat_erp_code_msg) &&
							empty($mat_sap_code_msg)
							
						){
							//IF ALL COLUMNS ARE VALID
							if(!$mat_id){
								// ADD ENTRY
								$set = array(
									'mat_sap_code'			=> $mat_sap_code,
									'mat_erp_code'			=> $mat_erp_code,
									'mat_short_name'		=> $mat_short_name,
									'mat_name'				=> $mat_name,
									'mat_group_id'			=> $mat_group_id,
									'mat_weight'			=> $mat_weight,
									'mat_sales_unit'		=> $mat_sales_unit,
									'mat_added_by'    		=> decode($info['userID']),
									'mat_added_date'  		=> date_now(),
									'mat_modified_date'  	=> date_now(),
									'mat_status' 			=> 1
								);
	
								$result_added = $this->main->insert_data($this->db_tbl, $set);
								if($result_added){
									$dataInserted = true;
									$display_msg = '<font color="green"><span class="fas fa-check-circle"></span> ADDED SUCCESSFULLY</font>';
									$i++;
								}

							} else {
								$set = array(
									'mat_sap_code'			=> $mat_sap_code,
									'mat_erp_code'			=> $mat_erp_code,
									'mat_short_name'		=> $mat_short_name,
									'mat_name'				=> $mat_name,
									'mat_group_id'			=> $mat_group_id,
									'mat_weight'			=> $mat_weight,
									'mat_sales_unit'		=> $mat_sales_unit,
									'mat_modified_by' 		=> decode($info['userID']),
									'mat_modified_date'   	=> date_now()
								);
		
								$result_updated = $this->main->update_data($this->db_tbl, $set, array('mat_id' => $mat_id));
								if($result_updated){
									$dataInserted = true;
									$display_msg = '<font color="green"><span class="fas fa-check-circle"></span> UPDATED SUCCESSFULLY</font>';
									$i++;
								} else {
									$dataInserted = true;
									$display_msg = '<font color="orange"><span class="fas fa-times-circle"></span> IGNORED</font>';
									$i++;
								}
							}
						} else {
							
							if($mat_group_msg || $mat_sap_code_msg || $mat_erp_code_msg){
								$display_msg = '<font color="red"><span class="fas fa-times-circle"></span> FAILED, '.$mat_sap_code_msg.$mat_erp_code_msg.$mat_group_msg.'</font>';
							} else {
								$display_msg = '<font color="red"><span class="fas fa-times-circle"></span> FAILED, All columns with (*) are required.</font>';
							}
							$hasFailed = true;

							
						}

						$import_table .= '
							<tr>
								
								<td class="align-middle text-center"><strong>'.$mat_sap_code.'</strong></td>
								<td class="align-middle text-center"><strong>'.$mat_erp_code.'</strong></td>
								<td class="align-middle text-left">'.$mat_short_name.'</td>
								<td class="align-middle text-left">'.$mat_name.'</td>
								<td class="align-middle text-left">'.$mat_group_name.'</td>
								<td class="align-middle text-left">'.$mat_weight.'</td>
								<td class="align-middle text-left">'.$mat_sales_unit.'</td>
								
								<td class="align-middle text-left">'.$display_msg.'</td>
							</tr>';

						
		                $excel_line++;
						end_here:
		                $added++;
		        	}


		        	$user_logs = array(
						'userID'		=>	decode($info['userID']),
						'userFullName'	=>	$info['userFullName'],
						'logTS'			=>	date_now(),
						'page'			=>	$this->controller.'/upload',
						'logDetail'		=>	'Successfully uploaded with entries : '.$i.', filename : '.$import_xls_file
					);
			        $this->main->user_logs($user_logs);

		        	
					
		        	if(!$import_table){
		        		$import_table = '
							<tr>
								<td colspan="8" class="align-middle text-center">No data found in template.</td>
							</tr>';
		        	}

					$import_table = $this->_remove_invalid_chars($import_table);

					if(!$hasFailed){
						$upload_result_msg = array(
							"success"		=>		true,
							"import_table"	=>		$import_table,
							'msg'			=>		'Upload file reading successful, You may check the result.'
						);
						
					} else {
						$upload_result_msg = array(
							"success"		=>		false,
							"import_table"	=>		$import_table,
							'msg'			=>		'Upload file reading successful, Seems something went wrong. Please check the result.'
						);
					}
					
					
					echo json_encode($upload_result_msg);

		        } catch (Exception $e) {
					die('Opps loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME)
		                  . '": ' .$e->getMessage());

				}
			} else {
				echo json_encode(array(
				  'success' => false,
				  'msg' => $this->upload->display_errors()//$error['error']
				));
			}

		}else{
			echo json_encode(array(
            	'success'       =>    false,
            	'msg'    =>    'Opps. Please contact system administrator.'
            ));
		}
	}

	// END OF Material CONTROLLER

	

	

}
