<?php
function image_gallery_show($PAGEDATA){
	$gvars=$PAGEDATA->vars;
	// {
	global $plugins_to_load;
	$c=$PAGEDATA->render();
	$start=isset($_REQUEST['start'])?(int)$_REQUEST['start']:0;
	if(!$start)$start=0;
	$vars=array(
		'image_gallery_directory'    =>'',
		'image_gallery_x'            =>3,
		'image_gallery_y'            =>2,
		'image_gallery_autostart'    =>0,
		'image_gallery_slidedelay'   =>5000,
		'image_gallery_thumbsize'    =>150,
		'image_gallery_captionlength'=>100,
		'image_gallery_type'         =>'ad-gallery'
	);
	foreach($gvars as $n=>$v)if($gvars->$n!='')$vars[$n]=$gvars->$n;
	$imagesPerPage=$vars['image_gallery_x']*$vars['image_gallery_y'];
	if($vars['image_gallery_directory']=='')$vars['image_gallery_directory']='/image-galleries/page-'.$PAGEDATA->id;
	// }
	$dir_id=kfm_api_getDirectoryId(preg_replace('/^\//','',$vars['image_gallery_directory']));
	$images=kfm_loadFiles($dir_id);
	$images=$images['files'];
	$n=count($images);
	if($n){
		switch($vars['image_gallery_type']){
			case 'ad-gallery':
				require dirname(__FILE__).'/gallery-type-ad.php';
				break;
			case 'simple gallery':
				require dirname(__FILE__).'/gallery-type-simple.php';
				break;
			default:
				return $c.'<em>unknown gallery type "'.htmlspecialchars($vars['image_gallery_type']).'"</em>';
		}
		return $c;
	}else{
		return $c.'<em>gallery "'.$vars['image_gallery_directory'].'" not found.</em>';
	}
}
