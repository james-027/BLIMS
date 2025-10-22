<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function encode($token){
	$cipher_method = 'aes-128-ctr';
	  $enc_key = 'jonel';
	  $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($cipher_method));
	  $crypted_token = openssl_encrypt($token, $cipher_method, $enc_key, 0, $enc_iv) . "::" . bin2hex($enc_iv);
	  unset($token, $cipher_method, $enc_key, $enc_iv);
	
	$encode_id= strtr(
		$crypted_token,
		array(
			'+' => '.',
			'=' => '-',
			'/' => '~'
		)
	);

	   return $encode_id;
}


function decode($token){
	if(!empty($token)){
		$enc_key = 'jonel';
		$decode_id= strtr(
			$token,
			array(
				'.' => '+',
				'-' => '=',
				'~' => '/'
			)
		);

		if(count(explode("::", $decode_id)) > 1){
			list($crypted_token, $enc_iv) = explode("::", $decode_id);
		}else{
			list($crypted_token, $enc_iv) = array($decode_id, '');
		}

		$crypted_token = $crypted_token;
		$cipher_method = 'aes-128-ctr';
		$token = openssl_decrypt($crypted_token, $cipher_method, $enc_key, 0, hex2bin($enc_iv));
		unset($crypted_token, $cipher_method, $enc_key, $enc_iv);
	}

	return $token;
}

function clean_data($data){
	$instanceName =& get_instance();
	$instanceName->load->helper('security');
	$clean = $instanceName->security->xss_clean($instanceName->db->escape_str($data));
	return $clean;
}

function generate_random($length){
	$random = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	return $random;
}

function create_id($format, $count){
	
	if($count > 0 && $count < 10){
		$id = $format . '00000' . $count;
	}elseif($count >= 10 && $count <= 99){
		$id = $format . '0000' . $count;
	}elseif($count >= 100 && $count <= 999){
		$id = $format . '000' . $count;
	}elseif($count >= 1000 && $count <= 9999){
		$id = $format . '00' . $count;
	}elseif($count >= 10000 && $count <= 99999){
		$id = $format . '0' . $count;
	}else{
		$id = $format . $count;
	}

	return $id;
}

function date_now(){
	$date = date('Y-m-d H:i:s');
	return $date;
}

function time_stamp($myDate){
	if($myDate){
		$ts = date("Y-m-d H:i:s",strtotime($myDate));
	} else {
		$ts = NULL;
	}
		
	return $ts;
}

function time_stamp_display($myDate){
	if($myDate){
		$ts = date("Y/m/d h:i A",strtotime($myDate));
	} else {
		$ts = NULL;
	}
		
	return $ts;
}

function my_standard_date($myTS){
	if($myTS){
		$sd = date("Y-m-d H:i:s",strtotime($myTS));
	} else {
		$sd = NULL;
	}
		
	return $sd;
	
}

function lbsi_date($myTS){
	if($myTS){
		$sd = date("Ymd",strtotime($myTS));
	} else {
		$sd = NULL;
	}
		
	return $sd;
	
}

function date_display($myTS, $format = 'Y-m-d'){
	if($myTS){
		$sd = date($format,strtotime($myTS));
	} else {
		$sd = NULL;
	}
		
	return $sd;
	
}

function picker_date($myTS){
	
	if($myTS){
		$pd = date("m/d/Y",strtotime($myTS));
	} else {
		$pd = NULL;
	}
	return $pd;
}

function date_addition($date, $addends, $format, $add_type = 'days'){
	if(($date || $date != '') && ($addends || $addends != '') ){
		$addends_string = ' + '.$addends.' '.$add_type;
		$total_date = date($format, strtotime($date . $addends_string));
	}
	return $total_date;
}

function date_subtraction($date, $subtrahend, $format, $subtract_type = 'days'){
	if(($date || $date != '') && ($subtrahend || $subtrahend != '') ){
		$subtrahend_string = ' - '.$subtrahend.' '.$subtract_type;
		$total_date = date($format, strtotime($date . $subtrahend_string));
	}
	return $total_date;
}

function check_num($num){
	if(!is_null($num) && is_numeric($num)){
		return $num;
		
	}else{
		return NULL;
	}
}



function check_null($num){
	if(!is_null($num) || $num == 0){
		return $num;
	}else{
		return null;
	}
}

function check_array($var){
	if(isset($var)){
		return $var;
	}else{
		return 0;
	}
}

function decimal_format($num, $dec_places=0){
	
	$num = str_replace(',', '', $num);
	if($num == '' || $num <= 0){
		if($num < 0){
			//return '('.number_format(-$num,$dec_places,'.',',').')';
			return number_format($num,$dec_places,'.',',');
		} else {
			return '';
		}
	}else{
		
		return number_format($num,$dec_places,'.',',');
	}

	
}

function datagrid_decimal_format($num, $dec_places=0){
	
	$num = str_replace(',', '', $num);
	if($num == '' || $num <= 0){
		if($num < 0){
			//return '('.number_format(-$num,$dec_places,'.',',').')';
			return number_format($num,$dec_places,'.',',');
		} else {
			return '';
		}
	}else{
		
		return number_format($num,$dec_places,'.',',');
	}

	
}

function convert_num($value){
	if($value >= 1000000000){
		$value = $value/1000000000;
		$value = number_format($value, 2) . ' B';
	}else if($value >= 1000000 && $value < 1000000000){
		$value = $value/1000000;
		$value = number_format($value, 2) . ' M';
	}else if($value > 1000 && $value < 1000000){
		$value = $value/1000;
		$value = number_format($value) . ' K';
	}else if($value > 99 && $value < 999){
		$value = $value/1000;
		$value = number_format($value, 2) . ' K';
	}else{
		 $value = '';
	}
	return $value;
}

function right_display($value){
	if($value){
		return '<div class="text-right">'.$value.'</div>';
	} else {
		return '';
	}
}

function get_link_view($where=FALSE, $row_type=FALSE, $addtl_param = null, $span = false, $spanPhraseLen = 15, $order=FALSE, $group=FALSE, $select=FALSE, $string = false){

	$ci =& get_instance();
	$ci->load->database();

	$select = 'a.view, b.linkName, b.link';
	$join = array(
		'modules b' => 'a.moduleID = b.moduleID and a.statusID = 1 and b.statusID = 1',
		'userkey c'	=> 'a.userID = c.userID and a.keyID = c.keyID and c.current=1'
	);
	$tbl = 'usermodules a';

	foreach($join as $row=>$value){
		$ci->db->join($row, $value);
	}

	if($select != FALSE){
		$ci->db->select($select);
	}
	
	if($group != FALSE){
		$ci->db->group_by($group);
	}

	if($order != FALSE){
		$ci->db->order_by($order);
	}

	if($where != FALSE){
		$ci->db->where($where);
	}

	$query = $ci->db->get($tbl);
	if($row_type === FALSE){
		$result = $query->result();
	}else{
		$result = $query->row();
	}
	if($string){
		return $ci->db->last_query();
	}

	if(!empty($result)){
		if($result->view == 1){
			$phraseLen = $spanPhraseLen;
			$url =
			'<a '.get_shortened_phrase($result->linkName, $phraseLen)->tooltip.' href="'.base_url().$result->link.'">
				<span class="sub-item">'.get_shortened_phrase($result->linkName, $phraseLen)->phrase.'</span>';
			
			
			if($addtl_param){
				$phraseLen = $span ? $spanPhraseLen : 15;
				$url =
				'<a '.get_shortened_phrase($result->linkName, $phraseLen)->tooltip.' href="'.base_url().$result->link.'/'.$addtl_param.'">
					<span class="sub-item">'.get_shortened_phrase($result->linkName, $phraseLen)->phrase.'</span>';
			}
			if($span){
				$url .= '<span id="'.$span.'"></span>';
			}
			$url .= '</a>';
		} else {
			$url = '';
		}
	} else {
		$url = '';
	}

	return $url;

}

function get_user_theme ($where=FALSE, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $string = false){
	$ci =& get_instance();
	$ci->load->database();

	$join = array('usertheme b' => 'a.userID = b.userID and a.statusID = 1 and b.statusID = 1');
	$tbl = 'users a';

	foreach($join as $row=>$value){
		$ci->db->join($row, $value);
	}

	if($select != FALSE){
		$ci->db->select($select);
	}
	
	if($group != FALSE){
		$ci->db->group_by($group);
	}

	if($order != FALSE){
		$ci->db->order_by($order);
	}

	if($where != FALSE){
		$ci->db->where($where);
	}

	$query = $ci->db->get($tbl);
	if($row_type === FALSE){
		$result = $query->result();
	}else{
		$result = $query->row();
	}
	if($string){
		return $ci->db->last_query();
	}
	if(!empty($result)){
		return $result;
	} else {
		$result = array(
			'backgroundColor'	=> 'white',
			'sideBarColor'	=> 'white',
			'topBarColor'	=> 'blue',
			'logoHeaderColor'	=> 'light-blue',
			'btnColor'	=> 'btn-success'
		);

		$object = (object) $result;
		return $object;
	}
}

function get_user_rating ($where=FALSE, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $string = false){
	$ci =& get_instance();
	$ci->load->database();

	$join = null;
	$tbl = 'userrating a';

	$select = 'a.userRatingID, a.userID';

	if(!empty($join)){
		foreach($join as $row=>$value){
			$ci->db->join($row, $value);
		}
	}

	if($select != FALSE){
		$ci->db->select($select);
	}
	
	if($group != FALSE){
		$ci->db->group_by($group);
	}

	if($order != FALSE){
		$ci->db->order_by($order);
	}

	if($where != FALSE){
		$ci->db->where($where);
	}

	$query = $ci->db->get($tbl);
	if($row_type === FALSE){
		$result = $query->result();
	}else{
		$result = $query->row();
	}
	if($string){
		return $ci->db->last_query();
	}
	if(!empty($result)){
		return $result;
	} else {
		$result = array(
			'userRatingID'	=> NULL,
			'userID'	=> NULL
		);

		$object = (object) $result;
		return $object;
	}
}

function get_shortened_phrase($phrase, $length = 15, $placement = 'bottom'){
	if(strlen($phrase)*1 > $length){
		$s_phrase = @substr($phrase, 0, $length).'...';
		$tooltip = 'data-toggle="tooltip" data-placement="'.$placement.'" title="'.$phrase.'"';
	} else {
		$s_phrase = $phrase;
		$tooltip = '';
	}
	$result = array(
		'phrase' => $s_phrase,
		'tooltip' => $tooltip
	);
	$object = (object) $result;
	return $object;
}

function get_module_access($where=FALSE, $row_type=FALSE, $order=FALSE, $group=FALSE, $select=FALSE, $string = false){

	$ci =& get_instance();
	$ci->load->database();

	$select = '
	a.view,
	a.add,
	a.edit,
	a.act,
	a.post,
	a.canc,
	a.prnt,
	a.ulod,
	a.dlod,
	a.clear,
	a.appr
	';

	$join = array(
		'modules b' => 'a.moduleID = b.moduleID and a.statusID = 1 and b.statusID = 1',
		'userkey c'	=> 'a.userID = c.userID and a.keyID = c.keyID and c.current=1'
	);
	$tbl = 'usermodules a';

	foreach($join as $row=>$value){
		$ci->db->join($row, $value);
	}

	if($select != FALSE){
		$ci->db->select($select);
	}
	
	if($group != FALSE){
		$ci->db->group_by($group);
	}

	if($order != FALSE){
		$ci->db->order_by($order);
	}

	if($where != FALSE){
		$ci->db->where($where);
	}

	$query = $ci->db->get($tbl);
	if($row_type === FALSE){
		$result = $query->result();
	}else{
		$result = $query->row();
	}
	if($string){
		return $ci->db->last_query();
	}
	
	if(!empty($result)){
		return $result;
	} else {
		$result = array(
			'view'	=> false,
			'add'	=> false,
			'edit'	=> false,
			'act'	=> false,
			'post'	=> false,
			'canc'	=> false,
			'prnt'	=> false,
			'ulod'	=> false,
			'dlod'	=> false,
			'clear'	=> false,
			'appr'	=> false
		);

		$object = (object) $result;
		return $object;
	}
}


function datediff( $str_interval, $date_minor, $date_major, $relative=false){

	if( is_string( $date_minor)) $date_minor = date_create( $date_minor);
	if( is_string( $date_major)) $date_major = date_create( $date_major);

	$diff = date_diff( $date_minor, $date_major, ! $relative);
  
	switch( $str_interval){
		case "y":
			$total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
		case "m":
			$total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
		break;
		case "d":
			$total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
		break;
		case "h":
			$total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
		break;
		case "i":
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
		break;
		case "s":
			$total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
		break;
	}
	if( $diff->invert){
		return -1 * $total;
	} else {
		return $total;
	}
}


/**
 * Return the first day of the Week/Month/Quarter/Year that the
 * current/provided date falls within
 *
 * @param string   $period The period to find the first day of. ('year', 'quarter', 'month', 'week')
 * @param DateTime $date   The date to use instead of the current date
 *
 * @return DateTime
 * @throws InvalidArgumentException
 */
function firstDayOf($period, DateTime $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');
 
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period) {
        case 'year':
            $newDate->modify('first day of january ' . $newDate->format('Y'));
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('first day of january ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('first day of april ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('first day of july ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('first day of october ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('first day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
            break;
    }
 
    return $newDate;
}



/**
 * Return the last day of the Week/Month/Quarter/Year that the
 * current/provided date falls within
 *
 * @param string   $period The period to find the last day of. ('year', 'quarter', 'month', 'week')
 * @param DateTime $date   The date to use instead of the current date
 *
 * @return DateTime
 * @throws InvalidArgumentException
 */
function lastDayOf($period, DateTime $date = null)
{
    $period = strtolower($period);
    $validPeriods = array('year', 'quarter', 'month', 'week');
 
    if ( ! in_array($period, $validPeriods))
        throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));
 
    $newDate = ($date === null) ? new DateTime() : clone $date;
 
    switch ($period)
    {
        case 'year':
            $newDate->modify('last day of december ' . $newDate->format('Y'));
            break;
        case 'quarter':
            $month = $newDate->format('n') ;
 
            if ($month < 4) {
                $newDate->modify('last day of march ' . $newDate->format('Y'));
            } elseif ($month > 3 && $month < 7) {
                $newDate->modify('last day of june ' . $newDate->format('Y'));
            } elseif ($month > 6 && $month < 10) {
                $newDate->modify('last day of september ' . $newDate->format('Y'));
            } elseif ($month > 9) {
                $newDate->modify('last day of december ' . $newDate->format('Y'));
            }
            break;
        case 'month':
            $newDate->modify('last day of this month');
            break;
        case 'week':
            $newDate->modify(($newDate->format('w') === '0') ? 'now' : 'sunday this week');
            break;
    }
 
    return $newDate;
}

function expColor($thColor = null){
	switch ($thColor) {
        case 'dark':
            $expThColor = '404040';
            $expFontColor = 'ffffff';
            $expDtColor = '32';
            $fontColor = 'text-white';
            break;
        case 'primary':
            $expThColor = '0066cc';
            $expFontColor = 'ffffff';
            $expDtColor = '47';
            $fontColor = 'text-white';
            break;
        case 'warning':
            $expThColor = 'cca300';
            $expFontColor = 'ffffff';
            $expDtColor = '36';
            $fontColor = 'text-white';
            break;
        case 'info':
            $expThColor = '0099cc';
            $expFontColor = 'ffffff';
            $expDtColor = '47';
            $fontColor = 'text-white';
            break;
        case 'success':
            $expThColor = '009900';
            $expFontColor = 'ffffff';
            $expDtColor = '42';
            $fontColor = 'text-white';
            break;
        case 'danger':
            $expThColor = 'b30000';
            $expFontColor = 'ffffff';
            $expDtColor = '37';
            $fontColor = 'text-white';
            break;
        case 'light':
            $expThColor = 'e6e6e6';
            $expFontColor = '111111';
            $expDtColor = '32';
            $fontColor = 'text-white';
            break;
        case 'secondary':
            $expThColor = '9933ff';
            $expFontColor = 'ffffff';
            $expDtColor = '32';
            $fontColor = 'text-white';
            break;
        
        default:
            $expThColor = 'ACDCFF';
            $expFontColor = '111111';
            $expDtColor = '27';
            $fontColor = 'text-dark';
            break;
    }
    $result = array(
    	'expThColor' => $expThColor,
    	'expFontColor' => $expFontColor,
    	'expDtColor' => $expDtColor,
    	'fontColor'	=>	$fontColor
    );
    $object = (object) $result;
	return $object;
}

function tableFontColor($userID = null){
	$thColor = get_user_theme(array('a.userID' => decode($userID)), true)->backgroundColor;
	switch ($thColor) {
        case 'dark':
            $tableFontColor = 'text-white';
			break;
        default:
            $tableFontColor = 'text-black';
            break;
    }
    $result = array(
    	'fontColor' => $tableFontColor
    );
    $object = (object) $result;
	return $object;
}


function menu_link_to_display($givenLink = null, $alias = null, $menuName=null, $segmentNo=null, $struc = 'simple', $icon=null){
	$CI =& get_instance();
	$userID = decode($CI->session->userdata[APP_SESS_NAME]['userID']);

	switch ($struc) {
		case 'simple':
			$added_class = $CI->uri->segment($segmentNo) == $givenLink ? ' active' : '';
			$show = $added_class == ' active' ? ' show' : '';

			$where =
			" a.userID = ".$userID." AND view = 1 AND
			(
				alias = '".$alias."'
			)";
			$viewAccess = get_module_access($where, true)->view;
			
			$result = '';
			if($viewAccess == 1){
				$result = '
					<li class="'.$added_class.'">
						'.get_link_view(array('alias' => $alias, 'a.userID' => $userID), true).'
					</li>';
			}

			return $result;
			break;
		
		case 'complex':
			if(is_array($givenLink) && is_array($alias)){
				if(!empty($givenLink) && !empty($alias)){
					
					$added_class_parent = in_array($CI->uri->segment($segmentNo), $givenLink) ? ' active' : '';
					$showParent = $added_class_parent == ' active' ? ' show' : '';
					
					$aliasFilter = join("','", $alias);
					$whereParent = " a.userID = ".$userID." AND view = 1 AND alias IN ('".$aliasFilter."')";
					$viewAccess = get_module_access($whereParent, true)->view;

					$result = '';
					if($viewAccess == 1){
						$result = '<li>
						<a data-toggle="collapse" href="#sub-'.$givenLink[0].'">
							<span class="sub-item">'.$menuName.'</span>
							<span class="caret"></span>
						</a>
						<div class="collapse '.$showParent.'" id="sub-'.$givenLink[0].'">
							<ul class="nav nav-collapse subnav">';

						$i = 0;
						foreach($givenLink as $row_link){
							$added_class = $CI->uri->segment($segmentNo) == $row_link ? ' active' : '';
							$result .= '
								<li class="'.$added_class.'">
									'.get_link_view(array('alias' => $alias[$i], 'a.userID' => $userID), true).'
								</li>';
							$i++;
						}

						$result .= '</ul></div></li>';
					}
				}
			}
			
			
			return $result;
			
			break;
		
		case 'prefix':
			if(is_array($givenLink) && is_array($alias)){
				if(!empty($givenLink) && !empty($alias)){
					
					$added_class_parent = '';
					foreach($givenLink as $key => $value){
						foreach($value as $value_seg => $value_link){
							if($CI->uri->segment($value_seg) == $value_link){
								$added_class_parent = ' active';
							}
						}
					}
					$showParent = $added_class_parent == ' active' ? ' show' : '';
					
					
					$aliasFilter = join("','", $alias);
					$whereParent = " a.userID = ".$userID." AND view = 1 AND alias IN ('".$aliasFilter."')";
					$viewAccess = get_module_access($whereParent, true)->view;


					
					$result = '';
					$hashID = strtolower(str_replace(" ", "-", $menuName));
					if($viewAccess == 1){
						$result = '
						<li class="nav-item '.$added_class_parent.' submenu">
							<a data-toggle="collapse" href="#'.$hashID.'">
								<i class="fas '.$icon.'"></i>
								<p>'.$menuName.'</p>
								<span class="caret"></span>
							</a>
							<div class="collapse '.$showParent.'" id="'.$hashID.'">
								<ul class="nav nav-collapse">';

						
					}
				}
			}
			
			
			return $result;
			
			break;
		
		case 'suffix':
			if(is_array($givenLink) && is_array($alias)){
				if(!empty($givenLink) && !empty($alias)){
					
					$added_class_parent = '';
					foreach($givenLink as $key => $value){
						foreach($value as $value_seg => $value_link){
							if($CI->uri->segment($value_seg) == $value_link){
								$added_class_parent = 'active';
							}
						}
					}
					$showParent = $added_class_parent == ' active' ? ' show' : '';
					
					
					$aliasFilter = join("','", $alias);
					$whereParent = " a.userID = ".$userID." AND view = 1 AND alias IN ('".$aliasFilter."')";
					$viewAccess = get_module_access($whereParent, true)->view;


					$result = '';
					$hashID = strtolower(str_replace(" ", "-", $menuName));
					if($viewAccess == 1){
						$result = '</ul></div></li>';
					}
				}
			}
			
			
			return $result;
			
			break;
		
		default:
			# code...
			break;
	}

}


?>