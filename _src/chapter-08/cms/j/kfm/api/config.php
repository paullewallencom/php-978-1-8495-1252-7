<?php
if(!defined('START_TIME'))define('START_TIME',microtime(true));
if($_SERVER['PHP_SELF']=='/j/kfm/get.php' || (isset($kfm_api_auth_override) && $kfm_api_auth_override))
	$inc='/ww.incs/basics.php';
else
	$inc='/ww.admin/admin_libs.php';
include_once $_SERVER['DOCUMENT_ROOT'].$inc;
$kfm_userfiles_address=$DBVARS['userbase'].'/f/';
if(!session_id()){
	if(isset($_GET['cms_session']))session_id($_GET['cms_session']);
	session_start();
}
if($_SERVER['PHP_SELF']=='/j/kfm/get.php'){
	$kfm_do_not_save_session=true;
}
$kfm_api_auth_override=true;
$kfm->defaultSetting('file_handler','return');
$kfm->defaultSetting('file_url','filename');
$kfm->defaultSetting('return_file_id_to_cms',false);
