<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

if(isset($_REQUEST['id'])){
	$id=(int)$_REQUEST['id'];
	dbQuery("delete from panels where id=$id");
}
echo 'ok';
