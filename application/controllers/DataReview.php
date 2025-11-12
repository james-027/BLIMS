<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class DataReview extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');

    }


    /*  
	module: Data Review Controller
	desc: Creation of Data Review Controller
	date created: 11-06-2025
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

        $data['title'] = 'Data Review';
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
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tp.param_name',
            'tn.name AS laboratory_tests',
            'td.lab_code AS lab_code',
            'tr.remark AS existing_remark',
            'tt.latest_timestamp  AS date_submitted',
        ]);
        $this->db->from('trans_details td');
        $this->db->join('trans_headers th', 'th.trans_id = td.trans_id');
        $this->db->join('samples s', 's.id = td.sample_id', 'left');
        $this->db->join('sample_types st', 'st.id = td.sample_type_id', 'left');
        $this->db->join('lab_tests lt', 'lt.test_id = td.lab_test_id AND lt.laboratory_id = th.laboratory_id', 'inner');
        $this->db->join('test_parameters tp', 'tp.id = lt.test_param_id', 'left');
        $this->db->join('tests t', 't.id = lt.test_id', 'left');
        $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
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
                    WHERE trans_detail_status_id = 27
                    GROUP BY trans_detail_id
                ) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id 
                    AND tr1.created_at = tr2.latest_created
                WHERE tr1.trans_detail_status_id = 27
            ) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');
        $this->db->join("
            (
                SELECT tt_latest.trans_detail_id, tt_latest.created_at AS latest_timestamp
                FROM trans_timestamps tt_latest
                INNER JOIN (
                    SELECT MAX(id) AS latest_id
                    FROM trans_timestamps
                    WHERE trans_detail_status_id = 27
                    GROUP BY trans_detail_id
                ) tt_max ON tt_latest.id = tt_max.latest_id
            ) tt", 'tt.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->where('td.trans_detail_status_id', 33);
       $this->db->order_by('tt.latest_timestamp', 'DESC');

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
        $data['test_statuses'] = $this->main->get_data('stats', ['status_type_id' => 4], false, 'statusID, statDesc', 'statDesc ASC');
        $data['review_verifications'] = $this->main->get_data('stats', ['status_type_id' => 6], false, 'statusID, statDesc', 'statDesc ASC');
        $data['content'] = $this->load->view('data_review/data_review_content', $data , TRUE);
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

    public function submit_data_review()
    {
        $info   = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);
        $review_verifications = $this->input->post('review_verifications');

        if (empty($review_verifications)) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'No Final Prep data received.'
            ]);
            return;
        }

        foreach ($review_verifications as $trans_detail_id => $reviewID) {
            if (empty($reviewID)) continue;

            $reasonID = $reasons[$trans_detail_id] ?? null;

        
            $updateData = [
                'review_verification_status_id' => $reviewID,
                'modified_at'    => date('Y-m-d H:i:s'),
                'updated_by'     => $userID
            ];

            if ((int)$reviewID === 35) {
                $updateData['trans_detail_status_id'] = 27;

            }else{
                $updateData['trans_detail_status_id'] = 36;
            }

            $result_prep = $this->main->update_data(
                'trans_details',
                $updateData,
                ['trans_detail_id' => $trans_detail_id]
            );

            $statusText = '';
                    if (!empty($reviewID)) {
                        $statusRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $reviewID)
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
                    $historyData['trans_detail_status_id'] = 33;
                    $historyData['created_by'] = $userID;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $this->main->insert_data('trans_history', $historyData);

                    $timestampData = [
                        'trans_detail_id'        => $trans_detail_id,
                        'trans_detail_status_id' => 33,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                }
                $statusText = '';
                    if (!empty($reviewID)) {
                        $statRow = $this->db->select('statDesc')
                                            ->from('stats')
                                            ->where('statusID', $reviewID)
                                            ->get()
                                            ->row_array();
                        $statusText = $statRow['statDesc'] ?? '';
                    } 

                    
                if ((int)$reviewID === 35) {
                    $timestampRow = $this->db->select('created_by')
                        ->from('trans_timestamps')
                        ->where('trans_detail_id', $trans_detail_id)
                        ->where('trans_detail_status_id', 27)
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
                                $remark = "This is for Re-analysis";
                                $this->email_format->generateEmailNotification(
                                    $transHeader,
                                    $trans_detail_id,
                                    $statusText,
                                    $remark,
                                    $recipient,
                                    'Re-Analysis'
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
            'message' => 'Data Review submitted successfully.'
        ]);
    }

	// END OF Data  Review CONTROLLER




}

