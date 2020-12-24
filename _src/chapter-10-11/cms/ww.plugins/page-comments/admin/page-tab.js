function page_comments_activate(id){
	$.get('/ww.plugins/page-comments/admin/activate.php?id='+id,function(){
		var el=document.getElementById('page-comments-tr-'+id);
		el.className='active';
		$(el).each(page_comments_build_links);
	});
}
function page_comments_deactivate(id){
	$.get('/ww.plugins/page-comments/admin/deactivate.php?id='+id,function(){
		var el=document.getElementById('page-comments-tr-'+id);
		el.className='inactive';
		$(el).each(page_comments_build_links);
	});
}
function page_comments_delete(id){
	$.get('/ww.plugins/page-comments/admin/delete.php?id='+id,function(){
		$('#page-comments-tr-'+id).fadeOut(500,function(){
			$(this).remove();
		});
	});
}
function page_comments_build_links(){
	var stat=this.className;
	if(!stat)return;
	var id=this.id.replace('page-comments-tr-','');
	var html='<a href="javascript:page_comments_'+(
		(stat=='active')
			?'deactivate('+id+');">deactivate'
			:'activate('+id+');">activate'
		)+'</a>&nbsp;|&nbsp;<a href="javascript:page_comments_delete('+id+');">delete</a>';
	$(this).find('td:last-child').html(html);
}
$(function(){
	$('#page-comments-table tr').each(page_comments_build_links);
});
