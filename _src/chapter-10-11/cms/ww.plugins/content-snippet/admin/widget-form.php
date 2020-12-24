<?php
require $_SERVER['DOCUMENT_ROOT'].'/ww.admin/admin_libs.php';

// { save data to the database if requested
if(isset($_REQUEST['action']) && $_REQUEST['action']=='save'){
	$id=(int)$_REQUEST['id'];
	$id_was=$id;
	$html=addslashes($_REQUEST['html']);
	$sql="content_snippets set html='$html'";
	if($id){
		$sql="update $sql where id=$id";
		dbQuery($sql);
	}
	else{
		$sql="insert into $sql";
		dbQuery($sql);
		$id=dbOne('select last_insert_id() as id','id');
	}
	$ret=array('id'=>$id,'id_was'=>$id_was);
	echo json_encode($ret);
	exit;
}
// }
// { return content from table if requested
if(isset($_REQUEST['get_content_snippet'])){
	require '../frontend/index.php';
	$o=new stdClass();
	$o->id=(int)$_REQUEST['get_content_snippet'];
	$ret=array('content'=>content_snippet_show($o));
	echo json_encode($ret);
	exit;
}
// }

// { set ID and show link in admin area
if(isset($_REQUEST['id']))$id=(int)$_REQUEST['id'];
else $id=0;
echo '<a href="javascript:content_snippet_edit('.$id.');" id="content_snippet_editlink_'.$id.'" class="content_snippet_editlink">view or edit snippet</a>';
// }

?>
<script>
if(!window.ww_content_snippet)window.ww_content_snippet={
	editor_instances:0
};
function content_snippet_edit(id){
	var el=document.getElementById('content_snippet_editlink_'+id);
	ww_content_snippet.editor_instances++;
	var rtenum=ww_content_snippet.editor_instances;
	var d=$('<div><textarea style="width:600px;height:300px;" id="content_snippet_html'+rtenum+'" name="content_snippet_html'+rtenum+'"></textarea></div>');
	$.getJSON('/ww.plugins/content-snippet/admin/widget-form.php',{'get_content_snippet':id},function(res){
		d.dialog({
			minWidth:630,
			minHeight:400,
			height:400,
			width:630,
			modal:true,
			beforeclose:function(){
				if(!ww_content_snippet.rte)return;
				ww_content_snippet.rte.destroy();
				ww_content_snippet.rte=null;
			},
			buttons:{
				'Save':function(){
					var html=ww_content_snippet.rte.getData();
					$.post('/ww.plugins/content-snippet/admin/widget-form.php',{'id':id,'action':'save','html':html},function(ret){
						if(ret.id!=ret.was_id){
							el.id='content_snippet_editlink_'+ret.id;
							el.href='javascript:content_snippet_edit('+ret.id+')';
						}
						id=ret.id;
						var w=$(el).closest('.widget-wrapper');
						var wd=w.data('widget');
						wd.id=id;
						w.data('widget',wd);
						updateWidgets(w.closest('.panel-wrapper'));
						d.remove();
					},'json');
				},
				'Close':function(){
					d.remove();
				}
			}
		});
		ww_content_snippet.rte=CKEDITOR.replace( 'content_snippet_html'+rtenum,{filebrowserBrowseUrl:"/j/kfm/",menu:"WebME"} );
		ww_content_snippet.rte.setData(res.content);
	});
}
</script>
