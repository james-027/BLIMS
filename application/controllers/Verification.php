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

    public function index01() 
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
        $data['title'] = 'Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.internal_id',
            'th.commercial_id',
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tp.param_name',
            'tn.name AS laboratory_tests',
            'sup.supplier_name',
            'p.plate_number',
            'b.batch_number',
            'td.lab_code AS lab_code',
            'tr.remark AS existing_remark' 
        ]);
        $this->db->from('trans_details td');
        $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
        $this->db->join('samples s', 's.id = td.sample_id', 'left');
        $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
        $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
        $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
        $this->db->join('tests t', 't.id = lt.test_id', 'left');
        $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
        $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
        $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
        $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
        $this->db->join('laboratories l', 'l.id = th.laboratory_id', 'left');
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
                    WHERE trans_detail_status_id = 20
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
                    AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 20
            ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');
             

        $this->db->where('td.trans_detail_status_id', 20);
        $this->db->where('(td.test_status_id IS NULL OR (td.test_status_id != 25 AND td.test_status_id != 23))', null, false);
        $this->db->order_by('th.trans_id', 'DESC');

        $all_details = $this->db->get()->result_array();

        $verification_jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($verification_jobs[$jobId])) {
                $verification_jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';

            $verification_jobs[$jobId]['samples'][] = $row;
        }


        $transIds = array_keys($verification_jobs);
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

        $verification_jobs_indexed = array_values($verification_jobs);

            usort($verification_jobs_indexed, function($a, $b) {
            $latestA = max(array_column($a['samples'], 'created_at'));
            $latestB = max(array_column($b['samples'], 'created_at'));
            return strtotime($latestB) - strtotime($latestA); 
        });
        $data['attachments'] = $attachments;
        $data['isViewOnly'] = false;
        $data['verification_jobs'] = $verification_jobs_indexed;
        $data['content'] = $this->load->view($this->controller.'/verification_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }




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

        $data['title'] = 'Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.internal_id',
            'th.commercial_id',
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tp.param_name',
            'tn.name AS laboratory_tests',
            'sup.supplier_name',
            'p.plate_number',
            'b.batch_number',
            'td.lab_code AS lab_code',
            'tr.remark AS existing_remark',
            'r.reason_name AS latest_reason' 
        ]);

        $this->db->from('trans_details td');
        $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
        $this->db->join('samples s', 's.id = td.sample_id', 'left');
        $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
        $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
        $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
        $this->db->join('tests t', 't.id = lt.test_id', 'left');
        $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
        $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
        $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
        $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
        $this->db->join('laboratories l', 'l.id = th.laboratory_id', 'left');
        if (!empty($data['lab_access'])) {
            $labIDs = array_column($data['lab_access'], 'laboratory_id');
            $this->db->where_in('th.laboratory_id', $labIDs);
        } else {
            $this->db->where('th.laboratory_id', 0); 
        }
        $this->db->group_by('td.trans_detail_id');
        $this->db->join("
            (
                SELECT tr1.trans_detail_id, tr1.remark
                FROM trans_remarks tr1
                INNER JOIN (
                    SELECT trans_detail_id, MAX(created_at) AS latest_created
                    FROM trans_remarks
                    WHERE trans_detail_status_id = 20
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
                    AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 20
            ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->join("
            (
                SELECT tr1.trans_detail_id, tr1.reason_id
                FROM trans_reasons tr1
                INNER JOIN (
                    SELECT trans_detail_id, MAX(created_at) AS latest_created
                    FROM trans_reasons
                    WHERE trans_detail_status_id = 20
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
                    AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 20
            ) trr", 'trr.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->join('reasons r', 'r.id = trr.reason_id', 'left');

        $this->db->where('td.trans_detail_status_id', 20);
        $this->db->where('(td.test_status_id IS NULL OR (td.test_status_id != 25 AND td.test_status_id != 23))', null, false);
        $this->db->order_by('th.trans_id', 'DESC');

        $all_details = $this->db->get()->result_array();

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
        $data['isViewOnly'] = false;
        $data['verification_jobs'] = $verification_jobs_indexed;
        $data['content'] = $this->load->view($this->controller.'/verification_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
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


   // public function submit_verification()
    // {
    //     $info   = $this->custom_lib->_require_login();
    //     $userID = decode($info['userID']);

    //     $testStatuses = $this->input->post('test_status');
    //     $reasons      = $this->input->post('reasons');
    //     $remarks      = $this->input->post('remarks');

    //     if (empty($testStatuses)) {
    //         echo json_encode([
    //             'status'  => 'error',
    //             'message' => 'No verification data received.'
    //         ]);
    //         return;
    //     }

    //     foreach ($testStatuses as $trans_detail_id => $statusID) {
    //         if (empty($statusID)) continue;

    //         $reasonID = $reasons[$trans_detail_id] ?? null;
    //         $remark   = $remarks[$trans_detail_id] ?? null;

    //         $updateData = ['test_status_id' => $statusID];
    //         if ((int)$statusID === 22) {
    //             $updateData['trans_detail_status_id'] = 22;
    //         }

    //         $result_verification = $this->main->update_data(
    //             'trans_details',
    //             $updateData,
    //             ['trans_detail_id' => $trans_detail_id]
    //         );

    //         if (!empty($result_verification)) {

    //             $user_logs = [
    //                 'userID'       => decode($info['userID']),
    //                 'userFullName' => $info['userFullName'],
    //                 'logTS'        => date_now(),
    //                 'page'         => 'Verification/submit_verification',
    //                 'logDetail'    => 'Successfully Updated Detail ID:' . $trans_detail_id
    //             ];
    //             $this->main->user_logs($user_logs);

    //             $detail = $this->db
    //                 ->where('trans_detail_id', $trans_detail_id)
    //                 ->get('trans_details')
    //                 ->row_array();

    //             if (!empty($detail)) {
    //                 $historyData = $detail;
    //                 unset($historyData['id']); 
    //                 $historyData['trans_detail_id'] = $trans_detail_id;
    //                 $historyData['trans_detail_status_id'] = 20; 
    //                 $historyData['created_by'] = $userID;
    //                 $historyData['created_at'] = date('Y-m-d H:i:s');
    //                 $this->main->insert_data('trans_history', $historyData);

    //                 $timestampData = [
    //                     'trans_detail_id'        => $trans_detail_id,
    //                     'trans_detail_status_id' => 20,
    //                     'status_id'              => 1,
    //                     'created_at'             => date('Y-m-d H:i:s'),
    //                     'created_by'             => $userID,
    //                 ];
    //                 $this->main->insert_data('trans_timestamps', $timestampData);
    //             }

    //             if (!empty($reasonID)) {
    //                 $set = [
    //                     'trans_detail_id'        => $trans_detail_id,
    //                     'trans_detail_status_id' => 20,
    //                     'reason_id'              => $reasonID,
    //                     'created_by'             => $userID,
    //                     'created_at'             => date('Y-m-d H:i:s'),
    //                 ];
    //                 $this->main->insert_data('trans_reasons', $set);
    //             }

    //             if (!empty($remark)) {
    //                 $set = [
    //                     'trans_detail_id'        => $trans_detail_id,
    //                     'trans_detail_status_id' => 20,
    //                     'remark'                 => $remark,
    //                     'created_by'             => $userID,
    //                     'created_at'             => date('Y-m-d H:i:s'),
    //                 ];
    //                 $this->main->insert_data('trans_remarks', $set);
    //             }

    //             if ((int)$statusID === 7) {
    //                 $this->db->select('th.trans_id, th.job_order_no, td.lab_code, th.created_by, u.userEmail as requester_email, u.userFirstName, u.userLastName');
    //                 $this->db->from('trans_headers th');
    //                 $this->db->join('users u', 'u.userID = th.created_by', 'left');
    //                 $this->db->join('trans_details td', 'td.trans_id = th.trans_id');
    //                 $this->db->where('td.trans_detail_id', $trans_detail_id);
    //                 $transHeader = $this->db->get()->row_array();

    //                 $requesterName = trim("{$transHeader['userFirstName']} {$transHeader['userLastName']}") ?: 'Requester';

    //                 $reason = '';
    //                 if (!empty($reasonID)) {
    //                     $reasonRow = $this->db->select('reason_name')->get_where('reasons', ['id' => $reasonID])->row_array();
    //                     $reason = $reasonRow['reason_name'] ?? '';
    //                 }

    //                 $sample = $this->db->select('s.sample_name')
    //                     ->from('trans_details td')
    //                     ->join('samples s', 's.id = td.sample_id', 'left')
    //                     ->where('td.trans_detail_id', $trans_detail_id)
    //                     ->get()->row_array();

    //                 $subject = "Job Order {$transHeader['job_order_no']} - On Hold Notification";
    //                 $message = "
    //                     <p>Dear {$requesterName},</p>
    //                     <p>The Laboratory Code <strong>{$transHeader['lab_code']}</strong> has been placed <strong>ON HOLD</strong>.</p>
    //                     <p><strong>Failed Sample:</strong> {$sample['sample_name']}</p>
    //                     <p><strong>Reason:</strong> {$reason}</p>
    //                     <p><strong>Remarks:</strong> {$remark}</p>
    //                     <br>
    //                     <p>Please check the system for more details.</p>
    //                     <p>-- <br> Laboratory System Notification</p>
    //                 ";

    //                 log_message('info', 'Sending email to: ' . $transHeader['requester_email']);


    //                 if (!empty($transHeader['requester_email'])) {
    //                     $mail = new PHPMailer(true);
    //                     try {
    //                         $mail->isSMTP();
    //                         $mail->SMTPDebug  = 0; 
    //                         $mail->Host       = 'smtp.gmail.com';
    //                         $mail->SMTPAuth   = true;
    //                         $mail->Username   = SYS_EMAIL;
    //                         $mail->Password   = SYS_EMAIL_PASS;
    //                         $mail->SMTPSecure = 'tls';
    //                         $mail->Port       = 587;

    //                         $mail->setFrom(SYS_EMAIL, 'Lab Information System');
    //                         $mail->addAddress($transHeader['requester_email']);

    //                         $mail->isHTML(true);
    //                         $mail->Subject = $subject;
    //                         $mail->Body    = $message;

    //                         log_message('info', "Attempting to send email to: {$transHeader['requester_email']} for Job Order: {$transHeader['job_order_no']}");

    //                         if ($mail->send()) {
    //                             log_message('info', "Email successfully sent to {$transHeader['requester_email']} for Job Order: {$transHeader['job_order_no']}");

    //                             $this->main->user_logs([
    //                                 'userID'       => $userID,
    //                                 'userFullName' => $info['userFullName'],
    //                                 'logTS'        => date_now(),
    //                                 'page'         => 'Verification/submit_verification',
    //                                 'logDetail'    => "On Hold email sent to {$transHeader['requester_email']} for Job Order: {$transHeader['job_order_no']}"
    //                             ]);
    //                         } else {
    //                             log_message('error', " Email failed to send. PHPMailer Error: {$mail->ErrorInfo}");
    //                         }

    //                     } catch (\PHPMailer\PHPMailer\Exception $e) {
    //                         log_message('error', "PHPMailer Exception for Job Order {$transHeader['job_order_no']} to {$transHeader['requester_email']}: {$e->getMessage()}");
    //                     } catch (\Exception $e) {
    //                         log_message('error', " General Exception for Job Order {$transHeader['job_order_no']} to {$transHeader['requester_email']}: {$e->getMessage()}");
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     echo json_encode([
    //         'status'  => 'success',
    //         'message' => 'Verification submitted successfully.'
    //     ]);
    // }