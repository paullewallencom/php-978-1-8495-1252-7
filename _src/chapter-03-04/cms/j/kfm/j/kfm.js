// see license.txt for licensing
var KFM=function(){
};
var kfm=new KFM();
kfm.about=function(){
	var div=document.createElement('div');
	div.style.width='400px';
	var html='<h1>KFM '+kfm_vars.version+'</h1>';
	{ // sponsors
		html+='<h2>Sponsors</h2>';
		html+='KFM is free software. Here are some recent sponsors:<br />';
		html+='<a href="http://tinyurl.com/2uerfm" style="float:right;display:block;border:1px solid red;background:#fff;text-decoration:none;text-align:center;margin-right:10px" target="_blank">Donate to KFM</a>';
		html+='<a href="http://webworks.ie/" target="_blank"><strong>webworks.ie</strong></a><br />';
		html+='<a href="http://www.bluenectar.com.au/" target="_blank">Blue Nectar</a><br />';
	}
	{ // developers
		html+='<h2>Developers</h2>';
		html+='<a href="http://verens.com/" target="_blank"><strong>Kae Verens</strong></a><br />';
		html+='<a href="http://www.companytools.nl/kfm" target="_blank">Benjamin ter Kuile</a><br />';
	}
	{ // translators
		html+='<h2>Translators</h2><table><tr><td>';
		html+='bg (Bulgarian): Tondy<br />';
		html+='cz (Czech): Petr Kamenik<br />';
		html+='da (Danish): Janich Rasmussen<br />';
		html+='de (German): Just Agens<br />';
		html+='en (English): Kae Verens<br />';
		html+='es (Spanish): Ramón Ramos<br />';
		html+='fa (Persion/Farsi): Ghassem Tofighi<br />';
		html+='fi (Finnish): Hannu (hlpilot)<br />';
		html+='fr (French): Hubert Garrido</td><td>';
		html+='ga (Irish): Kae Verens<br />';
		html+='hu (Hungarian): Ujj-Mészáros István<br />';
		html+='it (Italian): Stefano Luchetta<br />';
		html+='jp (Japanese): S. Higashi<br />';
		html+='nl (Dutch): Roy Lubbers<br />';
		html+='pl (Polish): Jan Kurek<br />';
		html+='ro (Romanian): Andrei Suscov<br />';
		html+='ru (Russian): Andrei Suscov<br />';
		html+='sv (Swedish): Aram Mäkivierikko</td></tr></table>';
	}
	{ // bug testers
		html+='<h2>Bug Testers</h2>';
		html+='To many to mention! To report a bug, please <a href="http://code.google.com/p/kfm/issues/">go here</a>.';
	}
	div.innerHTML=html;
	kfm_modal_open(div,kfm.lang['about KFM']);
};
kfm.addCell=function(o,colNum,colSpan,subEls,className){
	var f=o.insertCell(+colNum);
	if(colSpan)f.colSpan=colSpan;
	if(subEls)kfm.addEl(f,subEls);
	if(className)f.className=className;
	return f;
};
kfm.addEl=function(o,a){
	if(!o)return;
	if(!a)return o;
	if($type(a)!='array')a=[a];
	for(var i=0;i<a.length;++i){
		if($type(a[i])=='array'){
			kfm.addEl(o,a[i]);
		}
		else{
			if($type(a[i])=='string')a[i]=newText(a[i]);
			if(!a[i])return;
			o.appendChild(a[i]);
		}
	}
	return o;
};
kfm.addRow=function(t,p,c){
	if(p)var position=p;
	else var position=-1;
	var o=t.insertRow(position);
	if(c && c!=undefined)o.className=c;
	return o;
};
kfm.alert=function(txt){
  $j('<div title="Alert"></div>').append(txt).dialog({modal:true});
};
kfm.showErrors=function(errors){
	var div=document.createElement('div');
	div.style.width='400px';
	var html='';
	for(var i=0;i<errors.length;i++){
		html+='<span>'+errors[i].message+'</span><br/>';
		/* Add tooltip or do something with:
		 *errors[i].level
		 *errors[i].function
		 *errors[i].class
		 *errors[i].file
		 */
	}
	div.innerHTML=html;
	kfm_modal_open(div,kfm.lang.Errors);
};
kfm.showMessages=function(messages){
	var message='';
	for(var i=0;i<messages.length;i++){
		message+=messages[i].message;
    if(i != messages.length-1)message+='<hr>';
	}
	new Notice(message);
};
kfm.switchFilesMode=function(m){
	kfm_listview = +m;
	x_kfm_loadFiles(kfm_cwd_id,true,kfm_refreshFiles);
};
kfm.build=function(){
	var form_panel,form,right_column,directories,logs,logHeight=64,j,i,w,win,dirs,j,tmp;
	if(kfm_vars.permissions.file.rm)kfm_addHook({name:"remove", mode:2,extensions:"all", writable:1,title:"delete file", doFunction:function(files){
			if(files.length>1)kfm_deleteSelectedFiles();
			else kfm_deleteFile(files[0]);
		}
	});
	win=$j(window);
	w={x:win.width(),y:win.height()};
	{ // extend language objects
		for(j in kfm.lang){
			if(kfm_regexps.percent_numbers.test(kfm.lang[j])){
				kfm.lang[j]=(function(str){
					return function(){
						tmp=str;
						for(i=1;i<arguments.length+1;++i)tmp=tmp.replace("%"+i,arguments[i-1]);
						return tmp;
					};
				})(kfm.lang[j]);
			}
		}
	}
	kfm_cwd_name=starttype;
	$j(document.getElementById('removeme')).remove();
	document.body.style.overflow='hidden';
	kfm_addContextMenu(document.body,function(e){
		kfm_closeContextMenu();
		for(i=0;i<HooksGlobal.length;i++){
			obj=HooksGlobal[i];
			context_categories[obj.category].add(obj);
		}
		// { place top-level hard-coded contextmenu items here
		context_categories['kfm'].add({name:'about',title:'about KFM',category:'kfm', doFunction:kfm.about});
		// }
		$j(document.body).click(kfm_closeContextMenu);
		kfm_createContextMenu({x:e.pageX,y:e.pageY},show_category_headers);
	});
	if(kfm_vars.use_templates){
		document.getElementById('templateWrapper').style.display='block';
		var documents_body=document.getElementById('documents_body');
		if(!documents_body)alert('no #documents_body on page - please fix your template');
		var wrapper=document.getElementById('kfm_search_wrapper');
		if(wrapper)wrapper.appendChild(kfm_searchBoxFile());
		var wrapper=document.getElementById('kfm_upload_wrapper');
		if(wrapper)kfm.addEl(wrapper,kfm_createFileUploadPanel(true));
	}
	else{
		{ // create left column
			var left_column=kfm_createPanelWrapper('kfm_left_column');
			kfm_resizeHandler_addMaxHeight('kfm_left_column');
			$each(['kfm_directories_panel','kfm_widgets_panel','kfm_search_panel','kfm_directory_properties_panel'],function(panel){
				if(!kfm_inArray(panel,kfm_hidden_panels))kfm_addPanel(left_column,panel);
			});
			left_column.panels_unlocked=1;
			left_column.style.height=w.y+'px';
			kfm_addContextMenu(left_column,function(e){
				var links=[],i;
				var l=left_column.panels_unlocked;
				links.push({title:l?'lock':'unlock', doFunction:function(){
						kfm_togglePanelsUnlocked();
					}
				});
				var ps=left_column.panels;
				for(var i=0;i<ps.length;++i){
					var p=document.getElementById(ps[i]);
          var panel_name=ps[i];
					if(!p.visible && !kfm_inArray(ps[i],kfm_hidden_panels))links.push({title:'Show panel '+p.panel_title, doFunction:function(){
							kfm_addPanel(document.getElementById('kfm_left_column'),panel_name);
						}
					});
				}
			});
		}
		{ // create right_column
			var option,lselect;
			right_column=document.createElement('div');
			right_column.id='kfm_right_column';
			// {{{ create view-type selectbox
			lselect=document.createElement('select');	
			lselect.style.position='absolute';
			lselect.style.zIndex=2;
			lselect.style.right=0;
			lselect.style.top='1px';
			lselect.style.border=0;
			$j.event.add(lselect,'change',function(){
				kfm.switchFilesMode(this.value);
			});
			// {{{ icons mode
			option=document.createElement('option');
			option.selected=!kfm_listview;
			option.value=0;
			option.innerHTML=kfm.lang.Icons;
			lselect.appendChild(option);
			// }}}
			// {{{
			option=document.createElement('option');
			option.selected=kfm_listview;
			option.value=1;
			option.innerHTML=kfm.lang.ListView;
			lselect.appendChild(option);
			// }}}
			// }}}
			var header=document.createElement('div');
			header.className='kfm_panel_header';
			header.id='kfm_panel_header';
      hhtml = '<span id="documents_loader"></span>' +
        '<span id="cwd_display"></span>' + 
        '<span id="folder_info"></span>';
			if(kfm_vars.show_admin_link) hhtml+='<a href="admin/" id="admin_panel_link">Admin panel</a>';
			header.innerHTML= hhtml;
			var documents_body=document.createElement('div');
			documents_body.id='documents_body';
			right_column.appendChild(lselect);
			right_column.appendChild(header);
			right_column.appendChild(documents_body);
		}
		{ // draw areas to screen and load files and directory info
			document.body.appendChild(left_column);
			document.body.appendChild(right_column);
		}
	}
	{ // set up main panel
		$j.event.add(documents_body,'click',function(e){
			if(e.button==2)return;
			if(!window.dragType)kfm_selectNone()
		});
		$j.event.add(documents_body,'mousedown',function(e){
			if(e.button==2)return;
			window.mouseAt={x:e.pageX,y:e.pageY};
			if(this.contentMode=='file_icons' && this.fileids.length)window.dragSelectionTrigger=setTimeout(function(){kfm_selection_dragStart()},200);
			$j.event.add(documents_body,'mouseup',kfm_selection_dragFinish);
		});
		kfm_addContextMenu(documents_body,function(e){
			var links=[],i;
			if(kfm_vars.permissions.file.mk)context_categories['edit'].add({
				name:'file_new',
				title:kfm.lang['create empty file'],
				category:'edit',
				doFunction:function(){kfm_createEmptyFile()}
			});
			if(selectedFiles.length>1 && kfm_vars.permissions.file.ed)context_categories['edit'].add({
				name:'files_rename',
				title:kfm.lang["rename file"],
				category:'edit',
				doFunction:function(){kfm_renameFiles()}
			});
			
			if(selectedFiles.length>1 && kfm_vars.permissions.file.mk)context_categories['main'].add({
				name:'files_zip',
				title:kfm.lang.ZipUpFiles,
				category:'main',
				doFunction:function(){kfm_zip()}
			});
			if(selectedFiles.length!=document.getElementById('documents_body').fileids.length)context_categories['selection'].add({
				name:'files_select_all',
				title:"select all",
				category:'selection',
				doFunction:function(){kfm_selectAll()}
			});
			if(selectedFiles.length){ // select none, invert selection
				context_categories['selection'].add({
					name:'files_select_none',
					title:kfm.lang.SelectNone,
					category:'selection',
					doFunction:function(){kfm_selectNone()}
				});
				context_categories['selection'].add({
					name:'files_select_invert',
					title:kfm.lang.InvertSelection,
					category:'selection',
					doFunction:function(){kfm_selectInvert()}
				});
			}
			for(i=0;i<HooksFilePanel.length;i++){
				obj=HooksFilePanel[i];
				context_categories[obj.category].add(obj);
			}
		});
		documents_body.parentResized=kfm_files_reflowIcons;
	}
	dirs=document.getElementById('kfm_directories');
	if(dirs){
		x_kfm_loadDirectories(kfm_vars.root_folder_id,kfm_refreshDirectories);
	}
	x_kfm_loadFiles(kfm_vars.startupfolder_id,kfm_refreshFiles);
	$j.event.add(document,'keyup',kfm.keyup);
	$j.event.add(window,'resize',function(){kfm_resizeHandler();});
	kfm_contextmenuinit();
	$j.event.add(documents_body,'scroll',kfm_setThumbnails);
};
kfm.confirm=function(txt, fn){
  if(!(typeof(fn)=='function')) fn = function(){};
  $j('<div title="Confirm"></div>').append(txt).dialog({
    modal:true,
    buttons:{
      Ok: function(){$j(this).remove();fn();}, // TODO language string
      Cancel: function(){$j(this).remove()} // TODO language string
    }
  });
};
kfm.getContainer=function(p,c){
	for(var i=0;i<c.length;++i){
		var a=c[i],x=getOffset(a,'Left'),y=getOffset(a,'Top');
		if(x<p.x&&y<p.y&&x+a.offsetWidth>p.x&&y+a.offsetHeight>p.y)return a;
	}
};
kfm.getParentEl=function(c,t){
	while(c.tagName!=t&&c)c=c.parentNode;
	return c;
};
kfm.keyup=function(e){
	var key=e.which;
	var cm=document.getElementById('documents_body').contentMode;
	switch(key){
		case 8:{ // delete
			kfm_delete(cm);
			break;
		}
		case 13:{ // enter
			if(!selectedFiles.length||window.inPrompt||cm!='file_icons')return;
			if(selectedFiles.length>1){
				var files=$j.extend([],selectedFiles);
				var openingHook=kfm_getDefaultOpener(files);
				if(openingHook)openingHook.doFunction(files);
			}else{
				var id=selectedFiles[0];
				var openingHook=kfm_getDefaultOpener([id]);
				if(openingHook)openingHook.doFunction([id]);
			}
			break;
		}
		case 27:{ // escape
			if(cm=='lightbox')kfm_img_stopLightbox();
			else if(!window.inPrompt) kfm.confirm(kfm.lang.AreYouSureYouWantToCloseKFM, function(){window.close()});
			break;
		}
		case 37:{ // left arrow
			if(cm=='file_icons'){
				if(!kfm_listview)kfm_shiftFileSelectionLR(-1);
			}
			else if(cm=='lightbox'){
				window.kfm_slideshow_stopped=1;
				if(window.lightbox_slideshowTimer)clearTimeout(window.lightbox_slideshowTimer);
				window.kfm_slideshow.at-=2;
				kfm_img_startLightbox();
			}
			else break;
			e.stopPropagation();
			break;
		}
		case 38:{ // up arrow
			if(cm=='file_icons'){
				if(kfm_listview)kfm_shiftFileSelectionLR(-1);
				else kfm_shiftFileSelectionUD(-1);
			}
			break;
		}
		case 39:{ // right arrow
			if(cm=='file_icons'){
				if(!kfm_listview)kfm_shiftFileSelectionLR(1);
			}
			else if(cm=='lightbox'){
				window.kfm_slideshow_stopped=1;
				if(window.lightbox_slideshowTimer)clearTimeout(window.lightbox_slideshowTimer);
				kfm_img_startLightbox();
			}
			else break;
			e.stopPropagation();
			break;
		}
		case 40:{ // down arrow
			if(cm=='file_icons'){
				if(kfm_listview)kfm_shiftFileSelectionLR(1);
				else kfm_shiftFileSelectionUD(1);
			}
			break;
		}
		case 46:{ // delete
			kfm_delete(cm);
			break;
		}
		case 65:{ // a
			if(e.control&&cm=='file_icons'){
				clearSelections(e);
				kfm_selectAll();
			}
			break;
		}
		case 85:{ // u
			if(e.control&&cm=='file_icons'){
				clearSelections(e);
				kfm_selectNone();
			}
			break;
		}
		case 113:{ // f2
			if(cm!='file_icons')return;
			if(!selectedFiles.length)return kfm.alert(_("Please select a file before you try to rename it"));
			if(selectedFiles.length==1){
				kfm_renameFile(selectedFiles[0]);
			}
			else kfm.alert(_("You can only rename one file at a time"));
			break;
		}
		case 127:{ // backspace
			kfm_delete(cm);
			break;
		}
	}
}
function kfm_delete(cm){
	if(!selectedFiles.length||cm!='file_icons')return;
	if(selectedFiles.length>1)kfm_deleteSelectedFiles();
	else kfm_deleteFile(selectedFiles[0]);
}
function kfm_inArray(needle,haystack){
	return haystack.indexOf(needle)!=-1;
}
function kfm_prompt(txt,val,fn){
  $j('<div title="Prompt"></div>').append(txt).append('<input id="kfm_prompt_input" value="'+val+'"/>').dialog({
    modal:true,
    buttons:{
      Ok: function(){
        val = $j(this).find('#kfm_prompt_input').val();
        fn(val);
        $j(this).dialog('close');
      },
      Cancel: function(){
        $j(this).dialog('close');
      }
    },
    open:function(){$j('#kfm_prompt_input').focus();},
    close:function(){$j(this).remove()}
  });
}
function kfm_run_delayed(name,call){
	name=name+'_timeout';
	if(window[name])clearTimeout(window[name]);
	window[name]=setTimeout(call,500);
}
function kfm_shrinkName(name,wrapper,text,size,maxsize,extension){
	var position=step=Math.ceil(name.length/2),postfix='[...]'+extension,prefix=size=='offsetHeight'?'. ':'';
	do{
		text.innerHTML=prefix+name.substring(0,position)+postfix;
		step=Math.ceil(step/2);
		position+=(wrapper[size]>maxsize)?-step:step;
	}while(step>1);
	var html='<span class="filename">'+name.substring(0,position+(prefix?0:-1))+'</span><span style="color:red;text-decoration:none">[...]</span>';
	if(extension)html+='<span class="filename">'+extension+'</span>';
	text.innerHTML=html;
}
/* Start kfm plugin iframe functions */
function kfm_pluginIframeShow(url){
	if(url){
		$j('#plugin_iframe_div').remove();
		var jDiv=$j('<div id="plugin_iframe_div"></div>').css({
			'display':'none',
			'position':'absolute',
			'left':0,
			'top':0,
			'width':'100%',
			'height':'100%',
			'backgroundImage':'url(i/bg-black-75.png)',
			'z-index':202
		});
		$j(jDiv).appendTo('body');
		$j(jDiv).append($j('<div id="plugin_iframe_header"></div>').css({
			'width':'100%',
			'height':'25px',
			'color':'white',
			'backgroundColor':'black'
		}));
		kfm_pluginIframeButton('close');
		$j(jDiv).slideDown('normal',function(){
			var x=$j('body').width(),y=$j('body').height()-25;
			$j(this).append(
				'<iframe id="plugin_iframe_element" src="'+url+'" style="width:100%;height:100%;"></iframe>'
			);
		});
	}else{
		$j('#plugin_iframe_div').slideDown('normal');
	}
}
function kfm_pluginIframeButton(code,text){
	var btncode,btn;
	var hdr=document.getElementById('plugin_iframe_header');
	if(!hdr)return;
	if(code=='close'){
		btn=$j('<img src="themes/'+kfm_theme+'/icons/remove.png"/>').click(function(){kfm_pluginIframeHide();});
		btn.css({'float':'right'});
	}else{
		btn=$j('<span class="kfm_plugin_iframe_button"></span>').click(function(){eval(code);});
	}
	if(text)$j(btn).text(text);
	$j(btn).appendTo(hdr);
}
function kfm_pluginIframeHide(){
	$j('#plugin_iframe_div').slideUp('normal');
	//var ifd=document.getElementById('plugin_iframe_div');
	//if(ifd) ifd.style.display='none';
}
function kfm_pluginIframeMessage(message){
	/* not tested yet, should not be needed*/
	var msgdiv=document.getElementById('plugin_iframe_message');
	if(!msgdiv)return;
	msgdiv.innerHTML=message;
	msgdiv.style.display='block';
	setTimeOut('document.getElementById("plugin_iframe_message").style.display="none";',3000);
}
function kfm_pluginIframeVar(varname){
	var ifr=document.getElementById('plugin_iframe_element');
	if(!ifr) return null;
	var ifrvar=eval('ifr.contentWindow.'+varname);
	return ifrvar;
}
/* End kfm plugin iframe functions */
var kfm_regexps={
	all_up_to_last_dot:/.*\./,
	all_up_to_last_slash:/.*\//,
	ascii_stuff:/%([89A-F][A-Z0-9])/g,
	get_filename_extension:/.*\.([^.]*)$/,
	percent_numbers:/%[1-9]/,
	plus:/\+/g,
	remove_filename_extension:/\.[^.]*$/
}
