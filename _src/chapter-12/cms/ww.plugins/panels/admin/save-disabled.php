<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

if(isset($_REQUEST['id']) && isset($_REQUEST['disabled'])){
	$id=(int)$_REQUEST['id'];
	$disabled=(int)$_REQUEST['disabled'];
	dbQuery("update panels set disabled='$disabled' where id=$id");
}
echo 'done';
