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
        $data = $this->prepare_report_data();

        $data['content'] = $this->load->view('reports/report_raw_materials_content', $data, TRUE);
        $this->load->view('admin/templates', $data);
    }


    private function prepare_report_data()
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

        $data['title'] = 'Report Raw Materials';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Reports';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

        $feedmill = $this->input->post('feedmill');
        $job_number = $this->input->post('job_number');
        $week = $this->input->post('week');
        $month = $this->input->post('month');
        $supplier = $this->input->post('supplier');

        $filters = [
            'feedmill' => is_array($feedmill) ? $feedmill : (!empty($feedmill) ? explode(',', $feedmill) : []),
            'job_number' => is_array($job_number) ? $job_number : (!empty($job_number) ? explode(',', $job_number) : []),
            'delivery_date_from' => $this->input->post('delivery_date_from'),
            'delivery_date_to' => $this->input->post('delivery_date_to'),
            'date_received_from' => $this->input->post('date_received_from'),
            'date_received_to' => $this->input->post('date_received_to'),
            'week' => is_array($week) ? $week : (!empty($week) ? explode(',', $week) : []),
            'month' => is_array($month) ? $month : (!empty($month) ? explode(',', $month) : []),
            'supplier' => is_array($supplier) ? $supplier : (!empty($supplier) ? explode(',', $supplier) : []),
        ];

        $data['selected_feedmill'] = $filters['feedmill'];
        $data['selected_job_number'] = $filters['job_number'];
        $data['selected_delivery_date_from'] = $filters['delivery_date_from'];
        $data['selected_delivery_date_to'] = $filters['delivery_date_to'];
        $data['selected_date_received_from'] = $filters['date_received_from'];
        $data['selected_date_received_to'] = $filters['date_received_to'];
        $data['selected_week'] = $filters['week'];
        $data['selected_month'] = $filters['month'];
        $data['selected_supplier'] = $filters['supplier'];

        $internalFeedmills = $this->db->select('id, feedmill_name')
            ->from('internal_feedmills')->where('status_id', 1)->get()->result_array();
        $commercialFeedmills = $this->db->select('id, feedmill_name')
            ->from('commercial_feedmills')->where('status_id', 1)->get()->result_array();
        $data['feedmills'] = array_merge($internalFeedmills, $commercialFeedmills);

        $sample_type_id = 5; // Raw Materials
        $all_details = $this->main->fetch_report_trans_details($filters, $data['lab_access'], $sample_type_id);
        
        // --- Compute dynamic headers ---
        $data['dynamic_test_headers'] = $this->compute_dynamic_headers($all_details);
        $data['jobs'] = $this->map_job_results($all_details, $data['dynamic_test_headers']);

        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');

        return $data;
    }



    public function datatable()
{
    // Clear any previous output
    ob_clean();
    header('Content-Type: application/json');

    // Get POST data
    $post = $this->input->post();

    // Get logged-in user
    $info   = $this->custom_lib->_require_login();
    $userID = decode($info['userID']);

    // Fetch all labs this user can access
    $lab_access = $this->db
        ->select('laboratory_id')
        ->from('userslabs')
        ->where('userID', $userID)
        ->get()
        ->result_array();

    $labIDs = array_column($lab_access, 'laboratory_id'); // Extract lab IDs

    $draw   = intval($post['draw'] ?? 1);
    $start  = intval($post['start'] ?? 0);
    $length = intval($post['length'] ?? 10);
    $search = $post['search']['value'] ?? '';

    // Filters from form
    $filters = [
        'feedmill' => isset($post['feedmill']) ? (array)$post['feedmill'] : [],
        'job_number' => isset($post['job_number']) ? (array)$post['job_number'] : [],
        'week' => isset($post['week']) ? (array)$post['week'] : [],
        'month' => isset($post['month']) ? (array)$post['month'] : [],
        'supplier' => isset($post['supplier']) ? (array)$post['supplier'] : [],
        'delivery_date_from' => $post['delivery_date_from'] ?? null,
        'delivery_date_to' => $post['delivery_date_to'] ?? null,
        'date_received_from' => $post['date_received_from'] ?? null,
        'date_received_to' => $post['date_received_to'] ?? null,
    ];

    $sample_type_id = $post['sample_type_id'] ?? null;

    // 🔹 Fetch all details first (no limit) to compute dynamic headers
    $all_details = $this->main->get_datatable_trans_details($filters, $labIDs, $sample_type_id);

    // Compute dynamic headers
    $dynamicHeaders = $this->compute_dynamic_headers($all_details);

    // Map jobs with dynamic test values
    $jobs = $this->map_job_results($all_details, $dynamicHeaders);

    // If no sample type or no lab access, return empty
    if (!$sample_type_id || empty($labIDs)) {
        echo json_encode([
            "draw" => $draw,
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "dynamic_test_headers" => $dynamicHeaders
        ]);
        exit;
    }

    // 🔹 Get total record count for this user and sample type
    $recordsTotal = $this->main->count_all_trans_details($labIDs, $sample_type_id);

    // 🔹 Get filtered count with search & filters
    $recordsFiltered = $this->main->count_filtered_trans_details($filters, $labIDs, $sample_type_id, $search);

    // 🔹 Fetch paginated data
    $paged_details = $this->main->get_datatable_trans_details(
        $filters,
        $labIDs,
        $sample_type_id,
        $search,
        $length,
        $start
    );

    // Map paginated jobs with dynamic test values
    $paged_jobs = $this->map_job_results($paged_details, $dynamicHeaders);

    // Return JSON response for DataTables
    echo json_encode([
        "draw" => $draw,
        "recordsTotal" => $recordsTotal,
        "recordsFiltered" => $recordsFiltered,
        "data" => $paged_jobs,
        "dynamic_test_headers" => $dynamicHeaders
    ]);
    exit;
}

public function fetch_report_trans()
{
    try {
        // IMPORTANT: prevent HTML output
        ob_clean();
        header('Content-Type: application/json');

        $filters = $this->input->post(); // DataTables sends POST
        $lab_access = $this->session->userdata('lab_access');
        $sample_type_id = $filters['sample_type_id'] ?? null;

        if (!$sample_type_id) {
            echo json_encode([
                "draw" => intval($filters['draw'] ?? 1),
                "recordsTotal" => 0,
                "recordsFiltered" => 0,
                "data" => [],
                "error" => "Sample type is required"
            ]);
            exit;
        }

        // Fetch data
        $data = $this->main->fetch_report_trans_details(
            $filters,
            $lab_access,
            $sample_type_id
        );

        // DataTables expects these keys
        echo json_encode([
            "draw" => intval($filters['draw'] ?? 1),
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        ]);
        exit;

    } catch (Throwable $e) {
        // NEVER let PHP error break JSON
        echo json_encode([
            "draw" => intval($_POST['draw'] ?? 1),
            "recordsTotal" => 0,
            "recordsFiltered" => 0,
            "data" => [],
            "error" => $e->getMessage()
        ]);
        exit;
    }
}


    /**
     * Fetch trans_details with all joins & filters
     */
  

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

            if ((int)$row['test_param_id'] === 8 && $testCode === 'WET-SALT') {
                $computedHeaders['WET-CI'] = true;
            }

            if (in_array((int)$row['test_param_id'], [2,3,4,5,6])) {
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
                $timestamp = $row['latest_timestamp'] ?? $row['created_at'];
                $timestampUnix = strtotime($timestamp);
                $leadTimeDays = (int)$row['lead_time'];
                $estimatedReleaseDate = date('M d, Y', strtotime("+$leadTimeDays days", $timestampUnix));

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
                    'latest_timestamp' => $row['latest_timestamp'],
                    'week_number' => date('W', $timestampUnix),
                    'month_name' => date('F', $timestampUnix),
                    'estimated_release_date' => $estimatedReleaseDate,
                    'class' => $row['class'],
                    '_nfe_nir' => [],
                    '_nfe_fallback' => [],
                    '_me_nir' => [],
                    '_me_fallback' => [],
                    '_traditional_me_nir' => [],
                    '_traditional_me_fallback' => [],
                ];

                foreach ($dynamicHeaders as $code) {
                    $jobs[$groupKey][$code] = '';
                }
            }

            $testCode = strtoupper($row['test_code']);
            $cleanValue = $this->sanitize_result($row['test_exec_lab_result']);
            $jobs[$groupKey][$testCode] = $cleanValue;

            // Collect NFE
            if (in_array((int)$row['test_param_id'], [2,3,4,5,6]) && $cleanValue !== null) {
                if ((int)$row['test_name_id'] === 11) $jobs[$groupKey]['_nfe_nir'][(int)$row['test_param_id']] = $cleanValue;
                if ((int)$row['test_name_id'] === 60) $jobs[$groupKey]['_nfe_fallback'][(int)$row['test_param_id']] = $cleanValue;
            }

            if ((int)$row['test_param_id'] === 8 && strtoupper($row['test_code']) === 'WET-SALT' && $cleanValue !== null) {
                $jobs[$groupKey]['WET-CI'] = round($cleanValue * 0.606605, 2);
            }
        }

        // Compute NFE
        foreach ($jobs as &$job) {
            $nfeSource = !empty($job['_nfe_nir']) ? $job['_nfe_nir'] : $job['_nfe_fallback'];
            $sum = 0;
            foreach ([2,3,4,5,6] as $pid) $sum += $nfeSource[$pid] ?? 0;
            $job['NFE'] = !empty($nfeSource) ? round(100 - $sum, 2) : '';


            // Cleanup temp keys
            unset($job['_nfe_nir'], $job['_nfe_fallback']);
        }
        unset($job);

        return array_values($jobs);
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

