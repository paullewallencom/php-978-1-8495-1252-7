<?php
$html='';
$comments=dbAll('select * from page_comments_comment where page_id='.$PAGEDATA['id'].' order by cdate desc');
if(!count($comments)){
	$html='<em>No comments yet.</em>';
	return;
}
$html.='<table id="page-comments-table"><tr><th>Name</th><th>Date</th><th>Contact</th><th>Comment</th><th>&nbsp;</th></tr>';
foreach($comments as $comment){
	$html.='<tr class="';
	if($comment['status'])$html.='active';
	else $html.='inactive';
	$html.='" id="page-comments-tr-'.$comment['id'].'">';
	$html.='<th>'.htmlspecialchars($comment['author_name']).'</th>';
	$html.='<td>'.date_m2h($comment['cdate'],'datetime').'</td>';
	$html.='<td>';
	$html.='<a href="mailto:'.htmlspecialchars($comment['author_email']).'">'.htmlspecialchars($comment['author_email']).'</a><br />';
	if($comment['author_website'])$html.='<a href="'.htmlspecialchars($comment['author_website']).'">'.htmlspecialchars($comment['author_website']).'</a>';
	$html.='</td>';
	$html.='<td>'.htmlspecialchars($comment['comment']).'</td>';
	$html.='<td></td></tr>';
}
$html.='</table><script src="/ww.plugins/page-comments/admin/page-tab.js"></script>';
