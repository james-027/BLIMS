<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/dompdf/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class Coa extends CI_Controller
{
    public function generate_pdf()
    {
        // Dompdf options
        $options = new Options();
        $options->set('isRemoteEnabled', true); 
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);

        // Logo and accreditation images
        $logo_path = base_url('assets/img/bounty_logo.png');
        $acc1 = base_url('assets/img/certification_international.jpg');
        $acc2 = base_url('assets/img/department_agriculture.png');
        $acc3 = base_url('assets/img/regulation.png');

        // Lab info
        $lab_name = 'PRODUFEED MILLING CORPORATION QUALITY ASSURANCE CENTRAL LABORATORY';
        $lab_address = 'Brgy. Guyong Sta. Maria, Bulacan';

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
            <td style="width:10%;  vertical-align: middle; border:0;">
                <img src="'.$logo_path.'" alt="Logo" style="max-height:70px;">
            </td>

            <!-- Lab info -->
            <td style="width:60%; vertical-align: middle; text-align: center; border:0;">
                <div style="font-weight: bold; font-size:12px; line-height:1.2;">
                    '.$lab_name.'<br>
                    '.$lab_address.'
                </div>
            </td>

            <!-- Accreditations -->
            <td style="width:50%; vertical-align: middle; text-align: center; border:0;">
                <div style="font-size:10px; display:flex; flex-direction: column; align-items:center; gap:2px;">
                    <span>Accreditations/Recognitions:</span>
                    <div style="display:flex; justify-content:center; align-items:center; gap:5px; margin-top:20px;">
                        <img src="'.$acc1.'" alt="Acc 1" style="max-height:40px;">
                        <img src="'.$acc2.'" alt="Acc 2" style="max-height:40px;">
                        <img src="'.$acc3.'" alt="Acc 3" style="max-height:40px;">
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px; margin:10px auto 20px;"></div>
</div>


            <h2>Certificate of Analysis</h2>
                <div class="client-info" style="margin-bottom:20px; font-size:12px; line-height:1.6; display: table; width:100%;">
                    <div style="display: table-row;">
                        <div style="display: table-cell;  width:150px;">Customer</div>
                        <div style="display: table-cell; width:10px;">:</div>
                        <div style="display: table-cell; font-weight:bold; color:#000;"><i>BOUNTY PLUS INC.</i></div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Address</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">Inoza Tower 40th Street BGC Taguig City</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Tel./Fax No</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">(+63) 0977-136-3845</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Laboratory Code</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell; font-weight:bold; color:#000;">401-0925-226</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell;">Sample Name</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;  font-weight:bold; color:#000;">CLL1M SPECIAL</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Received</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">September 27, 2025</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Analyzed</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">September 27, 2025</div>
                    </div>
                    <div style="display: table-row;">
                        <div style="display: table-cell; ">Date Reported</div>
                        <div style="display: table-cell;">:</div>
                        <div style="display: table-cell;">September 27, 2025</div>
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
                    <tr>
                        <td>Item A</td>
                        <td>10</td>
                        <td>Passed</td>
                    </tr>
                    <tr>
                        <td>Item B</td>
                        <td>5</td>
                        <td>Failed</td>
                    </tr>
                </tbody>
            </table>

                    <div class="remarks" style="margin-top:15px; font-size:12px; line-height:1.4;">
                <strong>Remarks:</strong><br>
                Results of analysis as per sample submitted. Samples will be kept only for a month from the date received.<br>
                <em>*SEP – Standard Error of Prediction</em>
            </div>

            
        
          
            <div class="references" style="margin-top:200px; font-size:12px; line-height:1.4;">
                <strong>REFERENCE/S:</strong><br>
                Fischer, Robert Blanchard, and Dennis G. Peters. Basic Theory and Practice of Quantitative Chemical Analysis. 3rd ed., Saunders, 1968 (Modified)
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

            <table class = "no-border"style="width:100%;  font-size:12px; line-height:1.4; text-align:center; table-layout:fixed;">
                <tr>
                    <td style="width:33%; padding-top:40px; vertical-align:top;">
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                        <strong>MIZPAH F. PAJIMNA</strong><br>
                        QA Laboratory Analyst<br>
                        Registered Chemical Technician<br>
                        License No.: 0003786<br>
                        Valid Until: June 29, 2026
                    </td>
                    <td style="width:33%; padding-top:40px; vertical-align:top;">
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                        <strong>REGINA GRACE D. GERONA</strong><br>
                        QA Laboratory Supervising Chemist<br>
                        Registered Chemist<br>
                        License No.: 0015794<br>
                        Valid Until: December 27, 2027
                    </td>
                    <td style="width:33%; padding-top:40px; vertical-align:top;">
                        <div style="border-top:1px solid #000; width:80%; margin:0 auto 6px auto;"></div>
                        <strong>MAYETTE R. DOMENCIL</strong><br>
                        Laboratory Manager<br>
                        Registered Chemist<br>
                        License No.: 8860<br>
                        Valid Until: May 01, 2026
                    </td>
                </tr>
            </table>


            <div style="border-top:4px solid #dbb50cff; width:100%; max-width:750px;"></div>
            <div class="footer" style="text-align: left;font-size:10px; color:#555;">
            Document Code: PFMQA-COA Rev 2 <br> 
            Effectivity Date: September 12, 2025


        </body>
        </html>
        ';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Open PDF in browser
        $dompdf->stream("COA_".date('Ymd_His').".pdf", ["Attachment" => 0]);
    }
}
