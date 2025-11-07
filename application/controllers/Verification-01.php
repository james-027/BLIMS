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
    $module_access = $this->custom_lib->module_access($alias);

    if (!$module_access->view) {
        redirect('admin');
    }

    $data['title'] = 'Verification';
    $data['menu_title'] = '';
    $data['parent_title'] = 'Transactional';
    $data['controller'] = $this->controller;
    $data['userID'] = $userID;
    $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
    $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
    $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

    $data['verification_jobs'] = $this->get_verification_jobs();

    $transIds = array_keys($data['verification_jobs']);
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

    $data['attachments'] = $attachments;
    $data['content'] = $this->load->view($this->controller . '/verification_content', $data, TRUE);
    $this->load->view('admin/templates', $data);
}


 

        public function submit_verification()
    {
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

            $reasonID = $reasons[$trans_detail_id] ?? null;
            $remark   = $remarks[$trans_detail_id] ?? null;

            $updateData = [
                'test_status_id' => $statusID,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];
            if ((int)$statusID === 22) {
                $updateData['trans_detail_status_id'] = 22;
            }

            $result_verification = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            if (!empty($result_verification)) {
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'Verification/submit_verification',
                    'logDetail'    => 'Successfully Updated Detail ID:' . $trans_detail_id
                ]);

                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                ->get('trans_details')
                                ->row_array();

                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id'] = $trans_detail_id;
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

                // Insert reasons & remarks
                if (!empty($reasonID)) {
                    $this->main->insert_data('trans_reasons', [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 20,
                        'reason_id'              => $reasonID,
                        'created_by'             => $userID,
                        'created_at'             => date('Y-m-d H:i:s'),
                    ]);
                }

                if (!empty($remark)) {
                    $this->main->insert_data('trans_remarks', [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 20,
                        'remark'                 => $remark,
                        'created_by'             => $userID,
                        'created_at'             => date('Y-m-d H:i:s'),
                    ]);
                }

                if ((int)$statusID === 7) {
                    $this->db->select('th.trans_id, th.job_order_no, td.lab_code, th.created_by, u.userEmail as requester_email, u.userFirstName, u.userLastName');
                    $this->db->from('trans_headers th');
                    $this->db->join('users u', 'u.userID = th.created_by', 'left');
                    $this->db->join('trans_details td', 'td.trans_id = th.trans_id');
                    $this->db->where('td.trans_detail_id', $trans_detail_id);
                    $transHeader = $this->db->get()->row_array();

                    if (!empty($transHeader['requester_email'])) {
                        $requesterName = trim("{$transHeader['userFirstName']} {$transHeader['userLastName']}") ?: 'Requester';
                        $reason = '';
                        if (!empty($reasonID)) {
                            $reasonRow = $this->db->select('reason_name')->get_where('reasons', ['id' => $reasonID])->row_array();
                            $reason = $reasonRow['reason_name'] ?? '';
                        }

                        $sample = $this->db->select('s.sample_name')
                            ->from('trans_details td')
                            ->join('samples s', 's.id = td.sample_id', 'left')
                            ->where('td.trans_detail_id', $trans_detail_id)
                            ->get()->row_array();

                        $subject = "Job Order {$transHeader['job_order_no']} - On Hold Notification";
                        $body = "
                            <p>Dear {$requesterName},</p>
                            <p>The Laboratory Code <strong>{$transHeader['lab_code']}</strong> has been placed <strong>ON HOLD</strong>.</p>
                            <p><strong>Failed Sample:</strong> {$sample['sample_name']}</p>
                            <p><strong>Reason:</strong> {$reason}</p>
                            <p><strong>Remarks:</strong> {$remark}</p>
                            <br>
                            <p>Please check the system for more details.</p>
                            <p>-- <br> Laboratory System Notification</p>
                        ";
                        $this->main->insert_data('email_queues', [
                                    'to_email'   => $transHeader['requester_email'],
                                    'to_name'    => $requesterName,
                                    'subject'    => $subject,
                                    'body'       => $body,
                                    'status'     => 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'created_by' => $userID
                            ]);

                        log_message('info', "Queued email for {$transHeader['requester_email']} for Job Order {$transHeader['job_order_no']}");
                    }
                }
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Verification submitted successfully.'
        ]);
    }



    public function get_verification_jobs()
{
    // Same query logic you currently have inside index()
    $this->db->select([
        'th.trans_id AS trans_id',
        'th.job_order_no',
        'th.internal_id',
        'th.commercial_id',
        'td.*',
        's.sample_name',
        'st.sample_type_name',
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
    $this->db->join('tests lt', 'lt.id = td.lab_test_id', 'left');
    $this->db->join('test_names tn', 'tn.id = lt.test_name_id', 'left');
    $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
    $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
    $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
    $this->db->join("(
        SELECT tr1.trans_detail_id, tr1.remark
        FROM trans_remarks tr1
        INNER JOIN (
            SELECT trans_detail_id, MAX(created_at) AS latest_created
            FROM trans_remarks
            GROUP BY trans_detail_id
        ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
            AND tr1.created_at = tr2.latest_created
    ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');
    $this->db->where('td.trans_detail_status_id', 20);
    $this->db->order_by('th.trans_id', 'ASC');

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

    echo json_encode([
        'status' => 'success',
        'data' => $verification_jobs
    ]);
}





    public function auto_cancel_old_records()
{
    $records = $this->db
        ->select('trans_detail_id, modified_at')
        ->from('trans_details')
        ->where('test_status_id', 23)
        ->get()
        ->result();

    $today = new DateTime();
    $updatedCount = 0;

    foreach ($records as $record) {
        if (empty($record->modified_at)) continue;

        $lastModified = new DateTime($record->modified_at);

        // Count business days
        $businessDays = 0;
        $current = clone $lastModified;
        while ($current < $today) {
            $current->modify('+1 day');
            if ($current->format('N') < 6) { // Monday–Friday
                $businessDays++;
            }
        }

        if ($businessDays >= 3) {
            $updateData = [
                'test_status_id'          => 25,
                'modified_at'             => date('Y-m-d H:i:s'),
            ];

            $this->main->update_data('trans_details', $updateData, [
                'trans_detail_id' => $record->trans_detail_id
            ]);


                $this->main->insert_data('trans_history', [
                    'trans_detail_id' => $record->trans_detail_id,
                    'test_status_id'  => 25,
                    'remarks'         => 'Auto-cancelled by system after 3 business days',
                    'created_at'      => date('Y-m-d H:i:s')
                ]);

            $updatedCount++;
        }
    }

    echo "Auto-cancelled {$updatedCount} record(s) that exceeded 3 business days.";
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