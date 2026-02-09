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

    // Theme & profile
    $data['js_file'] = 'assets/js/reports.js?v=2.0';
    $data['profile'] = $this->custom_lib->_get_profile();
    $theme = get_user_theme(['a.userID' => $userID], true);
    $data['menuColor'] = $theme->menuColor;
    $data['tableColor'] = $theme->tableColor;
    $data['thColor'] = $theme->thColor;
    $data['btnColor'] = $theme->btnColor;

    $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
    $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);

    $module_access = $this->custom_lib->module_access($alias);
    if (!$module_access->view) redirect('admin');

    $data['can_modify'] = (!empty($module_access->add) && (int)$module_access->add === 1) 
                        || (!empty($module_access->edit) && (int)$module_access->edit === 1);
    $data['can_download'] = $module_access->dlod;
    $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

    $data['title'] = 'Report Feeds';
    $data['menu_title'] = '';
    $data['parent_title'] = 'Reports';
    $data['controller'] = $this->controller;
    $data['userID'] = $userID;
    $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);
    $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

    $selectedFeedmill = $this->input->post('feedmill');
    $data['selected_feedmill'] = $selectedFeedmill;

    $selectedJobNumber = $this->input->post('job_number');
    $deliveryFrom = $this->input->post('delivery_date_from');
    $deliveryTo   = $this->input->post('delivery_date_to');
    $dateReceivedFrom = $this->input->post('date_received_from');
    $dateReceivedTo   = $this->input->post('date_received_to');
    $selectedWeek = $this->input->post('week');
    $selectedMonth = $this->input->post('month');

    $internalFeedmills = $this->db->select('id, feedmill_name')
        ->from('internal_feedmills')->where('status_id', 1)->get()->result_array();
    $commercialFeedmills = $this->db->select('id, feedmill_name')
        ->from('commercial_feedmills')->where('status_id', 1)->get()->result_array();
    $data['feedmills'] = array_merge($internalFeedmills, $commercialFeedmills);


    $data['selected_job_number'] = $selectedJobNumber;
    $data['selected_delivery_date_from'] = $deliveryFrom;
    $data['selected_delivery_date_to'] = $deliveryTo;
    $data['selected_date_received_from'] = $dateReceivedFrom;
    $data['selected_date_received_to'] = $dateReceivedTo;
    $data['selected_week'] = $selectedWeek;
    $data['selected_month'] = $selectedMonth;


    $this->db->select([
        'td.trans_detail_id',
        'td.trans_id',
        'th.job_order_no',
        'th.laboratory_id',
        'td.sample_id',
        'lt.lab_test_grouping_id',
        't.test_code',
        'td.lab_test_id',
        'td.test_exec_lab_result',
        'td.ext_lab_code AS lab_code',
        'lt.test_param_id',
        'td.lead_time',
        'td.delivery_date',
        'td.created_at',
        'tn.name AS test_name',
        's.sample_name',
        't.test_name_id',
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
    $this->db->join('samples s', 's.id = td.sample_id', 'inner');
    $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
    $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
    $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
    $this->db->join('tests t', 't.id = lt.test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
    $this->db->join('users us', 'us.userID = th.client_id', 'left');
    $this->db->join('nutritionists n', 'n.id = th.nutritionist_id', 'left');
    $this->db->join('internal_feedmills if', 'if.id = th.internal_id', 'left');
    $this->db->join('commercial_feedmills cf', 'cf.id = th.commercial_id', 'left');

    if (!empty($selectedFeedmill)) {

    if (in_array("ALL", $selectedFeedmill)) {
    }else{
   $this->db->group_start();
        $this->db->where_in('if.feedmill_name', $selectedFeedmill);
        $this->db->or_where_in('cf.feedmill_name', $selectedFeedmill);
        $this->db->group_end();
    }
     
    }




    // Lab access filter
    if (!empty($data['lab_access'])) {
        $labIDs = array_column($data['lab_access'], 'laboratory_id');
        $this->db->where_in('th.laboratory_id', $labIDs);
    } else {
        $this->db->where('th.laboratory_id', 0);
    }

    // Latest timestamp
    $this->db->join("(SELECT trans_detail_id, MAX(created_at) AS date_received
                    FROM trans_timestamps
                    WHERE trans_detail_status_id = 26
                    GROUP BY trans_detail_id) tt_finalprep",
                    'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');

    // Latest remark
    $this->db->join("(SELECT tr1.trans_detail_id, tr1.remark
                    FROM trans_remarks tr1
                    INNER JOIN (
                        SELECT trans_detail_id, MAX(created_at) AS latest_created
                        FROM trans_remarks
                        WHERE trans_detail_status_id = 27
                        GROUP BY trans_detail_id
                    ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id AND tr1.created_at = tr2.latest_created
                    WHERE tr1.trans_detail_status_id = 27
                    ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');


    // --- Job Number filter ---
    if (!empty($selectedJobNumber)) {
        $this->db->where_in('th.job_order_no', $selectedJobNumber);
    }

    
    // --- Delivery Date range filter ---
    if (!empty($deliveryFrom)) {
        $this->db->where('td.delivery_date >=', $deliveryFrom);
    }
    if (!empty($deliveryTo)) {
        $this->db->where('td.delivery_date <=', $deliveryTo);
    }

    // --- Date Received range filter ---
    if (!empty($dateReceivedFrom)) {
        $this->db->where('DATE(tt_finalprep.date_received) >=', $dateReceivedFrom);
    }
    if (!empty($dateReceivedTo)) {
        $this->db->where('DATE(tt_finalprep.date_received) <=', $dateReceivedTo);
    }

    // --- Week# filter ---
    if (!empty($selectedWeek)) {
        $weekConditions = [];
        foreach ($selectedWeek as $w) {
            $weekConditions[] = "WEEK(IFNULL(tt_finalprep.date_received, td.created_at), 1) = " . (int)$w;
        }
        $this->db->where('(' . implode(' OR ', $weekConditions) . ')');
    }

    // --- Month filter ---
    if (!empty($selectedMonth)) {
        $monthConditions = [];
        foreach ($selectedMonth as $m) {
            $monthConditions[] = "MONTH(IFNULL(tt_finalprep.date_received, td.created_at)) = " . (int)$m;
        }
        $this->db->where('(' . implode(' OR ', $monthConditions) . ')');
    }


    // Only include trans_details with status 37
    $this->db->where('td.trans_detail_status_id', 37);
    // Only include sample_type_id = 3
    $this->db->where('s.sample_type_id', 3);
    $this->db->group_by('td.trans_detail_id');
    $this->db->order_by('td.modified_at', 'DESC');

    $all_details = $this->db->get()->result_array();

    $allHeaders = [];
    $testHeaders     = [];
    $computedHeaders = [
    'WET-CI' => false,
    'NFE'    => false,
    'ME'     => false,
    'TRADITIONAL_ME'     => false,
    ];

    foreach ($all_details as $row) {

        if (empty($row['test_code'])) {
            continue;
        }
        $testCode = strtoupper($row['test_code']);

        $testHeaders[$testCode] = true;

        if (
            (int)$row['test_param_id'] === 8 &&
            $testCode === 'WET-SALT'
        ) {
            $computedHeaders['WET-CI'] = true;
        }

        if (in_array((int)$row['test_param_id'], [2,3,4,5,6])) {
        $computedHeaders['NFE'] = true;
        $computedHeaders['ME']  = true; 
        $computedHeaders['TRADITIONAL_ME']  = true; 
        }

    }

        $finalComputed = [];

        if ($computedHeaders['WET-CI']) {
            $finalComputed[] = 'WET-CI';
        }

        if ($computedHeaders['NFE']) {
            $finalComputed[] = 'NFE';
        }

            
        if ($computedHeaders['ME']) {
            $finalComputed[] = 'ME';
        }

        if ($computedHeaders['TRADITIONAL_ME']) {
            $finalComputed[] = 'TRADITIONAL_ME';
        }



        $data['dynamic_test_headers'] = array_merge(
            array_keys($testHeaders),
            $finalComputed
        );


    // --- Map results dynamically ---
        $jobs = [];
        foreach ($all_details as $row) {
            $groupKey = $row['trans_id'] . '_' . $row['sample_name'] . '_' . $row['lab_code'];

            if (!isset($jobs[$groupKey])) {
                $jobs[$groupKey]['_nfe_nir'] = [];
                $jobs[$groupKey]['_nfe_fallback'] = [];
                $jobs[$groupKey]['_me_nir'] = [];
                $jobs[$groupKey]['_me_fallback'] = [];
                $jobs[$groupKey]['_traditional_me_nir'] = [];
                $jobs[$groupKey]['_traditional_me_fallback'] = [];

                $timestamp = $row['latest_timestamp'] ?? $row['created_at'];
                $timestampUnix = strtotime($timestamp);
                $leadTimeDays = (int)$row['lead_time'];
                $estimatedReleaseDate = date('M d, Y', strtotime("+$leadTimeDays days", $timestampUnix));

                $jobs[$groupKey] = [
                    'job_order_no' => $row['job_order_no'],
                    'feedmill' => $row['commercial_feedmill_name'] ?? $row['internal_feedmill_name'],
                    'sample_name' => $row['sample_name'],
                    'test_name' => $row['test_name'],
                    'lab_code' => $row['lab_code'],
                    'delivery_date' => $row['delivery_date'],
                    'latest_timestamp' => $row['latest_timestamp'],
                    'week_number' => date('W', $timestampUnix),
                    'month_name' => date('F', $timestampUnix),
                    'estimated_release_date' => $estimatedReleaseDate,
                ];

                // Initialize dynamic test columns as empty
            foreach ($data['dynamic_test_headers'] as $code) {
                $jobs[$groupKey][$code] = '';
            }
            }

            // Fill the test result
            $testCode = strtoupper($row['test_code']);
            $cleanValue = $this->sanitize_result($row['test_exec_lab_result']);
            $jobs[$groupKey][$testCode] = $cleanValue;

            // Collect NFE components
            if (
                in_array((int)$row['test_param_id'], [2,3,4,5,6]) &&
                $cleanValue !== null
            ) {
                if ((int)$row['test_name_id'] === 11) {
                    // NIR
                    $jobs[$groupKey]['_nfe_nir'][(int)$row['test_param_id']] = $cleanValue;
                } elseif ((int)$row['test_name_id'] === 60) {
                    // fallback
                    $jobs[$groupKey]['_nfe_fallback'][(int)$row['test_param_id']] = $cleanValue;
                }



            }


            // Collect ME components
            if (
                    in_array((int)$row['test_param_id'], [3,4]) && // CP, CF
                    $cleanValue !== null
                ) {
                    if ((int)$row['test_name_id'] === 11) {
                        $jobs[$groupKey]['_me_nir'][(int)$row['test_param_id']] =
                            $cleanValue;
                    } elseif ((int)$row['test_name_id'] === 60) {
                        $jobs[$groupKey]['_me_fallback'][(int)$row['test_param_id']] =
                            $cleanValue;
                    }
                }


                // Collect TRADITIONAL_ME components
                    if (
                        in_array((int)$row['test_param_id'], [3,4]) && // CP, CF
                        $cleanValue !== null
                    ) {
                        if ((int)$row['test_name_id'] === 11) {
                            $jobs[$groupKey]['_traditional_me_nir'][(int)$row['test_param_id']] = $cleanValue;
                        } elseif ((int)$row['test_name_id'] === 60) {
                            $jobs[$groupKey]['_traditional_me_fallback'][(int)$row['test_param_id']] = $cleanValue;
                        }
                    }


                // Compute WET-CI
                    if (
                        (int)$row['test_param_id'] === 8 &&
                        strtoupper($row['test_code']) === 'WET-SALT' &&
                        $cleanValue !== null
                    ) {
                        $jobs[$groupKey]['WET-CI'] = round($cleanValue * 0.606605, 2);
                    }


        }


        foreach ($jobs as &$job) {

            $useParams = [];

            if (!empty($job['_nfe_nir'])) {
                $useParams = $job['_nfe_nir'];
            }
            elseif (!empty($job['_nfe_fallback'])) {
                $useParams = $job['_nfe_fallback'];
            }

            if (!empty($useParams)) {
                $sum = 0;
                foreach ([2,3,4,5,6] as $pid) {
                    $sum += $useParams[$pid] ?? 0;
                }

                $job['NFE'] = round(100 - $sum, 2);
            } else {
                $job['NFE'] = '';
            }

            // Cleanup temp keys
            unset($job['_nfe_nir'], $job['_nfe_fallback']);
    }

    foreach ($jobs as &$job) {

        if ($job['NFE'] === '' || !is_numeric($job['NFE'])) {
            $job['ME'] = '';
            continue;
        }

        $useParams = [];

        if (!empty($job['_me_nir'])) {
            $useParams = $job['_me_nir'];
        } elseif (!empty($job['_me_fallback'])) {
            $useParams = $job['_me_fallback'];
        }

        if (
            isset($useParams[3], $useParams[4])
        ) {
            $job['ME'] = round(
                10 * (
                    ($useParams[3] * 3.5) +   // Crude Protein
                    ($useParams[4] * 8.5) +   // Crude Fat
                    ($job['NFE'] * 3.5)
                )
                
            );
        } else {
            $job['ME'] = '';
        }

            unset($job['_me_nir'], $job['_me_fallback']);
    }


        foreach ($jobs as &$job) {

        if ($job['NFE'] === '' || !is_numeric($job['NFE'])) {
            $job['TRADITIONAL_ME'] = '';
            continue;
        }

        $useParams = [];

        if (!empty($job['_traditional_me_nir'])) {
            $useParams = $job['_traditional_me_nir'];
        } elseif (!empty($job['_traditional_me_fallback'])) {
            $useParams = $job['_traditional_me_fallback'];
        }

        if (
            isset($useParams[3], $useParams[4])
        ) {
            $job['TRADITIONAL_ME'] = round(
                10 * (
                    ($useParams[3] * 4) +   // Crude Protein
                    ($useParams[4] * 9) +   // Crude Fat
                    ($job['NFE'] * 4)
                )
                
            );
        } else {
            $job['TRADITIONAL_ME'] = '';
        }

            unset($job['_traditional_me_nir'], $job['_traditional_me_fallback']);
    }
    

        unset($job);





    $data['jobs'] = array_values($jobs);
    $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
    $data['content'] = $this->load->view('reports/report_feeds_content', $data, TRUE);
    $this->load->view('admin/templates', $data);
}

    public function index01() 
{
    $alias = $this->alias;
    $info = $this->custom_lib->_require_login();
    $userID = decode($info['userID']);

    // Theme & profile
    $data['js_file'] = 'assets/js/reports.js?v=2.0';
    $data['profile'] = $this->custom_lib->_get_profile();
    $theme = get_user_theme(['a.userID' => $userID], true);
    $data['menuColor'] = $theme->menuColor;
    $data['tableColor'] = $theme->tableColor;
    $data['thColor'] = $theme->thColor;
    $data['btnColor'] = $theme->btnColor;

    $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
    $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);

    $module_access = $this->custom_lib->module_access($alias);
    if (!$module_access->view) redirect('admin');

    $data['can_modify'] = (!empty($module_access->add) && (int)$module_access->add === 1) 
                        || (!empty($module_access->edit) && (int)$module_access->edit === 1);
    $data['can_download'] = $module_access->dlod;
    $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

    $data['title'] = 'Report Feeds';
    $data['menu_title'] = '';
    $data['parent_title'] = 'Reports';
    $data['controller'] = $this->controller;
    $data['userID'] = $userID;
    $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);
    $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

    $selectedFeedmill = $this->input->post('feedmill');
    $data['selected_feedmill'] = $selectedFeedmill;

    $internalFeedmills = $this->db->select('id, feedmill_name')
        ->from('internal_feedmills')->where('status_id', 1)->get()->result_array();
    $commercialFeedmills = $this->db->select('id, feedmill_name')
        ->from('commercial_feedmills')->where('status_id', 1)->get()->result_array();
    $data['feedmills'] = array_merge($internalFeedmills, $commercialFeedmills);

    $this->db->select([
        'td.trans_detail_id',
        'td.trans_id',
        'th.job_order_no',
        'th.laboratory_id',
        'td.sample_id',
        'lt.lab_test_grouping_id',
        't.test_code',
        'td.lab_test_id',
        'td.test_exec_lab_result',
        'td.ext_lab_code AS lab_code',
        'lt.test_param_id',
        'td.lead_time',
        'td.delivery_date',
        'td.created_at',
        'tn.name AS test_name',
        's.sample_name',
        't.test_name_id',
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
    $this->db->join('samples s', 's.id = td.sample_id', 'inner');
    $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
    $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
    $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
    $this->db->join('tests t', 't.id = lt.test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
    $this->db->join('users us', 'us.userID = th.client_id', 'left');
    $this->db->join('nutritionists n', 'n.id = th.nutritionist_id', 'left');
    $this->db->join('internal_feedmills if', 'if.id = th.internal_id', 'left');
    $this->db->join('commercial_feedmills cf', 'cf.id = th.commercial_id', 'left');

    if (!empty($selectedFeedmill)) {
        $this->db->group_start();
        $this->db->where_in('if.feedmill_name', $selectedFeedmill);
        $this->db->or_where_in('cf.feedmill_name', $selectedFeedmill);
        $this->db->group_end();
    }

    // Lab access filter
    if (!empty($data['lab_access'])) {
        $labIDs = array_column($data['lab_access'], 'laboratory_id');
        $this->db->where_in('th.laboratory_id', $labIDs);
    } else {
        $this->db->where('th.laboratory_id', 0);
    }

    // Latest timestamp
    $this->db->join("(SELECT trans_detail_id, MAX(created_at) AS date_received
                    FROM trans_timestamps
                    WHERE trans_detail_status_id = 26
                    GROUP BY trans_detail_id) tt_finalprep",
                    'tt_finalprep.trans_detail_id = td.trans_detail_id', 'left');

    // Latest remark
    $this->db->join("(SELECT tr1.trans_detail_id, tr1.remark
                    FROM trans_remarks tr1
                    INNER JOIN (
                        SELECT trans_detail_id, MAX(created_at) AS latest_created
                        FROM trans_remarks
                        WHERE trans_detail_status_id = 27
                        GROUP BY trans_detail_id
                    ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id AND tr1.created_at = tr2.latest_created
                    WHERE tr1.trans_detail_status_id = 27
                    ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');

    // Only include trans_details with status 37
    $this->db->where('td.trans_detail_status_id', 37);
    // Only include sample_type_id = 3
    $this->db->where('s.sample_type_id', 3);
    $this->db->group_by('td.trans_detail_id');
    $this->db->order_by('td.modified_at', 'DESC');

    $all_details = $this->db->get()->result_array();

    $allHeaders = [];
    $testHeaders     = [];
    $computedHeaders = [
    'WET-CI' => false,
    'NFE'    => false,
    'ME'     => false,
    'TRADITIONAL_ME'     => false,
    ];

    foreach ($all_details as $row) {

        if (empty($row['test_code'])) {
            continue;
        }

        $testCode = strtoupper($row['test_code']);

        $testHeaders[$testCode] = true;

        if (
            (int)$row['test_param_id'] === 8 &&
            $testCode === 'WET-SALT'
        ) {
            $computedHeaders['WET-CI'] = true;
        }

        if (in_array((int)$row['test_param_id'], [2,3,4,5,6])) {
        $computedHeaders['NFE'] = true;
        $computedHeaders['ME']  = true; 
        $computedHeaders['TRADITIONAL_ME']  = true; 
        }

    }

        $finalComputed = [];

        if ($computedHeaders['WET-CI']) {
            $finalComputed[] = 'WET-CI';
        }

        if ($computedHeaders['NFE']) {
            $finalComputed[] = 'NFE';
        }

            
        if ($computedHeaders['ME']) {
            $finalComputed[] = 'ME';
        }

        if ($computedHeaders['TRADITIONAL_ME']) {
            $finalComputed[] = 'TRADITIONAL_ME';
        }



        $data['dynamic_test_headers'] = array_merge(
            array_keys($testHeaders),
            $finalComputed
        );


    // --- Map results dynamically ---
        $jobs = [];
        foreach ($all_details as $row) {
            $groupKey = $row['trans_id'] . '_' . $row['sample_name'] . '_' . $row['lab_code'];

            if (!isset($jobs[$groupKey])) {
                $jobs[$groupKey]['_nfe_nir'] = [];
                $jobs[$groupKey]['_nfe_fallback'] = [];
                $jobs[$groupKey]['_me_nir'] = [];
                $jobs[$groupKey]['_me_fallback'] = [];
                $jobs[$groupKey]['_traditional_me_nir'] = [];
                $jobs[$groupKey]['_traditional_me_fallback'] = [];

                $timestamp = $row['latest_timestamp'] ?? $row['created_at'];
                $timestampUnix = strtotime($timestamp);
                $leadTimeDays = (int)$row['lead_time'];
                $estimatedReleaseDate = date('M d, Y', strtotime("+$leadTimeDays days", $timestampUnix));

                $jobs[$groupKey] = [
                    'job_order_no' => $row['job_order_no'],
                    'feedmill' => $row['commercial_feedmill_name'] ?? $row['internal_feedmill_name'],
                    'sample_name' => $row['sample_name'],
                    'test_name' => $row['test_name'],
                    'lab_code' => $row['lab_code'],
                    'delivery_date' => $row['delivery_date'],
                    'latest_timestamp' => $row['latest_timestamp'],
                    'week_number' => date('W', $timestampUnix),
                    'month_name' => date('F', $timestampUnix),
                    'estimated_release_date' => $estimatedReleaseDate,
                ];

                // Initialize dynamic test columns as empty
            foreach ($data['dynamic_test_headers'] as $code) {
                $jobs[$groupKey][$code] = '';
            }
            }

            // Fill the test result
            $testCode = strtoupper($row['test_code']);
            $cleanValue = $this->sanitize_result($row['test_exec_lab_result']);
            $jobs[$groupKey][$testCode] = $cleanValue;

            // Collect NFE components
            if (
                in_array((int)$row['test_param_id'], [2,3,4,5,6]) &&
                $cleanValue !== null
            ) {
                if ((int)$row['test_name_id'] === 11) {
                    // NIR
                    $jobs[$groupKey]['_nfe_nir'][(int)$row['test_param_id']] = $cleanValue;
                } elseif ((int)$row['test_name_id'] === 60) {
                    // fallback
                    $jobs[$groupKey]['_nfe_fallback'][(int)$row['test_param_id']] = $cleanValue;
                }



            }


            // Collect ME components
            if (
                    in_array((int)$row['test_param_id'], [3,4]) && // CP, CF
                    $cleanValue !== null
                ) {
                    if ((int)$row['test_name_id'] === 11) {
                        $jobs[$groupKey]['_me_nir'][(int)$row['test_param_id']] =
                            $cleanValue;
                    } elseif ((int)$row['test_name_id'] === 60) {
                        $jobs[$groupKey]['_me_fallback'][(int)$row['test_param_id']] =
                            $cleanValue;
                    }
                }


                // Collect TRADITIONAL_ME components
                    if (
                        in_array((int)$row['test_param_id'], [3,4]) && // CP, CF
                        $cleanValue !== null
                    ) {
                        if ((int)$row['test_name_id'] === 11) {
                            $jobs[$groupKey]['_traditional_me_nir'][(int)$row['test_param_id']] = $cleanValue;
                        } elseif ((int)$row['test_name_id'] === 60) {
                            $jobs[$groupKey]['_traditional_me_fallback'][(int)$row['test_param_id']] = $cleanValue;
                        }
                    }


                // Compute WET-CI
                    if (
                        (int)$row['test_param_id'] === 8 &&
                        strtoupper($row['test_code']) === 'WET-SALT' &&
                        $cleanValue !== null
                    ) {
                        $jobs[$groupKey]['WET-CI'] = round($cleanValue * 0.606605, 2);
                    }


        }


        foreach ($jobs as &$job) {

            $useParams = [];

            if (!empty($job['_nfe_nir'])) {
                $useParams = $job['_nfe_nir'];
            }
            elseif (!empty($job['_nfe_fallback'])) {
                $useParams = $job['_nfe_fallback'];
            }

            if (!empty($useParams)) {
                $sum = 0;
                foreach ([2,3,4,5,6] as $pid) {
                    $sum += $useParams[$pid] ?? 0;
                }

                $job['NFE'] = round(100 - $sum, 2);
            } else {
                $job['NFE'] = '';
            }

            // Cleanup temp keys
            unset($job['_nfe_nir'], $job['_nfe_fallback']);
    }

    foreach ($jobs as &$job) {

        if ($job['NFE'] === '' || !is_numeric($job['NFE'])) {
            $job['ME'] = '';
            continue;
        }

        $useParams = [];

        if (!empty($job['_me_nir'])) {
            $useParams = $job['_me_nir'];
        } elseif (!empty($job['_me_fallback'])) {
            $useParams = $job['_me_fallback'];
        }

        if (
            isset($useParams[3], $useParams[4])
        ) {
            $job['ME'] = round(
                10 * (
                    ($useParams[3] * 3.5) +   // Crude Protein
                    ($useParams[4] * 8.5) +   // Crude Fat
                    ($job['NFE'] * 3.5)
                )
                
            );
        } else {
            $job['ME'] = '';
        }

            unset($job['_me_nir'], $job['_me_fallback']);
    }


        foreach ($jobs as &$job) {

        if ($job['NFE'] === '' || !is_numeric($job['NFE'])) {
            $job['TRADITIONAL_ME'] = '';
            continue;
        }

        $useParams = [];

        if (!empty($job['_traditional_me_nir'])) {
            $useParams = $job['_traditional_me_nir'];
        } elseif (!empty($job['_traditional_me_fallback'])) {
            $useParams = $job['_traditional_me_fallback'];
        }

        if (
            isset($useParams[3], $useParams[4])
        ) {
            $job['TRADITIONAL_ME'] = round(
                10 * (
                    ($useParams[3] * 4) +   // Crude Protein
                    ($useParams[4] * 9) +   // Crude Fat
                    ($job['NFE'] * 4)
                )
                
            );
        } else {
            $job['TRADITIONAL_ME'] = '';
        }

            unset($job['_traditional_me_nir'], $job['_traditional_me_fallback']);
    }
    

        unset($job);





    $data['jobs'] = array_values($jobs);
    $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
    $data['content'] = $this->load->view('reports/report_feeds_content', $data, TRUE);
    $this->load->view('admin/templates', $data);
}


public function export_csv()
{
    $alias = $this->alias;
    $info = $this->custom_lib->_require_login();
    $userID = decode($info['userID']);

    // Get the same filters from POST (or GET)
    $selectedFeedmill     = $this->input->post('feedmill') ?? $this->input->get('feedmill');
    $selectedJobNumber    = $this->input->post('job_number') ?? $this->input->get('job_number');
    $deliveryFrom         = $this->input->post('delivery_date_from') ?? $this->input->get('delivery_date_from');
    $deliveryTo           = $this->input->post('delivery_date_to') ?? $this->input->get('delivery_date_to');
    $dateReceivedFrom     = $this->input->post('date_received_from') ?? $this->input->get('date_received_from');
    $dateReceivedTo       = $this->input->post('date_received_to') ?? $this->input->get('date_received_to');
    $selectedWeek         = $this->input->post('week') ?? $this->input->get('week');
    $selectedMonth        = $this->input->post('month') ?? $this->input->get('month');

    // Run the same query logic as index() to get $jobs
    $jobs = $this->get_filtered_jobs($selectedFeedmill, $selectedJobNumber, $deliveryFrom, $deliveryTo, $dateReceivedFrom, $dateReceivedTo, $selectedWeek, $selectedMonth);

    // Prepare CSV
    $filename = "report_feeds_" . date('Ymd_His') . ".csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    $output = fopen('php://output', 'w');

    if (!empty($jobs)) {
        // CSV header
        $header = array_keys($jobs[0]);
        fputcsv($output, $header);

        // CSV rows
        foreach ($jobs as $row) {
            fputcsv($output, $row);
        }
    }

    fclose($output);
    exit;
}





private function sanitize_result($value)
{
    if ($value === null) {
        return null;
    }

    // Remove percentage sign
    $value = str_replace('%', '', $value);

    // Handle ± (take only the first number)
    if (strpos($value, '±') !== false) {
        $parts = explode('±', $value);
        $value = trim($parts[0]);
    }

    // Final trim
    $value = trim($value);

    return is_numeric($value) ? (float)$value : null;
}



 


	// END OF Report Feeds CONTROLLER




}

