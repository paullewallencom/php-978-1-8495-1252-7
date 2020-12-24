<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

$id=(int)$_REQUEST['id'];
$widgets=addslashes($_REQUEST['data']);
dbQuery("update panels set body='$widgets' where id=$id");
