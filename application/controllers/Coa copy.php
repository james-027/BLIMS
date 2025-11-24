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
        $this->load->library('email_format');
        $this->load->library('encryption');

    }


    public function select_template_pdf($id, $lab_id = null) {
        if (!$id) show_404();
        
        if($lab_id == 3){
            $this->generate_mambatangan_pdf($id);
        }else{
            $this->generate_produ_pdf($id);
        }

    }


    public function generate_produ_pdf($id = null)
    {
        if (!$id) show_404();

        $id = strtr($id, '-_', '+/');
        $mod4 = strlen($id) % 4;
        if ($mod4) {
            $id .= str_repeat('=', 4 - $mod4);
        }

        $trans_detail_id = $this->encryption->decrypt(base64_decode($id));

        if (!$trans_detail_id) show_404();


            // Dompdf options
            $options = new Options();
            $options->set('isRemoteEnabled', true); 
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);

            $data = $this->main->get_pdf_trans_detail($trans_detail_id);

            $analyst_valid = date('F d, Y', strtotime($data['license_valid']));
            $analyst_profession = ucwords(strtolower($data['analyzed_profession']));
            $analyst_usertypename = ucwords(strtolower($data['analyzed_usertype']));

            $lab_id = $data['laboratory_id']; 

            $signatories = $this->main->get_lab_signatories($lab_id);

            $test_results = $this->main->get_pdf_test_results($trans_detail_id);

            $tbody = '';
            foreach ($test_results as $row) {
                $tbody .= '<tr>
                    <td>'.htmlspecialchars($row['param_name']).'</td>
                    <td>'.htmlspecialchars($row['test_result']).'</td>
                    <td>'.htmlspecialchars($row['method_name']).'</td>
                </tr>';
            }

            $refbody = '';
            $refs = array_column($test_results, 'reference_method');

            // Remove duplicates and empty values
            $refs = array_filter(array_unique($refs));

            if (!empty($refs)) {
                $refbody .= implode('<br>', $refs);
            } else {
                $refbody .= '<strong>REFERENCE/S:</strong><br>None';
            }

            
            // Logo and accreditation images
            $logo_path = base_url('assets/img/bounty_logo.png');
            $acc1 = base_url('assets/img/certification_international.jpg');
            $acc2 = base_url('assets/img/department_agriculture.png');
            $acc3 = base_url('assets/img/regulation.png');

            $acc1_code = 'CIP/5541/25/02/1241';
            $acc2_code = 'BAI-FL-2025-004(R)';  
            $acc3_code = 'CATO No. 550';


            $columns = [
                'certified' => null,
                'signed'    => null
            ];

            foreach ($signatories as $sig) {
                switch($sig['userTypeID']) {
                    case 19: 
                        $columns['certified'] = $sig;
                        break;
                    case 17: 
                        $columns['signed'] = $sig;
                        break;
                }
            }

       $signature_html = '';

        foreach (['certified', 'signed'] as $role) {
            $signature_html .= '  <td style="width:33%; padding-top:40px; vertical-align:top;">';
            
            if (!empty($columns[$role])) {
                $sig = $columns[$role];
                $signature_html .= '
                    <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                    <strong>'.strtoupper($sig['userFirstName'].' '.$sig['userLastName']).'</strong><br>
                    '.ucwords(strtolower($sig['profession_name'])).'<br>
                    '.ucwords(strtolower($sig['userTypeName'])).'<br>
                    License No.: '.$sig['license_no'].'<br>
                    Valid Until: '.date('F d, Y', strtotime($sig['license_valid'])).'
                ';
            } else {
                $signature_html .= '
                    <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                    <strong>---</strong><br>---
                ';
            }

            $signature_html .= '</td>';
        }

        $signature_html .= '</tr></table>';



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
                    th, td { padding: 8px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #555; }
                                    table.no-border, 
                    table.no-border td, 
                    table.no-border th {
                        border: none !important;
                    }
                </style>
            </head>
            <body>
                <div class="header">


                <table class="header-table">
                    <tr>
                        <!-- Logo -->
                        <td style="width:5%; vertical-align: middle; border:0; padding-right:5px;">
                            <img src="'.$logo_path.'" alt="Logo" style="max-height:60px;">
                        </td>

                        <td style="width:100%; vertical-align: middle; text-align: left; border:0;">
                            <div style="font-weight: bold; font-size:12px; line-height:1.2; margin-left:5px;">
                                '.$data['laboratory_name'].'<br>
                                '.$data['laboratory_address'].'
                            </div>
                        </td>


                        <!-- Accreditations -->
                        <td style="width:50%; vertical-align: middle; text-align: center; border:0;">
                            <div style="font-size:10px; text-align:center; margin-top:10px;">

                                <div style="font-weight:bold; margin-bottom:8px;">Accreditations/Recognitions:</div>

                                <table class = "no-border">
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

                            </div>

                        </td>
                    </tr>
                </table>


                    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto 20px;"></div>
                </div>

                    <h2 style="text-align:center; margin-bottom:5px;">Certificate of Analysis</h2>
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
                </table>

                        <div class="remarks" style="margin-top:15px; font-size:12px; line-height:1.4;">
                    <strong>Remarks:</strong><br>
                    Results of analysis as per sample submitted. Samples will be kept only for a month from the date received.<br>
                    <em>*SEP – Standard Error of Prediction</em>
                </div>

            
                <div class="references" style="margin-top:150px; font-size:12px; line-height:1.4;">
                    <strong>REFERENCE/S:</strong><br>
                    '.$refbody.'
                </div>

                <table class = "no-border"style="width:100%;  font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                <tr>
                <td> Analyzed by:
                </td>
                <td> Certified True and Correct:
                </td>
                <td> Signed for the Company by:
                </td>
                </tr>
                </table>

                <table class = "no-border"style="width:100%; margin-top:10px; font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                    <tr>
                        <td style="width:33%; padding-top:40px; vertical-align:top;">
                            <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                            <strong>'.$data['analyzed_firstname'] . ' ' . $data['analyzed_lastname'].'</strong><br>
                            '.$analyst_profession.'<br>
                            '.$analyst_usertypename.'<br>
                            License No.:'.$data['license_no'].'<br>
                            Valid Until: '.$analyst_valid.'
                        </td>
                        '.$signature_html.'
                    </tr>
                </table>

                    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto;"></div>


                    <table class = "no-border"style="width:100%; max-width:750px; font-size:10px; color:#555; margin:0 auto;">
                        <tr>
                            <td style="text-align:left; padding:2px;">
                                Document Code: '.PDF_DOCU_PRODU_CODE.'
                            </td>
                            <td style="text-align:right; padding:2px;">
                                Downloaded At: '.date('M d, Y H:i:s').'
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:left; padding:2px;" colspan="2">
                                Effectivity Date: '.PDF_PRODU_EFFECT.'
                            </td>
                        </tr>
                    </table>




            </div>
            </body>
            </html>
            ';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $canvas = $dompdf->getCanvas();
            $font = $dompdf->getFontMetrics()->getFont('Arial', 'italic');
            $canvas->page_text(
                520,  
                820,  
                "Page {PAGE_NUM} of {PAGE_COUNT}",
                $font,
                8,  
                array(0,0,0) 
            );

            // Open PDF in browser
            $dompdf->stream($data['release_ref_number'] . "_" . $data['ext_lab_code'] . ".pdf", ["Attachment" => 1]);

    }






       public function generate_mambatangan_pdf($id = null)
    {
        if (!$id) show_404();

        $id = strtr($id, '-_', '+/');
        $mod4 = strlen($id) % 4;
        if ($mod4) {
            $id .= str_repeat('=', 4 - $mod4);
        }

        $trans_detail_id = $this->encryption->decrypt(base64_decode($id));

        if (!$trans_detail_id) show_404();


            // Dompdf options
            $options = new Options();
            $options->set('isRemoteEnabled', true); 
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);

            $data = $this->main->get_pdf_trans_detail($trans_detail_id);

            $analyst_valid = date('F d, Y', strtotime($data['license_valid']));
            $analyst_profession = ucwords(strtolower($data['analyzed_profession']));
            $analyst_usertypename = ucwords(strtolower($data['analyzed_usertype']));

            $lab_id = $data['laboratory_id']; 

            $signatories = $this->main->get_lab_signatories($lab_id);

            $test_results = $this->main->get_pdf_test_results($trans_detail_id);

            $tbody = '';
            foreach ($test_results as $row) {
                $tbody .= '<tr>
                    <td>'.htmlspecialchars($row['param_name']).'</td>
                    <td>'.htmlspecialchars($row['test_result']).'</td>
                    <td>'.htmlspecialchars($row['method_name']).'</td>
                </tr>';
            }

            $refbody = '';
            $refs = array_column($test_results, 'reference_method');

            // Remove duplicates and empty values
            $refs = array_filter(array_unique($refs));

            if (!empty($refs)) {
                $refbody .= implode('<br>', $refs);
            } else {
                $refbody .= '<strong>REFERENCE/S:</strong><br>None';
            }

            
            // Logo and accreditation images
            $logo_path = base_url('assets/img/bounty_logo.png');
            $acc1 = base_url('assets/img/certification_international.jpg');
            $acc2 = base_url('assets/img/department_agriculture.png');
            $acc3 = base_url('assets/img/regulation.png');

            $acc1_code = 'CIP/5541/25/02/1241';
            $acc2_code = 'BAI-FL-2025-004(R)';  
            $acc3_code = 'CATO No. 550';

            

            $signature_html = '';

            if (!empty($signatories)) {
                foreach ($signatories as $sig) {

                    $full_name = strtoupper($sig['userFirstName'].' '.$sig['userLastName']);

                $profession = ucwords(strtolower($sig['profession_name']));
                $usertypename = ucwords(strtolower($sig['userTypeName']));


                    $valid_until = date('F d, Y', strtotime($sig['license_valid']));

                    $signature_html .= '
                        <td style="width:33%; padding-top:40px; vertical-align:top;">
                            <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                            <strong>'.$full_name.'</strong><br>
                            '.$profession.'<br>
                            '.$usertypename.'<br>
                            License No.: '.$sig['license_no'].'<br>
                            Valid Until: '.$valid_until.'
                        </td>
                    ';
                }
            }



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
                    th, td { padding: 8px; text-align: center; }
                    th { background-color: #f2f2f2; }
                    .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #555; }
                                    table.no-border, 
                    table.no-border td, 
                    table.no-border th {
                        border: none !important;
                    }
                        @page {
    margin-top: 15mm;
    margin-bottom: 25mm;
}

@page {
    footer: pdf_footer;
}
                </style>
            </head>
            <body>
                <div class="header">


                <table class="header-table">
                    <tr>
                        <!-- Logo -->
                        <td style="width:5%; vertical-align: middle; border:0; padding-right:5px;">
                            <img src="'.$logo_path.'" alt="Logo" style="max-height:60px;">
                        </td>

                        <td style="width:100%; vertical-align: middle; text-align: left; border:0;">
                            <div style="font-weight: bold; font-size:12px; line-height:1.2; margin-left:5px;">
                                '.$data['laboratory_name'].'<br>
                                '.$data['laboratory_address'].'
                            </div>
                        </td>



                    </tr>
                </table>


                    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto 20px;"></div>
                </div>

                    <h2 style="text-align:center; margin-bottom:5px;">Certificate of Analysis</h2>
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
                </table>

                        <div class="remarks" style="margin-top:15px; font-size:12px; line-height:1.4;">
                    <strong>Remarks:</strong><br>
                    Results of analysis as per sample submitted. Samples will be kept only for a month from the date received.<br>
                    <em>*SEP – Standard Error of Prediction</em>
                </div>

            
                <div class="references" style="margin-top:300px; font-size:12px; line-height:1.4;">
                    <strong>REFERENCE/S:</strong><br>
                    '.$refbody.'
                </div>

                <table class = "no-border"style="width:100%;  font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                <tr>
                <td> Analyzed by:
                </td>
                <td> Certified True and Correct:
                </td>
                </tr>
                </table>

                <table class = "no-border"style="width:100%; margin-top:10px; font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                    <tr>
                        <td style="width:33%; padding-top:40px; vertical-align:top;">
                            <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                            <strong>'.$data['analyzed_firstname'] . ' ' . $data['analyzed_lastname'].'</strong><br>
                            '.$analyst_profession.'<br>
                            '.$analyst_usertypename.'<br>
                            License No.:'.$data['license_no'].'<br>
                            Valid Until: '.$analyst_valid.'
                        </td>
                        '.$signature_html.'
                    </tr>
                </table>

   


                
                    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:800px; margin:10px auto;"></div>
              
            </body>
            </html>
            ';

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $canvas = $dompdf->getCanvas();
            $font = $dompdf->getFontMetrics()->getFont('Arial', 'italic');
            $canvas->page_text(30, 810, "Document Code: " . PDF_DOCU_MAMBATANGAN_CODE, $font, 8);
            $canvas->page_text(450, 810, "Downloaded At: " . date('M d, Y H:i:s'), $font, 8);
            $canvas->page_text(30, 822, "Effectivity Date: " . PDF_MAMBATANGAN_EFFECT, $font, 8);
            $canvas->page_text(
                520,  
                820,  
                "Page {PAGE_NUM} of {PAGE_COUNT}",
                $font,
                8,  
                array(0,0,0) 
            );

            $dompdf->stream($data['release_ref_number'] . "_" . $data['ext_lab_code'] . ".pdf", ["Attachment" => 0]);

    }
}
