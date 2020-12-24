<?php
$c.='<div class="tabs">';
// { table of contents
$c.='<ul><li><a href="#forms-header">Header</a></li>'
	.'<li><a href="#forms-main-details">Main Details</a></li>'
	.'<li><a href="#forms-fields">Fields</a></li>'
	.'<li><a href="#forms-success-message">Success Message</a></li>'
	.'<li><a href="#forms-template">Template</a></li></ul>';
// }
// { header
$c.='<div id="forms-header"><p>Text to be shown above the form</p>';
$c.=ckeditor('body',$page['body']);
$c.='</div>';
// }
// { main details
$c.= '<div id="forms-main-details"><table>';
// { send as email
if(!isset($page_vars['forms_send_as_email']))$page_vars['forms_send_as_email']=1;
$c.= '<tr><th>Send as Email</th><td><select name="page_vars[forms_send_as_email]"><option value="1">Yes</option><option value="0"';
if(!$page_vars['forms_send_as_email'])$c.=' selected="selected"';
$c.='>No</option></select></td>';
// }
// { recipient
if(!isset($page_vars['forms_recipient']))$page_vars['forms_recipient']=$_SESSION['userdata']['email'];
$c.= '<th>Recipient</th><td><input name="page_vars[forms_recipient]" value="'.htmlspecialchars($page_vars['forms_recipient']).'" /></td></tr>';
// }
// { captcha required
if(!isset($page_vars['forms_captcha_required']))$page_vars['forms_captcha_required']=1;
$c.= '<tr><th>Captcha Required</th><td><select name="page_vars[forms_captcha_required]"><option value="1">Yes</option><option value="0"';
if(!$page_vars['forms_captcha_required'])$c.=' selected="selected"';
$c.='>No</option></select></td>';
// }
// { reply-to
if(!isset($page_vars['forms_replyto']) || !$page_vars['forms_replyto'])$page_vars['forms_replyto']='FIELD{email}';
$c.= '<th>Reply-To</th><td><input name="page_vars[forms_replyto]" value="'.htmlspecialchars($page_vars['forms_replyto']).'" /></td></tr>';
// }
// { record in database
if(!isset($page_vars['forms_record_in_db']))$page_vars['forms_record_in_db']=0;
$c.= '<tr><th>Record In DB</th><td><select name="page_vars[forms_record_in_db]"><option value="0">No</option><option value="1"';
if($page_vars['forms_record_in_db'])$c.=' selected="selected"';
$c.='>Yes</option></select></td>';
// }
// { export
if($id){
	$c.= '<th>Export<br /><i style="font-size:small">(requires Record In DB)</i></th><td>from: <input id="export_from" class="date" value="'.date('Y-m-d',mktime(0,0,0,date("m")-1,date("d"),date("Y"))).'" />. <a href="javascript:form_export('.$id.')">export</a></td></tr>';
}
else{
	$c.='<td colspan="2">&nbsp;</td></tr>';
}
// }
$c.= '</table></div>';
// }
// { fields
$c.= '<div id="forms-fields">';
$c.= '<table id="formfieldsTable" width="100%"><tr><th width="30%">Name</th><th width="30%">Type</th><th width="10%">Required</th><th id="extrasColumn"><a href="javascript:formfieldsAddRow()">add field</a></th></tr></table><ul id="form_fields" style="list-style:none">';
$q2=dbAll('select * from forms_fields where formsId="'.$id.'" order by id');
$i=0;
$arr=array('input box','email','textarea','date','checkbox','selectbox','hidden');
foreach($q2 as $r2){
	$c.= '<li><table width="100%"><tr>';
	// { name
	$c.='<td width="30%"><input name="formfieldElementsName['.$i.']" value="'.htmlspecialchars($r2['name']).'" /></td>';
	// }
	// { type
	$c.='<td width="30%"><select name="formfieldElementsType['.$i.']">';
	foreach($arr as $v){
		$c.='<option value="'.$v.'"';
		if($v==$r2['type'])$c.=' selected="selected"';
		$c.='>'.$v.'</option>';
	}
	$c.='</select></td>';
	// }
	// { is required
	$c.='<td><input type="checkbox" name="formfieldElementsIsRequired['.($i).']"';
	if($r2['isrequired'])$c.=' checked="checked"';
	$c.=' /></td>';
	// }
	// { extras
	$c.='<td>';
	switch($r2['type']){
		case 'selectbox':case 'hidden':{
			$c.='<textarea class="small" name="formfieldElementsExtra['.($i++).']">'.htmlspecialchars($r2['extra']).'</textarea>';
			break;
		}
		default:{
			$c.='<input type="hidden" name="formfieldElementsExtra['.($i++).']" value="'.htmlspecialchars($r2['extra']).'" />';
		}
	}
	$c.= '</td>';
	// }
	$c.='</tr></table></li>';
}
$c.= '</ul></div>';
// }
// { success message
$c.= '<div id="forms-success-message">';
$c.= '<p>What should be displayed on-screen after the message is sent.</p>';
if(!$page_vars['forms_successmsg'])$page_vars['forms_successmsg']='<h2>Thank You</h2><p>We will be in contact as soon as we can.</p>';
$c.= ckeditor('page_vars[forms_successmsg]',$page_vars['forms_successmsg']);
$c.= '</div>';
// }
// { template
$c.= '<div id="forms-template">';
$c.= '<p>Leave blank to have an auto-generated template displayed.</p>';
$c.= ckeditor('page_vars[forms_template]',$page_vars['forms_template']);
$c.= '</div>';
// }
$c.='</div>';
$c.='<script>var formfieldElements='.$i.';</script>';
$c.='<script src="/ww.plugins/forms/admin/forms.js"></script>';
