<?php
require '../admin_libs.php';

function page_show_pagenames($i=0,$n=1,$s=0,$id=0){
	$q=dbAll('select name,id from pages where parent="'.$i.'" and id!="'.$id.'" order by ord,name');
	if(count($q)<1)return;
	foreach($q as $r){
		if($r['id']!=''){
			echo '<option value="'.$r['id'].'" title="'.htmlspecialchars($r['name']).'"';
			echo($s==$r['id'])?' selected="selected">':'>';
			for($j=0;$j<$n;$j++)echo '&nbsp;';
			$name=$r['name'];
			if(strlen($name)>20)$name=substr($name,0,17).'...';
			echo htmlspecialchars($name).'</option>';
			page_show_pagenames($r['id'],$n+1,$s,$id);
		}
	}
}

$selected=isset($_REQUEST['selected'])?$_REQUEST['selected']:0;
$id=isset($_REQUEST['other_GET_params'])?(int)$_REQUEST['other_GET_params']:-1;
echo '<option value="0"> --  none  -- </option>';
page_show_pagenames(0,0,$selected,$id);
