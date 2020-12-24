function plugin_edit_area(){
	this.name='edit_area';
	this.title='Edit with edit area';
	this.category='edit';
	this.mode=2; //only one file
	this.writable=1; // only writable files
	this.extensions=['html','c','cpp','css','js', 'pas','php', 'python', 'sql', 'vb', 'xml','txt'];
	this.doFunction=function(files){
		//fid=files[0];
		kfm_pluginIframeShow('plugins/edit_area/editfile.php?fid='+files.join(',')+kfm_vars.get_params);
	}
}
kfm_addHook(new plugin_edit_area());
