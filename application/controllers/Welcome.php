<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'third_party/phpmailer/src/PHPMailer.php');
require_once(APPPATH.'third_party/phpmailer/src/SMTP.php');
require_once(APPPATH.'third_party/phpmailer/src/Exception.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->view('welcome_message');
	}

	
    public function test_email_phpmailer()
    {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->SMTPDebug  = 2;              // detailed debug
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'jamesdimaculangan222@gmail.com';
            $mail->Password   = 'mbneztmrybczyfcc'; // use Gmail app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            $mail->setFrom('jamesdimaculangan222@gmail.com', 'CI Mail Test');
            $mail->addAddress('jamesdimaculangan222@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = 'PHPMailer Test Email';
            $mail->Body    = '<p>This is a test email sent via PHPMailer.</p>';

            $mail->send();
            echo '✅ Email sent successfully!';
        } catch (Exception $e) {
            echo '❌ Email failed: ' . $mail->ErrorInfo;
        }
    }


}



