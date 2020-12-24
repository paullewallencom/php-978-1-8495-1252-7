<?php
require 'initialise.php';

switch($_REQUEST['action']){
	case 'delete_file': // {
		$id=(int)$_REQUEST['id'];
		$file=kfmFile::getInstance($id);
		if($file){
			$file->delete();
			echo 'ok';
			exit;
		}
		else die('file does not exist');
	// }
}
