<?php
if($version==0){ // create tables
	dbQuery('create table if not exists `page_comments_comment` (
		`id` int(11) NOT NULL auto_increment,
		`comment` text,
		`author_name` text,
		`author_email` text,
		`author_website` text,
		`page_id` int,
		`cdate` datetime,
		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8');
	$version++;
}
if($version==1){ // add moderation details
	$DBVARS[$pname.'|moderation_email']='';
	$DBVARS[$pname.'|moderation_enabled']='no';
	$version++;
}
