<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function __construct() {
    	parent::__construct();
		$this->load->helper('download');
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
	}



}

	
