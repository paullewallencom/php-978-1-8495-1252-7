<script src="/j/jquery.jstree/jquery.tree.js"></script>
<script src="/j/jquery.jstree/plugins/jquery.tree.contextmenu.js"></script>
<script src="/ww.admin/pages/menu.js"></script>
<?php
echo '<div id="pages-wrapper">';
$rs=dbAll('select id,type,name,parent from pages order by ord,name');
$pages=array();
foreach($rs as $r){
	if(!isset($pages[$r['parent']]))$pages[$r['parent']]=array();
	$pages[$r['parent']][]=$r;
}
function show_pages($id,$pages){
	if(!isset($pages[$id]))return;
	echo '<ul>';
	foreach($pages[$id] as $page){
		echo '<li id="page_'.$page['id'].'"><a href="pages.php?id='.$page['id'].'">';
		echo '<ins>&nbsp;</ins>'.htmlspecialchars($page['name']).'</a>';
		show_pages($page['id'],$pages);
		echo '</li>';
	}
	echo '</ul>';
}
show_pages(0,$pages);
echo '</div>';
