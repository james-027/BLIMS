<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

class FinalPreparation extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');

    }


    /*  
	module: Final Preparation Controller
	desc: Creation of Final Preparation
	date created: 10-29-2025
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

        $data['title'] = 'Final Preparation';
        $data['menu_title'] = 'Transactional';
        $data['parent_title'] = 'Initial Preparation';
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
                    WHERE trans_detail_status_id = 26
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
                    AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 26
            ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->where('td.trans_detail_status_id', 26);
     $this->db->order_by('td.modified_at', 'DESC');

        $all_details = $this->db->get()->result_array();

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
         $data['analyticals'] = $this->main->get_data('stats', ['status_type_id' => 5], false, 'statusID, statDesc', 'statDesc ASC');
        $data['prep_verifications'] = $this->main->get_data('stats',                
            false,
            false,                  
            'statusID, statDesc',   
            'statDesc DESC',         
            false,                  
            false,                  
            'statusID',             
            [22, 23]                
        );

        $data['content'] = $this->load->view('final_preparation/final_preparation_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }
 

    public function submit_final_prep()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $prep_verifications = $this->input->post('prep_verifications');
        $remarks      = $this->input->post('remarks');

        if (empty($prep_verifications)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No Final Prep data received.'
            ]);
            return;
        }

        foreach ($prep_verifications as $trans_detail_id => $prepVeriID) {
            if (empty($prepVeriID)) continue;

            $reasonID = $reasons[$trans_detail_id] ?? null;
            $remark   = $remarks[$trans_detail_id] ?? null;

        


            $updateData = [
                'prep_verification_status_id' => $prepVeriID,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];

            if ((int)$prepVeriID === 23) {
                $updateData['trans_detail_status_id'] = 24;

            }else{
                $updateData['trans_detail_status_id'] = 27;
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            $statusText = '';
                    if (!empty($prepVeriID)) {
                        $statusRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $prepVeriID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statusRow['statDesc'] ?? '';
            }


            if (!empty($result_prep)) {
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'FinalPreparation/submit_final_prep',
                    'logDetail'    => 'Successfully Updated Final Prep ID:' . $trans_detail_id
                ]);

                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                ->get('trans_details')
                                ->row_array();

                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id'] = $trans_detail_id;
                    $historyData['detail_change'] = $statusText;
                    $historyData['trans_detail_status_id'] = 26;
                    $historyData['created_by'] = $userID;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 26,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }
                $statusText = '';
                    if (!empty($prepVeriID)) {
                        $statRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $prepVeriID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statRow['statDesc'] ?? '';
                    } 

                if (!empty($remark)) {
                        $this->main->insert_data('trans_remarks', [
                            'trans_detail_id'        => $trans_detail_id,
                            'trans_detail_status_id' => 26,
                            'remark'                 => $remark,
                            'created_by'             => $userID,
                            'created_at'             => date('Y-m-d H:i:s'),
                        ]); 
                }
                    
                if ((int)$prepVeriID === 23) {
                    $timestampRow = $this->db->select('created_by')
                        ->from('trans_timestamps')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->where('trans_detail_status_id', 24)
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
                                ->select('th.trans_id, th.job_order_no, td.lab_code')
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
                                    'Failed'
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
            'message' => 'Final Preparation submitted successfully.'
        ]);
    }


public function submit_final_prep_per_jo()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $prep_verifications = $this->input->post('prep_verifications');
        $remarks            = $this->input->post('remarks');

        if (empty($prep_verifications)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No Final Prep data received.'
            ]);
            return;
        }

        $failedTransByRecipient = []; // Step 1: Collect failed trans_detail_ids per recipient per JO

        foreach ($prep_verifications as $trans_detail_id => $prepVeriID) {
            if (empty($prepVeriID)) continue;

            $remark = $remarks[$trans_detail_id] ?? null;

            // Update trans_details
            $updateData = [
                'prep_verification_status_id' => $prepVeriID,
                'modified_at'                 => date('Y-m-d H:i:s'),
                'updated_by'                  => $userID
            ];

            if ((int)$prepVeriID === 23) {
                $updateData['trans_detail_status_id'] = 24;
            } else {
                $updateData['trans_detail_status_id'] = 27;
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            // Get status text
            $statusText = '';
            if (!empty($prepVeriID)) {
                $statRow = $this->db->select('statDesc')
                                    ->from('stats')
                                    ->where('statusID', $prepVeriID)
                                    ->get()
                                    ->row_array();
                $statusText = $statRow['statDesc'] ?? '';
            }

            if (!empty($result_prep)) {
                // Log user action
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'FinalPreparation/submit_final_prep',
                    'logDetail'    => 'Successfully Updated Final Prep ID:' . $trans_detail_id
                ]);

                // Insert history & timestamps
                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                ->get('trans_details')
                                ->row_array();
                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id']        = $trans_detail_id;
                    $historyData['detail_change']          = $statusText;
                    $historyData['trans_detail_status_id'] = 26;
                    $historyData['created_by']             = $userID;
                    $historyData['created_at']             = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 26,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }

                // Insert remarks
                if (!empty($remark)) {
                    $this->main->insert_data('trans_remarks', [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 26,
                        'remark'                 => $remark,
                        'created_by'             => $userID,
                        'created_at'             => date('Y-m-d H:i:s'),
                    ]);
                }

                // Only handle Failed status (prepVeriID = 23)
                if ((int)$prepVeriID === 23) {
                    // Get recipient from timestamp
                    $timestampRow = $this->db->select('created_by')
                                            ->from('trans_timestamps')
                                            ->where('trans_detail_id', $trans_detail_id)
                                            ->where('trans_detail_status_id', 24)
                                            ->order_by('created_at', 'DESC')
                                            ->get()
                                            ->row_array();
                    if (empty($timestampRow)) continue;

                    $userFromTimestamp = (int) $timestampRow['created_by'];

                    $recipient = $this->db
                        ->select('u.userID, u.userEmail, u.userFirstName, u.userLastName')
                        ->from('users u')
                        ->where('u.userID', $userFromTimestamp)
                        ->where('u.userEmail IS NOT NULL AND u.userEmail !=', '')
                        ->get()
                        ->row_array();
                    if (empty($recipient)) continue;

                    // Get Job Order info
                    $transHeader = $this->db
                        ->select('th.trans_id, th.job_order_no')
                        ->from('trans_headers th')
                        ->join('trans_details td', 'td.trans_id = th.trans_id')
                        ->where('td.trans_detail_id', $trans_detail_id)
                        ->get()
                        ->row_array();
                    if (empty($transHeader)) continue;

                    // Group failed trans_detail_ids by recipient + Job Order
                    $key = $recipient['userID'] . '_' . $transHeader['job_order_no'];
                    if (!isset($failedTransByRecipient[$key])) {
                        $failedTransByRecipient[$key] = [
                            'recipient'        => $recipient,
                            'transHeader'      => $transHeader,
                            'trans_detail_ids' => []
                        ];
                    }
                    $failedTransByRecipient[$key]['trans_detail_ids'][] = $trans_detail_id;
                }
            }
        }

        foreach ($failedTransByRecipient as $group) {
            $recipient        = $group['recipient'];
            $transHeader      = $group['transHeader'];
            $trans_detail_ids = $group['trans_detail_ids'];

            $this->email_format->generateEmailNotificationByJO(
                $transHeader,
                $trans_detail_ids, 
                'Failed',         
                $remark,              
                $recipient,
                'Failed'
            );

            log_message('info', "Sent 'Failed' notification to {$recipient['userEmail']} for trans_detail_ids: " . implode(',', $trans_detail_ids));
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Final Preparation submitted successfully.'
        ]);
    }


 


	// END OF Initial Preparation CONTROLLER




}

