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
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

        $data['title'] = 'Test Execution and Data Entry';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
  
        $all_details = $this->main->get_trans_details($data,27,26); // data , TEST EXECUTION STATUS , FINAL PREP STATUS, 

        $jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($jobs[$jobId])) {
                $jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
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
        $data['content'] = $this->load->view('test_execution/test_execution_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
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
            td.lab_code AS labCode,
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

    public function submit_test_exec01()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $test_status = $this->input->post('test_status');
        $date_submitted = $this->input->post('date_submitted');
        $lab_results      = $this->input->post('lab_results');
        $remarks      = $this->input->post('remarks');
        $lead_time     = $this->input->post('lead_time');

   

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

            $reasonID = $reasons[$trans_detail_id] ?? null;
            $remark   = $remarks[$trans_detail_id] ?? null;
            $lab_result = $lab_results[$trans_detail_id] ?? null;


            $updateData = [
                'test_exec_status_id' => $testExecID,
                'test_exec_lab_result' => $lab_result,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];

            if ((int)$testExecID === 29) {
                $updateData['trans_detail_status_id'] = 27;
            }else{
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
                    
                if ((int)$testExecID === 23) {

                    $timestampRow = $this->db->select('created_by')
                        ->from('trans_timestamps')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->where('trans_detail_status_id', 24)
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->row_array();

                    if (!empty($timestampRow)) {
                        $userFromTimestamp = $timestampRow['created_by'];
                        $this->db->select('th.trans_id, th.job_order_no, td.lab_code, u.userEmail as requester_email, u.userFirstName, u.userLastName');
                        $this->db->from('trans_headers th');
                        $this->db->join('trans_details td', 'td.trans_id = th.trans_id');
                        $this->db->join('users u', 'u.userID = ' . (int) $userFromTimestamp, 'left');
                        $this->db->where('td.trans_detail_id', $trans_detail_id);
                        $transHeader = $this->db->get()->row_array();
                        $this->email_format->generateEmailNotification(
                            $transHeader,
                            $trans_detail_id,
                            $statusText,
                            $remark,
                            $userFromTimestamp,
                            'Failed'
                        );
                    }
                }
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Test Execution and Data Entry submitted successfully.'
        ]);
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

	// END OF Test Execution and Data Entry CONTROLLER




}

