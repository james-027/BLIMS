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

class Email extends CI_Controller {

    public function send_queue()
    {
        if (!$this->input->is_cli_request()) {
            echo "This script can only be run from CLI.\n";
            return;
        }

        $this->load->database();

        echo "Email automation started...\n";

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

                        if (!empty($email['attachment_path'])) {
                            $mail->addStringAttachment(base64_decode($email['attachment_path']), 'COA_'.$email['to_name'].'.pdf');
                        }

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
}
