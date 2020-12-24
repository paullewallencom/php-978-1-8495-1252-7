<?php
require 'header.php';
$pname=$_REQUEST['_plugin'];
$pagename=$_REQUEST['_page'];
if(preg_match('/[^\-a-zA-Z0-9]/',$pagename) || $pagename=='')die('illegal character in page name');
if(!isset($PLUGINS[$pname]))die('no plugin of that name ('.htmlspecialchars($pname).') exists');
$plugin=$PLUGINS[$pname];
$_url='/ww.admin/plugin.php?_plugin='.urlencode($pname).'&amp;_page='.$pagename;
echo '<h1>'.htmlspecialchars($pname).'</h1>';
if(!file_exists(SCRIPTBASE.'/ww.plugins/'.$pname.'/admin/'.$pagename.'.php')){
	echo '<em>The <strong>'.htmlspecialchars($pname).'</strong> plugin does not have an admin page named <strong>'.$pagename.'</strong>.</em>';
}
else{
	if(file_exists(SCRIPTBASE.'/ww.plugins/'.$pname.'/admin/menu.php')){
		include SCRIPTBASE.'/ww.plugins/'.$pname.'/admin/menu.php';
		echo '<div class="has-left-menu">';
		include SCRIPTBASE.'/ww.plugins/'.$pname.'/admin/'.$pagename.'.php';
		echo '</div>';
	}
	else include SCRIPTBASE.'/ww.plugins/'.$pname.'/admin/'.$pagename.'.php';
}
require 'footer.php';
