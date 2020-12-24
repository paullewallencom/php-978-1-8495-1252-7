<?php
if(file_exists('../.private/config.php'))exit;

if(extension_loaded('pdo_sqlite')){
	echo '{"status":1,"test":"sqlite"}';
	exit;
}

echo '{"status":0,"error":"'
	.'You must have the PDO SQLite library installed. '
	.'"test":"sqlite"}';
