<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

$id=(int)$_REQUEST['id'];
dbQuery('update page_comments_comment set status=0 where id='.$id);
echo '0';
