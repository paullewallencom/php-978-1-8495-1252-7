window.kfm_zip=function(name){
	if(!name || name.toString()!==name)name='zipped.zip';
	var ok=false;
	kfm_prompt(kfm.lang.WhatFilenameDoYouWantToUse,name,function(name){
		if(!name)return;
		if(/\.zip$/.test(name))ok=true;
		else kfm.alert(kfm.lang.TheFilenameShouldEndWithN('.zip'));
		if(!ok)return kfm_zip(name);
		x_kfm_zip(name,selectedFiles,kfm_refreshFiles);
	});
}
