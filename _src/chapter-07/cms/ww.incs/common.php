<?php
require dirname(__FILE__).'/basics.php';
require_once SCRIPTBASE . 'ww.incs/Smarty-2.6.26/libs/Smarty.class.php';
function import_script_once($script){
	global $external_scripts;
	if(isset($external_scripts[$script]))return '';
	$external_scripts[$script]=1;
	return '<script src="'.htmlspecialchars($script).'"></script>';
}
function import_css_once($css){
	global $external_css;
	if(isset($external_css[$css]))return '';
	$external_css[$css]=1;
	return '<link rel="stylesheet" href="'.htmlspecialchars($css).'"/ >';
}
function menu_build_fg($parentid,$depth,$options){
	$PARENTDATA=Page::getInstance($parentid);
	// { menu order
	$order='ord,name';
	if(isset($PARENTDATA->vars->order_of_sub_pages)){
		switch($PARENTDATA->vars->order_of_sub_pages){
			case 1: // { alphabetical
				$order='name';
				if($PARENTDATA->vars->order_of_sub_pages_dir)$order.=' desc';
				break;
			// }
			case 2: // { associated_date
				$order='associated_date';
				if($PARENTDATA->vars->order_of_sub_pages_dir)$order.=' desc';
				$order.=',name';
				break;
			// }
			default: // { by admin order
				$order='ord';
				if($PARENTDATA->vars->order_of_sub_pages_dir)$order.=' desc';
				$order.=',name';
			// }
		}
	}
	// }
	$rs=dbAll("select id,name,type from pages where parent='".$parentid."' and !(special&2) order by $order");
	if($rs===false || !count($rs))return '';

	$items=array();
	foreach($rs as $r){
		$item='<li>';
		$page=Page::getInstance($r['id']);
		$item.='<a href="'.$page->getRelativeUrl().'">'.htmlspecialchars($page->name).'</a>';
		$item.=menu_build_fg($r['id'],$depth+1,$options);
		$item.='</li>';
		$items[]=$item;
	}
	$options['columns']=(int)$options['columns'];

	// return top-level menu
	if(!$depth)return '<ul>'.join('',$items).'</ul>';

	$s='';
	if($options['background'])$s.='background:'.$options['background'].';';
	if($options['opacity'])$s.='opacity:'.$options['opacity'].';';
	if($s){
		$s=' style="'.$s.'"';
	}

	// return 1-column sub-menu
	if($options['columns']<2)return '<ul'.$s.'>'.join('',$items).'</ul>';

	// return multi-column submenu
	$items_count=count($items);
	$items_per_column=ceil($items_count/$options['columns']);
	$c='<table'.$s.'><tr><td><ul>';
	for($i=1;$i<$items_count+1;++$i){
		$c.=$items[$i-1];
		if($i!=$items_count && !($i%$items_per_column))$c.='</ul></td><td><ul>';
	}
	$c.='</ul></td></tr></table>';
	return $c;
}
function menu_show_fg($opts){
	$c='';
	$c.=import_script_once('/j/fg-menu/fg.menu.js');
	$c.=import_css_once('/j/fg-menu/fg.menu.css');
	$options=array(
		'direction' => 0,  // 0: horizontal, 1: vertical
		'parent'    => 0,  // top-level
		'background'=> '', // sub-menu background colour
		'columns'   => 1,  // for wide drop-down sub-menus
		'opacity'   => 0   // opacity of the sub-menu
	);
	foreach($opts as $k=>$v){
		if(isset($options[$k]))$options[$k]=$v;
	}
	if(!is_numeric($options['parent'])){
		$r=Page::getInstanceByName($options['parent']);
		if($r)$options['parent']=$r->id;
	}
	if(is_numeric($options['direction'])){
		if($options['direction']=='0')$options['direction']='horizontal';
		else $options['direction']='vertical';
	}
	$items=array();
	$menuid=$GLOBALS['fg_menus']++;
	$c.='<div class="menu-fg menu-fg-'.$options['direction'].'" id="menu-fg-'.$menuid.'">'.menu_build_fg($options['parent'],0,$options).'</div>';
	if($options['direction']=='vertical'){
		$posopts="positionOpts: { posX: 'left', posY: 'top',
			offsetX: 40, offsetY: 10, directionH: 'right', directionV: 'down',
			detectH: true, detectV: true, linkToFront: false },";
	}
	else{
		$posopts='';
	}
	$c.="<script>
jQuery.fn.outer = function() {
  return $( $('<div></div>').html(this.clone()) ).html();

}
$(function(){
	$('#menu-fg-$menuid>ul>li>a').each(function(){
		if(!$(this).next().length)return; // empty
		$(this).menu({
			content:$(this).next().outer(),
			choose:function(ev,ui){
				document.location=ui.item[0].childNodes(0).href;
			},
			$posopts
			flyOut:true
		});
	});
	$('.menu-fg>ul>li').addClass('fg-menu-top-level');
});
</script>";
	return $c;
}
function smarty_setup($cdir){
	$smarty = new Smarty;
	if(!file_exists(SCRIPTBASE.'ww.cache/'.$cdir)){
		if(!mkdir(SCRIPTBASE.'ww.cache/'.$cdir)){
			die(SCRIPTBASE.'ww.cache/'.$cdir.' not created.<br />please make sure that '.SCRIPTBASE.'ww.cache is writable by the web-server');
		}
	}
	$smarty->compile_dir=SCRIPTBASE.'ww.cache/'.$cdir;
	$smarty->left_delimiter = '{{';
	$smarty->right_delimiter = '}}';
	$smarty->register_function('MENU', 'menu_show_fg');
	return $smarty;
}
$fg_menus=0;
