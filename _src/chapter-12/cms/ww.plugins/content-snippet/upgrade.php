<?php
if($version=='0'){ // add table
	dbQuery('create table if not exists content_snippets( id int auto_increment not null primary key, html text)default charset=utf8;');
	$version=1;
}
