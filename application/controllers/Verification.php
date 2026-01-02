<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

class Verification extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
		$this->alias = 'verification';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
    	$this->load->library('email_format');

	}


    /*  
	module: Verification Controller
	desc: Creation of Verification Registration
	date created: 10-22-2025
	created by: James
	Change Management #1`
	*/

    public function index() 
    {

        $alias = $this->alias;
        $info = $this->custom_lib->_require_login();

        $data['js_file'] = 'assets/js/verification.js?v=2.0';
        $data['profile'] = $this->custom_lib->_get_profile();
        $data['menuColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->menuColor;
        $data['tableColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->tableColor;
        $data['thColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->thColor;
        $data['btnColor'] = get_user_theme(['a.userID' => decode($info['userID'])], true)->btnColor;

        $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;

        $userID = decode($info['userID']);
        $data['available_access'] = $this->custom_lib->_get_available_access(['userID' => $userID]);
        $data['lab_access'] = $this->custom_lib->get_lab_access(['userID' => $userID]);

        $module_access = $this->custom_lib->module_access($alias);
        $data['new_button'] = '<div class="row pl-3">';
        $btn_class = 'btn btn-primary shadow-sm'.$data['btnColor'].' mr-2 mb-2';
        $data['new_button'] .= '';
        $data['new_button'] .= '</div>';

        if (!$module_access->view) { redirect('admin'); }
        $data['can_modify'] =(isset($module_access->add) && (int)$module_access->add === 1) ||(isset($module_access->edit) && (int)$module_access->edit === 1);
        $data['title'] = 'Ongoing Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Verification';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $verification_status = 20; //VERIFICATION STATUS
        $searchValue = "";
        $searchField = "";
        $all_details = $this->main->get_trans_details($data,$verification_status,null,null,null,$searchValue,$searchField); // data , VERIFICATION STATUS
        $verification_jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($verification_jobs[$jobId])) {
                $verification_jobs[$jobId] = [
                    'trans_id' => $row['trans_id'],
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';

            $verification_jobs[$jobId]['samples'][] = $row;
        }

             foreach ($verification_jobs as $jobId => &$job) {
            if (!empty($job['samples'])) {
                usort($job['samples'], function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']); // latest first
                });
            }
        }
        unset($job); // break reference

        $transIds = array_keys($verification_jobs);
        $attachments = [];
        if (!empty($transIds)) {
            $this->db->select(['trans_id', 'filename', 'filepath', 'original_name']);
            $this->db->from('attachments');
            $this->db->where_in('trans_id', $transIds);
            $result = $this->db->get()->result_array();
            foreach ($result as $attachment) {
                $attachments[$attachment['trans_id']][] = $attachment;
            }
        }

        $verification_jobs_indexed = array_values($verification_jobs);

        usort($verification_jobs_indexed, function($a, $b) {
            $latestA = max(array_column($a['samples'], 'created_at'));
            $latestB = max(array_column($b['samples'], 'created_at'));
            return strtotime($latestB) - strtotime($latestA); 
        });

        $data['attachments'] = $attachments;
        $data['verification_jobs'] = $verification_jobs_indexed;
        $data['content'] = $this->load->view($this->controller.'/verification_content', $data , TRUE);
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
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;

        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data, TRUE);

        $verification_status = 20; //VERIFICATION STATUS
        $all_details = $this->main->get_trans_prep_details($data,$verification_status,null,null,null,$searchValue,$searchField); // data , VERIFICATION STATUS

        $jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($jobs[$jobId])) {
                $jobs[$jobId] = [
                    'trans_id' => $row['trans_id'],
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
            $this->db->select(['trans_id', 'filename', 'filepath', 'original_name']);
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
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $html = $this->load->view('verification/verification_container', $data, TRUE);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
    }


    public function submit_verification()
    {
        ob_start(); 
        try {
            $info   = $this->custom_lib->_require_login();
            $userID = decode($info['userID']);

            $testStatuses = $this->input->post('test_status');
            $reasons      = $this->input->post('reasons');
            $remarks      = $this->input->post('remarks');

            if (empty($testStatuses)) {
                echo json_encode([
                    'status'  => 'error',
                    'message' => 'No verification data received.'
                ]);
                return;
            }

            foreach ($testStatuses as $trans_detail_id => $statusID) {
                if (empty($statusID)) continue;

                $currentDetail = $this->main->get_data('trans_details', ['trans_detail_id' => $trans_detail_id], true);
                $currentStatus = (int)($currentDetail->test_status_id ?? 0);

                $reasonID = isset($reasons[$trans_detail_id]) ? (int)$reasons[$trans_detail_id] : null;
                $remark   = $remarks[$trans_detail_id] ?? null;

                $updateData = [];

                if ($currentStatus !== (int)$statusID || (int)$statusID === 22) {
                    $updateData['test_status_id'] = $statusID;
                    $updateData['modified_at']    = date('Y-m-d H:i:s');
                    $updateData['updated_by']     = $userID;

                    if ((int)$statusID === 22) {
                        $updateData['trans_detail_status_id'] = 24;
                    }

                    $this->main->update_data('trans_details', $updateData, ['trans_detail_id' => $trans_detail_id]);
                }

                $isReasonChanged = false;
                $isRemarkChanged = false;


                // Insert reason only if changed
                if (!empty($reasonID)) {
                    $lastReason = $this->db
                        ->select('reason_id')
                        ->from('trans_reasons')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->order_by('created_at', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row_array();

                    $lastReasonID = (int)($lastReason['reason_id'] ?? 0);

                    if ($reasonID !== $lastReasonID) {
                        $this->main->insert_data('trans_reasons', [
                            'trans_detail_id'        => $trans_detail_id,
                            'trans_detail_status_id' => 20,
                            'reason_id'              => $reasonID,
                            'created_by'             => $userID,
                            'created_at'             => date('Y-m-d H:i:s'),
                        ]);

                            $isReasonChanged = true;
                    }
                }

                // Insert remark only if changed
                if (!empty($remark)) {
                    $lastRemark = $this->db
                        ->select('remark')
                        ->from('trans_remarks')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->order_by('created_at', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row_array();

                    if (empty($lastRemark) || trim($lastRemark['remark']) !== trim($remark)) {
                        $this->main->insert_data('trans_remarks', [
                            'trans_detail_id'        => $trans_detail_id,
                            'trans_detail_status_id' => 20,
                            'remark'                 => $remark,
                            'created_by'             => $userID,
                            'created_at'             => date('Y-m-d H:i:s'),
                        ]);

                        $isRemarkChanged = true;
                    }
                }

                // Insert history/timestamps only if status changed
                if (!empty($updateData)) {
                    $statusText = '';
                    if (!empty($statusID)) {
                        $statusRow = $this->db->select('statDesc')
                            ->from('stats')
                            ->where('statusID', $statusID)
                            ->get()
                            ->row_array();
                        $statusText = $statusRow['statDesc'] ?? '';
                    }

                    $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                    ->get('trans_details')
                                    ->row_array();

                    if (!empty($detail)) {
                        $historyData = $detail;
                        unset($historyData['id']);
                        $historyData['trans_detail_id'] = $trans_detail_id;
                        $historyData['detail_change'] = $statusText; 
                        $historyData['trans_detail_status_id'] = 20;
                        $historyData['created_by'] = $userID;
                        $historyData['created_at'] = date('Y-m-d H:i:s');
                        $this->main->insert_data('trans_history', $historyData);

                        $timestampData = [
                            'trans_detail_id'        => $trans_detail_id,
                            'trans_detail_status_id' => 20,
                            'status_id'              => 1,
                            'created_at'             => date('Y-m-d H:i:s'),
                            'created_by'             => $userID,
                        ];
                        $this->main->insert_data('trans_timestamps', $timestampData);
                    }
                }

                // On Hold notification
                if ($isReasonChanged || $isRemarkChanged) {

                    if ((int)$statusID === 7) {
                        $transHeader = $this->db
                            ->select('th.trans_id, th.job_order_no, td.lab_code, th.created_by')
                            ->from('trans_headers th')
                            ->join('trans_details td', 'td.trans_id = th.trans_id')
                            ->where('td.trans_detail_id', $trans_detail_id)
                            ->get()
                            ->row_array();

                        if (!empty($transHeader)) {
                            $userHeader = (int)$transHeader['created_by'];
                            $recipient = $this->db
                                ->select('u.userID, u.userEmail, u.userFirstName, u.userLastName, u.userTypeId')
                                ->from('users u')
                                ->where('u.userID', $userHeader)
                                ->where('u.userEmail IS NOT NULL AND u.userEmail !=', '')
                                ->get()
                                ->row_array();

                            if (!empty($recipient)) {
                                $statusText = 'On Hold';
                                $remarkText = !empty($remark)
                                    ? $remark
                                    : 'The request has been placed on hold.';

                                $this->email_format->generateEmailNotification(
                                    $transHeader,
                                    $trans_detail_id,
                                    $statusText,
                                    $remarkText,
                                    $recipient,
                                    'On Hold'
                                );

                                log_message('info', "Sent 'On Hold' notification to {$recipient['userEmail']} for trans_detail_id {$trans_detail_id}");
                            } else {
                                log_message('warning', "No valid recipient found for On Hold notification (userID {$userHeader}).");
                            }
                        }
                    }
                }


            }

            $output = ob_get_clean(); 
            echo json_encode([
                'status'  => 'success',
                'message' => 'Verification submitted successfully.',
                'debug'   => trim($output)
            ]);

        } catch (Exception $e) {
            ob_end_clean();
            echo json_encode([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }


	// END OF Verification CONTROLLER




}

