<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

class TestExecution extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
          $this->alias = 'testexecution';
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');

    }


    /*  
	module: Test Execution and Data Entry Controller
	desc: Creation of Test Execution and Data Entry
	date created: 11-04-2025
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
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

        $data['title'] = 'Test Execution and Data Entry';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $test_exec_status = 27; // TEST EXECUTION STATUS
        $final_prep = 26; // FINAL PREP STATUS
        
        $searchValue = "";
        $searchField = "";

        $all_details = $this->main->get_trans_details($data,$test_exec_status,$final_prep,null,null,$searchValue ,$searchField); // data , TEST EXECUTION STATUS , FINAL PREP STATUS, 
        $jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($jobs[$jobId])) {
                $jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'laboratory_id' => $row['laboratory_id'],
                    'trans_id' => $row['trans_id'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';

            $jobs[$jobId]['samples'][] = $row;
        }


        $transIds = array_keys($jobs);
        $attachments = [];
        if (!empty($transIds)) {
            $this->db->select('trans_id, filename, filepath');
            $this->db->from('attachments');
            $this->db->where_in('trans_id', $transIds);
            $result = $this->db->get()->result_array();
            foreach ($result as $attachment) {
                $attachments[$attachment['trans_id']][] = $attachment;
            }
        }

            $jobs_indexed = array_values($jobs);


        $data['jobs'] = $jobs_indexed;
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 4], false, 'statusID, statDesc', 'statDesc ASC');
        $data['plate_numbers']   = $this->main->get_data('plate_numbers', ['status_id' => 1], false, 'id, plate_number', 'plate_number ASC');
		$data['suppliers']      = $this->main->get_data('suppliers', ['status_id' => 1], false, 'id, supplier_name', 'supplier_name ASC');
		$data['batches']      = $this->main->get_data('batch_numbers', ['status_id' => 1], false, 'id, batch_number', 'batch_number ASC');
		$data['samples']      = $this->main->get_data('samples', ['status_id' => 1], false, 'id, sample_name', 'sample_name ASC');
        $data['content'] = $this->load->view('test_execution/test_execution_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }

       public function search_details()
    {
        $alias = $this->alias;   
        $info = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $searchValue = $this->input->get_post('search') ?? '';
        $searchField = $this->input->get_post('field') ?? '';


        $theme = get_user_theme(['a.userID' => $userID], true);
        $data['thColor'] = $theme->thColor;
        $data['btnColor'] = $theme->btnColor;
        $data['tableColor'] = $theme->tableColor;
        $data['menuColor'] = $theme->menuColor;
        $data['profile'] = $this->custom_lib->_get_profile();
        $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
        $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);
        $module_access = $this->custom_lib->module_access($alias);
        $data['can_modify'] =(isset($module_access->add) && (int)$module_access->add === 1) ||(isset($module_access->edit) && (int)$module_access->edit === 1);
        $data['title'] = 'Result Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;

        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);
 
        $test_exec_status = 27; // TEST EXECUTION STATUS
        $final_prep = 26; // FINAL PREP STATUS
        $all_details = $this->main->get_trans_details($data,$test_exec_status,$final_prep,null,null,$searchValue,$searchField);

        $jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($jobs[$jobId])) {
                $jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'laboratory_id' => $row['laboratory_id'],
                    'trans_id' => $row['trans_id'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';
            $jobs[$jobId]['samples'][] = $row;
        }

        $transIds = array_keys($jobs);
        $attachments = [];
        if (!empty($transIds)) {
            $this->db->select('trans_id, filename, filepath');
            $this->db->from('attachments');
            $this->db->where_in('trans_id', $transIds);
            $result = $this->db->get()->result_array();
            foreach ($result as $attachment) {
                $attachments[$attachment['trans_id']][] = $attachment;
            }
        }

        $data['jobs'] = array_values($jobs);
        $data['attachments'] = $attachments;

        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 4], false, 'statusID, statDesc', 'statDesc ASC');
        $data['plate_numbers']   = $this->main->get_data('plate_numbers', ['status_id' => 1], false, 'id, plate_number', 'plate_number ASC');
		$data['suppliers']      = $this->main->get_data('suppliers', ['status_id' => 1], false, 'id, supplier_name', 'supplier_name ASC');
		$data['batches']      = $this->main->get_data('batch_numbers', ['status_id' => 1], false, 'id, batch_number', 'batch_number ASC');
		$data['samples']      = $this->main->get_data('samples', ['status_id' => 1], false, 'id, sample_name', 'sample_name ASC');
        $html = $this->load->view('test_execution/test_execution_container', $data, TRUE);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
    }

    public function get_logs($trans_detail_id)
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $join = [
            'stats s' => ['s.statusID = th.trans_detail_status_id' => 'LEFT'],
            'users u' => ['u.userID = th.created_by' => 'LEFT'],
            'trans_details td' => ['td.trans_detail_id = th.trans_detail_id' => 'LEFT'],
            'trans_headers thd' => ['thd.trans_id = th.trans_id' => 'LEFT'],
        ];

        $select = "
            th.trans_history_id,
            th.trans_detail_id,
            td.ext_lab_code AS labCode,
            thd.job_order_no AS jo,
            s.statDesc AS module,
            th.detail_change AS action,
            (
                SELECT tr1.remark 
                FROM trans_remarks tr1
                WHERE tr1.trans_detail_id = th.trans_detail_id
                AND tr1.trans_detail_status_id = th.trans_detail_status_id
                AND tr1.created_at <= th.created_at
                ORDER BY tr1.created_at DESC
                LIMIT 1
            ) AS latest_remark,
            th.created_at AS log_date,
            CONCAT(u.userFirstName, ' ', u.userLastName) AS performed_by
        ";

        $recFound = $this->main->get_join_datatables(
            'trans_history th',
            $join,
            false,
            'th.created_at DESC',
            false,
            $select,
            ['th.trans_detail_id' => $trans_detail_id]
        );

        $data = [];
        foreach ($recFound->result() as $log) {
            $data[] = [
                'module'       => $log->module ?? '-',
                'jo'           => $log->jo ?? '-',
                'lab_code'     => $log->labCode ?? '-',
                'action'       => $log->action ?? '-',
                'remarks'      => $log->latest_remark ?? '-',
                'performed_by' => $log->performed_by ?? 'Auto Generated by System',
                'log_date'     => !empty($log->log_date) ? date('F d, Y h:i A', strtotime($log->log_date)) : '-',
            ];
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function submit_test_exec()
    {
        
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $test_status = $this->input->post('test_status');
        $date_submitted = $this->input->post('date_submitted');
        $lab_results = $this->input->post('lab_results');
        $remarks = $this->input->post('remarks');
        $lead_time = $this->input->post('lead_time');

        foreach ($date_submitted as $key => $value) {
            if (!empty($value)) {
                $date_submitted[$key] = date('Y-m-d', strtotime($value));
            }
        }

        if (empty($test_status)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No Final Prep data received.'
            ]);
            return;
        }

        foreach ($test_status as $trans_detail_id => $testExecID) {
            if (empty($testExecID)) continue;

            $remark = $remarks[$trans_detail_id] ?? null;
            $lab_result = $lab_results[$trans_detail_id] ?? null;

            $updateData = [
                'test_exec_status_id' => $testExecID,
                'test_exec_lab_result' => $lab_result,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];

            if ((int)$testExecID === 29) {
                $updateData['trans_detail_status_id'] = 27;
            } else {
                $updateData['trans_detail_status_id'] = 33;
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            $statusText = '';
            if (!empty($testExecID)) {
                $statRow = $this->db->select('statDesc')
                    ->from('stats')
                    ->where('statusID', $testExecID)
                    ->get()
                    ->row_array();
                $statusText = $statRow['statDesc'] ?? '';
            }

            if (!empty($result_prep)) {
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'TestExecution/submit_test_exec',
                    'logDetail'    => 'Successfully Updated Test Exec Prep ID:' . $trans_detail_id
                ]);

                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                    ->get('trans_details')
                    ->row_array();

                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id'] = $trans_detail_id;
                    $historyData['trans_detail_status_id'] = 27;
                    $historyData['detail_change'] = $statusText;
                    $historyData['created_by'] = $userID;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 27,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }

                if (!empty($remark)) {
                    $this->main->insert_data('trans_remarks', [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 27,
                        'remark'                 => $remark,
                        'created_by'             => $userID,
                        'created_at'             => date('Y-m-d H:i:s'),
                    ]);
                }

            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Test Execution and Data Entry submitted successfully.'
        ]);
    }

    	public function get_lab_tests($laboratory_id = null)
	{
		
		if (!$laboratory_id) {
			echo json_encode(['tests' => [], 'samples' => []]);
			return;
		}

		$this->db->select('lt.test_id, tn.name as test_name, t.test_code,lt.sample_type_id, st.sample_type_name');
		$this->db->from('lab_tests lt');
		$this->db->join('tests t', 'lt.test_id = t.id', 'left');
		$this->db->join('test_names tn', 't.test_name_id = tn.id', 'left');
		$this->db->join('sample_types st', 'lt.sample_type_id = st.id', 'left');
		$this->db->where('lt.laboratory_id', $laboratory_id);
		$query = $this->db->get();
		$data = $query->result();

		$tests = [];
		$samples = [];

		foreach ($data as $row) {
			$tests[$row->test_id] = $row->test_code;
			$samples[$row->sample_type_id] = $row->sample_type_name;
		}

		echo json_encode([
			'tests' => array_map(function ($id, $name) {
				return ['id' => $id, 'name' => $name];
			}, array_keys($tests), $tests),
			'samples' => array_map(function ($id, $name) {
				return ['id' => $id, 'name' => $name];
			}, array_keys($samples), $samples),
		]);
	}

    public function get_detail_data($detailId)
    {
        $this->db->select([
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'sup.supplier_name',
            'p.plate_number',
            'b.batch_number',
            'td.lab_test_id',
            'td.sample_type_id',
            'td.coa_flag',
            'td.lead_time AS lead_time'
        ]);
        $this->db->from('trans_details td');
        $this->db->join('samples s', 's.id = td.sample_id', 'left');
        $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
        $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id', 'left');
        $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
        $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
        $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
        $this->db->where('td.trans_detail_id', $detailId);


        $detail = $this->db->get()->row_array();

        // Format delivery_date for <input type="date">
        if (!empty($detail['delivery_date'])) {
            $detail['delivery_date'] = date('Y-m-d', strtotime($detail['delivery_date']));
        }

        echo json_encode($detail);
    }

    public function get_lead_times()
	{

		$laboratoryId = $this->input->get('laboratory_id');
		$testId = $this->input->get('test_id');
        

		$leadTimes = $this->db
			->where('laboratory_id', $laboratoryId)
			->where('test_id', $testId)
			->get('lab_tests')
			->row();

		$response = [];

		if ($leadTimes) {
			if (!is_null($leadTimes->lead_regular)) {
				$response[] = [
					'label' => 'Regular',
					'value' => $leadTimes->lead_regular,
				];
			}

			if (!is_null($leadTimes->lead_rush)) {
				$response[] = [
					'label' => 'Rush',
					'value' => $leadTimes->lead_rush,
				];
			}
		}

		echo json_encode($response);
	}

    public function replicate_sample_details()
    {
        $info = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $this->load->helper('url');

        $trans_id        = $this->input->post('transId');  
        $detail_id        = $this->input->post('detailId');  
        $labTests        = $this->input->post('testCode'); 
        $leadTimeTypes   = $this->input->post('leadTimeType'); 
        $typeOfSamples   = $this->input->post('typeOfSample'); 
        $coaRequired     = $this->input->post('coaRequired'); 

        $existing = $this->db
                ->where('trans_detail_id', $detail_id)
                ->get('trans_details')
                ->row();
        $existing = $this->db
        ->where('trans_detail_id', $detail_id)
        ->get('trans_details')
        ->row();

        if ($existing) {
            foreach ($labTests as $index => $sampleTypeId) {
                $coa_flag = isset($coaRequired[$index]) && $coaRequired[$index] ? 'Y' : 'N';

                $detailData = [
                    'trans_id'           => $trans_id,
                    'sample_id'          => $existing->sample_id,
                    'sample_type_id'     => $existing->sample_type_id,
                    'lab_test_id'        => $labTests[$index] ?? null,
                    'delivery_date'      => $existing->delivery_date,
                    'supplier_id'        => $existing->supplier_id,
                    'plate_number_id'    => $existing->plate_number_id,
                    'batch_number_id'    => $existing->batch_number_id,
                    'lead_time'          => $leadTimeTypes[$index] ?? null,
                    'coa_flag'           => $coa_flag,
                    'lab_code'           => $existing->lab_code,
                    'ext_lab_code'       => $existing->ext_lab_code,
                    'created_at'         => date('Y-m-d H:i:s'),
                    'created_by'         => $userID,
                    'trans_detail_status_id' => 27
                ];

                $details_result = $this->main->insert_data('trans_details', $detailData, TRUE);

                if (!empty($details_result['id'])) {
                    $user_logs = [
                        'userID'       => $userID,
                        'userFullName' => $info['userFullName'],
                        'logTS'        => date_now(),
                        'page'         => 'Registration/submit_registration',
                        'logDetail'    => 'Successfully added Trans Detail ID:' . $details_result['id']
                    ];
                    $this->main->user_logs($user_logs);

                    $historyData = $detailData;
                    $historyData['trans_detail_id'] = $details_result['id'];
                    $historyData['detail_change'] = "This Detail is Replicated from {$existing->ext_lab_code}";
                    $historyData['trans_detail_status_id'] = 27;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $historyData['created_by'] = $userID;
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $details_result['id'],
                        'trans_detail_status_id' => 26,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                        'updated_by'             => null,
                        'modified_at'            => null
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Sample details replicated successfully!'
        ]);
        exit;

    }

    
	// END OF Test Execution and Data Entry CONTROLLER




}

