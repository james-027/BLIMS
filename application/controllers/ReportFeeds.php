<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

class ReportFeeds extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
        $this->alias = 'reportfeeds';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');

	}


    /*  
	module: Report Feeds Controller
	desc: Creation of  Report Feeds
	date created: 01-05-2025
	created by: James
	Change Management #1`
	*/

        public function index() 
    {
         $alias = $this->alias;
        $info = $this->custom_lib->_require_login();
        $data['js_file'] = 'assets/js/preparation.js?v=2.0';
        $data['profile'] = $this->custom_lib->_get_profile();
        $data['menuColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->menuColor;
        $data['tableColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->tableColor;
        $data['thColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->thColor;
        $data['btnColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->btnColor;

        $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
        $userID = decode($info['userID']);
        $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);
        $module_access = $this->custom_lib->module_access($alias);
        if(!$module_access->view){redirect('admin');}
        $data['can_modify'] =(isset($module_access->add) && (int)$module_access->add === 1) ||(isset($module_access->edit) && (int)$module_access->edit === 1);
        $data['can_download'] = $module_access->dlod;
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);
        
        $data['title'] = 'Report Feeds';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Reports';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        
        $this->db->select([
        'th.trans_id AS trans_id',
        'th.job_order_no',
        'th.laboratory_id',
        'td.trans_detail_id',
        'td.sample_id',
        'td.lab_test_id',
        'td.test_exec_lab_result',
        'td.ext_lab_code AS lab_code',
        'td.delivery_date',
        'td.created_at',
        's.sample_name',
        'st.sample_type_name',
        'tp.param_name',
        'if.feedmill_name AS internal_feedmill_name',
        'cf.feedmill_name AS commercial_feedmill_name',
        'GROUP_CONCAT(DISTINCT tn.name ORDER BY tn.name SEPARATOR ", ") AS laboratory_tests',
        'tr.remark AS existing_remark',
        'CONCAT(us.userFirstName, " ", us.userLastName) AS client_name',
        'n.nutritionist_name AS nutritionist_name'
        ]);
        $this->db->from('trans_details td');
        $this->db->join('trans_headers th', 'th.trans_id = td.trans_id', 'inner');
        $this->db->join('samples s', 's.id = td.sample_id', 'left');
        $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
        $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
        $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
        $this->db->join('tests t', 't.id = lt.test_id', 'left');
        $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
        $this->db->join('laboratories l', 'l.id = th.laboratory_id', 'left');
        $this->db->join('users us', 'us.userID = th.client_id', 'left');
        $this->db->join('nutritionists n', 'n.id = th.nutritionist_id', 'left');
        $this->db->join('internal_feedmills if', 'if.id = th.internal_id', 'left');
        $this->db->join('commercial_feedmills cf', 'cf.id = th.commercial_id', 'left');

        if (!empty($data['lab_access'])) {
            $labIDs = array_column($data['lab_access'], 'laboratory_id');
            $this->db->where_in('th.laboratory_id', $labIDs);
        } else {
            $this->db->where('th.laboratory_id', 0);
        }

        $this->db->join("
            (
                SELECT tr1.trans_detail_id, tr1.remark
                FROM trans_remarks tr1
                INNER JOIN (
                    SELECT trans_detail_id, MAX(created_at) AS latest_created
                    FROM trans_remarks
                    WHERE trans_detail_status_id = 27
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
                AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 27
            ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->where('td.trans_detail_status_id', 37);

        $this->db->group_by('td.trans_detail_id');

        $this->db->order_by('td.modified_at', 'DESC');

        $all_details = $this->db->get()->result_array();


        $jobs = [];

        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];

                    if (!isset($jobs[$jobId])) {
                        $jobs[$jobId] = [
                            'job_order_no' => $row['job_order_no'],
                            'feedmill' => $row['commercial_feedmill_name'] ?? $row['internal_feedmill_name'],
                            'details' => []
                        ];
                    }
                    $jobs[$jobId]['details'][] = [
                        'sample_name' => $row['sample_name'],
                        'test_name' => $row['laboratory_tests'],   
                        'lab_code' => $row['lab_code'],
                        'test_exec_lab_result' => $row['test_exec_lab_result'],
                        'delivery_date' => $row['delivery_date'],
                        'created_at' => $row['created_at'],
                    ];
        }


        $jobs_indexed = array_values($jobs);

        $data['jobs'] = $jobs_indexed;
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['content'] = $this->load->view('reports/report_feeds_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }


 


 


	// END OF Report Feeds CONTROLLER




}

