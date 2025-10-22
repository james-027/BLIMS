<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_temp {

    public function _body($heading, $subject, $greetings, $message, $expThColor, $expFontColor, $emailContainerWidth = 600){
    	
    	$caution_msg = '<br><br><strong><center><font style="font-size:8px">-- This is a system generated email, Please do not reply. --</font></center></strong>';

		$body = '
		<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
		<html xmlns="http://www.w3.org/1999/xhtml">
		    <head>
		        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		        <title>' . html_escape($subject) . '</title>
		        <style>
		        	body {
						font-family: Arial, Verdana, Helvetica, sans-serif;
						font-size: 13px;
						font-color:#111;
						padding-right:10rem;
				        padding-left:10rem;
					}
		        </style>
		    </head>
		    <body style="background-color:#ccc;">
		        <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
		            <tr>
		                <td align="center" valign="top">
		                    <table style="background-color:white;" border="0" cellpadding="0" cellspacing="0" width="'.$emailContainerWidth.'" id="emailContainer">
		                        <tr style="background-color:#'.$expThColor.';">
		                            <td align="center" valign="top">
		                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailHeader">
		                                    <tr>
		                                        <td align="center" valign="middle" style="height:40px; padding:35px; font-size:22px; text-align:center;  color:#'.$expFontColor.';">
		                                            '.$heading.'
		                                        </td>
		                                    </tr>
		                                </table>
		                            </td>
		                        </tr>
		                        <tr>
		                            <td align="center" valign="top">
		                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailBody">
		                                    <tr>
		                                        <td align="left" valign="top">
		                                            '.$greetings.'
		                                        </td>
		                                    </tr>
		                                    <tr>
		                                        <td align="left" valign="top">
		                                            '.$message.'
		                                        </td>
		                                    </tr>
		                                    <tr>
		                                        <td align="center" valign="bottom">
		                                            '.$caution_msg.'
		                                        </td>
		                                    </tr>
		                                </table>
		                            </td>
		                        </tr>
		                        
		                        <tr style="background-color:#'.$expThColor.';">
		                            <td align="center" valign="top">
		                                <table border="0" cellpadding="20" cellspacing="0" width="100%" id="emailFooter">
		                                    
		                                    <tr>
		                                        <td align="center" valign="middle" style="height:20px; padding:15px; font-size:11px; text-align:center; color:#'.$expFontColor.';">
		                                            BAVI © '.$this->cright_year.'
		                                        </td>
		                                    </tr>
		                                </table>
		                            </td>
		                        </tr>
		                    </table>
		                </td>
		            </tr>
		        </table>
		    </body>
		</html>';

		return $body;
    }

    public $mail_from_name = SYS_NAME.' Notif';
	public $mail_from = SYS_EMAIL;
	public $cright_year = CRIGHT_YEAR;
}



?>