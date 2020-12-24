<?php
$plugin=array(
	'name' => 'Form',
	'admin' => array(
		'page_type' => array(
			'form' => 'form_admin_page_form'
		)
	),
	'description' => 'Generate forms for sending as email or saving in the database',
	'frontend' => array(
		'page_type' => array(
			'form' => 'form_frontend'
		)
	),
	'version' => 3
);
function form_admin_page_form($page,$page_vars){
	$id=$page['id'];
	$c='';
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='Update Page Details')require dirname(__FILE__).'/admin/save.php';
	require dirname(__FILE__).'/admin/form.php';
	return $c;
}
function form_frontend($PAGEDATA){
	require dirname(__FILE__).'/frontend/show.php';
	return $PAGEDATA->render().form_controller($PAGEDATA);
}
