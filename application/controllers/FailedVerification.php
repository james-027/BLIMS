<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FailedVerification extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
		$this->alias = 'registration';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');

	}


    /*  
	module: Failed Verification Controller
	desc: Viewing of Failed Verification
	date created: 12-10-2025
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

        		$data['new_button'] = '<div class="row pl-3">';

		$btn_class = 'btn btn-icon btn-sm btn-round btn-'.$data['btnColor'].' mr-2 mb-2';
		if($module_access->add){
			$data['new_button'] .= '
				<button type="button" class="add-registration '.$btn_class.'"><span class="fas fa-plus"></span></button>
				';
		}

		$data['new_button'] .= '</div>';

        if (!$module_access->view) { redirect('admin'); }
        $data['title'] = 'Failed Verification';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Verification';
        $data['controller'] = $this->controller;
        $data['userID'] = $userID;
        $data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
        $data['test_statuses'] = $this->main->get_data(
                'stats',
                "status_type_id = 3 OR statusID = 25",
                false,
                'statusID, statDesc',
                'statDesc ASC'
                );
        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');
        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.laboratory_id',
            'th.internal_id',
            'th.commercial_id',
            'th.trans_id',
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tn.name AS laboratory_tests',
            'sup.supplier_name',
            'tp.param_name',
            'p.plate_number',
            'b.batch_number',
            'td.ext_lab_code AS lab_code',
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
        $this->db->where('th.created_by', $userID);
        $this->db->where('td.test_status_id', 23);
        $this->db->order_by('th.trans_id', 'DESC');
        $all_details = $this->db->get()->result_array();


        $verification_jobs = [];
        foreach ($all_details as $row) {
            $jobId = $row['trans_id'];
            if (!isset($verification_jobs[$jobId])) {
                $verification_jobs[$jobId] = [
                    'job_order_no' => $row['job_order_no'],
                    'lab_code' => $row['lab_code'],
                    'laboratory_id' => $row['laboratory_id'],
                    'trans_id' => $row['trans_id'],
                    'samples' => []
                ];
            }

            $row['delivery_date'] = !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';

            $verification_jobs[$jobId]['samples'][] = $row;
        }


        foreach ($verification_jobs as $jobId => &$job) {

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

        unset($job);

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
        $data['is_all_released'] = true OR false;
        $data['verification_jobs'] = $verification_jobs_indexed;
        $data['isViewOnly'] = true;
        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');
        $data['plate_numbers']   = $this->main->get_data('plate_numbers', ['status_id' => 1], false, 'id, plate_number', 'plate_number ASC');
		$data['suppliers']      = $this->main->get_data('suppliers', ['status_id' => 1], false, 'id, supplier_name', 'supplier_name ASC');
		$data['batches']      = $this->main->get_data('batch_numbers', ['status_id' => 1], false, 'id, batch_number', 'batch_number ASC');
		$data['samples']      = $this->main->get_data('samples', ['status_id' => 1], false, 'id, sample_name', 'sample_name ASC');
        $data['content'] = $this->load->view('verification/sub_verification_content', $data , TRUE);
        
        $this->load->view('admin/templates', $data);
    }


    public function search_details()
    {
        $searchValue = $this->input->get_post('search') ?? '';
        $searchField = $this->input->get_post('field') ?? '';


        $info = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $this->db->select([
            'th.trans_id AS trans_id',
            'th.job_order_no',
            'th.laboratory_id',
            'th.internal_id',
            'th.commercial_id',
            'td.*',
            's.sample_name',
            'st.sample_type_name',
            'tn.name AS laboratory_tests',
            'sup.supplier_name',
            'tp.param_name',
            'p.plate_number',
            'b.batch_number',
            'stat.statDesc',
            'td.ext_lab_code AS lab_code',
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
        $this->db->join('stats stat', 'stat.statusID = td.test_status_id', 'left');
        $this->db->join('test_names tn', 'tn.id = t.test_name_id', 'left');
        $this->db->join('suppliers sup', 'sup.id = td.supplier_id', 'left');
        $this->db->join('plate_numbers p', 'p.id = td.plate_number_id', 'left');
        $this->db->join('batch_numbers b', 'b.id = td.batch_number_id', 'left');
        $this->db->join('laboratories l', 'l.id = th.laboratory_id', 'left');


        $this->db->group_by('td.trans_detail_id');

        // latest remarks
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

        // latest reasons
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
        $this->db->where('th.created_by', $userID);
        $this->db->where('td.test_status_id', 23);
        $this->db->order_by('th.trans_id', 'DESC');

        if (!empty($searchValue)) {
            $this->db->group_start();

            switch ($searchField) {
                case "job_order_no":
                    $this->db->like('th.job_order_no', $searchValue);
                    break;

                case "lab_code":
                    $this->db->like('td.ext_lab_code', $searchValue);
                    break;

            
                case "sample_name":
                    $this->db->like('s.sample_name', $searchValue);
                    break;

                case "test_name":
                    $this->db->like('tn.name', $searchValue);
                    break;
                default:
                    $this->db->like('th.job_order_no', $searchValue);
                    $this->db->or_like('td.ext_lab_code', $searchValue);
                    $this->db->or_like('s.sample_name', $searchValue);
                    $this->db->or_like('tn.name', $searchValue);
                    break;
            }

            $this->db->group_end();
        }

        
        $results = $this->db->get()->result_array();

        $jobs = [];

        foreach ($results as $row) {
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

            $row['delivery_date'] =
                !empty($row['delivery_date']) ? date('Y-m-d', strtotime($row['delivery_date'])) : '';

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
        unset($job);


        $jobs_sorted = array_values($jobs);

        usort($jobs_sorted, function ($a, $b) {
            $latestA = max(array_column($a['samples'], 'created_at'));
            $latestB = max(array_column($b['samples'], 'created_at'));
            return strtotime($latestB) - strtotime($latestA);
        });

        $data['jobs'] = $jobs_sorted;
        


        $theme = get_user_theme(['a.userID' => $userID], true);

        $data['thColor'] = $theme->thColor;
        $data['btnColor'] = $theme->btnColor;
        $data['tableColor'] = $theme->tableColor;
        $data['menuColor'] = $theme->menuColor;

        $data['display_status'] = $this->main->get_data('stats', false, false, 'statusID, statDesc', 'statDesc ASC');

        $data['test_statuses'] = $this->main->get_data(
            'stats',
            "status_type_id = 3 OR statusID = 25",
            false,
            'statusID, statDesc',
            'statDesc ASC'
        );

        $data['reasons'] = $this->main->get_data('reasons', ['status_id' => 1], false, 'id, reason_name', 'reason_name ASC');

        $html = $this->load->view('verification/sub_verification_container', $data, TRUE);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'status' => 'success',
            'html' => $html
        ]);
        exit;
    }










	// END OF Failed Verification CONTROLLER

	

	

}
