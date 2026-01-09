<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_model extends CI_Model {

	public function get_data($tbl, $where=null, $row=FALSE, $select=null, $order=FALSE, $or_where = false, $group_by = false, $where_in_field = false, $where_in = false, $string = false){

		if($where != null){
			$this->db->where($where);
		}

		if($or_where != null){
			$this->db->or_where($or_where);
		}

		if($select != null){
			$this->db->select($select);
		}

		if($group_by != FALSE){
			$this->db->group_by($group_by);
		}

		if($order != FALSE){
			$this->db->order_by($order);
		}

		if($where_in != FALSE && $where_in_field != FALSE){
			$this->db->where_in($where_in_field, $where_in);
		}

		$query = $this->db->get($tbl);
		//return $this->db->last_query();
		if($string){

			return $this->db->last_query();
		}
		
		if($row == TRUE){
			$result_data = $query->row();	
		}else{
			$result_data= $query->result();
		}
		return $result_data;
	}

	public function insert_data($tbl, $set, $id=FALSE){
		$this->db->trans_start();

		$this->db->set($set);
		$this->db->insert($tbl);
		$insert_id = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		}else{
			$this->db->trans_commit();
			if ($id == TRUE) {
				$result['result'] = TRUE;
				$result['id'] = $insert_id;
				return $result;
			} else {
				return TRUE;
			}
		}	
	}

	public function update_data($tbl, $set, $where){
		$this->db->trans_start();

		$this->db->set($set);
		$this->db->where($where);
		$this->db->update($tbl);
		
		//return $this->db->last_query();
		
		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return FALSE;
		}else{
			$this->db->trans_commit();
			return TRUE;
		}
	}

	public function check_data($tbl, $where, $row=FALSE, $select=FALSE, $string = FALSE){

		if($select != FALSE){
			$this->db->select($select);
		}

		$query = $this->db->get_where($tbl, $where);

		

		$result = $query->num_rows();

		if($result > 0){
			if($row == TRUE){
				$data['info'] = $query->row();
				$data['result'] = TRUE;
			}else{
				$data = TRUE;
			}
			
		}else{
			if($row == TRUE){
				$data['result'] = FALSE;
			}else{

				$data = FALSE;
			}
			
		}
		if($string){
			return $this->db->last_query();
		} else {
			return $data;
		}
	}

	public function get_join($tbl, $join, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $where=FALSE, $where_not_in_field = false, $where_not_in = false, $where_in_field = false, $where_in = false, $string = false){

		if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}

		if($select != FALSE){
			$this->db->select($select);
		}
		
		if($group != FALSE){
			$this->db->group_by($group);
		}

		if($order != FALSE){
			$this->db->order_by($order);
		}

		if($where != FALSE){
			$this->db->where($where);
		}

		if($where_not_in != FALSE && $where_not_in_field != FALSE){
			$this->db->where_not_in($where_not_in_field, $where_not_in);
		}

		if($where_in != FALSE && $where_in_field != FALSE){
			$this->db->where_in($where_in_field, $where_in);
		}

		$query = $this->db->get($tbl);
		if($string){

			return $this->db->last_query();
		}
		if($row_type === FALSE){
			$result = $query->result();
		}else{
			$result = $query->row();
		}
		return $result;
	}

	public function get_query($sql_query, $row_type=FALSE, $update_string = false, $delete_string = false, $dt_query=false, $string = false){

		$query = $this->db->query($sql_query);

		if($update_string){
			if($this->db->trans_status() === FALSE){
				$this->db->trans_rollback();
				return FALSE;
			}else{
				$this->db->trans_commit();
				return TRUE;
			}
		}

		if($string){
			return $this->db->last_query();
		}

		if($row_type === FALSE){

			if($delete_string){
				$result = TRUE;
			} elseif($dt_query){
				$result = $query;
			} else {
				$result = $query->result();
			}
			
		}else{
			if($delete_string){
				$result = TRUE;
			} elseif($dt_query){
				$result = $query;
			} else {
				$result = $query->row();
			}
		}
		return $result;
	}

	public function check_join($tbl, $join, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $where = false, $string = FALSE){

		
		if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}
		
		if($select != FALSE){
			$this->db->select($select);
		}
		
		if($group != FALSE){
			$this->db->group_by($group);
		}

		if($order != FALSE){
			$this->db->order_by($order);
		}

		if($where != FALSE){
			$this->db->where($where);
		}

		$query = $this->db->get($tbl);

		if($string) {
			return $this->db->last_query();
		}

		$num_rows = $query->num_rows();
		if($row_type == FALSE){

			if($num_rows > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{

			if($num_rows > 0){
				$result['result'] = TRUE;

				$result['info'] = $query->row();
				return $result;
			}else{
				$result['result'] = FALSE;
				return $result;
			}
		}
		
	}

	public function check_query($query, $row_data=FALSE){
		$query = $this->db->query($query);

		$num = $query->num_rows();
		if($row_data == FALSE){
			
			if($num > 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}else{
			if($num > 0){
				$data['result'] = TRUE;	
				$data['info'] = $query->row();
			}else{
				$data['result'] = FALSE;	
			}
			
		}
	}

	public function get_count($tbl, $where=null){
		
		if($where != null){
			$this->db->where($where);	
		}
		
		$query = $this->db->get($tbl);

		$num = $query->num_rows();
		return $num;
	}

	public function get_join_datatables($tbl, $join, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $where=FALSE, $where_field = false, $where_in = false, $or_where = false, $limit = false, $string = false){

		if($join != false){
            foreach($join as $row => $value){
            	if(is_array($value)){
	            	foreach ($value as $key => $key_data) {
	            		$this->db->join($row, $key, $key_data);
	            	}
            	} else {
            		$this->db->join($row, $value);
            	}
            }
        }

		if($select != FALSE){
			$this->db->select($select);
		}
		
		if($group != FALSE){
			$this->db->group_by($group);
		}

		if($order != FALSE){
			$this->db->order_by($order);
		}

		if($where != FALSE){
			$this->db->where($where);
		}

		if($where_in != FALSE && $where_field != FALSE){
			$this->db->where_in($where_field, $where_in);
		}

		if($or_where != FALSE){
			$this->db->or_where($or_where);
		}

		if($limit != FALSE){
			$this->db->limit($limit);
		}

		$query = $this->db->get($tbl);
		
		if($row_type === FALSE){
			$result = $query;
		}else{
			$result = $query->row();
		}

		if($string){
			return $this->db->last_query();
		} else {
			return $result;
		}
	}

	public function void_table($table, $filter){
		$query = $this->db->where($filter)->delete($table);
		if($this->db->affected_rows() > 0)
		{
			return true;
		}else{
			return $this->db->error();
		}
	}

	public function user_logs ($user_logs) {

		$logsDB = $this->load->database('logs', TRUE);
		$query = $logsDB->insert('userlogs', $user_logs);

	}

	public function get_sys_logs($postData, $table, $column_order = false, $column_search = null, $order = null, $select = false, $join = null, $filter = false){
        $logsDB = $this->load->database('logs', TRUE);

        if($select != FALSE){
            $logsDB->select($select);
        }
        if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}

        $logsDB->from($table);
 
        $i = 0;
        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $logsDB->group_start();
                    $logsDB->like($item, $postData['search']['value']);
                }else{
                    $logsDB->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $logsDB->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $logsDB->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($order)){
            
            $logsDB->order_by(key($order), $order[key($order)]);
        }
        

        if($postData['length'] != -1){
            $logsDB->limit($postData['length'], $postData['start']);
        }
        $query = $logsDB->get();
        $result['query'] = $logsDB->last_query();
        $result['result'] = $query;
        return $result;
        
    }

    public function trails_countAll($table){
        $logsDB = $this->load->database('logs', TRUE);

        $logsDB->from($table);
        return $logsDB->count_all_results();
    }

    public function trails_countFiltered($postData, $table, $column_order = null, $column_search = null, $order = null, $search = false, $join = null, $select = false){
        $logsDB = $this->load->database('logs', TRUE);
        
        if($select != FALSE){
            $logsDB->select($select);
        }
        if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}

        $logsDB->from($table);
 
        $i = 0;
        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $logsDB->group_start();
                    $logsDB->like($item, $postData['search']['value']);
                }else{
                    $logsDB->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $logsDB->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $logsDB->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($order)){
            
            $logsDB->order_by(key($order), $order[key($order)]);
        }

        $query = $logsDB->get();

        return $query->num_rows();
    }

	public function get_dynamic_dt($postData, $table, $column_order = false, $column_search = null, $order = null, $select = false, $join = null, $filter = false){
        

        if($select != FALSE){
            $this->db->select($select);
        }
        if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}

        $this->db->from($table);
 
        $i = 0;
        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($order)){
            
            $this->db->order_by(key($order), $order[key($order)]);
        }
        

        if($postData['length'] != -1){
            $this->db->limit($postData['length'], $postData['start']);
        }
        $query = $this->db->get();
        $result['query'] = $this->db->last_query();
        $result['result'] = $query;
		$result['num_rows'] = $query->num_rows();
        return $result;
        
    }

    public function dt_countAll($table){

        $this->db->from($table);
        return $this->db->count_all_results();
    }

    public function dt_count_filtered($postData, $table, $column_order = null, $column_search = null, $order = null, $search = false, $join = null, $select = false){
        
        
        if($select != FALSE){
            $this->db->select($select);
        }
        if($join){
			foreach($join as $row => $value){
				if(is_array($value)){
					foreach ($value as $key => $key_data) {
						$this->db->join($row, $key, $key_data);
					}
				} else {
					$this->db->join($row, $value);
				}
			}
		}

        $this->db->from($table);
 
        $i = 0;
        // loop searchable columns 
        foreach($column_search as $item){
            // if datatable send POST for search
            if($postData['search']['value']){
                // first loop
                if($i===0){
                    // open bracket
                    $this->db->group_start();
                    $this->db->like($item, $postData['search']['value']);
                }else{
                    $this->db->or_like($item, $postData['search']['value']);
                }
                
                // last loop
                if(count($column_search) - 1 == $i){
                    // close bracket
                    $this->db->group_end();
                }
            }
            $i++;
        }
         
        if(isset($postData['order'])){
            $this->db->order_by($column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
        }else if(isset($order)){
            
            $this->db->order_by(key($order), $order[key($order)]);
        }

        $query = $this->db->get();

        return $query->num_rows();
    }

	public function get_trans_details($data, $status_id, $timestamp_from_status = null, $remark_from_status = null , $remark_from_verification = null,$searchValue,$searchField) 
	{
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
			'td.ext_lab_code AS lab_code',
			'tr.remark AS existing_remark',
			 'r.reason_name AS latest_reason',
			($timestamp_from_status ? 'tt.latest_timestamp AS date_submitted' : ''),
			($timestamp_from_status ? 'tt.date_roundoff AS date_roundoff' : ''),
			($remark_from_verification ?'tr33.remark AS result_verification_remark' : ''),
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

		// Lab access filter
		if (!empty($data['lab_access'])) {
			$labIDs = array_column($data['lab_access'], 'laboratory_id');
			$this->db->where_in('th.laboratory_id', $labIDs);
		} else {
			$this->db->where('th.laboratory_id', 0);
		}

		$this->db->group_by('td.trans_detail_id');

		$remark_status = $remark_from_status ?? $status_id;
		$remark_join = "
			SELECT tr1.trans_detail_id, tr1.remark
			FROM trans_remarks tr1
			INNER JOIN (
				SELECT trans_detail_id, MAX(created_at) AS latest_created
				FROM trans_remarks
				WHERE trans_detail_status_id = {$remark_status}
				GROUP BY trans_detail_id
			) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
				AND tr1.created_at = tr2.latest_created
			WHERE tr1.trans_detail_status_id = {$remark_status}
		";
		$this->db->join("($remark_join) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');
		
			if ($remark_from_verification) {
			$remark_join_verif = "
				SELECT tr1.trans_detail_id, tr1.remark
				FROM trans_remarks tr1
				INNER JOIN (
					SELECT trans_detail_id, MAX(created_at) AS latest_created
					FROM trans_remarks
					WHERE trans_detail_status_id = {$remark_from_verification}
					GROUP BY trans_detail_id
				) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
					AND tr1.created_at = tr2.latest_created
				WHERE tr1.trans_detail_status_id = {$remark_from_verification}
			";
			$this->db->join("($remark_join_verif) tr33", 'tr33.trans_detail_id = td.trans_detail_id', 'left');
		}

		if ($timestamp_from_status) {
			$timestamp_join = "
				SELECT tt1.trans_detail_id, tt1.created_at AS latest_timestamp, lead_ts_window_start AS date_roundoff
				FROM trans_timestamps tt1
				INNER JOIN (
					SELECT trans_detail_id, MAX(id) AS latest_id
					FROM trans_timestamps
					WHERE trans_detail_status_id = {$timestamp_from_status}
					GROUP BY trans_detail_id
				) tt2 ON tt1.trans_detail_id = tt2.trans_detail_id 
					AND tt1.id = tt2.latest_id
			";
			$this->db->join("($timestamp_join) tt", 'tt.trans_detail_id = td.trans_detail_id', 'left');
		}

		
		
			$reason_join = "
				SELECT tr1.trans_detail_id, tr1.reason_id
				FROM trans_reasons tr1
				INNER JOIN (
					SELECT trans_detail_id, MAX(created_at) AS latest_created
					FROM trans_reasons
					WHERE trans_detail_status_id = {$status_id}
					GROUP BY trans_detail_id
				) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
					AND tr1.created_at = tr2.latest_created
				WHERE tr1.trans_detail_status_id = {$status_id}
			";
			$this->db->join("($reason_join) trr", 'trr.trans_detail_id = td.trans_detail_id', 'left');
			$this->db->join('reasons r', 'r.id = trr.reason_id', 'left');


	

			
        if (!empty($searchValue)) {
            $this->db->group_start();

            switch ($searchField) {
                case "job_order_no":
                    $this->db->like('th.job_order_no', $searchValue);
                    break;

                case "lab_code":
                    $this->db->like('td.ext_lab_code', $searchValue);
                    break;

                case "date_submitted":
                    $this->db->where("DATE_FORMAT(tt.latest_timestamp, '%M %d, %Y') LIKE", "%$searchValue%");
                    break;

                case "lab_result":
                    $this->db->like('td.test_exec_lab_result', $searchValue);
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
                    $this->db->or_where("DATE_FORMAT(tt.latest_timestamp, '%M %d, %Y') LIKE", "%$searchValue%");
                    $this->db->or_like('td.test_exec_lab_result', $searchValue);
                    break;
            }

            $this->db->group_end();
        }
		

		$this->db->where('td.trans_detail_status_id', $status_id);

		// Test status condition only for 20
		if ($status_id == 20) {
			$this->db->where('(td.test_status_id IS NULL OR (td.test_status_id != 25 AND td.test_status_id != 23))', null, false);
			$this->db->order_by('th.trans_id', 'DESC');
		} else {
			$this->db->order_by('td.modified_at', 'DESC');
		}

		return $this->db->get()->result_array();
	}

	public function get_trans_prep_details($data, $status_id, $timestamp_from_status = null, $remark_from_status = null , $remark_from_verification = null,$searchValue )
	{
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
			'td.ext_lab_code AS lab_code',
			'tr.remark AS existing_remark',
			 'r.reason_name AS latest_reason',
			($timestamp_from_status ? 'tt.latest_timestamp AS date_submitted' : ''),
			($timestamp_from_status ? 'tt.date_roundoff AS date_roundoff' : ''),
			($remark_from_verification ?'tr33.remark AS result_verification_remark' : ''),
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

		// Lab access filter
		if (!empty($data['lab_access'])) {
			$labIDs = array_column($data['lab_access'], 'laboratory_id');
			$this->db->where_in('th.laboratory_id', $labIDs);
		} else {
			$this->db->where('th.laboratory_id', 0);
		}

		$this->db->group_by('td.trans_detail_id');

		$remark_status = $remark_from_status ?? $status_id;
		$remark_join = "
			SELECT tr1.trans_detail_id, tr1.remark
			FROM trans_remarks tr1
			INNER JOIN (
				SELECT trans_detail_id, MAX(created_at) AS latest_created
				FROM trans_remarks
				WHERE trans_detail_status_id = {$remark_status}
				GROUP BY trans_detail_id
			) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
				AND tr1.created_at = tr2.latest_created
			WHERE tr1.trans_detail_status_id = {$remark_status}
		";
		$this->db->join("($remark_join) tr", 'tr.trans_detail_id = td.trans_detail_id', 'left');
		
			if ($remark_from_verification) {
			$remark_join_verif = "
				SELECT tr1.trans_detail_id, tr1.remark
				FROM trans_remarks tr1
				INNER JOIN (
					SELECT trans_detail_id, MAX(created_at) AS latest_created
					FROM trans_remarks
					WHERE trans_detail_status_id = {$remark_from_verification}
					GROUP BY trans_detail_id
				) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
					AND tr1.created_at = tr2.latest_created
				WHERE tr1.trans_detail_status_id = {$remark_from_verification}
			";
			$this->db->join("($remark_join_verif) tr33", 'tr33.trans_detail_id = td.trans_detail_id', 'left');
		}

		if ($timestamp_from_status) {
			$timestamp_join = "
				SELECT tt1.trans_detail_id, tt1.created_at AS latest_timestamp, lead_ts_window_start AS date_roundoff
				FROM trans_timestamps tt1
				INNER JOIN (
					SELECT trans_detail_id, MAX(id) AS latest_id
					FROM trans_timestamps
					WHERE trans_detail_status_id = {$timestamp_from_status}
					GROUP BY trans_detail_id
				) tt2 ON tt1.trans_detail_id = tt2.trans_detail_id 
					AND tt1.id = tt2.latest_id
			";
			$this->db->join("($timestamp_join) tt", 'tt.trans_detail_id = td.trans_detail_id', 'left');
		}

		
		
			$reason_join = "
				SELECT tr1.trans_detail_id, tr1.reason_id
				FROM trans_reasons tr1
				INNER JOIN (
					SELECT trans_detail_id, MAX(created_at) AS latest_created
					FROM trans_reasons
					WHERE trans_detail_status_id = {$status_id}
					GROUP BY trans_detail_id
				) tr2 ON tr1.trans_detail_id = tr2.trans_detail_id
					AND tr1.created_at = tr2.latest_created
				WHERE tr1.trans_detail_status_id = {$status_id}
			";
			$this->db->join("($reason_join) trr", 'trr.trans_detail_id = td.trans_detail_id', 'left');
			$this->db->join('reasons r', 'r.id = trr.reason_id', 'left');


			    if (!empty($searchValue)) {
				$this->db->group_start();
				$this->db->like('th.job_order_no', $searchValue);
				$this->db->or_like('td.lab_code', $searchValue);
				$this->db->or_like('td.test_exec_lab_result', $searchValue);
				$this->db->or_like('s.sample_name', $searchValue);
				$this->db->or_like('tn.name', $searchValue);
				$this->db->group_end();
			}
		

		$this->db->where('td.trans_detail_status_id', $status_id);

		// Test status condition only for 20
		if ($status_id == 20) {
			$this->db->where('(td.test_status_id IS NULL OR (td.test_status_id != 25 AND td.test_status_id != 23))', null, false);
			$this->db->order_by('th.trans_id', 'DESC');
		} else {
			$this->db->order_by('td.modified_at', 'DESC');
		}

		return $this->db->get()->result_array();
	}

	public function get_pdf_trans_detail($trans_detail_id) 
	{
			$this->db->select('sample_id, trans_id');
			$this->db->from('trans_details');
			$this->db->where('trans_detail_id', $trans_detail_id);
			$result = $this->db->get()->row_array();

			if (!$result) return null;

			$sample_id = $result['sample_id'];
			$trans_id = $result['trans_id'];

			$this->db->select('trans_detail_id');
			$this->db->from('trans_details');
			$this->db->where('sample_id', $sample_id);
			$this->db->where('trans_id', $trans_id);
			$this->db->where('trans_detail_status_id', 37);
			$this->db->order_by('modified_at', 'DESC');
			$latest_detail = $this->db->get()->row_array();

			$target_detail_id = $latest_detail['trans_detail_id'] ?? $trans_detail_id;

			$tt_received_sub = "(SELECT t1.* 
								FROM trans_timestamps t1
								WHERE t1.trans_detail_status_id = 20
								AND t1.created_at = (
									SELECT MAX(t2.created_at)
									FROM trans_timestamps t2
									WHERE t2.trans_detail_id = t1.trans_detail_id
										AND t2.trans_detail_status_id = 20
								))";

			$tt_analyzed_sub = "(SELECT t1.* 
								FROM trans_timestamps t1
								WHERE t1.trans_detail_status_id = 26
								AND t1.created_at = (
									SELECT MAX(t2.created_at)
									FROM trans_timestamps t2
									WHERE t2.trans_detail_id = t1.trans_detail_id
										AND t2.trans_detail_status_id = 26
								))";

			$tt_reported_sub = "(SELECT t1.* 
								FROM trans_timestamps t1
								WHERE t1.trans_detail_status_id = 36
								AND t1.created_at = (
									SELECT MAX(t2.created_at)
									FROM trans_timestamps t2
									WHERE t2.trans_detail_id = t1.trans_detail_id
										AND t2.trans_detail_status_id = 36
								))";

			$this->db->select('
				td.*, 
				th.*, 
				s.sample_name AS sample_name,
				tt_received.created_at AS date_received,
				tt_analyzed.created_at AS date_analyzed,
				u_analyzed.userFirstName AS analyzed_firstname,
				u_analyzed.userLastName AS analyzed_lastname,
				u_analyzed.userEsign AS analyzed_userEsign,
				prof.name AS analyzed_profession,
				ss_prof.license_no AS license_no,
				ss_prof.license_valid AS license_valid,
				ut_analyzed.userTypeName AS analyzed_usertype,
				tt_reported.created_at AS date_reported,
				lab.laboratory_name AS laboratory_name,
				lab.address AS laboratory_address
			');
			$this->db->from('trans_details td');
			$this->db->join('trans_headers th', 'td.trans_id = th.trans_id', 'left');
			$this->db->join('samples s', 'td.sample_id = s.id', 'left');
			$this->db->join('laboratories lab', 'th.laboratory_id = lab.id', 'left');

			$this->db->join("($tt_received_sub) tt_received", "tt_received.trans_detail_id = td.trans_detail_id", 'left');
			$this->db->join("($tt_analyzed_sub) tt_analyzed", "tt_analyzed.trans_detail_id = td.trans_detail_id", 'left');
			$this->db->join('users u_analyzed', 'u_analyzed.userID = tt_analyzed.created_by', 'left');
			$this->db->join('user_professions ss_prof', 'ss_prof.userID = u_analyzed.userID', 'left');
			$this->db->join('professions prof', 'prof.id = ss_prof.profession_id', 'left');
			$this->db->join('usertype ut_analyzed', 'ut_analyzed.userTypeID = u_analyzed.userTypeID', 'left');
			$this->db->join("($tt_reported_sub) tt_reported", "tt_reported.trans_detail_id = td.trans_detail_id", 'left');

			$this->db->where('td.trans_detail_id', $target_detail_id);

			return $this->db->get()->row_array();
	}

	public function get_pdf_test_results($trans_detail_id) {
		$detail = $this->db->select('trans_id, sample_id, release_ref_number')
						->from('trans_details')
						->where('trans_detail_id', $trans_detail_id)
						->get()
						->row_array();

		$trans_id = $detail['trans_id'];
		$sample_id = $detail['sample_id'];
		$release_ref_number = $detail['release_ref_number'];

		$this->db->select('
			td.trans_detail_id,
			tp.param_name,
			tm.method_name,
			GROUP_CONCAT(DISTINCT tm_ref.method_name SEPARATOR ", ") AS reference_method,
			td.test_exec_lab_result AS test_result
		', false)
		->from('trans_details td')
		->join('trans_headers th', 'td.trans_id = th.trans_id', 'left')
		->join(
			'lab_tests lt',
			'td.lab_test_id = lt.test_id AND lt.laboratory_id = th.laboratory_id',
			'left'
		)
		->join('test_parameters tp', 'lt.test_param_id = tp.id', 'left')
		->join('test_methods tm', 'lt.test_method_id = tm.id', 'left')
		->join('ref_methods tm_ref', 'lt.ref_method_id = tm_ref.id', 'left')
		->where('td.release_ref_number', $release_ref_number) //same results
		->group_by([
			'td.trans_detail_id',
			'tp.param_name',
			'tm.method_name',
			'td.test_exec_lab_result'
		])
		->order_by('td.trans_detail_id', 'ASC');

		return $this->db->get()->result_array();
	}

	

	public function get_lab_signatories($lab_id)
	{
		  if (!is_array($lab_id)) {
				$lab_id = [$lab_id]; // ensure it's an array
			}
		$this->db->select('ss.*, u.userFirstName, u.userLastName, p.name AS profession_name, ut.userTypeName, ut.userTypeID ,u.userEsign');
		$this->db->from('user_signatories_labs usl');              
		$this->db->join('user_professions ss', 'ss.id = usl.user_profession_id', 'inner');
		$this->db->join('users u', 'u.userID = ss.userID', 'left'); 
		$this->db->join('professions p', 'p.id = ss.profession_id', 'left'); 
		$this->db->join('usertype ut', 'ut.userTypeID = u.userTypeID', 'left'); 
		$this->db->where_in('usl.laboratory_id', $lab_id);             
		$this->db->order_by('ss.id', 'ASC');
		return $this->db->get()->result_array();
	}




}

