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
          $this->alias = 'finalpreparation';
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

        $data['title'] = 'Final Preparation';
        $data['menu_title'] = 'Transactional';
        $data['parent_title'] = 'Initial Preparation';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $searchValue = "";
        $searchField = "";
        $final_prep_status = 26;
        $all_details = $this->main->get_trans_details($data,$final_prep_status,null,null,null,$searchValue,$searchField ); // data, FINAL PREP STATUS
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

        $final_prep_status = 26;
        $all_details = $this->main->get_trans_prep_details($data,$final_prep_status,null,null,null,$searchValue,$searchField );

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
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 4], false, 'statusID, statDesc', 'statDesc ASC');
        $html = $this->load->view('final_preparation/final_preparation_container', $data, TRUE);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
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
                        'lead_ts_window_start'   => $this->getCutoffTimestamp(),
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

    private function getCutoffTimestamp()
    {
        $now = new DateTime('now');

        $cutoff = new DateTime($now->format('Y-m-d') . ' 12:00:00');

        if ($now >= $cutoff) {
            $now->modify('+1 day');
        }

        return $now->format('Y-m-d H:i:s');
    }

}
