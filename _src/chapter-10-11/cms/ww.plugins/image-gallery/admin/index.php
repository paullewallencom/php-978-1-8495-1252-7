<?php
function image_gallery_get_subdirs($base,$dir){
	$arr=array();
	$D=new DirectoryIterator($base.$dir);
	$ds=array();
	foreach($D as $dname){
		$d=$dname.'';
		if($d{0}=='.')continue;
		if(!is_dir($base.$dir.'/'.$d))continue;
		$ds[]=$d;
	}
	asort($ds);
	foreach($ds as $d){
		$arr[]=$dir.'/'.$d;
		$arr=array_merge($arr,image_gallery_get_subdirs($base,$dir.'/'.$d));
	}
	return $arr;
}
// { initialise variables
$gvars=array(
	'image_gallery_directory'    =>'',
	'image_gallery_x'            =>3,
	'image_gallery_y'            =>2,
	'image_gallery_autostart'    =>0,
	'image_gallery_slidedelay'   =>5000,
	'image_gallery_thumbsize'    =>150,
	'image_gallery_captionlength'=>100,
	'image_gallery_type'         =>'ad-gallery'
);
foreach($gvars as $n=>$v)if(isset($vars[$n]))$gvars[$n]=$vars[$n];
// }
$c='<div class="tabs">';
// { table of contents
$c.='<ul><li><a href="#image-gallery-images">Images</a></li>'
	.'<li><a href="#image-gallery-header">Header</a></li>'
	.'<li><a href="#image-gallery-settings">Settings</a></li></ul>';
// }
// { images
$c.='<div id="image-gallery-images">';
if(!$gvars['image_gallery_directory'] || !is_dir(SCRIPTBASE.'f/'.$gvars['image_gallery_directory'])){
	@mkdir(SCRIPTBASE.'f/image-galleries');
	$gvars['image_gallery_directory']='/image-galleries/page-'.$page['id'];
	@mkdir(SCRIPTBASE.'f/'.$gvars['image_gallery_directory']);
}
$dir_id=kfm_api_getDirectoryId(preg_replace('/^\//','',$gvars['image_gallery_directory']));
$images=kfm_loadFiles($dir_id);
$images=$images['files'];
$n=count($images);
$c.='<iframe src="/ww.plugins/image-gallery/admin/uploader.php?image_gallery_directory='.urlencode($gvars['image_gallery_directory']).'" style="width:400px;height:50px;border:0;overflow:hidden"></iframe>'
	.'<script>window.kfm={alert:function(){}};window.kfm_vars={};function x_kfm_loadFiles(){}function kfm_dir_openNode(){document.location=document.location;}</script>';
if($n){
	$c.='<div id="image-gallery-wrapper">';
	for($i=0;$i<$n;$i++){
		$c.='<div><img src="/kfmget/'.$images[$i]['id'].',width=64,height=64" title="'.str_replace('\\\\n','<br />',$images[$i]['caption']).'" /><br /><input type="checkbox" id="image-gallery-dchk-'.$images[$i]['id'].'" /><a href="javascript:;" id="image-gallery-dbtn-'.$images[$i]['id'].'">delete</a></div>';
	}
	$c.='</div>';
}
else{
	$c.='<em>no images yet. please upload some.</em>';
}
$c.='</div>';
// }
// { header
$c.='<div id="image-gallery-header">';
$c.=ckeditor('body',$page['body']);
$c.='</div>';
// }
// { settings
$c.='<div id="image-gallery-settings">';
$c.='<table><tr><th>Image Directory</th><td><select id="image_gallery_directory" name="page_vars[image_gallery_directory]"><option value="/">/</option>';
foreach(image_gallery_get_subdirs(SCRIPTBASE.'f','') as $d){
	$c.='<option value="'.htmlspecialchars($d).'"';
	if($d==$gvars['image_gallery_directory'])$c.=' selected="selected"';
	$c.='>'.htmlspecialchars($d).'</option>';
}
$c.='</select></td>';
$c.='<td colspan="2"><a style="background:#ff0;font-weight:bold;color:red;display:block;text-align:center;" href="#page_vars[image_gallery_directory]" onclick="javascript:window.open(\'/j/kfm/?startup_folder=\'+$(\'#image_gallery_directory\').attr(\'value\'),\'kfm\',\'modal,width=800,height=600\');">Manage Images</a></td></tr>';
// { columns
$c.='<tr><th>Columns</th><td><input name="page_vars[image_gallery_x]" value="'.(int)$gvars['image_gallery_x'].'" /></td>';
// }
// { gallery type
$c.='<th>Gallery Type</th><td><select name="page_vars[image_gallery_type]">';
$types=array('ad-gallery','simple gallery');
foreach($types as $t){
	$c.='<option value="'.$t.'"';
	if(isset($gvars['image_gallery_type']) && $gvars['image_gallery_type']==$t)$c.=' selected="selected"';
	$c.='>'.$t.'</option>';
}
$c.='</select></td></tr>';
// }
// { rows
$c.='<tr><th>Rows</th><td><input name="page_vars[image_gallery_y]" value="'.(int)$gvars['image_gallery_y'].'" /></td>';
// }
// { autostart the slideshow
$c.='<th>Autostart slide-show</th><td><select name="page_vars[image_gallery_autostart]"><option value="0">No</option><option value="1"';
if($gvars['image_gallery_autostart'])$c.=' selected="selected"';
$c.='>Yes</option></select></td></tr>';
// }
// { caption length
$cl=(int)@$gvars['image_gallery_captionlength'];
$cl=$cl?$cl:100;
$c.='<tr><th>Caption Length</th><td><input name="page_vars[image_gallery_captionlength]" value="'.$cl.'" /></td>';
// }
// { slide delay
$sd=(int)@$gvars['image_gallery_slidedelay'];
$c.='<th>Slide Delay</th><td><input name="page_vars[image_gallery_slidedelay]" class="small" value="'.$sd.'" />ms</td></tr>';
// }
// { thumb size
$ts=(int)@$gvars['image_gallery_thumbsize'];
$ts=$ts?$ts:150;
$c.='<tr><th>Thumb Size</th><td><input name="page_vars[image_gallery_thumbsize]" value="'.$ts.'" /></td></tr>';
// }
$c.='</table>';
$c.='</div>';
// }
$c.='</div>';
$c.='<link rel="stylesheet" href="/ww.plugins/image-gallery/admin/admin.css" />';
$c.='<script src="/ww.plugins/image-gallery/admin/js.js"></script>';
