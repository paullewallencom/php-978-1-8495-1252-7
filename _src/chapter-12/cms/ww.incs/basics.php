<?php
session_start();
if(!function_exists('__autoload')){
	function __autoload($name) {
		require $name . '.php';
	}
}
function cache_clear($type){
	if(!is_dir(SCRIPTBASE.'/ww.cache/'.$type))return;
	$d=new DirectoryIterator(SCRIPTBASE.'/ww.cache/'.$type);
	foreach($d as $f){
		$f=$f->getFilename();
		if($f=='.' || $f=='..')continue;
		unlink(SCRIPTBASE.'/ww.cache/'.$type.'/'.$f);
	}
}
function config_rewrite(){
	global $DBVARS;
	$tmparr=$DBVARS;
	$tmparr['plugins']=join(',',$DBVARS['plugins']);
	$tmparr2=array();
	foreach($tmparr as $name=>$val)$tmparr2[]='\''.addslashes($name).'\'=>\''.addslashes($val).'\'';
	$config="<?php\n\$DBVARS=array(\n	".join(",\n	",$tmparr2)."\n);";
	file_put_contents(CONFIG_FILE,$config);
}
function date_m2h($d, $type = 'date') {
	$date = preg_replace('/[- :]/', ' ', $d);
	$date = explode(' ', $date);
	if ($type == 'date') {
		return date('l jS F, Y', mktime(0, 0, 0, $date[1], $date[2], $date[0]));
	}
	return date(DATE_RFC822, mktime($date[5], $date[4], $date[3], $date[1], $date[2], $date[0]));
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
function plugin_trigger($trigger_name){
	global $PLUGIN_TRIGGERS,$PAGEDATA;
	if(!isset($PLUGIN_TRIGGERS[$trigger_name]))return;
	foreach($PLUGIN_TRIGGERS[$trigger_name] as $fn)$fn($PAGEDATA);
}
define('SCRIPTBASE', $_SERVER['DOCUMENT_ROOT'] . '/');
if(file_exists(SCRIPTBASE . '.private/config.php')){
	require SCRIPTBASE . '.private/config.php';
}
else{
	header('Location: /ww.installer');
	exit;
}
if(!defined('CONFIG_FILE'))define('CONFIG_FILE',SCRIPTBASE.'.private/config.php');
set_include_path(SCRIPTBASE.'ww.php_classes'.PATH_SEPARATOR.get_include_path());
// { theme variables
if(isset($DBVARS['theme_dir']))define('THEME_DIR',$DBVARS['theme_dir']);
else define('THEME_DIR',SCRIPTBASE.'ww.skins');
if(isset($DBVARS['theme']) && $DBVARS['theme'])define('THEME',$DBVARS['theme']);
else{
	$dir=new DirectoryIterator(THEME_DIR);
	$themes_found=0;
	$DBVARS['theme']='.default';
	foreach($dir as $file){
		if($file->isDot())continue;
		$DBVARS['theme']=$file->getFileName();
		break;
	}
	define('THEME',$DBVARS['theme']);
}
// }
// { plugins
$PLUGINS=array();
$PLUGINS_TRIGGERS=array();
if(isset($DBVARS['plugins'])&&$DBVARS['plugins']){
	$DBVARS['plugins']=explode(',',$DBVARS['plugins']);
	foreach($DBVARS['plugins'] as $pname){
		if(strpos('/',$pname)!==false)continue;
		require SCRIPTBASE . 'ww.plugins/'.$pname.'/plugin.php';
		if(isset($plugin['version']) && $plugin['version'] && (!isset($DBVARS[$pname.'|version']) || $DBVARS[$pname.'|version']!=$plugin['version'])){
			$version=isset($DBVARS[$pname.'|version'])
				?(int)$DBVARS[$pname.'|version']
				:0;
			require SCRIPTBASE . 'ww.plugins/'.$pname.'/upgrade.php';
			$DBVARS[$pname.'|version']=$version;
			config_rewrite();
			header('Location: '.$_SERVER['REQUEST_URI']);
			exit;
		}
		$PLUGINS[$pname]=$plugin;
		if(isset($plugin['triggers'])){
			foreach($plugin['triggers'] as $name=>$fn){
				if(!isset($PLUGIN_TRIGGERS[$name]))$PLUGIN_TRIGGERS[$name]=array();
				$PLUGIN_TRIGGERS[$name][]=$fn;
			}
		}
	}
}
else $DBVARS['plugins']=array();
// }
