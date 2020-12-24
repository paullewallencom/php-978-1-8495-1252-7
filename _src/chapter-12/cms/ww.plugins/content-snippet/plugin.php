<?php
$plugin=array(
	'name' => 'Content Snippets',
	'admin' => array(
		'widget' => array(
			'form_url' => '/ww.plugins/content-snippet/admin/widget-form.php'
		)
	),
	'description' => 'Add small static HTML snippets to any panel - address, slogan, footer, image, etc.',
	'frontend' => array(
		'widget' => 'contentsnippet_show'
	),
	'version' => '1'
);
function contentsnippet_show($vars=null){
	require_once SCRIPTBASE.'ww.plugins/content-snippet/frontend/index.php';
	return content_snippet_show($vars);
}
