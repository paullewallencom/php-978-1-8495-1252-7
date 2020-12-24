<?php
if(file_exists('../.private/config.php'))exit;

$errors=array();

$dbname=@$_REQUEST['dbname'];
$dbhost=@$_REQUEST['dbhost'];
$dbuser=@$_REQUEST['dbuser'];
$dbpass=@$_REQUEST['dbpass'];
$admin=@$_REQUEST['admin'];
$adpass=@$_REQUEST['adpass'];
$adpass2=@$_REQUEST['adpass2'];

if($dbname=='' || $dbhost=='' || $dbuser==''){
	$errors[]='db requires name, hostname and username';
}
else{
	$db=mysql_connect($dbhost,$dbuser,$dbpass);
	if(!$db)$errors[]='db: could not connect - incorrect details';
	else if(!mysql_select_db($dbname,$db)){
		$errors[]='db: could not select database "'.addslashes($dbname).'"';
	}
}
if(!filter_var($admin,FILTER_VALIDATE_EMAIL)){
	$errors[]='admin account must be an email address';
}
if(!$adpass && !$adpass2)$errors[]='admin password must not be empty';
else if($adpass!=$adpass2)$errors[]='admin passwords must both be equal';

if(!count($errors)){
	mysql_query('create table user_accounts(id int auto_increment not null primary key, email text, password char(32), active smallint default 0, groups text, activation_key char(32), extras text)default charset=utf8');
	mysql_query('insert into user_accounts values(1,"'.addslashes($admin).'", "'.md5($adpass).'", 1, \'["_superadministrators"]\',"","")');
	mysql_query('create table groups(id int auto_increment not null primary key,name text)default charset=utf8');
	mysql_query('insert into groups values(1,"_superadministrators"),(2,"_administrators")');
	mysql_query('create table pages(id int auto_increment not null primary key,name text, body text, parent int, ord int, cdate datetime, special int, edate datetime, title text, template text, type varchar(64), keywords text, description text, associated_date date, vars text)default charset=utf8');
	$config='<?php $DBVARS=array('
		.'"username"=>"'.addslashes($dbuser).'",'
		.'"password"=>"'.addslashes($dbpass).'",'
		.'"hostname"=>"'.addslashes($dbhost).'",'
		.'"db_name"=>"'.addslashes($dbname).'");';
	file_put_contents('../.private/config.php',$config);
}
echo json_encode($errors);
