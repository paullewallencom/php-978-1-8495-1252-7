<?php
function pages_setup_name($id,$pid){
	$name=trim($_REQUEST['name']);
	if(dbOne('select id from pages where name="'.addslashes($name).'" and parent='.$pid.' and id!='.$id,'id')){
		$i=2;
		while(dbOne('select id from pages where name="'.addslashes($name.$i).'" and parent='.$pid.' and id!='.$id,'id'))$i++;
		echo '<em>A page named "'.htmlspecialchars($name).'" already exists. Page name amended to "'.htmlspecialchars($name.$i).'".</em>';
		$name=$name.$i;
	}
	return $name;
}
function pages_setup_specials($id=0){
	$special=0;
	$specials=isset($_REQUEST['special'])?$_REQUEST['special']:array();
	foreach($specials as $a=>$b)$special+=pow(2,$a);
	$homes=dbOne("SELECT COUNT(id) AS ids FROM pages WHERE (special&1) AND id!=$id",'ids');
	if($special&1){ // there can be only one homepage
		if($homes!=0){
			dbQuery("UPDATE pages SET special=special-1 WHERE special&1");
		}
	}
	else{
		if($homes==0){
			$special+=1;
			echo '<em>This page has been marked as the site\'s Home Page, because there must always be one.</em>';
		}
	}
	return $special;
}

// { set up common variables
$id              =(int)$_REQUEST['id'];
$pid             =(int)$_REQUEST['parent'];
$keywords        =$_REQUEST['keywords'];
$description     =$_REQUEST['description'];
$associated_date =$_REQUEST['associated_date'];
$title           =$_REQUEST['title'];
$name            =pages_setup_name($id,$pid);
$body            =str_replace('&quot;','"',$_REQUEST['body']);
$special         =pages_setup_specials($id);
if(isset($_REQUEST['page_vars']))$vars=json_encode($_REQUEST['page_vars']);
else $vars='[]';
// }

// { create SQL
$q='template="'.addslashes($_REQUEST['template']).'",edate=now(),type="'.addslashes($_REQUEST['type']).'",associated_date="'.addslashes($associated_date).'",keywords="'.addslashes($keywords).'",description="'.addslashes($description).'",name="'.addslashes($name).'",title="'.addslashes($title).'",body="'.addslashes($body).'",parent='.$pid.',special='.$special.',vars="'.addslashes($vars).'"';
// }

// { run the query
if($_REQUEST['action']=='Update Page Details'){
	$q="update pages set $q where id=$id";
	dbQuery($q);
}
else{
	$q="insert into pages set cdate=now(),$q";
	dbQuery($q);
	$_REQUEST['id']=dbLastInsertId();
}
// }

echo '<em>Page Saved</em>';
cache_clear('pages');
