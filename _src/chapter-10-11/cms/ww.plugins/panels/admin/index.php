<?php
echo '<table style="width:95%"><tr>';
echo '<td><h3>Widgets</h3><p>Drag a widget into a panel on the right.</p><div id="widgets"></div><br style="clear:both" /></td>';
echo '<td style="width:220px"><h3>Panels</h3><p>Click a header to open it.</p><div id="panels"></div><br style="clear:both" /></td></tr>';
echo '</table>';
echo '<link rel="stylesheet" type="text/css" href="/ww.plugins/panels/admin/css.css" />';
// { panel and widget data
echo '<script>';
// { panels
echo 'ww_panels=[';
$ps=array();
$rs=dbAll('select * from panels order by name');
foreach($rs as $r)$ps[]='{id:'.$r['id'].',disabled:'.$r['disabled'].',name:"'.$r['name'].'",widgets:'.$r['body'].'}';
echo join(',',$ps);
echo '];';
// }
// { widgets
echo 'ww_widgets=[';
$ws=array();
foreach($PLUGINS as $n=>$p){
	if(isset($p['frontend']['widget']))$ws[]='{type:"'.$n.'",description:"'.addslashes($p['description']).'"}';
}
echo join(',',$ws);
echo '];';
// }
// { widget forms
echo 'ww_widgetForms={';
$ws=array();
foreach($PLUGINS as $n=>$p){
	if(isset($p['admin']['widget']) && isset($p['admin']['widget']['form_url']))$ws[]='"'.$n.'":"'.addslashes($p['admin']['widget']['form_url']).'"';
}
echo join(',',$ws);
echo '};';
// }
// }
?>
</script><script src="/ww.plugins/panels/admin/js.js"></script>
<script src="/ww.plugins/panels/admin/jquery.inlinemultiselect.js"></script>
