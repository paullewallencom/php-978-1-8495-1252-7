<?php
global $pagecontent,$DBVARS;

$c='';
$message='';
// { add submitted comments to database
if(isset($_REQUEST['action']) && $_REQUEST['action']=='Submit Comment'){
	if(!isset($_REQUEST['page-comments-name']) || $_REQUEST['page-comments-name']=='')$message.='<li>Please enter your name.</li>';
	if(!isset($_REQUEST['page-comments-email']) || !filter_var($_REQUEST['page-comments-email'],FILTER_VALIDATE_EMAIL))$message.='<li>Please enter your email address.</li>';
	if(!isset($_REQUEST['page-comments-comment']) || !$_REQUEST['page-comments-comment'])$message.='<li>Please enter a comment.</li>';
	if($message)$message='<ul class="error page-comments-error">'.$message.'</ul>';
	else{
		$website=isset($_REQUEST['page-comments-website'])?$_REQUEST['page-comments-website']:'';
		if($DBVARS['page-comments|moderation_enabled']=='yes'){
			$status=0;
			mail($DBVARS['page-comments|moderation_email'],'['.$_SERVER['HTTP_HOST'].'] comment submitted','A new comment has been submitted to the page "'.$PAGEDATA->getRelativeUrl().'". Please log into the admin area of the site and moderate it.','From: noreply@'.$_SERVER['HTTP_HOST']."\nReply-to: noreply@".$_SERVER['HTTP_HOST']);
			$message='<p>Comments are moderated. It may be a few minutes before your comment appears.</p>';
		}
		else $status=1;
		dbQuery('insert into page_comments_comment set comment="'.addslashes($_REQUEST['page-comments-comment']).'",author_name="'.addslashes($_REQUEST['page-comments-name']).'",author_email="'.$_REQUEST['page-comments-email'].'",author_website="'.addslashes($website).'",cdate=now(),page_id='.$PAGEDATA->id.',status='.$status);
	}
}
// }
// { show existing comments
$c.='<h3>Comments</h3>';
$comments=dbAll('select * from page_comments_comment where status=1 and page_id='.$PAGEDATA->id.' order by cdate');
if(!count($comments)){
	$c.='<p>No comments yet.</p>';
}
else foreach($comments as $comment){
	$c.=htmlspecialchars($comment['author_name']);
	if($comment['author_website'])$c.=' (<a href="'.htmlspecialchars($comment['author_website']).'">'.htmlspecialchars($comment['author_website']).'</a>)';
	$c.=' said, at '.date_m2h($comment['cdate']).':<br /><blockquote>'.nl2br(htmlspecialchars($comment['comment'])).'</blockquote>';
}
// }
// { show comment entry form
$c.='<a name="page-comments-submit"></a><h3>Add a comment</h3>';
if($message)$c.=$message;
$c.='<form action="'.$PAGEDATA->getRelativeURL().'#page-comments-submit" method="post"><table>';
$c.='<tr><th>Name</th><td><input name="page-comments-name" /></td></tr>';
$c.='<tr><th>Email</th><td><input type="email" name="page-comments-email" /></td></tr>';
$c.='<tr><th>Website</th><td><input name="page-comments-website" /></td></tr>';
$c.='<tr><th>Your Comment</th><td><textarea name="page-comments-comment"></textarea></td></tr>';
$c.='<tr><th colspan="2"><input name="action" type="submit" value="Submit Comment" /></th></tr>';
$c.='</table></form>';
// }
$pagecontent.='<div id="page-comments-wrapper">'.$c.'</div>';
