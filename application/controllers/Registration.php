<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {

	public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
		$this->alias = 'registration';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');

	}


    /*  
	module: Registration Controller
	desc: Creation of Sample Registration
	date created: 10-02-2025
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
        $data['title'] = 'Registration';
        $data['menu_title'] = '';
        $data['parent_title'] = 'Transactional';
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

        // Join reasons table to get reason_name
        $this->db->join('reasons r', 'r.id = trr.reason_id', 'left');


        $this->db->where('th.created_by', $userID);
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
        $data['verification_jobs'] = $verification_jobs_indexed;
        $data['isViewOnly'] = true;
        $data['content'] = $this->load->view('verification/verification_content', $data , TRUE);
        $this->load->view('admin/templates', $data);
    }



    public function sample_registration(){
		$alias = $this->alias;
		$info = $this->custom_lib->_require_login();
		$data['js_file'] = 'assets/js/registration.js?v=4.0';
		$data['profile'] = $this->custom_lib->_get_profile();
		$data['menuColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->menuColor;
	    $data['tableColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->tableColor;
	    $data['thColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->thColor;
	    $data['btnColor'] = get_user_theme(array('a.userID' => decode($info['userID'])), true)->btnColor;
	    
	    $data['notif_counter'] = $this->custom_lib->_get_notifications()->counter;

	    $keyID = decode($info['current_keyID']);
	    $userID = decode($info['userID']);
		
		$data['available_access'] = $this->custom_lib->_get_available_access( array('userID' => decode($info['userID'])) );
		
		$module_access = $this->custom_lib->module_access($alias);
		
		
		if(!$module_access->view){redirect('admin');}
		

		$data['title'] = 'Registration';
		$data['menu_title'] = '';
		$data['parent_title'] = 'Transactional';
		$data['controller']   = $this->controller;

		$data['userID'] = decode($info['userID']);
		$data['breadcrumbs'] = $this->load->view('admin/breadcrumbs', $data , TRUE);
		$data['internalFeedmills'] = $this->main->get_data('internal_feedmills', ['status_id' => 1], false, 'id, feedmill_name', 'feedmill_name ASC');
		$data['commercialFeeds']   = $this->main->get_data('commercial_feedmills', ['status_id' => 1], false, 'id, feedmill_name', 'feedmill_name ASC');
		$data['nutritionists']     = $this->main->get_data('nutritionists', ['status_id' => 1], false, 'id, nutritionist_name', 'nutritionist_name ASC');
		$data['laboratories']      = $this->main->get_data('laboratories', ['status_id' => 1], false, 'id, laboratory_name', 'laboratory_name ASC');
		$data['plate_numbers']      = $this->main->get_data('plate_numbers', ['status_id' => 1], false, 'id, plate_number', 'plate_number ASC');
		$data['suppliers']      = $this->main->get_data('suppliers', ['status_id' => 1], false, 'id, supplier_name', 'supplier_name ASC');
		$data['batches']      = $this->main->get_data('batch_numbers', ['status_id' => 1], false, 'id, batch_number', 'batch_number ASC');
		$data['samples']      = $this->main->get_data('samples', ['status_id' => 1], false, 'id, sample_name', 'sample_name ASC');
		$user = $this->main->get_data('users', ['userID' => $data['userID']], TRUE, 'userFirstName, userLastName');
		$data['submittedBy'] = $user->userFirstName . ' ' . $user->userLastName;
		


		$data['content'] = $this->load->view($this->controller.'/registration_content', $data , TRUE);
		
		$this->load->view('admin/templates', $data);
	}

	

	public function get_commercial_feeds($internalID = null)
	{
		if (!$internalID) {
			echo json_encode([]);
			return;
		}

		$feeds = $this->main->get_data(
			'commercial_feedmills',
			['internal_feedmill_id' => $internalID, 'status_id' => 1], 
			false,
			'id, feedmill_name',
			'feedmill_name ASC'
		);

		echo json_encode($feeds);
	}


	public function get_lab_tests($laboratory_id = null)
	{
		
		if (!$laboratory_id) {
			echo json_encode(['tests' => [], 'samples' => []]);
			return;
		}

		$this->db->select('lt.test_id, tn.name as test_name, t.test_code,lt.sample_type_id, st.sample_type_name');
		$this->db->from('lab_tests lt');
		$this->db->join('tests t', 'lt.test_id = t.id', 'left');
		$this->db->join('test_names tn', 't.test_name_id = tn.id', 'left');
		$this->db->join('sample_types st', 'lt.sample_type_id = st.id', 'left');
		$this->db->where('lt.laboratory_id', $laboratory_id);
		$query = $this->db->get();

		$data = $query->result();

		$tests = [];
		$samples = [];

		foreach ($data as $row) {
			$tests[$row->test_id] = $row->test_code;
			$samples[$row->sample_type_id] = $row->sample_type_name;
		}

		echo json_encode([
			'tests' => array_map(function ($id, $name) {
				return ['id' => $id, 'name' => $name];
			}, array_keys($tests), $tests),
			'samples' => array_map(function ($id, $name) {
				return ['id' => $id, 'name' => $name];
			}, array_keys($samples), $samples),
		]);
	}


	public function get_lead_times()
	{
		$laboratoryId = $this->input->get('laboratory_id');
		$testId = $this->input->get('test_id');

		$leadTimes = $this->db
			->where('laboratory_id', $laboratoryId)
			->where('test_id', $testId)
			->get('lab_tests')
			->row();

		$response = [];

		if ($leadTimes) {
			if (!is_null($leadTimes->lead_regular)) {
				$response[] = [
					'label' => 'Regular',
					'value' => $leadTimes->lead_regular,
				];
			}

			if (!is_null($leadTimes->lead_rush)) {
				$response[] = [
					'label' => 'Rush',
					'value' => $leadTimes->lead_rush,
				];
			}
		}

		echo json_encode($response);
	}


    public function submit_registration()
    {
        $info = $this->custom_lib->_require_login();
        $userID = decode($info['userID']);

        $this->load->library('form_validation');
        $this->load->helper('url');

        $this->form_validation->set_rules('internalFeedmill', 'Internal Feedmill', 'required');
        $this->form_validation->set_rules('address', 'Address', 'required');
        $this->form_validation->set_rules('contactNumber', 'Contact Number', 'required');
        $this->form_validation->set_rules('nutritionist', 'Nutritionist', 'required');
        $this->form_validation->set_rules('labLocation', 'Laboratory Location', 'required');
        $this->form_validation->set_rules('deliveryType', 'Delivery Type', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode([
                'status' => 'error',
                'message' => validation_errors()
            ]);
            return;
        }

        $laboratoryID = $this->input->post('labLocation');
        $lab = $this->main->get_data('laboratories', ['id' => $laboratoryID], TRUE, 'identifier_code');
        $labCode = $lab ? $lab->identifier_code : 'XXXX';
        $moduleCode = 'JN';
        $monthYear = date('my');

        $this->db->select('job_order_no');
        $this->db->like('job_order_no', "-$moduleCode-$monthYear-", 'both');
        $this->db->order_by('trans_id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get('trans_headers')->row();

        $seq = ($last && preg_match('/(\d{4})$/', $last->job_order_no, $matches)) ? intval($matches[1]) + 1 : 1;
        $seqFormatted = str_pad($seq, 4, '0', STR_PAD_LEFT);
        $jobOrderNo = "$labCode-$moduleCode-$monthYear-$seqFormatted";

        $headerData = [
            'job_order_no'        => $jobOrderNo,
            'internal_id'         => $this->input->post('internalFeedmill'),
            'commercial_id'       => $this->input->post('commercialFeed') ?: null,
            'address'             => $this->input->post('address'),
            'external_client_name'=> $this->input->post('externalClientName') ?: null,
            'contact_number'      => $this->input->post('contactNumber'),
            'nutritionist_id'     => $this->input->post('nutritionist'),
            'laboratory_id'       => $laboratoryID,
            'remarks'             => $this->input->post('remarks'),
            'way_bill'            => $this->input->post('wayBillNumber'),
            'driver_name'         => $this->input->post('driverName'),
            'plate_number'        => $this->input->post('plateNumber') ?: null,
            'client_id'           => $userID,
            'created_by'          => $userID,
            'created_at'          => date('Y-m-d H:i:s')
        ];



        $header_result = $this->main->insert_data('trans_headers', $headerData, TRUE);

        if (!empty($header_result['id'])) {
            $user_logs = [
                'userID'       => decode($info['userID']),
                'userFullName' => $info['userFullName'],
                'logTS'        => date_now(),
                'page'         => 'Registration/submit_registration',
                'logDetail'    => 'Successfully added Trans Header ID:' . $header_result['id']
            ];
            $this->main->user_logs($user_logs);
        }

        if (!empty($_FILES['attachFile']['name'])) {
            $config['upload_path']   = './uploads/trans_attachments/';
            $config['allowed_types'] = '*';
            $config['max_size']      = 5120;

            $originalName = pathinfo($_FILES['attachFile']['name'], PATHINFO_FILENAME);
            $extension = pathinfo($_FILES['attachFile']['name'], PATHINFO_EXTENSION);
            $timestamp = date('Ymd_His'); 
            $newFileName = $originalName . '_' . $timestamp . '.' . $extension;

            $config['file_name'] = $newFileName;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('attachFile')) {
                $this->session->set_flashdata('error', $this->upload->display_errors());
                redirect(base_url($this->controller));
                return;
            } else {
                $uploadData = $this->upload->data();
                $attachment = [
                    'trans_id'   => $header_result['id'],
                    'filename'   => $uploadData['file_name'],
                    'filepath'   => $uploadData['full_path'],
                    'original_name'   => $originalName,
                    'status_id'  => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => $userID,
                ];
                $this->main->insert_data('attachments', $attachment);
            }
        }

        $sampleNames     = $this->input->post('sampleName');
        $typeOfSamples   = $this->input->post('typeOfSample');
        $labTests        = $this->input->post('laboratoryTests');
        $productionDates = $this->input->post('productionDate');
        $suppliers       = $this->input->post('shipmentSupplier');
        $plateNumbers    = $this->input->post('plateVanNumber');
        $batchNumbers    = $this->input->post('batchLotNumber');
        $leadTimeTypes   = $this->input->post('leadTimeType');
        $coaRequired     = $this->input->post('coaRequired'); 
        $commercialID    = $this->input->post('commercialFeed') ?: null;
        $internalID      = $this->input->post('internalFeedmill');

            $mmyy = date('my');

            $this->db->select('lab_code');
            $this->db->like('lab_code', "-$mmyy-", 'both');
            $this->db->order_by('trans_detail_id', 'DESC');
            $this->db->limit(1);
            $last = $this->db->get('trans_details')->row();

            if ($last && preg_match('/(\d{4})$/', $last->lab_code, $matches)) {
                $lastIncrement = (int)$matches[1];
            } else {
                $lastIncrement = 0;
            }

            // Increment for this new job order
            $increment = str_pad($lastIncrement + 1, 4, '0', STR_PAD_LEFT);


        foreach ($sampleNames as $index => $sampleID) {
            $testID     = $labTests[$index] ?? null;
            $supplierID = $suppliers[$index] ?? null;

            $plateVal = trim(strtoupper($plateNumbers[$index] ?? ''));
            $plateID = null;
            if (!empty($plateVal)) {
                $plateCheck = $this->main->get_data('plate_numbers', ['plate_number' => $plateVal], TRUE);
                if ($plateCheck) {
                    $plateID = $plateCheck->id;
                } else {
                    $set = [
                        'plate_number' => $plateVal,
                        'status_id'    => 1,
                        'created_by'   => $userID,
                        'created_at'   => date_now(),
                        'modified_at'  => date_now(),
                    ];
                    $result = $this->main->insert_data('plate_numbers', $set, TRUE);
                    $plateID = $result['id'];
                }
            }

            $batchVal = trim(strtoupper($batchNumbers[$index] ?? ''));
            $batchID = null;
            if (!empty($batchVal)) {
                $batchCheck = $this->main->get_data('batch_numbers', ['batch_number' => $batchVal], TRUE);
                if ($batchCheck) {
                    $batchID = $batchCheck->id;
                } else {
                    $set = [
                        'batch_number' => $batchVal,
                        'status_id'    => 1,
                        'created_by'   => $userID,
                        'created_at'   => date_now(),
                        'modified_at'  => date_now(),
                    ];
                    $result = $this->main->insert_data('batch_numbers', $set, TRUE);
                    $batchID = $result['id'];
                }
            }

            $sampleRow = $this->db->get_where('samples', ['id' => $sampleID])->row();
            $sampleCode = $sampleRow ? $sampleRow->sample_code : 'XXXX';

            $testRow = $this->db->get_where('tests', ['id' => $testID])->row();
            $testCode = $testRow ? $testRow->test_code : 'XXXX';

            $feedmillCode = null;
            if (!empty($commercialID)) {
                $commercial = $this->db->get_where('commercial_feedmills', ['id' => $commercialID])->row();
                $feedmillCode = $commercial ? $commercial->feedmill_code : null;
            }
            if (!$feedmillCode) {
                $internal = $this->db->get_where('internal_feedmills', ['id' => $internalID])->row();
                $feedmillCode = $internal ? $internal->feedmill_code : 'FM';
            }

            $laboratoryCode = "$feedmillCode-$mmyy-$sampleCode-$testCode-$increment";

            $coa_flag = isset($coaRequired[$index]) ? 'Y' : 'N';

            $detailData = [
                'trans_id'           => $header_result['id'],
                'sample_id'          => $sampleID,
                'sample_type_id'     => $typeOfSamples[$index] ?: null,
                'lab_test_id'        => $testID ?: null,
                'delivery_date'      => $productionDates[$index] ?: null,
                'supplier_id'        => $supplierID ?: null,
                'plate_number_id'    => $plateID,
                'batch_number_id'    => $batchID,
                'lead_time'          => $leadTimeTypes[$index] ?: null,
                'coa_flag'           => $coa_flag,
                'lab_code'           => $laboratoryCode,
                'created_at'         => date('Y-m-d H:i:s'),
                'created_by'         => $userID,
                'trans_detail_status_id' => 20
            ];

            $details_result = $this->main->insert_data('trans_details', $detailData, TRUE);

            if (!empty($details_result['id'])) {
                $user_logs = [
                    'userID'       => decode($info['userID']),
                    'userFullName' => $info['userFullName'],
                    'logTS'        => date_now(),
                    'page'         => 'Registration/submit_registration',
                    'logDetail'    => 'Successfully added Trans Detail ID:' . $details_result['id']
                ];
                $this->main->user_logs($user_logs);
                    $historyData = $detailData; 
                    $historyData['trans_detail_id'] = $details_result['id']; 
                    $historyData['detail_change'] = "Successfully Registered"; 
                    $historyData['trans_detail_status_id'] = 19;
                    $historyData['created_at'] = date('Y-m-d H:i:s');
                    $historyData['created_by'] = $userID;
                    $this->main->insert_data('trans_history', $historyData);
                    
                    $timestampData = [
                        'trans_detail_id'        => $details_result['id'],
                        'trans_detail_status_id' => 19,
                        'status_id'              => 1,
                        'created_at'             => date('Y-m-d H:i:s'),
                        'created_by'             => $userID,
                        'updated_by'             => null,
                        'modified_at'            => null
                    ];
                    $this->main->insert_data('trans_timestamps', $timestampData);
                    
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => "Registration saved successfully! Job Order: $jobOrderNo"
        ]);
    }












	

	// END OF Registration CONTROLLER

	

	

}
