<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Custom_lib {

    public function _get_access ($filter, $column) {
    	$ci =& get_instance();

		$check_bc_access = $ci->main->check_data('key', $filter, true, false, false);
		
		if($check_bc_access['result']){
			if($column == 'bcID'){
				return $check_bc_access['info']->bcID;
			} else if ($column == 'keyID'){
				return $check_bc_access['info']->keyID;
			} else if ($column == 'keyCode'){
				return $check_bc_access['info']->keyCode;
			} else if ($column == 'keyCode2'){
				return $check_bc_access['info']->keyCode2;
			}
		} else {
			return NULL;
		}
	}

	public function _get_data_access ($filter, $column=null) {
    	$ci =& get_instance();
		$filter = 
		$check_bc_access = $ci->main->get_data('userbc', $filter, false, false, false);
		
		if(!empty($check_bc_access)){
			foreach ($check_bc_access as $r) {
				$bc_arr[] = $r->bcID;
			}
			return $bc_arr;
		} else {
			return NULL;
		}
	}

	public function _get_sloc_access($filter){
		$ci =& get_instance();

		$join = array(
					'storagelocation b' => 'a.slocID = b.slocID and a.statusID = 1',
					'cgdtl c' => 'b.slocID = c.slocID',
					'vet d' => 'c.vetID = d.vetID',
					);
		$check_module_access = $ci->main->get_join('usersloc a', $join, false, false, false, false, $filter, false, false, false, false, false);

		
		if(!empty($check_module_access)){
			return $check_module_access;
		} else {
			return false;
		}
	}


	public function _get_available_access($filter){
		$ci =& get_instance();

		$join = array(
					'key b' => 'a.keyID = b.keyID and a.statusID = 1',
					'businesscenter c' => 'b.bcID = c.bcID',
					'businessunit d' => 'b.buID = d.buID'
					);
		$check_available_access = $ci->main->get_join('userkey a', $join, false, false, false, false, $filter, false, false, false, false, false);
		
		if(!empty($check_available_access)){
			return $check_available_access;
		} else {
			return false;
		}
	}

}



?>