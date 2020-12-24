<?php
require '../admin_libs.php';
if(in_array($_REQUEST['n'],$DBVARS['plugins'])){
	unset($DBVARS['plugins'][
		array_search($_REQUEST['n'],$DBVARS['plugins'])
	]);
	config_rewrite();
}
header('Location: /ww.admin/plugins.php');
