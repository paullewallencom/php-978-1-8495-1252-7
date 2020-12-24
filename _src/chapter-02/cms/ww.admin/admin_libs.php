<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.incs/basics.php';
function is_admin(){
	if(!isset($_SESSION['userdata']))return false;
	if(
		isset($_SESSION['userdata']['groups']['_administrators']) ||
		isset($_SESSION['userdata']['groups']['_superadministrators'])
	)return true;
	if(!isset($_REQUEST['login_msg']))$_REQUEST['login_msg']='permissiondenied';
	return false;
}
if(!is_admin()){
	require SCRIPTBASE.'ww.admin/login/login.php';
	exit;
}
