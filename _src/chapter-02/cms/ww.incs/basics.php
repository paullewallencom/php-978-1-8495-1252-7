<?php
session_start();
function __autoload($name) {
	require $name . '.php';
}
function dbAll($query,$key='') {
	$q = dbQuery($query);
	$results=array();
	while($r=$q->fetch(PDO::FETCH_ASSOC))$results[]=$r;
	if(!$key)return $results;
	$arr=array();
	foreach($results as $r)$arr[$r[$key]]=$r;
	return $arr;
}
function dbInit(){
	if(isset($GLOBALS['db']))return $GLOBALS['db'];
	global $DBVARS;
	$db=new PDO('mysql:host='.$DBVARS['hostname'].';dbname='.$DBVARS['db_name'],$DBVARS['username'],$DBVARS['password']);
	$db->query('SET NAMES utf8');
	$db->num_queries=0;
	$GLOBALS['db']=$db;
	return $db;
}
function dbOne($query, $field='') {
	$r = dbRow($query);
	return $r[$field];
}
function dbLastInsertId() {
	return dbOne('select last_insert_id() as id','id');
}
function dbQuery($query){
	$db=dbInit();
	$q=$db->query($query);
	$db->num_queries++;
	return $q;
}
function dbRow($query) {
	$q = dbQuery($query);
	return $q->fetch(PDO::FETCH_ASSOC);
}
define('SCRIPTBASE', $_SERVER['DOCUMENT_ROOT'] . '/');
require SCRIPTBASE . '.private/config.php';
if(!defined('CONFIG_FILE'))define('CONFIG_FILE',SCRIPTBASE.'.private/config.php');
set_include_path(SCRIPTBASE.'ww.php_classes'.PATH_SEPARATOR.get_include_path());
