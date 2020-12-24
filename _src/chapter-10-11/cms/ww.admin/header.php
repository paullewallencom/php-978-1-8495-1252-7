<?php
header('Content-type: text/html; Charset=utf-8');
require 'admin_libs.php';
?>
<html>
	<head>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/jquery-ui.min.js"></script>
		<script src="/j/jquery.remoteselectoptions/jquery.remoteselectoptions.js"></script>
		<script src="/ww.admin/j/admin.js"></script>
		<script src="/j/ckeditor/ckeditor.js"></script>
		<script src="/j/fg-menu/fg.menu.js"></script>
		<link rel="stylesheet" href="/j/fg-menu/fg.menu.css"/ >
		<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.0/themes/south-street/jquery-ui.css" type="text/css" />
		<link rel="stylesheet" href="/ww.admin/theme/admin.css" type="text/css" />
	</head>
	<body>
		<div id="header"> 
<?php
	$menus=array(
		'Pages'=>array(
			'_link'=>'/ww.admin/pages.php'
		),
		'Site Options'=>array(
			'Users'  => array('_link'=>'/ww.admin/users.php'),
			'Themes' => array('_link'=>'/ww.admin/themes.php'),
			'Plugins'=> array('_link'=>'/ww.admin/plugins.php')
		)
	);
	// }
	// { add custom items (from plugins)
	foreach($PLUGINS as $pname=>$p){
		if(!isset($p['admin']) || !isset($p['admin']['menu']))continue;
		foreach($p['admin']['menu'] as $name=>$page){
			if(preg_match('/[^a-zA-Z0-9 >]/',$name))continue; # illegal characters in name
			$json='{"'.str_replace('>','":{"',$name).'":{"_link":"plugin.php?_plugin='.$pname.'&amp;_page='.$page.'"}}'.str_repeat('}',substr_count($name,'>'));
			$menus=array_merge_recursive($menus,json_decode($json,true));
		}
	}
	// }
	$menus['Log Out']=array('_link'=>'/ww.incs/logout.php?redirect=/ww.admin/');
	// { display menu as UL list
	function admin_menu_show($items,$name=false,$prefix,$depth=0){
		if(isset($items['_link']))echo '<a href="'.$items['_link'].'">'.$name.'</a>';
		else if($name!='top')echo '<a href="#'.$prefix.'-'.$name.'">'.$name.'</a>';
		if(count($items)==1 && isset($items['_link']))return;
		if($depth<2)echo '<div id="'.$prefix.'-'.$name.'">';
		echo '<ul>';
		foreach($items as $iname=>$subitems){
			if($iname=='_link')continue;
			echo '<li>';
			admin_menu_show($subitems,$iname,$prefix.'-'.$name,$depth+1);
			echo '</li>';
		}
		echo '</ul>';
		if($depth<2)echo '</div>';
	}
	admin_menu_show($menus,'top','menu');
	// }
?>
		</div>
		<div id="wrapper">
