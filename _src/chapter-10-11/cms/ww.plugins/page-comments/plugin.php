<?php
$plugin=array(
	'name' => 'Page Comments',
	'description' => 'Allow your visitors to comment on pages.',
	'version' => '0',
	'admin' => array(
		'menu' => array(
			'Communication>Page Comments' => 'comments'
		),
		'page_tab' => array(
			'name' => 'Comments',
			'function' => 'page_comments_admin_page_tab'
		)
	),
	'triggers' => array(
		'page-content-created' => 'page_comments_show'
	),
	'version' => 2
);

function page_comments_admin_page_tab($PAGEDATA){
	require_once SCRIPTBASE.'ww.plugins/page-comments/admin/page-tab.php';
	return $html;
}
function page_comments_show($PAGEDATA){
	if(isset($PARENTDATA->vars->comments_disabled) && $PARENTDATA->vars->comments_disabled=='yes')
		return;
	require_once SCRIPTBASE.'ww.plugins/page-comments/frontend/show.php';
}
