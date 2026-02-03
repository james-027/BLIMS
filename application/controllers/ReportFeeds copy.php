<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
        $userID = decode($info['userID']);

        // Theme and profile data
        $data['js_file'] = 'assets/js/reports.js?v=2.0';
        $data['profile'] = $this->custom_lib->_get_profile();
        $data['menuColor'] = get_user_theme(['a.userID' => $userID], true)->menuColor;
        $data['tableColor'] = get_user_theme(['a.userID' => $userID], true)->tableColor;
        $data['thColor'] = get_user_theme(['a.userID' => $userID], true)->thColor;
        $data['btnColor'] = get_user_theme(['a.userID' => $userID], true)->btnColor;

        $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
        $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);

        $module_access = $this->custom_lib->module_access($alias);
        if(!$module_access->view){ redirect('admin'); }

        $data['can_modify'] = (isset($module_access->add) && (int)$module_access->add === 1) ||
                            (isset($module_access->edit) && (int)$module_access->edit === 1);
        $data['can_download'] = $module_access->dlod;
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

        $data['title'] = 'Report Feeds';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Reports';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

        $selectedFeedmill = $this->input->post('feedmill');
        $selectedDateReceived = $this->input->post('data_received');
        $data['selected_feedmill'] = $selectedFeedmill;

        $internalFeedmills = $this->db
            ->select('id, feedmill_name')
            ->from('internal_feedmills')
            ->where('status_id', 1)
            ->get()
            ->result_array();

        $commercialFeedmills = $this->db
            ->select('id, feedmill_name')
            ->from('commercial_feedmills')
            ->where('status_id', 1)
            ->get()
            ->result_array();

        $data['feedmills'] = array_merge($internalFeedmills, $commercialFeedmills);

        // --- Main query for report ---
        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.laboratory_id',
            'td.trans_detail_id',
            'td.sample_id',
            'lt.lab_test_grouping_id',
            't.test_code',
            'td.lab_test_id',
            'td.test_exec_lab_result',
            'td.ext_lab_code AS lab_code',
            'td.lead_time',
            'td.delivery_date',
            'td.created_at',
            's.sample_name',
            'tt_finalprep.date_received AS latest_timestamp',
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

        // --- Feedmill filter ---
        if (!empty($selectedFeedmill)) {
            $this->db->group_start();
            $this->db->where('if.feedmill_name', $selectedFeedmill);
            $this->db->or_where('cf.feedmill_name', $selectedFeedmill);
            $this->db->group_end();
        }

        if (!empty($data['lab_access'])) {
            $labIDs = array_column($data['lab_access'], 'laboratory_id');
            $this->db->where_in('th.laboratory_id', $labIDs);
        } else {
            $this->db->where('th.laboratory_id', 0);
        }

        $this->db->join("
            (
                SELECT 
                    trans_detail_id,
                    MAX(created_at) AS date_received
                FROM trans_timestamps
                WHERE trans_detail_status_id = 26
                GROUP BY trans_detail_id
            ) tt_finalprep
        ", 'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');

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

        $nirMap = [
        'CP'    => 'NIR_CP',
        'MC'    => 'NIR_MC',
        'FAT'   => 'NIR_FAT',
        'CF' => 'NIR_CF',
        'ASH'   => 'NIR_ASH',
        'CA'    => 'NIR_CA',
        'P'     => 'NIR_P',
        'SALT'  => 'NIR_SALT',
        ];


        foreach ($all_details as $row) {
            $jobId    = $row['trans_id'];
            $sample   = $row['sample_name'];
                      $groupKey = $jobId . '_' . $sample . '_' . $row['lab_code'];


            if (!isset($jobs[$groupKey])) {
                $timestamp = $row['latest_timestamp'] ?? $row['created_at'];
                $timestampUnix = strtotime($timestamp);

                $leadTimeDays = (int)$row['lead_time'];
                $estimatedReleaseDate = date('M d, Y', strtotime("+$leadTimeDays days", $timestampUnix));

                $jobs[$groupKey] = [
                    'job_order_no' => $row['job_order_no'],
                    'feedmill'     => $row['commercial_feedmill_name'] ?? $row['internal_feedmill_name'],
                    'sample_name'  => $sample,
                    'lab_code'     => $row['lab_code'],
                    'test_name' => $row['laboratory_tests'],  
                    'delivery_date'=> $row['delivery_date'],
                    'latest_timestamp' => $row['latest_timestamp'],
                    'week_number'  => date('W', $timestampUnix),
                    'month_name'   => date('F', $timestampUnix),
                    'estimated_release_date' => $estimatedReleaseDate,
                    'NIR_CP' => '',
                    'NIR_MC' => '',
                    'NIR_FAT' => '',
                    'NIR_CF' => '',
                    'NIR_ASH' => '',
                    'NIR_CA' => '',
                    'NIR_P' => '',
                    'NIR_SALT' => '',
                    'CI' => '',
                    'NFE' => '',
                    'ME' => '',
                    'Traditional_ME' => '',
                    'AAfla' => '',
                    'T2' => '',
                    'Zea' => '',
                    'Ochra' => '',
                    'DON' => '',
                    'Hista' => '',
                    'Pan' => '',
                    '601' => '',
                    'PS' => '',
                    'PDI' => '',
                    'Fines' => '',
                    'Density' => '',
                    'Water_Activity' => '',
                    'Formula_Code' => '',
                    'Others' => '',
                    'Remarks' => '',
                ];
            }

                if ((int)$row['lab_test_grouping_id'] === 4) {

                    $testCode = strtoupper(str_replace('NIR-', '', $row['test_code']));

                    if (isset($nirMap[$testCode])) {
                        $nirColumn = $nirMap[$testCode];
                        $jobs[$groupKey][$nirColumn] = $row['test_exec_lab_result'];
                    }
                }

        }




        $data['jobs'] = array_values($jobs);
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['content'] = $this->load->view('reports/report_feeds_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }



        public function index01() 
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
        'td.lead_time',
        'td.delivery_date',
        'td.created_at',
        's.sample_name',
        'tt_finalprep.date_received AS latest_timestamp',
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
                SELECT 
                    trans_detail_id,
                    MAX(created_at) AS date_received
                FROM trans_timestamps
                WHERE trans_detail_status_id = 26
                GROUP BY trans_detail_id
            ) tt_finalprep
        ", 'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');


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
                        'latest_timestamp' => $row['latest_timestamp'],   
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


 

    public function export_csv()
{
    $info = $this->custom_lib->_require_login();
    $userID = decode($info['userID']);

    $this->db->select([
        'th.job_order_no',
        'td.ext_lab_code AS lab_code',
        's.sample_name',
        'td.delivery_date',
        'tt_finalprep.date_received AS date_received',
        'td.lead_time',
        'td.test_exec_lab_result',
        'GROUP_CONCAT(DISTINCT tn.name ORDER BY tn.name SEPARATOR ", ") AS laboratory_tests',
        'if.feedmill_name AS internal_feedmill_name',
        'cf.feedmill_name AS commercial_feedmill_name',
        'td.created_at'
    ]);

    $this->db->from('trans_details td');
    $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
    $this->db->join('samples s', 's.id = td.sample_id', 'left');
    $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id');
    $this->db->join('tests t', 't.id = lt.test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
    $this->db->join('internal_feedmills if', 'if.id = th.internal_id', 'left');
    $this->db->join('commercial_feedmills cf', 'cf.id = th.commercial_id', 'left');

    $this->db->join("
        (
            SELECT trans_detail_id, MAX(created_at) AS date_received
            FROM trans_timestamps
            WHERE trans_detail_status_id = 26
            GROUP BY trans_detail_id
        ) tt_finalprep
    ", 'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');

    $this->db->where('td.trans_detail_status_id', 37);
    $this->db->group_by('td.trans_detail_id');
    $this->db->order_by('td.modified_at', 'DESC');

    $rows = $this->db->get()->result_array();


    $filename = 'Report_Feeds_' . date('Ymd') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    $output = fopen('php://output', 'w');

    /* Excel UTF-8 fix */
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    /* HEADER ROW */
    fputcsv($output, [
        'Feed Type',
        'Sample Name',
        'Feedmill',
        'Laboratory Code',
        'Production / Delivery Date',
        'Date Received',
        'Week #',
        'Month',
        'Job Number',
        'Estimated Release Date',
        'Analysis Requested',
        'Result'
    ]);

    /* DATA ROWS */
    foreach ($rows as $r) {

        $received = $r['date_received'] ?? $r['created_at'];
        $week = date('W', strtotime($received));
        $month = date('F', strtotime($received));
        $estRelease = date('M d, Y', strtotime("+{$r['lead_time']} days", strtotime($received)));

        fputcsv($output, [
            'Feed',
            $r['sample_name'],
            $r['commercial_feedmill_name'] ?? $r['internal_feedmill_name'],
            $r['lab_code'],
            date('M d, Y', strtotime($r['delivery_date'])),
            date('M d, Y', strtotime($received)),
            $week,
            $month,
            $r['job_order_no'],
            $estRelease,
            $r['laboratory_tests'],
            $r['test_exec_lab_result']
        ]);
    }

    fclose($output);
    exit;
}


private function build_report_query($filters = [])
{
    $this->db->select([
        'th.trans_id',
        'th.job_order_no',
        'td.trans_detail_id',
        'td.ext_lab_code AS lab_code',
        's.sample_name',
        'td.delivery_date',
        'td.lead_time',
        'td.test_exec_lab_result',
        'GROUP_CONCAT(DISTINCT tn.name ORDER BY tn.name SEPARATOR ", ") AS laboratory_tests',
        'if.feedmill_name AS internal_feedmill_name',
        'cf.feedmill_name AS commercial_feedmill_name',
        'tt_finalprep.date_received',
        'td.created_at',
        'td.modified_at'
    ]);

    $this->db->from('trans_details td');
    $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
    $this->db->join('samples s', 's.id = td.sample_id', 'left');
    $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id');
    $this->db->join('tests t', 't.id = lt.test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
    $this->db->join('internal_feedmills if', 'if.id = th.internal_id', 'left');
    $this->db->join('commercial_feedmills cf', 'cf.id = th.commercial_id', 'left');

    $this->db->join("
        (
            SELECT trans_detail_id, MAX(created_at) AS date_received
            FROM trans_timestamps
            WHERE trans_detail_status_id = 26
            GROUP BY trans_detail_id
        ) tt_finalprep
    ", 'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');

    /* ==============================
       BASE CONDITION
       ============================== */
    $this->db->where('td.trans_detail_status_id', 37);

    /* ==============================
       FILTERS
       ============================== */

    if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
        $this->db->where('DATE(tt_finalprep.date_received) >=', $filters['date_from']);
        $this->db->where('DATE(tt_finalprep.date_received) <=', $filters['date_to']);
    }

    if (!empty($filters['laboratory_id'])) {
        $this->db->where('th.laboratory_id', $filters['laboratory_id']);
    }

    if (!empty($filters['feedmill'])) {
        if ($filters['feedmill'] === 'internal') {
            $this->db->where('th.internal_id IS NOT NULL', null, false);
        }
        if ($filters['feedmill'] === 'commercial') {
            $this->db->where('th.commercial_id IS NOT NULL', null, false);
        }
    }

    $this->db->group_by('td.trans_detail_id');
    $this->db->order_by('td.modified_at', 'DESC');
}



 


	// END OF Report Feeds CONTROLLER




}

