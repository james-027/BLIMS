<?php
// Turn on errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);


error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

// Define CI3 environment constants for CLI
define('ENVIRONMENT', 'development'); // or 'production'
define('BASEPATH', realpath(__DIR__ . '/../system') . '/');
define('APPPATH', realpath(__DIR__ . '/../application') . '/');
define('VIEWPATH', APPPATH . 'views/');

// Manually load core
require_once BASEPATH . 'core/CodeIgniter.php';

// Get CI instance
$CI =& get_instance();

// Load database
$CI->load->database();

// Load PHPMailer
require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
require_once(APPPATH.'third_party/phpmailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

echo "Email automation started...\n";

while (true) {
    // Fetch emails with status = 0
    $emails = $CI->db->get_where('email_queues', ['status' => '0'])->result_array();

    if (empty($emails)) {
        echo "[" . date('Y-m-d H:i:s') . "] No emails in queue.\n";
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
                    $CI->db->update('email_queues', [
                        'status'  => '1',
                        'sent_at' => date('Y-m-d H:i:s')
                    ], ['id' => $email['id']]);
                } else {
                    echo "[".date('Y-m-d H:i:s')."] Failed to send to {$email['to_email']}\n";
                }
            } catch (Exception $e) {
                echo "[".date('Y-m-d H:i:s')."] Exception for {$email['to_email']}: " . $e->getMessage() . "\n";
            }
        }
    }

    sleep(10);
}
