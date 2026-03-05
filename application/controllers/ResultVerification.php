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
        $data['result_verifications'] = $this->main->get_data('stats', ['status_type_id' => 7], false, 'statusID, statDesc', 'statDesc ASC', ['statusID' => 35]  );
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
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_WARNING);
    header('Content-Type: application/json');

    $info = $this->custom_lib->_require_login();
    if (!$info) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'Unauthorized'
        ]);
        exit;
    }

    $userID = decode($info['userID']);

    $result_verifications = (array) $this->input->post('result_verifications');
    $remarks = (array) $this->input->post('result_verification_remarks');

    if (empty($result_verifications)) {
        echo json_encode([
            'status'  => 'error',
            'message' => 'No changes detected.'
        ]);
        exit;
    }

    foreach ($result_verifications as $trans_detail_id => $resultVeriID) {

        $trans_detail_id = (int) $trans_detail_id;
        $resultVeriID    = (int) $resultVeriID;
        $remark          = trim($remarks[$trans_detail_id] ?? '');

        if ($resultVeriID === 0 && $remark === '') {
            continue;
        }

        $current = $this->db
            ->select('test_result_id')
            ->from('trans_details')
            ->where('trans_detail_id', $trans_detail_id)
            ->get()
            ->row_array();

        if (!$current) {
            continue;
        }

        if ((int)$current['test_result_id'] === $resultVeriID && $remark === '') {
            continue;
        }

        //16 - Disapproved
        //35 - Re-Analysis
        //27 - Test Execution
        //37 - Result Verification
        $newStatus = ((int)$resultVeriID === 16 || (int)$resultVeriID === 35) ? 27 : 37;

        $updateData = [
            'test_result_id'          => $resultVeriID,
            'trans_detail_status_id'  => $newStatus,
            'modified_at'             => date('Y-m-d H:i:s'),
            'updated_by'              => $userID
        ];

        $updated = $this->main->update_data(
            'trans_details',
            $updateData,
            ['trans_detail_id' => $trans_detail_id]
        );

        if (!$updated) {
            continue;
        }

        $statusText = '';
        $statusRow = $this->db
            ->select('statDesc')
            ->from('stats')
            ->where('statusID', $resultVeriID)
            ->get()
            ->row_array();

        $statusText = $statusRow['statDesc'] ?? '';

        $this->main->user_logs([
            'userID'       => $userID,
            'userFullName' => $info['userFullName'],
            'logTS'        => date_now(),
            'page'         => 'ResultVerification/submit_result_veri',
            'logDetail'    => 'Updated Result Verification ID: ' . $trans_detail_id
        ]);

        $detail = $this->db
            ->where('trans_detail_id', $trans_detail_id)
            ->get('trans_details')
            ->row_array();

        if ($detail) {
            unset($detail['id']);

            $detail['trans_detail_id']        = $trans_detail_id;
            $detail['detail_change']          = $statusText;
            $detail['trans_detail_status_id'] = 36;
            $detail['created_by']             = $userID;
            $detail['created_at']             = date('Y-m-d H:i:s');

            $this->main->insert_data('trans_history', $detail);

            $this->main->insert_data('trans_timestamps', [
                'trans_detail_id'        => $trans_detail_id,
                'trans_detail_status_id' => 36,
                'status_id'              => 1,
                'created_at'             => date('Y-m-d H:i:s'),
                'lead_ts_window_start'   => $this->getCutoffTimestamp(),
                'created_by'             => $userID,
            ]);
        }

        if ($remark !== '') {
            $this->main->insert_data('trans_remarks', [
                'trans_detail_id'        => $trans_detail_id,
                'trans_detail_status_id' => 36,
                'remark'                 => $remark,
                'created_by'             => $userID,
                'created_at'             => date('Y-m-d H:i:s'),
            ]);
        }

        if ($newStatus === 27) {

            $notificationHeader = ($resultVeriID === 16) ? 'Disapproved' : 'Re-Analysis';

            $timestampRow = $this->db
                ->select('created_by')
                ->from('trans_timestamps')
                ->where('trans_detail_id', $trans_detail_id)
                ->where('trans_detail_status_id', 27)
                ->order_by('created_at', 'DESC')
                ->get()
                ->row_array();

            if (!empty($timestampRow)) {

                $recipient = $this->db
                    ->select('userID, userEmail, userFirstName, userLastName')
                    ->from('users')
                    ->where('userID', (int)$timestampRow['created_by'])
                    ->where('userEmail IS NOT NULL AND userEmail !=', '')
                    ->get()
                    ->row_array();

                if ($recipient) {

                    $transHeader = $this->db
                        ->select('th.trans_id, th.job_order_no, td.ext_lab_code')
                        ->from('trans_headers th')
                        ->join('trans_details td', 'td.trans_id = th.trans_id')
                        ->where('td.trans_detail_id', $trans_detail_id)
                        ->get()
                        ->row_array();

                    if ($transHeader) {
                        $this->email_format->generateEmailNotification(
                            $transHeader,
                            $trans_detail_id,
                            $statusText,
                            $remark,
                            $recipient,
                            $notificationHeader
                        );
                    }
                }
            }
        }
    }

    echo json_encode([
        'status'  => 'success',
        'message' => 'Result Verification submitted successfully.'
    ]);
    exit;
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
                    'laboratory_id' => $row['laboratory_id'],
                    'trans_id' => $row['trans_id'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';
            $jobs[$jobId]['samples'][] = $row;      
        }


        foreach ($jobs as $jobId => &$job) {

            $this->db->where('trans_id', $jobId);
            $this->db->group_start();  
            $this->db->where_not_in('test_status_id', [23, 25]);
            $this->db->or_where('test_status_id IS NULL', null, false);
            $this->db->group_end(); 
            $total = $this->db->count_all_results('trans_details');

            $this->db->where('trans_id', $jobId);
            $this->db->where('is_released', 1);
            $this->db->group_start();  
            $this->db->where_not_in('test_status_id', [23, 25]);
            $this->db->or_where('test_status_id IS NULL', null, false);
            $this->db->group_end();
            $released = $this->db->count_all_results('trans_details');


            $job['is_all_released'] = ($total > 0 && $total == $released);

            if (!empty($job['samples'])) {

                $labStatus = [];

                foreach ($job['samples'] as $sample) {

                    if (in_array($sample['test_status_id'], [23, 25])) {
                        continue;
                    }

                    $lab = $sample['lab_code'];

                    if (!isset($labStatus[$lab])) {
                        $labStatus[$lab] = ['total' => 0, 'released' => 0];
                    }

                    $labStatus[$lab]['total']++;

                    if (!empty($sample['is_released'])) {
                        $labStatus[$lab]['released']++;
                    }
                }

                foreach ($job['samples'] as &$sample) {

                    if (in_array($sample['test_status_id'], [23, 25])) {
                        $sample['replicate_disabled'] = true;
                        continue;
                    }

                    $lab = $sample['lab_code'];

                    if (isset($labStatus[$lab])) {
                        $sample['replicate_disabled'] =
                            ($labStatus[$lab]['total'] > 0 &&
                            $labStatus[$lab]['total'] == $labStatus[$lab]['released']);
                    } else {
                        $sample['replicate_disabled'] = false; 
                    }
                }
                unset($sample);
                usort($job['samples'], function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
            }
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
        $data['is_all_released'] = true OR false;
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['result_verifications'] = $this->main->get_data('stats', ['status_type_id' => 7], false, 'statusID, statDesc', 'statDesc ASC', ['statusID' => 35]  );
        $html = $this->load->view('result_verification/result_verification_container', $data, TRUE);


        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
    }
    

        private function getCutoffTimestamp()
    {
        $now = new DateTime('now');

        $cutoff = new DateTime($now->format('Y-m-d') . ' 12:00:00');

        if ($now >= $cutoff) {
            $now->modify('+1 day');
        }

        return $now->format('Y-m-d H:i:s');
    }
    

	// END OF Result Verification CONTROLLER




}

