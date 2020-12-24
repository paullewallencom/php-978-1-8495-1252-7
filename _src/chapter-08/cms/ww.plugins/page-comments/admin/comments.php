<?php

if(isset($_REQUEST['action']) && $_REQUEST['action']=='save'){
	$mod=($_REQUEST['moderation_enabled']=='yes')?'yes':'no';
	$email=$_REQUEST['moderation_email'];
	if(($mod=='yes' && $email=='') || ($mod=='yes' && !filter_var($email,FILTER_VALIDATE_EMAIL))){
		echo '<em>error: email is not valid. please retry</em>';
	}
	else{
		$DBVARS['page-comments|moderation_email']=$email;
		$DBVARS['page-comments|moderation_enabled']=$mod;
		config_rewrite();
		echo '<em>Moderation options saved</em>';
	}
}

$htmlurl=htmlspecialchars('/ww.admin/plugin.php?_plugin=page-comments&_page=comments');
// { moderation settings
echo '<form action="',$htmlurl,'" method="post"><h2>Moderation</h2><table><tr><th>Enabled</th><th>Moderator\'s email</th></tr>';
// { moderation enabled
echo '<tr><td><select name="moderation_enabled"><option value="no">No</option><option value="yes"';
if($DBVARS['page-comments|moderation_enabled']=='yes')echo ' selected="selected"';
echo '>yes</option></select></td>';
// }
// { moderation email
echo '<td><input name="moderation_email" value="',htmlspecialchars($DBVARS['page-comments|moderation_email']),'" /></td>';
// }
echo '<td><input type="submit" name="action" value="save" /></td></tr></table></form>';
// }
