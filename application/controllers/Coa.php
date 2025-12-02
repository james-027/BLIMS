<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;


class Coa extends CI_Controller
{

    public function __construct() {
    	parent::__construct();

		$this->controller = strtolower(__CLASS__);
		$this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('encryption');
        $this->load->library('Email_format');
        $this->load->library('Coa_lib'); 

    }


    public function select_template_pdf($trans_detail_id, $lab_id = null) {
        if (!$trans_detail_id) show_404();

        if ($lab_id == 3) {
            $file = $this->coa_lib->generate_mambatangan_pdf($trans_detail_id);
        } else {
            $file = $this->coa_lib->generate_produ_pdf($trans_detail_id);
        }

        if (!$file || !file_exists($file)) {
            show_error('PDF could not be generated');
        }

        $this->load->helper('download');
        force_download(basename($file), file_get_contents($file));
    }






}
