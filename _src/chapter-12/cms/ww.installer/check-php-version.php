<?php
if(file_exists('../.private/config.php'))exit;

$vs=explode('.',phpversion());
if($vs[0]>5
	|| ($vs[0]==5 && $vs[1]>=2)){
	echo '{"status":1,"test":"php-version"}';
	exit;
}

echo '{"status":0,"error":"'
	.'The PHP version must be at least 5.2. '
	.'It is currently '.phpversion().'",'
	.'"test":"php-version"}';
