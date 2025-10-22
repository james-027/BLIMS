<?php
$userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
$logoHeaderColor = get_user_theme(array('a.userID' => $userID), true)->logoHeaderColor;
$topBarColor = get_user_theme(array('a.userID' => $userID), true)->topBarColor;
$backgroundColor = get_user_theme(array('a.userID' => $userID), true)->backgroundColor;
$btnColor = get_user_theme(array('a.userID' => $userID), true)->btnColor;
$thColor = get_user_theme(array('a.userID' => $userID), true)->thColor;
$sideBarColor = get_user_theme(array('a.userID' => $userID), true)->sideBarColor;
$miniSideBarConf = false;

$keyAccess = $this->session->userdata[APP_SESS_NAME]['keyCode'].' - '.$this->session->userdata[APP_SESS_NAME]['bcCode'];


$lightLogoArray = array(
	'orange',
	'white',
	'orange2'
);
$logo_added_class = !in_array($logoHeaderColor, $lightLogoArray) ? 'text-white' : '';

$modal_text = $backgroundColor == 'dark' ? 'text-white' : '';

$access_file_script = $this->uri->segment(1) == 'admin' && ($this->uri->segment(2) == '' || $this->uri->segment(2) == 'dashboard' || $this->uri->segment(2) == 'export-pdf') ? true : false;

$expThColor = expColor(@$thColor)->expThColor;
$expFontColor = expColor(@$thColor)->expFontColor;
$expDtColor = expColor(@$thColor)->expDtColor;

//$notification_head = @$notif_counter > 0 ? 'You have '.@$notif_counter.' new notification{s}' : 'Notification(s)';
$notif_bell_class = @$notif_counter > 0 ? 'notification' : '';

$userTypeName = get_shortened_phrase($this->session->userdata[APP_SESS_NAME]['userTypeName'])->phrase;
$userTypeToolTip = get_shortened_phrase($this->session->userdata[APP_SESS_NAME]['userTypeName'])->tooltip;

$userRatingInd = get_user_rating(array('a.userID' => $userID), true)->userRatingID;
?>