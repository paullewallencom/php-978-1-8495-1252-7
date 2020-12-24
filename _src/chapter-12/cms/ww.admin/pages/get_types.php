<?php
require '../admin_libs.php';
echo '<option value="0">normal</option>';
foreach($PLUGINS as $n=>$plugin){
	if(!isset($plugin['admin']['page_type']))continue;
	foreach($plugin['admin']['page_type'] as $n=>$p){
		echo '<option value="'.htmlspecialchars($n).'">'.htmlspecialchars($n).'</option>';
	}
}
