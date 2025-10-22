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
}