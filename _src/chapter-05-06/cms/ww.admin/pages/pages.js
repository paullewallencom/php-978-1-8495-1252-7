$(function(){
	$('.tabs').tabs();
	$('#pages_form select[name=parent]').remoteselectoptions({
		url:'/ww.admin/pages/get_parents.php',
		other_GET_params:currentpageid
	});
});
