<?php
require '../admin_libs.php';

$id=(int)$_REQUEST['id'];
if(!$id)exit;

$r=dbRow("SELECT COUNT(id) AS pagecount FROM pages");
if($r['pagecount']<2){
	die('cannot delete - there must always be one page');
}
else{
	$pid=dbOne("select parent from pages where id=$id",'parent');
	dbQuery("delete from pages where id=$id");
	dbQuery("update pages set parent=$pid where parent=$id");
}
echo 1;
