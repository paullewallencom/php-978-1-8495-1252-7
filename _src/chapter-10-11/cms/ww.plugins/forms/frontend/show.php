<?php
require_once SCRIPTBASE.'ww.incs/recaptcha.php';
function form_controller($page){
	$fields=dbAll('select * from forms_fields where formsId="'.$page->id.'" order by id');
	if(isset($_POST['_form_action']))return form_submit($page,$fields);
	return form_display($page,$fields);
}
function form_submit($page,$fields){
	$errors=form_validate($page,$fields);
	if(count($errors)){
		return '<ul id="forms-plugin-errors"><li>'
			.join('</li><li>',$errors)
			.'</ul>'
			.form_display($page,$fields);
	}
	if($page->vars->forms_send_as_email)form_send_as_email($page,$fields);
	if($page->vars->forms_record_in_db)form_record_in_db($page,$fields);
	return $page->vars->forms_successmsg;
}
function form_validate($page,$fields){
	$errors=array();
	if($page->vars->forms_captcha_required){
		$resp=recaptcha_check_answer(
			RECAPTCHA_PRIVATE,
			$_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"],
			$_POST["recaptcha_response_field"]
		);
		if(!$resp->is_valid)$errors[]='Please fill in the captcha.';
	}
	foreach($fields as $f){
		$name=preg_replace('/[^a-zA-Z0-9_]/','',$f['name']);
		if(isset($_POST[$name])){
			$val=$_POST[$name];
		}
		else $val='';
		if($f['isrequired'] && !$val){
			$errors[]='The "'.htmlspecialchars($f['name']).'" field is required.';
			continue;
		}
		if(!$val)continue;
		switch($f['type']){
			case 'date': // {
				if(preg_replace('/[0-9]{4}-[0-9]{2}-[0-9]{2}/','',$val)=='')continue;
				$errors[]='"'.htmlspecialchars($f['name']).'" must be in yyyy-mm-dd format.';
				break;
			// }
			case 'email': // {
				if(filter_var($val,FILTER_VALIDATE_EMAIL))continue;
				$errors[]='"'.htmlspecialchars($f['name']).'" must be an email address.';
				break;
			// }
			case 'selectbox': // {
				$arr=explode("\n",htmlspecialchars($f['extra']));
				$found=0;
				foreach($arr as $li){
					if($li=='')continue;
					if($val==trim($li))$found=1;
				}
				if($found)continue;
				$errors[]='You must choose one of the options in "'.htmlspecialchars($f['name']).'".';
				break;
			// }
		}
	}
	return $errors;
}
function form_record_in_db($page,$fields){
	$formId=$page->id;
	dbQuery("insert into forms_saved (forms_id,date_created) values($formId,now())");
	$id=dbOne('select last_insert_id() as id','id');
	foreach($fields as $r){
		$name=preg_replace('/[^a-zA-Z0-9_]/','',$r['name']);
		if(isset($_POST[$name]))$val=addslashes($_POST[$name]);
		else $val='';
		$key=addslashes($r['name']);
		dbQuery("insert into forms_saved_values (forms_saved_id,name,value) values($id,'$key','$val')");
	}
}
function form_send_as_email($page,$fields){
	$m="--------------------------------\n";
	foreach($fields as $f){
		$name=preg_replace('/[^a-zA-Z0-9_]/','',$f['name']);
		if(!isset($_POST[$name]))continue;
		$m.=$f['name']."\n\n";
		$m.=$_POST[$name];
		$m.="\n--------------------------------\n";
	}
	$from=preg_replace('/^FIELD{|}$/','',$page->vars->forms_replyto);
	$to=preg_replace('/^FIELD{|}$/','',$page->vars->forms_recipient);
	if($page->vars->forms_replyto!=$from)$from=$_POST[preg_replace('/[^a-zA-Z0-9_]/','',$from)];
	if($page->vars->forms_recipient!=$to)$to=$_POST[preg_replace('/[^a-zA-Z0-9_]/','',$to)];
	mail($to,'['.$_SERVER['HTTP_HOST'].'] '.addslashes($page->name),$m,"From: $from\nReply-to: $from");
}
function form_display($page,$fields){
	if(isset($page->vars->forms_template)){
		$template=$page->vars->forms_template;
		if($template=='&nbsp;')$template=false;
	}
	else $template=false;
	if(!$template)$template=form_template_generate($page,$fields);
	return form_template_render($template,$fields);
}
function form_template_generate($page,$fields){
	$t='<table>';
	foreach($fields as $f){
		if($f['type']=='hidden')continue;
		$name=preg_replace('/[^a-zA-Z0-9_]/','',$f['name']);
		$t.='<tr><th>'.htmlspecialchars($f['name']).'</th><td>{{$'.$name.'}}</td></tr>';
	}
	if($page->vars->forms_captcha_required){
		$t.='<tr><td>&nbsp;</td><td>{{CAPTCHA}}</td></tr>';
	}
	return $t.'</table>';
}
function form_template_render($template,$fields){
	if(strpos($template,'{{CAPTCHA}}')!==false){
		$template=str_replace('{{CAPTCHA}}',recaptcha_get_html(RECAPTCHA_PUBLIC),$template);
	}
	foreach($fields as $f){
		$name=preg_replace('/[^a-zA-Z0-9_]/','',$f['name']);
		if($f['isrequired'])$class=' required';
		else $class='';
		if(isset($_POST[$name])){
			$val=$_POST[$name];
		}
		else $val='';
		switch($f['type']){
			case 'checkbox': // {
				$d='<input type="checkbox" id="forms-plugin-'.$name.'" name="'.$name.'"';
				if($val)$d.=' checked="'.$_REQUEST[$name].'"';
				$d.=' class="'.$class.'" />';
				break;
			// }
			case 'date': // {
				if(!$val)$val=date('Y-m-d');
				$d='<input id="forms-plugin-'.$name.'" name="'.$name.'" value="'.htmlspecialchars($val).'" class="date'.$class.'" />';
				break;
			// }
			case 'email': // {
				$d='<input type="email" id="forms-plugin-'.$name.'" name="'.$name.'" value="'.htmlspecialchars($val).'" class="email'.$class.'" />';
				break;
			// }
			case 'selectbox': // {
				$d='<select id="forms-plugin-'.$name.'" name="'.$name.'" class="'.$class.'">';
				$arr=explode("\n",htmlspecialchars($f['extra']));
				foreach($arr as $li){
					if($li=='')continue;
					$li=trim($li);
					if($val==$li)$d.='<option selected="selected">'.$li.'</option>';
					else $d.='<option>'.$li.'</option>';
				}
				$d.='</select>';
				break;
			// }
			case 'textarea': // {
				$d='<textarea id="forms-plugin-'.$name.'" name="'.$name.'" class="'.$class.'">'.htmlspecialchars($val).'</textarea>';
				break;
			// }
			default: // {
				$d='<input id="forms-plugin-'.$name.'" name="'.$name.'" value="'.htmlspecialchars($val).'" class="text'.$class.'" />';
			// }
		}
		$template=str_replace('{{$'.$name.'}}',$d,$template);
	}
	return '<form method="post">'.$template.'<input type="submit" name="_form_action" value="submit" /></form><script src="/ww.plugins/forms/frontend/forms.js"></script>';
}
