<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ResultVerification extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');
          $this->alias = 'resultverification';


    }


    /*  
	module: Result Verification Controller
	desc: Creation of Result Verification Controller
	date created: 11-11-2025
	created by: James
	Change Management #1`
	*/

    public function index() 
    {
         $alias = $this->alias;
        $info = $this->custom_lib->_require_login();
        $data['js_file'] = 'assets/js/preparation.js?v=2.0';
        $data['profile'] = $this->custom_lib->_get_profile();
        $userID = decode($info['userID']);
        $theme = get_user_theme(['a.userID' => $userID], true);

        $data['menuColor'] = $theme->menuColor;
        $data['tableColor'] = $theme->tableColor;
        $data['thColor'] = $theme->thColor;
        $data['btnColor'] = $theme->btnColor;

        $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;
        $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);
        $module_access = $this->custom_lib->module_access($alias);
        if(!$module_access->view){redirect('admin');}
        $data['can_modify'] =(isset($module_access->add) && (int)$module_access->add === 1) ||(isset($module_access->edit) && (int)$module_access->edit === 1);
        $data['lab_access'] = $this->custom_lib->get_lab_access(['ul.userID' => $userID]);

        $data['title'] = 'Result Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $result_veri_stat = 36; // RESULT VERIFICATION STATUS
        $data_review_stat = 33; // DATA REVIEW STATUS
        $test_exec_stat = 27; // TEST EXECUTION STATUS
        $result_veri_remark = 36; // RESULT VERIFICATION REMARK
        $searchValue = "";
        $searchField = "";
        $all_details = $this->main->get_trans_details($data,$result_veri_stat,$data_review_stat,$test_exec_stat,$result_veri_remark,$searchValue,$searchField); // data , RESULT VERIFICATION STATUS , DATA REVIEW STATUS, ,TEST EXECUTION STATUS , RESULT VERIFICATINO STATUS
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

        $data['jobs'] = array_values($jobs);
        $data['attachments'] = $attachments;
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['result_verifications'] = $this->main->get_data('stats', ['status_type_id' => 7], false, 'statusID, statDesc', 'statDesc ASC');
        $data['content'] = $this->load->view('result_verification/result_verification_content', $data , TRUE);
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

    public function submit_result_veri()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $result_verifications = $this->input->post('result_verifications');
        $remarks      = $this->input->post('result_verification_remarks');

        if (empty($result_verifications)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No Final Prep data received.'
            ]);
            return;
        }

        foreach ($result_verifications as $trans_detail_id => $resultVeriID) {
            if (empty($resultVeriID)) continue;

            $remark   = $remarks[$trans_detail_id] ?? null;

            $updateData = [
                'test_result_id' => $resultVeriID,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];

            if ((int)$resultVeriID === 16) {
                $updateData['trans_detail_status_id'] = 27;

            }else{
                $updateData['trans_detail_status_id'] = 37;
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            $statusText = '';
                    if (!empty($resultVeriID)) {
                        $statusRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $resultVeriID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statusRow['statDesc'] ?? '';
            }


            if (!empty($result_prep)) {
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'ResultVerification/submit_result_veri',
                    'logDetail'    => 'Successfully Updated Result Veri ID:' . $trans_detail_id
                ]);

                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                ->get('trans_details')
                                ->row_array();

                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id'] = $trans_detail_id;
                    $historyData['detail_change'] = $statusText;
                    $historyData['trans_detail_status_id'] = 36;
                    $historyData['created_by'] = $userID;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 36,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }
                $statusText = '';
                    if (!empty($resultVeriID)) {
                        $statRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $resultVeriID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statRow['statDesc'] ?? '';
                    } 

                if (!empty($remark)) {
                        $this->main->insert_data('trans_remarks', [
                            'trans_detail_id'        => $trans_detail_id,
                            'trans_detail_status_id' => 36,
                            'remark'                 => $remark,
                            'created_by'             => $userID,
                            'created_at'             => date('Y-m-d H:i:s'),
                        ]); 
                }
                    
                if ((int)$resultVeriID === 16) {
                    $timestampRow = $this->db->select('created_by')
                        ->from('trans_timestamps')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->where('trans_detail_status_id', 33)
                        ->order_by('created_at', 'DESC')
                        ->get()
                        ->row_array();

                    if (!empty($timestampRow)) {
                        $userFromTimestamp = (int) $timestampRow['created_by'];

                        $recipient = $this->db
                            ->select('u.userID, u.userEmail, u.userFirstName, u.userLastName, u.userTypeId')
                            ->from('users u')
                            ->where('u.userID', $userFromTimestamp)
                            ->where('u.userEmail IS NOT NULL AND u.userEmail !=', '')
                            ->get()
                            ->row_array();

                        if (!empty($recipient)) {
                            $transHeader = $this->db
                                ->select('th.trans_id, th.job_order_no, td.ext_lab_code')
                                ->from('trans_headers th')
                                ->join('trans_details td', 'td.trans_id = th.trans_id')
                                ->where('td.trans_detail_id', $trans_detail_id)
                                ->get()
                                ->row_array();

                            if (!empty($transHeader)) {
                                $remark = $remark;
                                $this->email_format->generateEmailNotification(
                                    $transHeader,
                                    $trans_detail_id,
                                    $statusText,
                                    $remark,
                                    $recipient,
                                    'Disapproved'
                                );

                                log_message('info', "Sent 'Failed' notification to {$recipient['userEmail']} for trans_detail_id {$trans_detail_id}");
                            }
                        } else {
                            log_message('warning', "No valid recipient found for userID {$userFromTimestamp} in Failed notification.");
                        }
                    }
                }

            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Result Verification submitted successfully.'
        ]);
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

        $result_veri_stat = 36; // RESULT VERIFICATION STATUS
        $data_review_stat = 33; // DATA REVIEW STATUS
        $test_exec_stat = 27; // TEST EXECUTION STATUS
        $result_veri_remark = 36; // RESULT VERIFICATION REMARK

        $all_details = $this->main->get_trans_details($data, $result_veri_stat, $data_review_stat, $test_exec_stat, $result_veri_remark, $searchValue, $searchField);

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

        $data['jobs'] = array_values($jobs);
        $data['attachments'] = $attachments;

        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['result_verifications'] = $this->main->get_data('stats', ['status_type_id' => 7], false, 'statusID, statDesc', 'statDesc ASC');
        $html = $this->load->view('result_verification/result_verification_container', $data, TRUE);


        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
    }

    

	// END OF Result Verification CONTROLLER




}

