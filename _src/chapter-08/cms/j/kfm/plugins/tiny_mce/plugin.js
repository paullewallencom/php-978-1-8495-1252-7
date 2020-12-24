function plugin_tiny_mce(){
	this.name='tiny_mce';
	this.title='Edit with TinyMCE';
	this.mode=0; //only one file
  this.category = "edit";
	this.writable=1; // only writable files
	this.extensions=['html','htm','tpl'];
	this.doFunction=function(files){
		fid=files[0];
		kfm_pluginIframeShow('plugins/tiny_mce/editfile.php?fid='+fid+kfm_vars.get_params);
	}
}
kfm_addHook(new plugin_tiny_mce());
