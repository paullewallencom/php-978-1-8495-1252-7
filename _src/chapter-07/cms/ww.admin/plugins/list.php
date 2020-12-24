<?php
echo '<table id="plugins-table">';
echo '<thead><tr><th>Plugin Name</th><th>Description</th><th>&nbsp;</th></tr></thead><tbody>';
// { list enabled plugins first
foreach($PLUGINS as $name=>$plugin){
	echo '<tr><th>',htmlspecialchars(@$plugin['name']),'</th>',
		'<td>',htmlspecialchars(@$plugin['description']),'</td>',
		'<td><a href="/ww.admin/plugins/disable.php?n=',htmlspecialchars($name),'">disable</a></td>',
		'</tr>';
}
// }
// { then list disabled plugins
$dir=new DirectoryIterator(SCRIPTBASE . 'ww.plugins');
foreach($dir as $plugin){
	if($plugin->isDot())continue;
	$name=$plugin->getFilename();
	if(isset($PLUGINS[$name]))continue;
	require_once(SCRIPTBASE . 'ww.plugins/' . $name .'/plugin.php');
	echo '<tr id="ww-plugin-',htmlspecialchars($name),'" class="disabled">',
		'<th>',htmlspecialchars($plugin['name']),'</th>',
		'<td>',htmlspecialchars($plugin['description']),'</td>',
		'<td><a href="/ww.admin/plugins/enable.php?n=',htmlspecialchars($name),'">enable</a></td>',
		'</tr>';
}
// }
echo '</tbody></table>';
