<?php
defined('BASEPATH') OR exit('No direct script access allowed');


error_reporting(E_ALL);
ini_set('display_errors', 1);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class AutomationScript extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->db_tbl = 'trans_headers';
    	$this->load->model('main_model', 'main');
    	$this->load->library('custom_lib');
        $this->load->library('email_format');
    }

    public function run_minor_notif()
    {
        if (!$this->input->is_cli_request()) {
            echo "This script can only be run from CLI.\n";
            return;
        }
        echo "[".date('Y-m-d H:i:s')."] Automation Script started.\n";
        
        $this->check_overdue_lead_times();

        $this->auto_cancel_onhold_records();

    }


    public function send_queue01()
    {
        if (!$this->input->is_cli_request()) {
            echo "This script can only be run from CLI.\n";
            return;
        }

        $this->load->database();

        echo "[".date('Y-m-d H:i:s')."] Email automation Script started.\n";


        while (true) {
            $emails = $this->db->limit(10)->get_where('email_queues', ['status' => 0])->result_array();

            if (empty($emails)) {
                echo "[".date('Y-m-d H:i:s')."] No emails in queue.\n";
            } else {
                foreach ($emails as $email) {
                    $body = trim($email['body'] ?? '');
                    if (empty($body)) {
                        echo "[".date('Y-m-d H:i:s')."] Skipping {$email['to_email']} — message empty\n";
                        continue;
                    }

                    $mail = new PHPMailer(true);
                    try {
                        $mail->isSMTP();
                        $mail->Host       = 'smtp.gmail.com';
                        $mail->SMTPAuth   = true;
                        $mail->Username   = SYS_EMAIL;
                        $mail->Password   = SYS_EMAIL_PASS;
                        $mail->SMTPSecure = 'tls';
                        $mail->Port       = 587;

                        $mail->setFrom(SYS_EMAIL, 'Lab Information System');
                        $mail->addAddress($email['to_email']);

                        $mail->isHTML(true);
                        $mail->Subject = $email['subject'];
                        $mail->Body    = $body;

                        if ($mail->send()) {
                            echo "[".date('Y-m-d H:i:s')."] Email sent to {$email['to_email']}\n";

                            $this->db->update('email_queues', [
                                'status'  => 1,
                                'sent_at' => date('Y-m-d H:i:s')
                            ], ['id' => $email['id']]);
                        }
                    } catch (Exception $e) {
                        echo "[".date('Y-m-d H:i:s')."] Exception for {$email['to_email']}: " . $e->getMessage() . "\n";
                    }
                }
            }

            sleep(10);
        }
    }

       public function send_queue()
    {
        if (!$this->input->is_cli_request()) {
            echo "This script can only be run from CLI.\n";
            return;
        }

        $this->load->database();

        echo "[".date('Y-m-d H:i:s')."] Email automation Script started.\n";

        while (true) {
            $emails = $this->db->limit(10)->get_where('email_queues', ['status' => 0])->result_array();

            if (empty($emails)) {
                echo "[".date('Y-m-d H:i:s')."] No emails in queue. Script ending.\n";
                break; 
            }

            foreach ($emails as $email) {
                $body = trim($email['body'] ?? '');
                if (empty($body)) {
                    echo "[".date('Y-m-d H:i:s')."] Skipping {$email['to_email']} — message empty\n";
                    continue;
                }

                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SYS_EMAIL;
                    $mail->Password   = SYS_EMAIL_PASS;
                    $mail->SMTPSecure = 'tls';
                    $mail->Port       = 587;

                    $mail->setFrom(SYS_EMAIL, 'Lab Information System');
                    $mail->addAddress($email['to_email']);

                    $mail->isHTML(true);
                    $mail->Subject = $email['subject'];
                    $mail->Body    = $body;

                    if ($mail->send()) {
                        echo "[".date('Y-m-d H:i:s')."] Email sent to {$email['to_email']}\n";

                        $this->db->update('email_queues', [
                            'status'  => 1,
                            'sent_at' => date('Y-m-d H:i:s')
                        ], ['id' => $email['id']]);
                    }
                } catch (Exception $e) {
                    echo "[".date('Y-m-d H:i:s')."] Exception for {$email['to_email']}: " . $e->getMessage() . "\n";
                }
            }

            sleep(10); // optional, can remove if you want to process immediately
        }

        echo "[".date('Y-m-d H:i:s')."] Email automation Script finished.\n";
    }

    public function check_overdue_lead_times()
    {
        echo "[" . date('Y-m-d H:i:s') . "] Checking for overdue lead times...\n";

        $this->db->select('td.trans_detail_id, td.lead_time, tt_latest.created_at AS date_submitted,tt_latest.lead_ts_window_start AS date_roundoff');
        $this->db->from('trans_details td');

        // Join only where the latest trans_timestamps record has status 26
        $this->db->join("
            (
                SELECT 
                    tt1.trans_detail_id, 
                    tt1.created_at, 
                    tt1.lead_ts_window_start, 
                    tt1.trans_detail_status_id
                FROM trans_timestamps tt1
                INNER JOIN (
                    SELECT 
                        trans_detail_id, 
                        MAX(id) AS latest_id
                    FROM trans_timestamps
                    GROUP BY trans_detail_id
                ) tt2 ON tt1.id = tt2.latest_id
                WHERE tt1.trans_detail_status_id = 26
            ) tt_latest
        ", 'tt_latest.trans_detail_id = td.trans_detail_id', 'inner');

        // only check records that have a defined lead time
        $this->db->where('td.lead_time IS NOT NULL');

        $details = $this->db->get()->result_array();

        foreach ($details as $detail) {
            $trans_detail_id = $detail['trans_detail_id'];
            $lead_time = [$trans_detail_id => $detail['lead_time']];
            $date_submitted = [$trans_detail_id => $detail['date_submitted']];
            $date_roundoff = [$trans_detail_id => $detail['date_roundoff']];

            if (empty($date_submitted[$trans_detail_id])) {
                continue; // skip if no submission date
            }



            $this->send_overdue_lead_time($lead_time, $date_submitted, $trans_detail_id,$date_roundoff);

        

        }
    }

    public function send_overdue_lead_time($lead_time, $date_submitted, $trans_detail_id,$date_roundoff)
    {

                    if (empty($date_submitted[$trans_detail_id])) {
                        return;
                    }

                  if($date_roundoff){
                     $submitted_date = $date_roundoff[$trans_detail_id];
                  }else{
                     $submitted_date = $date_submitted[$trans_detail_id];
                    
                  }

                    $lead_days = (int) $lead_time[$trans_detail_id];
                    $today = date('Y-m-d');
                    $days_diff = (strtotime($today) - strtotime($submitted_date)) / (60 * 60 * 24);


                    if ($days_diff > $lead_days) {
                        $lab_row = $this->db
                            ->select('th.laboratory_id, td.lead_time_identifier,td.*')
                            ->from('trans_details td')
                            ->join('trans_headers th', 'th.trans_id = td.trans_id')
                            ->where('td.trans_detail_id', $trans_detail_id)
                            ->get()
                            ->row();
                            
                        if ($lab_row) {
                            $lab_id = $lab_row->laboratory_id;
                            $recipients = $this->db
                                ->select('u.userID, u.userEmail, u.userFirstName, u.userLastName, u.userTypeID')
                                ->from('users u')
                                ->join('userslabs ul', 'ul.userID = u.userID')
                                ->where('ul.laboratory_id', $lab_id)
                                 ->where_in('u.userTypeID', [17, 19,20]) 
                                //   ->where_in('u.userTypeID', [12]) 
                                ->where('u.userEmail IS NOT NULL AND u.userEmail !=', '') 
                                ->get()
                                ->result_array();

                            if (empty($recipients)) {
                                log_message('warning', "No valid recipients found for lab_id: {$lab_id}");
                                return;
                            }


                            $transHeader = $this->db
                                ->select('th.trans_id, th.job_order_no, td.lab_code, u.userEmail as requester_email, u.userFirstName, u.userLastName')
                                ->from('trans_headers th')
                                ->join('trans_details td', 'td.trans_id = th.trans_id')
                                ->join('users u', 'u.userID = th.created_by', 'left')
                                ->where('td.trans_detail_id', $trans_detail_id)
                                ->get()
                                ->row_array();

                            if (empty($transHeader)) {
                                log_message('error', "No transaction header found for trans_detail_id: {$trans_detail_id}");
                                return;
                            }

                            $statusText = 'Lead Time Exceeded';
                            $remark = "Lab test has exceeded its allowed lead time of {$lead_days} days.";

                            if(!$lab_row->lead_time_identifier){

                                $allEmailsSent = true;

                                foreach ($recipients as $recipient) {


                                    $sent = $this->email_format->generateEmailNotification(
                                        $transHeader,
                                        $trans_detail_id,
                                        $statusText,
                                        $remark,
                                        $recipient,
                                        'Lead Time Exceeded'
                                    );

                                    if(!$sent){
                                        $allEmailsSent = false; 
                                    }
                                }
                                if($allEmailsSent){
                                    $updateData = [
                                        'lead_time_identifier' => 1,
                                        'modified_at' => date('Y-m-d H:i:s'),
                                    ];
                                    $this->main->update_data('trans_details', $updateData, ['trans_detail_id' => $trans_detail_id]);
                                }
                            } 
                        
                        }

                    }
    }

    public function auto_cancel_onhold_records()
    {
        if (!$this->input->is_cli_request()) return;


        $records = $this->db
            ->select('trans_detail_id, modified_at')
            ->from('trans_details')
            ->where('test_status_id', 7)
            ->get()
            ->result();

        $today = new DateTime();
        foreach ($records as $record) {

            if (empty($record->modified_at)) continue;

            $lastModified = new DateTime($record->modified_at);
            $businessDays = 0;
            $current = clone $lastModified;

            while ($current < $today) {
                if ($current->format('N') < 6) $businessDays++;
                $current->modify('+1 day');

            }
            if ($businessDays >= 3) {
                $updateData = [
                    'test_status_id' => 25,
                    'modified_at'    => date('Y-m-d H:i:s'),
                ];

                $this->main->update_data('trans_details', $updateData, ['trans_detail_id' => $record->trans_detail_id]);
                $this->insert_history_and_remark($record->trans_detail_id, 20, 'Auto-cancelled by system after 3 business days');
            }
        }

    }


    private function insert_history_and_remark($trans_detail_id, $status_id, $remark)
    {
        $detail = $this->db->where('trans_detail_id', $trans_detail_id)
                           ->get('trans_details')
                           ->row_array();
        if (!$detail) return;

        unset($detail['id']);
        $detail['trans_detail_id'] = $trans_detail_id;
        $detail['trans_detail_status_id'] = $status_id;
        $detail['created_at'] = date('Y-m-d H:i:s');
        $this->main->insert_data('trans_history', $detail);

        $timestampData = [
            'trans_detail_id'        => $trans_detail_id,
            'trans_detail_status_id' => $status_id,
            'status_id'              => 1,
            'created_at'             => date('Y-m-d H:i:s'),
        ];
        $this->main->insert_data('trans_timestamps', $timestampData);

        $this->main->insert_data('trans_remarks', [
            'trans_detail_id'        => $trans_detail_id,
            'trans_detail_status_id' => $status_id,
            'remark'                 => $remark,
            'created_at'             => date('Y-m-d H:i:s'),
        ]);
    }




}
