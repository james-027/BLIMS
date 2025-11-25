<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class ReportRelease extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');

    }


    /*  
	module: Report Release Controller
	desc: Creation of Report Release Controller
	date created: 11-12-2025
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

        $data['title'] = 'Report Release';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        
        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.laboratory_id',
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tp.param_name',
            'tn.name AS laboratory_tests',
            'td.ext_lab_code AS lab_code',
            'tr.remark AS existing_remark',
            'tt.latest_timestamp AS date_submitted',
            'CONCAT(us.userFirstName, " ", us.userLastName) AS client_name',
            'n.nutritionist_name AS nutritionist_name'
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
        $this->db->join('users us', 'us.userID = th.client_id', 'left');
        $this->db->join('nutritionists n', 'n.id = th.nutritionist_id', 'left');

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
                    WHERE trans_detail_status_id = 36
                    GROUP BY trans_detail_id
                ) tt_max ON tt_latest.id = tt_max.latest_id
            ) tt", 'tt.trans_detail_id = td.trans_detail_id', 'left');

        $this->db->join("
        (
            SELECT tt_latest.trans_detail_id, tt_latest.created_at AS latest_timestamp
            FROM trans_timestamps tt_latest
            INNER JOIN (
                SELECT MAX(id) AS latest_id
                FROM trans_timestamps
                WHERE trans_detail_status_id = 37
                GROUP BY trans_detail_id
            ) tt_max_latest ON tt_latest.id = tt_max_latest.latest_id
        ) tt_latest", 'tt_latest.trans_detail_id = td.trans_detail_id', 'left');

       $this->db->where('td.trans_detail_status_id', 37);
       $this->db->order_by('td.modified_at', 'DESC');

        $all_details = $this->db->get()->result_array();
        $jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($jobs[$jobId])) {
                $jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'client_name' => $row['client_name'] ?? 'N/A',
                    'nutritionist_name' => $row['nutritionist_name'] ?? 'N/A',
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
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['content'] = $this->load->view('report_release/report_release_content', $data , TRUE);
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
            td.ext_lab_code AS labCode,
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

    public function submit_for_release()
    {
        $info      = $this->custom_lib->_require_login();
        $userID    = decode($info['userID']);
        $releasing = $this->input->post('releasing');

        $groups = [];
        $labs   = []; 

        foreach ($releasing as $trans_detail_id => $releaseID) {
            if (empty($releaseID)) continue;

            $detail = $this->main->get_data('trans_details', ['trans_detail_id' => $trans_detail_id], true);
            if (!$detail) continue;

            $trans_id = $detail->trans_id;
            $groups[$trans_id][$detail->sample_id][] = $trans_detail_id;

            if (!isset($labs[$trans_id])) {
                $header = $this->main->get_data('trans_headers', ['trans_id' => $trans_id], true);
                $labs[$trans_id] = $header->laboratory_id ?? null;
            }
        }

        $lab_sequences = [];

        foreach ($groups as $trans_id => $samples) {
            $laboratory_id = $labs[$trans_id] ?? null;
            if (!$laboratory_id) continue;

            if (!isset($lab_sequences[$laboratory_id])) {
                $headers = $this->main->get_data('trans_headers', ['laboratory_id' => $laboratory_id], false);
                $released_items = [];

                if (!empty($headers)) {
                    foreach ($headers as $h) {
                        $tds = $this->main->get_data('trans_details', ['trans_id' => $h->trans_id, 'is_released' => 1], false);
                        if (!empty($tds)) {
                            $released_items = array_merge($released_items, $tds);
                        }
                    }
                }
                $year = date('y'); 
                if (!empty($released_items)) {
                    $existing_sequences = array_map(function ($r) use ($year) {
                        $num = $r->release_ref_number ?? '00000';
                        $parts = explode('-', $num);
                        return ($parts[0] == $year) ? (int)$parts[2] : 0;
                    }, $released_items);

                    $lab_sequences[$laboratory_id][$year] = max($existing_sequences);
                } else {
                    $lab_sequences[$laboratory_id][$year] = 0;
                }
            }

            $lab_code = $this->main->get_data('laboratories', ['id' => $laboratory_id], true)->identifier_code ?? 'LAB';

            foreach ($samples as $sample_id => $trans_details) {
                $lab_sequences[$laboratory_id][$year]++;
                $sequence_padded = str_pad($lab_sequences[$laboratory_id][$year], 5, '0', STR_PAD_LEFT);
                $release_ref_number = "{$year}-{$lab_code}-{$sequence_padded}";

                foreach ($trans_details as $trans_detail_id) {
                    $updateData = [
                        'is_released'        => 1,
                        'modified_at'        => date('Y-m-d H:i:s'),
                        'is_released_time'   => date('Y-m-d H:i:s'),
                        'updated_by'         => $userID,
                        'release_ref_number' => $release_ref_number
                    ];

                    $result_releasing = $this->main->update_data(
                        'trans_details',
                        $updateData,
                        ['trans_detail_id' => $trans_detail_id]
                    );


                    if (!empty($result_releasing)) {
                            $this->main->user_logs([
                                'userID'       => $userID,
                                'userFullName' => $info['userFullName'],
                                'logTS'        => date_now(),
                                'page'         => 'ReportRelease/submit_for_release',
                                'logDetail'    => 'Successfully Updated Report Release ID:' . $trans_detail_id
                            ]);

                            $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                                            ->get('trans_details')
                                            ->row_array();

                            if (!empty($detail)) {
                                $historyData = $detail;
                                unset($historyData['id']);
                                $historyData['trans_detail_id'] = $trans_detail_id;
                                $historyData['detail_change'] = "COA RELEASED";
                                $historyData['trans_detail_status_id'] = 37;
                                $historyData['created_by'] = $userID;
                                $historyData['created_at'] = date('Y-m-d H:i:s');
                                $this->main->insert_data('trans_history', $historyData);

                                $timestampData = [
                                    'trans_detail_id'        => $trans_detail_id,
                                    'trans_detail_status_id' => 37,
                                    'status_id'              => 1,
                                    'created_at'             => date('Y-m-d H:i:s'),
                                    'created_by'             => $userID,
                                ];
                                $this->main->insert_data('trans_timestamps', $timestampData);
                            }
                    }
                        

                }
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => 'Report Release submitted successfully.'
        ]);
    }




	// END OF Report Release CONTROLLER




}

