<?php
$userID = decode($this->session->userdata[APP_SESS_NAME]['userID']);
$btnColor = get_user_theme(array('a.userID' => $userID), true)->btnColor;

//$userTypeName = strlen($this->session->userdata[APP_SESS_NAME]['userTypeName']) > 15 ? @substr($this->session->userdata[APP_SESS_NAME]['userTypeName'], 0, 15).' ...' : $this->session->userdata[APP_SESS_NAME]['userTypeName'];
$userTypeName = get_shortened_phrase($this->session->userdata[APP_SESS_NAME]['userTypeName'])->phrase;
$userTypeToolTip = get_shortened_phrase($this->session->userdata[APP_SESS_NAME]['userTypeName'])->tooltip;

$dynamicUserFullName = $this->session->userdata[APP_SESS_NAME]['userTitle'] ? $this->session->userdata[APP_SESS_NAME]['userTitle'].' '.$this->session->userdata[APP_SESS_NAME]['userFullName'] : $this->session->userdata[APP_SESS_NAME]['userFullName'];
$userFullName = get_shortened_phrase($dynamicUserFullName)->phrase;
$userFullNameToolTip = get_shortened_phrase($dynamicUserFullName)->tooltip;
?>