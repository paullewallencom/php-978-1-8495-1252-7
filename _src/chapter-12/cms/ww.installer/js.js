function step1(){
	var tests=[
		['php-version','PHP version 5.2 or higher'],
		['sqlite','PDO SQLite version 3+'],
		['f','Write permissions for user-files directory'],
		['ww-cache','Write permissions for cache directory'],
		['private','Write permissions for config directory']
	]
	var html='';
	for(var i=0;i<tests.length;++i){
		html+='<div id="'+tests[i][0]+'" class="checking">'
			+'Checking: '+tests[i][1]
			+'</div>';
	}
	$('#content').html(html);
	$('.checking').each(function(){
		$.post('/ww.installer/check-'+this.id+'.php',step1_verify,'json');
	});
	setTimeout(step1_finished,500);
}
function step1_finished(){
	if($('.checking').length){
		setTimeout(step1_finished,500);
	}
	else step2();
}
function step1_verify(res){
	if(res.status==1){
		$('#'+res.test).slideUp(function(){
			 $('#'+res.test).remove();
		});
		return;
	}
	$('#'+res.test)
		.addClass('error')
		.append('<p>'+res.error+'</p>');
}
function step2(){
	$('#content').html('<table>'
		+'<tr><th id="db" colspan="2">Database name</th></tr>'
		+'<tr><th>Name</th><td><input id="dbname" /></td></tr>'
		+'<tr><th>Host</th><td><input id="dbhost" value="localhost" /></td></tr>'
		+'<tr><th>User</th><td><input id="dbuser" /></td></tr>'
		+'<tr><th>Password</th><td><input id="dbpass" /></td></tr>'
		+'<tr><th id="ad" colspan="2">Administrator</th></tr>'
		+'<tr><th>Email address</th><td><input id="admin" /></td></tr>'
		+'<tr><th>Password</th><td><input id="adpass" /></td></tr>'
		+'<tr><th>(and again)</th><td><input id="adpass2" /></td></tr>'
		+'</table><div class="error" id="errors"></div>');
	$('#content input').change(step2_verify);
}
function step2_verify(){
	var opts={
		dbname:$('#dbname').val(),
		dbhost:$('#dbhost').val(),
		dbuser:$('#dbuser').val(),
		dbpass:$('#dbpass').val(),
		admin:$('#admin').val(),
		adpass:$('#adpass').val(),
		adpass2:$('#adpass2').val(),
	}
	$.post('/ww.installer/check-config.php',
		opts,step2_verify2,'json');
}
function step2_verify2(res){
	if(!res.length)return step3();
	var html='<ul>';
	for(var i=0;i<res.length;++i){
		html+='<li>'+res[i]+'</li>';
	}
	html+='</ul>';
	$('#errors').html(html);
}
function step3(){
	$('#content').html(
		'<p>Installation is complete. Your CMS is ready for population.</p>'
		+'<p>Please <a href="/ww.admin/">log in</a> to the administration area to create your first page.</p>'
	);
}
$(step1);
