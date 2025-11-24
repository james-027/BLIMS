<?php
defined('BASEPATH') OR exit('No direct script access allowed');

        require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
        require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
        require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

        use PHPMailer\PHPMailer\PHPMailer;
        use PHPMailer\PHPMailer\Exception;

class InitialPreparation extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');

	}


    /*  
	module: Initial Preparation Controller
	desc: Creation of  Initial Preparation
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

        $data['title'] = 'Initial Preparation';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 3], false, 'statusID, statDesc', 'statDesc ASC');
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $initial_prep_status = 24;
        $all_details = $this->main->get_trans_details($data,$initial_prep_status);// data , INITIAL PREP STATUS ,

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
        $data['content'] = $this->load->view('initial_preparation/initial_preparation_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }
 

    public function submit_initial_prep()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $analyticals = $this->input->post('analyticals');


        if (empty($analyticals)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No initial Prep data received.'
            ]);
            return;
        }

        foreach ($analyticals as $trans_detail_id => $analyticalID) {
            if (empty($analyticalID)) continue;

            $reasonID = $reasons[$trans_detail_id] ?? null;
            $remark   = $remarks[$trans_detail_id] ?? null;

            $updateData = [
                'pre_analytical_id' => $analyticalID,
                'trans_detail_status_id' => 26,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];
            
            $statusText = '';
                    if (!empty($analyticalID)) {
                        $statusRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $analyticalID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statusRow['statDesc'] ?? '';
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            if (!empty($result_prep)) {
                $this->main->user_logs([
                    'userID'       => $userID,
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'InitialPreparation/submit_initial_prep',
                    'logDetail'    => 'Successfully Updated Initial Prep ID:' . $trans_detail_id
                ]);

                $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                ->get('trans_details')
                                ->row_array();

                if (!empty($detail)) {
                    $historyData = $detail;
                    unset($historyData['id']);
                    $historyData['trans_detail_id'] = $trans_detail_id;
                    $historyData['detail_change'] = $statusText; 
                    $historyData['trans_detail_status_id'] = 24;
                    $historyData['created_by'] = $userID;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 24,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }



            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Initial Preparation submitted successfully.'
        ]);
    }


 


	// END OF Initial Preparation CONTROLLER




}

