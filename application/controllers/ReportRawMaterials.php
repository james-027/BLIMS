<?php

defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');
        require_once(APPPATH.'third_party/PHPExcel-1.8/Classes/PHPExcel.php');




class ReportRawMaterials extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
        $this->alias = 'reportfeeds';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');

	}


    /*  
	module: Report Raw Materials Controller
	desc: Creation of  Report Raw Materials 
	date created: 02-05-2025
	created by: James
	Change Management #1`
	*/


    public function index()
    {
        $alias = $this->alias;
        $info = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

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

        $data['title'] = 'Report Raw Materials';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Reports';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $data['laboratories'] = $this->main->get_data('laboratories', ['status_id' => 1], false, 'id, identifier_code', 'identifier_code ASC');
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');

        $data = array_merge($data, $this->prepare_report_data($data['lab_access']));
        $data['content'] = $this->load->view('reports/report_raw_materials_content', $data, TRUE);
        $this->load->view('admin/templates', $data);
    }


    private function prepare_report_data($lab_access)
    {
        $data = [];
        $hasPost = !empty($_POST);

        $filter_keys = [
            'feedmill', 'job_number', 'laboratory', 'week', 'month', 'supplier',
            'delivery_date_from', 'delivery_date_to',
            'date_received_from', 'date_received_to'
        ];

        $filters = [];
        foreach ($filter_keys as $key) {
            $value = $this->input->post($key);
            if (in_array($key, ['feedmill', 'job_number', 'laboratory', 'week', 'month', 'supplier'])) {
                $filters[$key] = is_array($value)
                    ? $value
                    : (!empty($value) ? explode(',', $value) : []);
            } else {
                $filters[$key] = $value ?: null;
            }

            $data['selected_' . $key] = $filters[$key];
        }

        
        $internalFeedmills = $this->db->select('id, feedmill_name')
            ->from('internal_feedmills')
            ->where('status_id', 1)
            ->get()
            ->result_array();

        $commercialFeedmills = $this->db->select('id, feedmill_name')
            ->from('commercial_feedmills')
            ->where('status_id', 1)
            ->get()
            ->result_array();

        $data['feedmills'] = array_merge($internalFeedmills, $commercialFeedmills);

        $sample_type_id = 5; 
        $all_details = $this->main->fetch_report_trans_details($filters, $lab_access, $sample_type_id);
            if (!$hasPost) {
        $headers = $this->compute_dynamic_headers($all_details);
        $this->session->set_userdata('locked_report_headers', $headers);
    } else {
        $headers = $this->session->userdata('locked_report_headers');
    }
        $data['dynamic_test_headers'] = $headers;
        $data['jobs'] = $this->map_job_results($all_details, $headers);

        return $data;
    }



    private function compute_dynamic_headers($all_details)
    {
        $testHeaders = [];
        $computedHeaders = [
            'WET-CI' => false,
            'NFE' => false,

        ];

        foreach ($all_details as $row) {
            if (empty($row['test_code'])) continue;
            $testCode = strtoupper($row['test_code']);
            $testHeaders[$testCode] = true;


            $paramName = $this->normalize_text($row['test_param_name'] ?? '');
            $testName  = $this->normalize_text($row['test_name'] ?? '');


        if ($paramName === 'SALT,%' && $testCode === 'WET-SALT') {
            $computedHeaders['WET-CI'] = true;
        }

        if (in_array($paramName, [
            'MOISTURE,%',
            'CRUDEPROTEIN,%',
            'CRUDEFAT,%',
            'CRUDEFIBER,%',
            'ASH,%',
        ])) {
            $computedHeaders['NFE'] = true;
        }
        }

        $finalComputed = [];
        foreach ($computedHeaders as $code => $active) {
            if ($active) $finalComputed[] = $code;
        }

        $extraColumns = ['STARCH(WET)','FFA ( OLEIC )','FFA ( LINOLEIC)','POTASSIUM','PDI','MESH # 10','MESH # 20','MESH # 30','MESH # 40','PAN','TOTAL','GOOD GRAINS','SMALL GRAINS','SHRUNKEN','SPROUT','SHRUNKEN','MOLDY','CRACKED','INSECT DAMAGED','HEAT DAMAGED','DIFF COLORS','SMUT','BLACK TIPS GRAIN','FOREIGN MATTER','Remarks'];

        return array_merge(array_keys($testHeaders), $finalComputed, $extraColumns);
    }


    private function map_job_results($all_details, $dynamicHeaders)
    {
        $jobs = [];

        foreach ($all_details as $row) {

            $groupKey = $row['trans_id'] . '_' . $row['sample_name'] . '_' . $row['lab_code'];

            if (!isset($jobs[$groupKey])) {

                $timestamp = $row['original_timestamp'] ?? $row['created_at'];
                $timestampUnix = strtotime($timestamp);
                $leadTimeDays = (int)$row['lead_time'];

                $jobs[$groupKey] = [
                    'job_order_no' => $row['job_order_no'],
                    'supplier_id'   => $row['supplier_id'],
                    'supplier_name' => $row['supplier_name'],
                    'feedmill' => $row['commercial_feedmill_name'] ?? $row['internal_feedmill_name'],
                    'sample_name' => $row['sample_name'],
                    'plate_number' => $row['plate_number'],
                    'test_name' => $row['test_name'],
                    'lab_code' => $row['lab_code'],
                    'delivery_date' => $row['delivery_date'],
                    'latest_timestamp' => $row['original_timestamp'],
                    'actual_date' => $row['original_actual_date'],
                    'week_number' => date('W', $timestampUnix),
                    'month_name' => date('F', $timestampUnix),
                    'estimated_release_date' => date('M d, Y', strtotime("+$leadTimeDays days", $timestampUnix)),

                    '_calc' => [
                        'nfe' => [],
                    ]
                ];

                foreach ($dynamicHeaders as $code) {
                    $jobs[$groupKey][$code] = '';
                }
            }

            $testCode  = strtoupper($row['test_code']);
            $paramName = $this->normalize_text($row['test_param_name'] ?? '');
            $testName  = $this->normalize_text($row['test_name'] ?? '');

            $result = $this->sanitize_result($row['test_exec_lab_result']);

            $rawValue     = $result['raw'];
            $numericValue = $result['numeric'];

            // Store RAW for display
            $jobs[$groupKey][$testCode] = $rawValue;

        
            if ($numericValue !== null) {

                if (in_array($paramName, [
                    'MOISTURE,%',
                    'CRUDEPROTEIN,%',
                    'CRUDEFAT,%',
                    'CRUDEFIBER,%',
                    'ASH,%',
                ])) {

                    $source = ($testName === 'NIRPROXIMATE') ? 'nir' : 'fallback';
                    $jobs[$groupKey]['_calc']['nfe'][$source][$paramName] = $numericValue;
                }

                if (
                    $paramName === 'SALT,%' &&
                    $testCode === 'WET-SALT'
                ) {
                    $jobs[$groupKey]['WET-CI'] = round($numericValue * 0.606605, 2);
                }
            }
        }

    
        foreach ($jobs as &$job) {

            $nfeSource = !empty($job['_calc']['nfe']['nir'])
                ? $job['_calc']['nfe']['nir']
                : ($job['_calc']['nfe']['fallback'] ?? []);

            if (!empty($nfeSource)) {

                $sum = 0;

                foreach ([
                    'MOISTURE,%',
                    'CRUDEPROTEIN,%',
                    'CRUDEFAT,%',
                    'CRUDEFIBER,%',
                    'ASH,%',
                ] as $p) {
                    $sum += $nfeSource[$p] ?? 0;
                }

                $job['NFE'] = round(100 - $sum, 2);

            } else {
                $job['NFE'] = '';
            }

            unset($job['_calc']);
        }

        unset($job);

        return array_values($jobs);
    }

    private function sanitize_result($value)
    {
        if ($value === null) {
            return [
                'raw' => null,
                'numeric' => null
            ];
        }

        $rawValue = trim($value);

        // Remove %
        $clean = str_replace('%', '', $rawValue);

        // Handle ±
        if (strpos($clean, '±') !== false) {
            $parts = explode('±', $clean);
            $clean = trim($parts[0]);
        }

        $numeric = is_numeric($clean) ? (float)$clean : null;

        return [
            'raw' => $rawValue,
            'numeric' => $numeric
        ];
    }

    private function normalize_text($name)
{
    return strtoupper(
        preg_replace('/\s+/', '', trim($name))
    );
}


	// END OF Report Raw Materials CONTROLLER




}

