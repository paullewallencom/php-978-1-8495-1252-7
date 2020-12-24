$(function(){
	$('.tabs').tabs();
	$('#pages_form select[name=parent]').remoteselectoptions({
		url:'/ww.admin/pages/get_parents.php',
		other_GET_params:currentpageid
	});
	$('#pages_form select[name=type]').remoteselectoptions({
		url:'/ww.admin/pages/get_types.php'
	});
});
