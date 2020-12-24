<?php
require '../admin_libs.php';
if(!in_array($_REQUEST['n'],$DBVARS['plugins'])){
	$DBVARS['plugins'][]=$_REQUEST['n'];
	config_rewrite();
}
header('Location: /ww.admin/plugins.php');
