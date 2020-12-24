<?php
class Page{
	static $instances			  = array();
	static $instancesByName		= array();
	static $instancesBySpecial	 = array();
	function __construct($v,$byField=0,$fromRow=0,$pvq=0){
		# byField: 0=ID; 1=Name; 3=special
		if (!$byField && is_numeric($v)){ // by ID
			$r=$fromRow?$fromRow:($v?dbRow("select * from pages where id=$v limit 1"):array());
		}
		else if ($byField == 1){ // by name
			$name=strtolower(str_replace('-','_',$v));
			$fname='page_by_name_'.md5($name);
			$r=dbRow("select * from pages where name like '".addslashes($name)."' limit 1");
		}
		else if ($byField == 3 && is_numeric($v)){ // by special
			$fname='page_by_special_'.$v;
			$r=dbRow("select * from pages where special&$v limit 1");
		}
		else return false;
		if(!count($r || !is_array($r)))return false;
		if(!isset($r['id']))$r['id']=0;
		if(!isset($r['type']))$r['type']=0;
		if(!isset($r['special']))$r['special']=0;
		if(!isset($r['name']))$r['name']='NO NAME SUPPLIED';
		foreach ($r as $k=>$v) $this->{$k}=$v;
		$this->urlname=$r['name'];
		$this->dbVals=$r;
		self::$instances[$this->id] =& $this;
		self::$instancesByName[preg_replace('/[^a-z0-9]/','-',strtolower($this->urlname))] =& $this;
		self::$instancesBySpecial[$this->special] =& $this;
		if(!$this->vars)$this->vars='{}';
		$this->vars=json_decode($this->vars);
	}
	function getInstance($id=0,$fromRow=false,$pvq=false){
		if (!is_numeric($id)) return false;
		if (!@array_key_exists($id,self::$instances)) self::$instances[$id]=new Page($id,0,$fromRow,$pvq);
		return self::$instances[$id];
	}
	function getInstanceByName($name=''){
		$name=strtolower($name);
		$nameIndex=preg_replace('#[^a-z0-9/]#','-',$name);
		if(@array_key_exists($nameIndex,self::$instancesByName))return self::$instancesByName[$nameIndex];
		self::$instancesByName[$nameIndex]=new Page($name,1);
		return self::$instancesByName[$nameIndex];
	}
	function getInstanceBySpecial($sp=0){
		if (!is_numeric($sp)) return false;
		if (!@array_key_exists($sp,$instancesBySpecial)) $instancesBySpecial[$sp]=new Page($sp,3);
		return $instancesBySpecial[$sp];
	}
}
