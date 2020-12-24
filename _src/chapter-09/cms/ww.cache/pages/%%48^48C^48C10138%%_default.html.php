<?php /* Smarty version 2.6.26, created on 2010-10-08 14:04:18
         compiled from /home/kae/websites/cms/cms/ww.skins/basic/h/_default.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'MENU', '/home/kae/websites/cms/cms/ww.skins/basic/h/_default.html', 9, false),)), $this); ?>
<!doctype html>
<html>
	<head>
		<?php echo $this->_tpl_vars['METADATA']; ?>

		<link rel="stylesheet" href="/ww.skins/basic/c/style.css" />
	</head>
	<body>
		<div id="wrapper">
			<div id="menu-wrapper"><?php echo menu_show_fg(array('direction' => 'horizontal'), $this);?>
</div>
			<div id="page-wrapper"><?php echo $this->_tpl_vars['PAGECONTENT']; ?>
</div>
		</div>
	</body>
</html>