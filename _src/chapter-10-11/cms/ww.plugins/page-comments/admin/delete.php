<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

$id=(int)$_REQUEST['id'];
dbQuery('delete from page_comments_comment where id='.$id);
echo '1';
