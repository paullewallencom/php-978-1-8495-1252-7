<?php
// { common variables and functions
include_once('ww.incs/common.php');
$page=isset($_REQUEST['page'])?$_REQUEST['page']:'';
$id=isset($_REQUEST['id'])?(int)$_REQUEST['id']:0;
$external_scripts=array();
$external_css=array();
// }
// { get current page id
if(!$id){
	if($page){ // load by name
		$r=Page::getInstanceByName($page);
		if($r && isset($r->id))$id=$r->id;
	}
	if(!$id){ // else load by special
		$special=1;
		if(!$page){
			$r=Page::getInstanceBySpecial($special);
			if($r && isset($r->id))$id=$r->id;
		}
	}
}
// }
// { load page data
if($id){
	$PAGEDATA=(isset($r) && $r)?$r : Page::getInstance($id);
}
else{
	echo '404 thing goes here';
	exit;
}
// }
// { set up pagecontent
switch($PAGEDATA->type){
	case '0': // { normal page
		$pagecontent=$PAGEDATA->render();
		break;
	// }
	// other cases will be handled here later
}
// }
// { set up metadata
// { page title
$title=($PAGEDATA->title!='')?$PAGEDATA->title:str_replace('www.','',$_SERVER['HTTP_HOST']).' > '.$PAGEDATA->name;
$metadata='<title>'.htmlspecialchars($title).'</title>';
// }
// { show stylesheet and javascript links
$metadata.='<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>'
	.'<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.1/jquery-ui.min.js"></script>';
// }
// { meta tags
$metadata.='<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
if($PAGEDATA->keywords)$metadata.='<meta http-equiv="keywords" content="'.htmlspecialchars($PAGEDATA->keywords).'" />';
if($PAGEDATA->description)$metadata.='<meta http-equiv="description" content="'.htmlspecialchars($PAGEDATA->description).'" />';
// }
// }
// { set up template
if(file_exists(THEME_DIR.'/'.THEME.'/h/'.$PAGEDATA->template.'.html')){
	$template=THEME_DIR.'/'.THEME.'/h/'.$PAGEDATA->template.'.html';
}
else if(file_exists(THEME_DIR.'/'.THEME.'/h/_default.html')){
	$template=THEME_DIR.'/'.THEME.'/h/_default.html';
}
else{
	$d=array();
	$dir=new DirectoryIterator(THEME_DIR.'/'.THEME.'/h/');
	foreach($dir as $f){
		if($f->isDot())continue;
		$n=$f->getFilename();
		if(preg_match('/^inc\./',$n))continue;
		if(preg_match('/\.html$/',$n))$d[]=preg_replace('/\.html$/','',$n);
	}
	asort($d);
	$template=THEME_DIR.'/'.THEME.'/h/'.$d[0].'.html';
}
if($template=='')die('no template created. please create a template first');
// }

$smarty=smarty_setup('pages');
$smarty->template_dir=THEME_DIR.'/'.THEME.'/h/';
// { some straight replaces
$smarty->assign('PAGECONTENT',$pagecontent);
$smarty->assign('PAGEDATA',$PAGEDATA);
$smarty->assign('METADATA',$metadata);
// }
// { display the document
header('Content-type: text/html; Charset=utf-8');
$smarty->display($template);
// }
