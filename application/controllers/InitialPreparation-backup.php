<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\IOFactory;


class InitialPreparation extends CI_Controller {

	public function __construct() {
    	parent::__construct();
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
		$this->controller = strtolower(__CLASS__);
		
	}

    public function index()
	{
		$info = $this->custom_lib->_require_login();
		 $data['js_file'] = 'assets/js/initial_preparation.js?v=2.0';
		$data['profile'] = $this->custom_lib->_get_profile();
		$data['menuColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->menuColor;
	    $data['tableColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->tableColor;
	    $data['thColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->thColor;
	    $data['btnColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->btnColor;
	    
	    $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;

	    $keyID = decode($info['current_keyID']);
	    $userID = decode($info['userID']);
		
		$data['available_access'] = $this->custom_lib->_get_available_access( array('userID' => decode($info['userID'])) );
		$module_access = $this->custom_lib->module_access('lab-test');
		$data['new_button'] = '<div class="row col-lg-12">';

		$btn_class = 'btn btn-icon btn-sm btn-round btn-'.$data['btnColor'].' mr-2 mb-2';
		
		
		if(!$module_access->view){redirect('admin');}
		
		$bc_access = $this->custom_lib->_get_data_access( array('userID' => $userID, 'statusID' => 1));
		$where_in_field = FALSE;
		$where_in = FALSE;
		if(!empty($bc_access)){
			$where_in_field = 'bcID';
			$where_in = $bc_access;
		}

		$filter = array('statusID'	=>	1);

		$data['title'] = 'Initial Preparation';
		$data['menu_title'] = '';
		$data['parent_title'] = 'Transactional';

		$data['userID'] = decode($info['userID']);
		$data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
		$data['content'] = $this->load->view('initial_preparation/initial_preparation_content', $data , TRUE);
		$this->load->view('admin/templates', $data);
	}



public function initialPreparationGrid()
{
    $info = $this->custom_lib->_require_login();
    $userID = decode($info['userID']);
    $module_access = $this->custom_lib->module_access('initialpreparation');

    $draw   = intval($this->input->get("draw"));
    $start  = intval($this->input->get("start"));
    $length = intval($this->input->get("length"));
    $data   = [];

    // --- Query ---
    $this->db->select([
        'td.lab_code AS lab_code',
        's.sample_name',
        'td.delivery_date',
        'sup.supplier_name',
        'tn.name AS test_name',
        'p.plate_number',
        'b.batch_number',
        'st.sample_type_name' // Type of Sample
    ]);
    $this->db->from('trans_details td');
    $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
    $this->db->join('samples s', 's.id = td.sample_id', 'left');
    $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
    $this->db->join('tests t', 't.id = lt.test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
    $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
    $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
    $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
    $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left'); // join sample types

    // --- Filters ---
    $this->db->where('td.trans_detail_status_id', 22);
    $this->db->order_by('th.trans_id', 'DESC');

    $recFound = $this->db->get();

    // --- Prepare DataTable Output ---
    foreach ($recFound->result() as $r) {
        $data[] = [
            $r->lab_code,
            $r->sample_name,
            !empty($r->delivery_date) ? date('Y-m-d', strtotime($r->delivery_date)) : '',
            $r->supplier_name,
            $r->test_name,
            $r->plate_number,
            $r->batch_number,
            $r->sample_type_name,
            '<select class="form-control form-control-sm dynamic-dropdown"><option value="">Select</option></select>'
        ];
    }

    $output = [
        "draw" => $draw,
        "recordsTotal" => $recFound->num_rows(),
        "recordsFiltered" => $recFound->num_rows(),
        "data" => $data
    ];

    echo json_encode($output);
    exit();
}






}

