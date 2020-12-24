<?php
function content_snippet_show($vars){
	if(is_object($vars) && isset($vars->id) && $vars->id){
		$html=dbOne('select html from content_snippets where id='.$vars->id,'html');
		if($html)return $html;
	}
	return '<p>this Content Snippet is not yet defined.</p>';
}
