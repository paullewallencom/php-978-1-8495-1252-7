<?php
if($version==0){ // forms_fields
	dbQuery('CREATE TABLE IF NOT EXISTS `forms_fields` (
		`id` int(11) NOT NULL auto_increment,
		`name` text,
		`type` text,
		`isrequired` smallint(6) default 0,
		`formsId` int(11) default NULL,
		`extra` text,
		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8');
	$version=1;
}
if($version==1){ // forms_saved
	dbQuery('CREATE TABLE IF NOT EXISTS `forms_saved` (
		`forms_id` int(11) default 0,
		`date_created` datetime default NULL,
		`id` int(11) NOT NULL auto_increment,
		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8');
	$version=2;
}
if($version==2){ // forms_saved_values
	dbQuery('CREATE TABLE IF NOT EXISTS `forms_saved_values` (
		`forms_saved_id` int(11) default 0,
		`name` text,
		`value` text,
		`id` int(11) NOT NULL auto_increment,
		PRIMARY KEY  (`id`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8');
	$version=3;
}
