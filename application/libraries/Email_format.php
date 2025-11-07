<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Email_format {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->model('main');
    }

    public function generateEmailNotification01($transHeader, $trans_detail_id, $reasonText, $remark, $userID, $notification)
    {
        if (empty($transHeader['requester_email'])) {
            log_message('error', 'No requester email found.');
            return false;
        }

        $requesterName = trim("{$transHeader['userFirstName']} {$transHeader['userLastName']}") ?: 'Requester';

        $sample = $this->CI->db->select('s.sample_name')
            ->from('trans_details td')
            ->join('samples s', 's.id = td.sample_id', 'left')
            ->where('td.trans_detail_id', $trans_detail_id)
            ->get()
            ->row_array();

        $subject = "Job Order {$transHeader['job_order_no']} - {$notification} Notification";

        $body = "
        <div style=\"font-family: Arial, sans-serif; background: #f7f7f7; padding: 32px;\">
            <div style=\"max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 0 0 32px 0;\">
                <div style=\"background: linear-gradient(90deg, #2d6cdf 0%, #4f8cff 100%); padding: 28px 0 18px 0; border-radius: 8px 8px 0 0; text-align: center;\">
                    <div style=\"font-size: 1.35rem; color: #fff; font-weight: 600; margin-top: 6px;\">BLIMS</div>
                </div>
                <div style=\"padding: 32px;\">
                    <h2 style=\"color: #2d6cdf; margin-top: 0;\">Job Order {$transHeader['job_order_no']} - {$notification}</h2>
                    <p>Hi <b>{$requesterName}</b>,</p>
                    <p>The Laboratory Code <strong>{$transHeader['lab_code']}</strong> has been marked as <strong>{$notification}</strong>.</p>
                    <ul style=\"line-height: 1.7;\">
                        <li><strong>Sample:</strong> {$sample['sample_name']}</li>
                        <li><strong>Description:</strong> {$reasonText}</li>
                        <li><strong>Remarks:</strong> {$remark}</li>
                    </ul>

                    <p style=\"color: #888; font-size: 13px;\">&copy; ".date('Y')." Bounty Laboratory Info Management System. All rights reserved.</p>
                </div>
            </div>
        </div>
        ";

        $this->CI->main->insert_data('email_queues', [
            'to_email'   => $transHeader['requester_email'],
            'to_name'    => $requesterName,
            'subject'    => $subject,
            'body'       => $body,
            'status'     => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $userID
        ]);

        log_message('info', "Queued {$notification} email for {$transHeader['requester_email']} for Job Order {$transHeader['job_order_no']}");

        return true;
    }

    public function generateEmailNotification($transHeader, $trans_detail_id, $statusText, $remark, $recipient, $notification)
    {
        if (empty($recipient['userEmail'])) {
            log_message('error', 'No recipient email found.');
            return false;
        }

        $recipientName = trim("{$recipient['userFirstName']} {$recipient['userLastName']}") ?: 'User';

        $sample = $this->CI->db->select('s.sample_name')
            ->from('trans_details td')
            ->join('samples s', 's.id = td.sample_id', 'left')
            ->where('td.trans_detail_id', $trans_detail_id)
            ->get()
            ->row_array();

        $subject = "Job Order {$transHeader['job_order_no']} - {$notification} Notification";

        $body = "
        <div style=\"font-family: Arial, sans-serif; background: #f7f7f7; padding: 32px;\">
            <div style=\"max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 0 0 32px 0;\">
                <div style=\"background: linear-gradient(90deg, #2d6cdf 0%, #4f8cff 100%); padding: 28px 0 18px 0; border-radius: 8px 8px 0 0; text-align: center;\">
                    <div style=\"font-size: 1.35rem; color: #fff; font-weight: 600; margin-top: 6px;\">BLIMS</div>
                </div>
                <div style=\"padding: 32px;\">
                    <h2 style=\"color: #2d6cdf; margin-top: 0;\">Job Order {$transHeader['job_order_no']} - {$notification}</h2>
                    <p>Hi <b>{$recipientName}</b>,</p>
                    <p>The Laboratory Code <strong>{$transHeader['lab_code']}</strong> has been marked as <strong>{$notification}</strong>.</p>
                    <ul style=\"line-height: 1.7;\">
                        <li><strong>Sample:</strong> {$sample['sample_name']}</li>
                        <li><strong>Status:</strong> {$statusText}</li>
                        <li><strong>Remarks:</strong> {$remark}</li>
                    </ul>

                    <p style=\"color: #888; font-size: 13px;\">&copy; " . date('Y') . " Bounty Laboratory Info Management System. All rights reserved.</p>
                </div>
            </div>
        </div>
        ";

        $this->CI->main->insert_data('email_queues', [
            'to_email'   => $recipient['userEmail'],
            'to_name'    => $recipientName,
            'subject'    => $subject,
            'body'       => $body,
            'status'     => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => $recipient['userID']
        ]);

        log_message('info', "Queued {$notification} email for {$recipient['userEmail']} for Job Order {$transHeader['job_order_no']}");

        return true;
    }


public function generateEmailNotificationByJO($transHeader, $trans_detail_id, $statusText, $remark, $recipient, $notification)
{
    if (empty($recipient['userEmail'])) {
        log_message('error', 'No recipient email found for JO notification.');
        return false;
    }

    $recipientName = trim("{$recipient['userFirstName']} {$recipient['userLastName']}") ?: 'User';

    // Ensure $trans_detail_id is always an array
    if (!is_array($trans_detail_id)) {
        $trans_detail_id = [$trans_detail_id];
    }

    // Get all lab codes matching only the provided trans_detail_id(s)
    $labCodes = $this->CI->db->select('td.lab_code, s.sample_name')
        ->from('trans_details td')
        ->join('samples s', 's.id = td.sample_id', 'left')
        ->where_in('td.trans_detail_id', $trans_detail_id)
        ->get()
        ->result_array();

    if (empty($labCodes)) {
        log_message('error', "No lab codes found for trans_detail_id(s): " . implode(',', $trans_detail_id));
        return false;
    }

    // Build HTML table rows
    $tableRows = '';
    foreach ($labCodes as $lab) {
        $tableRows .= "
            <tr>
                <td style='padding:8px; border:1px solid #ddd;'>{$lab['lab_code']}</td>
                <td style='padding:8px; border:1px solid #ddd;'>{$lab['sample_name']}</td>
                <td style='padding:8px; border:1px solid #ddd;'>{$statusText}</td>
                <td style='padding:8px; border:1px solid #ddd;'>{$remark}</td>
            </tr>
        ";
    }

    $subject = "Job Order {$transHeader['job_order_no']} - {$notification} Notification";

    $body = "
    <div style=\"font-family: Arial, sans-serif; background: #f7f7f7; padding: 32px;\">
        <div style=\"max-width: 600px; margin: auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 0 0 32px 0;\">
            <div style=\"background: linear-gradient(90deg, #2d6cdf 0%, #4f8cff 100%); padding: 28px 0 18px 0; border-radius: 8px 8px 0 0; text-align: center;\">
                <div style=\"font-size: 1.35rem; color: #fff; font-weight: 600; margin-top: 6px;\">BLIMS</div>
            </div>
            <div style=\"padding: 32px;\">
                <h2 style=\"color: #2d6cdf; margin-top: 0;\">Job Order {$transHeader['job_order_no']} - {$notification}</h2>
                <p>Hi <b>{$recipientName}</b>,</p>
                <p>The following laboratory codes under Job Order <strong>{$transHeader['job_order_no']}</strong> have been marked as <strong>{$notification}</strong>:</p>
                
                <table style='width:100%; border-collapse:collapse; font-size:14px; margin-top:10px;'>
                    <thead>
                        <tr style='background:#f0f4ff; text-align:left;'>
                            <th style='padding:8px; border:1px solid #ddd;'>Lab Code</th>
                            <th style='padding:8px; border:1px solid #ddd;'>Sample</th>
                            <th style='padding:8px; border:1px solid #ddd;'>Status</th>
                            <th style='padding:8px; border:1px solid #ddd;'>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        {$tableRows}
                    </tbody>
                </table>

                <p style=\"color: #888; font-size: 13px; margin-top: 20px;\">&copy; " . date('Y') . " Bounty Laboratory Info Management System. All rights reserved.</p>
            </div>
        </div>
    </div>
    ";

    // Queue email
    $this->CI->main->insert_data('email_queues', [
        'to_email'   => $recipient['userEmail'],
        'to_name'    => $recipientName,
        'subject'    => $subject,
        'body'       => $body,
        'status'     => 0,
        'created_at' => date('Y-m-d H:i:s'),
        'created_by' => $recipient['userID']
    ]);

    log_message('info', "Queued {$notification} JO email for {$recipient['userEmail']} (Job Order {$transHeader['job_order_no']}), trans_detail_id(s): " . implode(',', $trans_detail_id));

    return true;
}



}
