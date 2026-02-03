<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Coa_lib {

    protected $CI;
    protected $db_tbl;

    public function __construct() {
        $this->CI =& get_instance();

        $this->db_tbl = 'trans_headers';
        $this->CI->load->model('main_model', 'main');
        $this->CI->load->library('custom_lib');
        $this->CI->load->database();
        $this->CI->load->model('main');
    }


      public function select_template_pdf($id, $lab_id = null) {

        if ($lab_id == 3) {
            return $this->generate_mambatangan_pdf($id);
        } else {
            return $this->generate_produ_pdf($id);
        }
    }



      private function render_pdf($html, $filename) {
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $dompdf->stream($filename . ".pdf", ["Attachment" => true]);
    }

    public function formatProfession($text) {
        $text = preg_replace('/\s+/', ' ', trim($text));
        
        $text = mb_convert_case($text, MB_CASE_TITLE, "UTF-8");
        
        $acronyms = ['QA'];
        foreach($acronyms as $a) {
            $text = preg_replace('/\b'.strtolower($a).'\b/i', $a, $text);
        }
        
   
        return $text;
    }

    
    
    public function generate_produ_pdf($trans_detail_id)
    {
       
        $data = $this->CI->main->get_pdf_trans_detail($trans_detail_id);
        $data['trans_detail_id'] = $trans_detail_id;


        $html = $this->generate_pdf_produ_html($data);

        // Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('Arial', 'italic');
        $canvas->page_text(520, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));

        $dompdf->stream($data['release_ref_number'] . "_" . $data['ext_lab_code'] . ".pdf", ["Attachment" => 1]);
    }

    public function generate_mambatangan_pdf($trans_detail_id)
    {
      
        $data = $this->CI->main->get_pdf_trans_detail($trans_detail_id);

        $data['trans_detail_id'] = $trans_detail_id;

        $html = $this->generate_pdf_mambatangan_html($data);

        // Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $canvas = $dompdf->getCanvas();
        $font = $dompdf->getFontMetrics()->getFont('Arial', 'italic');
        $canvas->page_text(520, 820, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 8, array(0,0,0));

        $dompdf->stream($data['release_ref_number'] . "_" . $data['ext_lab_code'] . ".pdf", ["Attachment" => 1]);
    }


    
  
       public function generate_pdf_produ_html($data)
    {
        $analyst_valid = date('F d, Y', strtotime($data['license_valid']));
        $analyst_profession = $this->formatProfession($data['analyzed_profession']);
        $analyst_usertypename = $this->formatProfession($data['analyzed_usertype']);
        $lab_id = $data['laboratory_id']; 

        $signatories = $this->CI->main->get_lab_signatories($lab_id);
        $test_results = $this->CI->main->get_pdf_test_results($data['trans_detail_id']);

        $refs = array_filter(array_unique(array_column($test_results, 'reference_method')));
        $refbody = !empty($refs)
        ? implode(' | ', array_map('htmlspecialchars', $refs))
        : 'None';



        $logo_path = base_url('assets/img/bounty_logo.png');
        $acc1 = base_url('assets/img/certification_international.jpg');
        $acc2 = base_url('assets/img/department_agriculture.png');
        $acc3 = base_url('assets/img/regulation.png');

        $acc1_code = 'CIP/5541/25/02/1241';
        $acc2_code = 'BAI-FL-2025-004(R)';  
        $acc3_code = 'CATO No. 550';

        $columns = ['certified' => null, 'signed' => null];
        foreach ($signatories as $sig) {
            if ($sig['userTypeID'] == 19) $columns['certified'] = $sig;
            if ($sig['userTypeID'] == 17) $columns['signed'] = $sig;
        }

        $img_height = 80; 
        $analyst_esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
        if (!empty($data['analyzed_userEsign'])) {
            $esign_path = FCPATH . 'uploads/esign/' . $data['analyzed_userEsign'];
            if(file_exists($esign_path)){
                $analyst_esign_img = '<img src="'.base_url('uploads/esign/'.$data['analyzed_userEsign']).'" style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;">';
            }
        }

        $signature_html = '';
        foreach (['certified', 'signed'] as $role) {
            if (!empty($columns[$role])) {
                $sig = $columns[$role];
                $esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
                if(!empty($sig['userEsign'])){
                    $esign_path = FCPATH.'uploads/esign/'.$sig['userEsign'];
                    if(file_exists($esign_path)){
                        $esign_img = '<img src="'.base_url('uploads/esign/'.$sig['userEsign']).'" style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;">';
                    }
                }

                $signature_html .= '
                <td style="width:33%; padding-top:25px; vertical-align:top; position:relative;">
                    <div style="position:absolute; top:-30px; left:0; right:0; text-align:center;">
                        '.$esign_img.'
                    </div>
                    <div style="margin-top:35px;">
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                        <strong>'.strtoupper($sig['userFirstName'].' '.$sig['userLastName']).'</strong><br>
                        '.$this->formatProfession($sig['profession_name']).'<br>
                        '.$this->formatProfession($sig['userTypeName']).'<br>
                        License No.: '.$sig['license_no'].'<br>
                        Valid Until: '.date('F d, Y', strtotime($sig['license_valid'])).'
                    </div>
                </td>';
            } else {
                $signature_html .= '<td>---</td>';
            }
        }

        $pages = array_chunk($test_results,10);

        $html = '
        <html>
        <head>
                <style>
                            body { font-family: Arial, sans-serif; font-size: 12px; }
                            .header { width: 100%; margin-bottom: 20px; text-align: center; } /* center header */
                            .header-table { display: inline-table; border-collapse: collapse; border: 0; } /* inline-table to center */
                            .header-table td { vertical-align: middle; border: 0; }
                            .lab-info { font-weight: bold; text-align: center; }
                            .accreditations { display: flex; justify-content: center; gap: 5px; margin-top: 5px; }
                            .accreditations img { max-height: 50px; }
                            h2 { text-align: center; margin: 10px 0; }
                            .client-info { margin-bottom: 20px; }
                            table { border-collapse: collapse; width: 100%; margin-top: 10px; }
                            table, th, td { border: 1px solid #333; }
                            th, td { padding: 2.5px; text-align: center; }
                            th { background-color: #f2f2f2; }
                            .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #555; }
                                            table.no-border, 
                            table.no-border td, 
                            table.no-border th {
                                border: none !important;
                            }

                            table.test-table {
                                table-layout: fixed;
                            }

                            table.test-table thead tr {
                                height: 30px;
                            }

                            table.test-table tbody {
                                height: 112px; /* 4 rows × 28px */
                            }

                        table.test-table {
                            table-layout: fixed;
                            border-collapse: collapse;
                        }

                        table.test-table th, table.test-table td {
                            border: 1px solid #333; 
                            padding: 8px;
                            text-align: center;
                        }

                        tbody .spacer-row td {
                            border: 0 !important;
                            padding: 0;
                            background: transparent;
                        }

                        table.test-table {
                            border-collapse: collapse;
                            border: none;  /* remove table border */
                            }
                            
                 </style>
        </head>
        <body>';

        foreach($pages as $index => $pageRows){
            $tbody = '';
             $maxRows = 10;
             $rowHeight = 20;

            foreach($pageRows as $row){
                $tbody .= '<tr>
                    <td>'.htmlspecialchars($row['param_name']).'</td>
                    <td>'.htmlspecialchars($row['test_result']).'</td>
                    <td>'.htmlspecialchars($row['method_name']).'</td>
                </tr>';
            }
        $remainingRows = $maxRows - count($pageRows);
        $remainingHeight = $remainingRows * $rowHeight;
        
        $tbody .= '<tr class="spacer-row">
            <td colspan="3" style="border:none; padding:0; height:0;"></td>
        </tr>';

            $html .= '
            <div class="page-container">
                <div class="header">
                    <table class="header-table">
                        <tr>
                            <td style="width:5%; vertical-align: middle; border:0; padding-right:4px;">
                                <img src="'.$logo_path.'" alt="Logo" style="max-height:60px;">
                            </td>
                            <td style="width:100%; vertical-align: middle; text-align: left; border:0;">
                                <div style="font-weight: bold; font-size:10.5px; line-height:1.2; margin-left:5px;">
                                    '.$data['coa_laboratory_name'].'<br>
                                    '.$data['laboratory_address'].'
                                </div>
                            </td>
                            <td style="width:50%; vertical-align: middle; text-align: center; border:0;">
                                <table class="no-border">
                               <tr>
                                            <td style="padding:0 10px; text-align:center;">
                                                <img src="'.$acc1.'" alt="Acc 1" style="max-height:50px; margin-bottom:10px"><br>
                                                <span style="font-size:10px;">'.$acc1_code.'</span>
                                            </td>
                                            <td style="padding:0 10px; text-align:center;">
                                                <img src="'.$acc2.'" alt="Acc 2" style="max-height:50px; margin-bottom:10px"><br>
                                                <span style="font-size:10px;">'.$acc2_code.'</span>
                                            </td>
                                            <td style="padding:0 10px; text-align:center;">
                                                <img src="'.$acc3.'" alt="Acc 3" style="max-height:50px;margin-bottom:10px"><br>
                                                <span style="font-size:10px;">'.$acc3_code.'</span>
                                            </td>
                                        </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto 20px;"></div>
                </div>

                <h2 style="text-align:center; margin-bottom:5px;text-transform:uppercase ">Certificate of Analysis</h2>
                <div style="font-size:12px; font-weight:bold; text-align:right;">
                    Reference No. '.$data['release_ref_number'].'
                </div>

                <div class="client-info" style="margin-bottom:20px; font-size:12px; line-height:1.6; display: table; width:100%;">
                    <div style="display: table-row;">
                        <div style="display: table-cell;  width:150px;">Customer</div>
                        <div style="display: table-cell; width:10px;">:</div>
                        <div style="display: table-cell; font-weight:bold; color:#000;"><i>BOUNTY PLUS INC.</i></div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Address</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">'.$data['address'].'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Tel./Fax No</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">'.$data['contact_number'].'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Laboratory Code</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell; font-weight:bold; color:#000;">'.$data['ext_lab_code'].'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">Sample Name</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;  font-weight:bold; color:#000;">'.$data['sample_name'].'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Received</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_received'])).'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Analyzed</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_analyzed'])).'</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Reported</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_reported'])).'</div>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>TEST PARAMETER</th>
                            <th>RESULTS</th>
                            <th>TEST METHOD</th>
                        </tr>
                    </thead>
                    <tbody>'.$tbody.'</tbody>
                </table>';

                   if($remainingHeight > 0){
                        $html .= '<div style="height:'.$remainingHeight.'px;"></div>';
                    }
                $html .= 
                '<div class="remarks" style="margin-top:20px; font-size:12px; line-height:1.4;">
                    <strong>Remarks:</strong><br>
                    Results of analysis as per sample submitted. Samples will be kept only for a month from the date received.<br>
                    <em>*SEP – Standard Error of Prediction</em>
                </div>

                <div class="references" style="margin-top:30px; font-size:12px; line-height:1.4;">
                    <strong>REFERENCE/S:</strong><br>'.$refbody.'
                </div>

                <table class="no-border" style="width:100%;  margin-top:30px; font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                    <tr>
                        <td>Analyzed by:</td>
                        <td>Certified True and Correct:</td>
                        <td>Signed for the Company by:</td>
                    </tr>
                </table>

                <table class="no-border" style="width:100%; margin-top:1px; font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                    <tr>
                        <td style="width:33%; padding-top:25px; vertical-align:top; position:relative;">
                            <div style="position:absolute; top:-30px; left:0; right:0; text-align:center;">
                                '.$analyst_esign_img.'
                            </div>
                            <div style="margin-top:35px;">
                                <div style="border-top:1px solid #000; width:80%; margin:0 auto 4px auto;"></div>
                                <strong>'.$data['analyzed_firstname'].' '.$data['analyzed_lastname'].'</strong><br>
                                '.$analyst_profession.'<br>'.$analyst_usertypename.'<br>
                                License No.: '.$data['license_no'].'<br>
                                Valid Until: '.$analyst_valid.'
                            </div>
                        </td>
                        '.$signature_html.'
                    </tr>
                </table>

                <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:0px auto;"></div>

                <table class="no-border" style="width:100%; max-width:750px; font-size:10px; color:#555; margin:0 auto;">
                    <tr>
                        <td style="text-align:left; padding:2px;">Document Code: '.PDF_DOCU_PRODU_CODE.'</td>
                        <td style="text-align:right; padding:2px;">Downloaded At: '.date('M d, Y H:i:s').'</td>
                    </tr>
                    <tr>
                        <td style="text-align:left; padding:2px;" colspan="2">Effectivity Date: '.PDF_PRODU_EFFECT.'</td>
                    </tr>
                </table>
            </div>';

            if($index < count($pages) - 1){
                $html .= '<div class="page-break"></div>';
            }
        }

        $html .= '</body></html>';

        return $html;
    }


    
     public function generate_pdf_mambatangan_html($data)
    {
        
        $analyst_valid = date('F d, Y', strtotime($data['license_valid']));
        $analyst_profession = $this->formatProfession($data['analyzed_profession']);
        $analyst_usertypename = $this->formatProfession($data['analyzed_usertype']);
        $lab_id = $data['laboratory_id']; 

        $signatories = $this->CI->main->get_lab_signatories($lab_id);
        $test_results = $this->CI->main->get_pdf_test_results($data['trans_detail_id']);

            $tbody = '';
            foreach ($test_results as $row) {
                $tbody .= '<tr>
                    <td>'.htmlspecialchars($row['param_name']).'</td>
                    <td>'.htmlspecialchars($row['test_result']).'</td>
                    <td>'.htmlspecialchars($row['method_name']).'</td>
                </tr>';
            }


        $refs = array_filter(array_unique(array_column($test_results, 'reference_method')));
        $refbody = !empty($refs)
        ? implode(' | ', array_map('htmlspecialchars', $refs))
        : 'None';

            
            // Logo and accreditation images
            $logo_path = base_url('assets/img/bounty_logo.png');
            $acc1 = base_url('assets/img/certification_international.jpg');
            $acc2 = base_url('assets/img/department_agriculture.png');
            $acc3 = base_url('assets/img/regulation.png');

            $acc1_code = 'CIP/5541/25/02/1241';
            $acc2_code = 'BAI-FL-2025-004(R)';  
            $acc3_code = 'CATO No. 550';

        $columns = ['certified' => null, 'signed' => null];
        foreach ($signatories as $sig) {
            if ($sig['userTypeID'] == 19) $columns['certified'] = $sig;
            if ($sig['userTypeID'] == 17) $columns['signed'] = $sig;
        }


     $img_height = 80; 


        $analyst_esign_img = '';
        if (!empty($data['analyzed_userEsign'])) {
            $real_file_name = $data['analyzed_userEsign']; 
            $esign_path = FCPATH . 'uploads/esign/' . $real_file_name;

            if (file_exists($esign_path)) {
                $analyst_esign_img = '<img src="' . base_url('uploads/esign/' . $real_file_name) . '" style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;">';
            } else {
                $analyst_esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
            }
        } else {
            $analyst_esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
        }


        $signature_html = '';
        foreach (['certified', 'signed'] as $role) {
            if (!empty($columns[$role])) {
                $sig = $columns[$role];

            $esign_img = '';
            if (!empty($sig['userEsign'])) {
                $real_file_name = $sig['userEsign']; 
                $esign_path = FCPATH.'uploads/esign/'.$real_file_name;
                if(file_exists($esign_path)){
                    $esign_img = '<img src="'.base_url('uploads/esign/'.$real_file_name).'" style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;">';
                } else {
                    $esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
                }
            } else {
                $esign_img = '<div style="height:'.$img_height.'px; display:block; margin:0 auto 6px auto;"></div>';
            }

                $signature_html .= '
                <td style="width:33%; padding-top:25px; vertical-align:top; position:relative;">

                    <div style="position:absolute; top:-30px; left:0; right:0; text-align:center;">
                        '.$esign_img.'
                    </div>

                    <div style="margin-top:35px;">
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                        <strong>'.strtoupper($sig['userFirstName'].' '.$sig['userLastName']).'</strong><br>
                        '.$this->formatProfession($sig['profession_name']).'<br>
                        '.$this->formatProfession($sig['userTypeName']).'<br>
                        License No.: '.$sig['license_no'].'<br>
                        Valid Until: '.date('F d, Y', strtotime($sig['license_valid'])).'
                    </div>

                </td>';
            } else {
                $signature_html .= '<div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div><strong>---</strong><br>---';
            }
            $signature_html .= '</td>';
        }
        $signature_html .= '</tr></table>';


        $pages = array_chunk($test_results, 10);

            $html = '
            <html>
            <head>
                <style>
                            body { font-family: Arial, sans-serif; font-size: 12px; }
                            .header { width: 100%; margin-bottom: 20px; text-align: center; } /* center header */
                            .header-table { display: inline-table; border-collapse: collapse; border: 0; } /* inline-table to center */
                            .header-table td { vertical-align: middle; border: 0; }
                            .lab-info { font-weight: bold; text-align: center; }
                            .accreditations { display: flex; justify-content: center; gap: 5px; margin-top: 5px; }
                            .accreditations img { max-height: 50px; }
                            h2 { text-align: center; margin: 10px 0; }
                            .client-info { margin-bottom: 20px; }
                            table { border-collapse: collapse; width: 100%; margin-top: 10px; }
                            table, th, td { border: 1px solid #333; }
                            th, td { padding: 2.5px; text-align: center; }
                            th { background-color: #f2f2f2; }
                            .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #555; }
                                            table.no-border, 
                            table.no-border td, 
                            table.no-border th {
                                border: none !important;
                            }

                            table.test-table {
                                table-layout: fixed;
                            }

                            table.test-table thead tr {
                                height: 30px;
                            }

                            table.test-table tbody {
                                height: 112px; /* 4 rows × 28px */
                            }

                        table.test-table {
                            table-layout: fixed;
                            border-collapse: collapse;
                        }

                        table.test-table th, table.test-table td {
                            border: 1px solid #333; 
                            padding: 8px;
                            text-align: center;
                        }

                        tbody .spacer-row td {
                            border: 0 !important;
                            padding: 0;
                            background: transparent;
                        }

                        table.test-table {
                            border-collapse: collapse;
                            border: none;  /* remove table border */
                            }
                            
                 </style>
            </head>
            <body>';

        foreach($pages as $index => $pageRows){
            $tbody = '';
             $maxRows = 10;
             $rowHeight = 20;

            foreach($pageRows as $row){
                $tbody .= '<tr>
                    <td>'.htmlspecialchars($row['param_name']).'</td>
                    <td>'.htmlspecialchars($row['test_result']).'</td>
                    <td>'.htmlspecialchars($row['method_name']).'</td>
                </tr>';
            }
        $remainingRows = $maxRows - count($pageRows);
        $remainingHeight = $remainingRows * $rowHeight;
        
        $tbody .= '<tr class="spacer-row">
            <td colspan="3" style="border:none; padding:0; height:0;"></td>
        </tr>';


                $html .= '
                <div class = "page-container">
                        <table class="header-table"  style="margin-top:15px;>
                            <tr>
                                <!-- Logo -->
                                <td style="width:5%; vertical-align: middle; border:0; padding-right:5px;">
                                    <img src="'.$logo_path.'" alt="Logo" style="max-height:60px;">
                                </td>

                                <td style="width:100%; vertical-align: middle; text-align: left; border:0;">
                                    <div style="font-weight: bold; font-size:11px; line-height:1.2; margin-left:5px;">
                                        '.$data['coa_laboratory_name'].'<br>
                                        '.$data['laboratory_address'].'
                                    </div>
                                </td>

                            </tr>
                        </table>

                        <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto 20px;"></div>
                        </div>

                            <h2 style="text-align:center; margin-bottom:5px;text-transform:uppercase ">Certificate of Analysis</h2>
                            <div style="font-size:12px; font-weight:bold; text-align:right;">
                            Reference No. '.$data['release_ref_number'].'
                            </div>

                            <div class="client-info" style="margin-bottom:20px; font-size:12px; line-height:1.6; display: table; width:100%;">
                                <div style="display: table-row;">
                                    <div style="display: table-cell;  width:150px;">Customer</div>
                                    <div style="display: table-cell; width:10px;">:</div>
                                    <div style="display: table-cell; font-weight:bold; color:#000;"><i>BOUNTY PLUS INC.</i></div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Address</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;">'.$data['address'].'</div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Tel./Fax No</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;">'.$data['contact_number'].'</div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Laboratory Code</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell; font-weight:bold; color:#000;">'.$data['ext_lab_code'].'</div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell;">Sample Name</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;  font-weight:bold; color:#000;">'.$data['sample_name'].'</div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Date Received</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_received'])).'</div>
                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Date Analyzed</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_analyzed'])).'</div>

                                </div>
                                <div style="display: table-row;">
                                    <div style="display: table-cell; ">Date Reported</div>
                                    <div style="display: table-cell;">:</div>
                                    <div style="display: table-cell;">'.date('M d, Y', strtotime($data['date_reported'])).'</div>

                                </div>
                            </div>

                        <table>
                            <thead>
                                <tr>
                                    <th>TEST PARAMETER</th>
                                    <th>RESULTS</th>
                                    <th>TEST METHOD</th>
                                </tr>
                            </thead>
                            <tbody>
                                '.$tbody.'
                            </tbody>
                           </table>';

                    if($remainingHeight > 0){
                        $html .= '<div style="height:'.$remainingHeight.'px;"></div>';
                    }
                    $html .= '
                            <div class="remarks" style="margin-top:20px; font-size:12px; line-height:1.4;">
                                <strong>Remarks:</strong><br>
                                Results of analysis as per sample submitted. Samples will be kept only for a month from the date received.<br>
                                <em>*SEP – Standard Error of Prediction</em>
                            </div>

                    
                            <div class="references" style="margin-top:60px; font-size:12px; line-height:1.4;">
                                <strong>REFERENCE/S:</strong><br>
                                '.$refbody.'
                            </div>

                            <table class = "no-border"style="width:100%;  margin-top:30px;  font-size:12px; line-height:0.4; text-align:center; table-layout:fixed;">
                            <tr>
                            <td> Analyzed by:
                            </td>
                            <td> Certified True and Correct:
                            </td>
                            <td> Signed for the Company by:
                            </td>
                            </tr>
                            </table>

                            <table class = "no-border"style="width:100%; margin-top:1px; font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                            
                                <tr>
                                    <td style="width:33%; padding-top:25px; vertical-align:top; position:relative;">
                                        <div style="position:absolute; top:-30px; left:0; right:0; text-align:center;">
                                            '.$analyst_esign_img.'
                                        </div>
                                        <div style="margin-top:35px;">
                                            <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                                            <strong>'.$data['analyzed_firstname'].' '.$data['analyzed_lastname'].'</strong><br>
                                            '.$analyst_profession.'<br>
                                            '.$analyst_usertypename.'<br>
                                            License No.: '.$data['license_no'].'<br>
                                            Valid Until: '.$analyst_valid.'
                                        </div>
                                    </td>
                                    '.$signature_html.'

                                </tr>


                            </table>

                            <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:0px auto;"></div>

                                <table class = "no-border"style="width:100%; max-width:750px; font-size:10px; color:#555; margin:0 auto;">
                                    <tr>
                                        <td style="text-align:left; padding:2px;">
                                            Document Code: '.PDF_DOCU_MAMBATANGAN_CODE.'
                                        </td>
                                        <td style="text-align:right; padding:2px;">
                                            Downloaded At: '.date('M d, Y H:i:s').'
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align:left; padding:2px;" colspan="2">
                                            Effectivity Date: '.PDF_MAMBATANGAN_EFFECT.'
                                        </td>
                                    </tr>
                                </table>
                            </div>
                    </div>'
                    
                    ;



                    if($index < count($pages) - 1){
                    $html .= '<div class="page-break"></div>';
                    }
                }
           

               $html .= '</body></html>';

        return $html;

    }






}
