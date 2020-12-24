<?php
$kfm_do_not_save_session=true;
require_once SCRIPTBASE.'j/kfm/api/api.php';
require_once SCRIPTBASE.'j/kfm/initialise.php';

$plugin=array(
	'name' => 'Image Gallery',
	'admin' => array(
		'page_type' => array(
			'image-gallery' => 'image_gallery_admin_page_form'
		)
	),
	'description' => 'Allows a directory of images to be shown as a gallery.',
	'frontend' => array(
		'page_type' => array(
			'image-gallery' => 'image_gallery_frontend'
		)
	)
);

function image_gallery_admin_page_form($page,$vars){
	require dirname(__FILE__).'/admin/index.php';
	return $c;
}
function image_gallery_frontend($PAGEDATA){
	require dirname(__FILE__).'/frontend/show.php';
	return image_gallery_show($PAGEDATA);
}
