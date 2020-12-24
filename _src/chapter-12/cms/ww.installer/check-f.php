<?php
if(file_exists('../.private/config.php'))exit;

$dname='f';

@mkdir('../'.$dname);
@file_put_contents('../'.$dname.'/test-permissions','test');
if(file_exists('../'.$dname.'/test-permissions')){
	echo '{"status":1,"test":"f"}';
	unlink('../'.$dname.'/test-permissions');
	exit;
}

$dir=preg_replace('/ww.installer$/',$dname,dirname(__FILE__));
if(!is_dir($dir))$error=$dir.' does not exist. Please create it.';
else $error=$dir.' is not writable by the web server.';
echo '{"status":0,'
	.'"error":"'.$error.'",'
	.'"test":"f"}';
