function plugin_directories_max_image_size(){
  this.name = "directories_max_image_size";
  this.title = "set maximum image size";
  this.category = "edit";
  this.mode = 4;
  this.extensions = 'all';
  this.writable = 2;
  this.doFunction = function(dirs){
		var id = dirs[0];
		var dir,txt;
		dir=kfm_directories[id];
		if(dir.maxWidth==0 || dir.maxHeight==0)txt=_('No maximum image width or height has been set on this directory yet.',0,0,1);
		else txt=_('Current maximum image width and height: ',0,0,1)+dir.maxWidth+'x'+dir.maxHeight;
		kfm_prompt(txt+"\n\n"+_('Please enter the new maximum width, or 0 for none.',0,0,1),dir.maxWidth,function(x){
			x=parseInt(x);
			if(!x)return x_kfm_setDirectoryMaxSizeImage(id,x,0,null);
			kfm_prompt(_('Please enter the new maximum height.',0,0,1),dir.maxHeight,function(y){
				y=parseInt(y);
				x_kfm_setDirectoryMaxSizeImage(id,x,y,function(){
					if(!x || !y) return;
					kfm.alert(_('The new maximum image size has been set.'));
					kfm_directories[id].maxWidth=x;
					kfm_directories[id].maxHeight=y;
				});
			});
		});
  }
  this.nocontextmenu = false;
}

kfm_addHook(new plugin_directories_max_image_size());
