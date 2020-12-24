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
function ckeditor($name,$value='',$height=250){
	return '<textarea style="width:100%;height:'.$height.'px" name="'.addslashes($name).'">'
		.htmlspecialchars($value)
		.'</textarea><script>$(function(){CKEDITOR.replace("'.addslashes($name).'",{
			filebrowserBrowseUrl:"/j/kfm/"
		});});</script>';
}
