function buildRightWidget(p){
	var widget=$('<div class="widget-wrapper '+(p.disabled?'disabled':'enabled')+'"></div>')
		.data('widget',p);
	var h4=$('<h4></h4>')
		.appendTo(widget);
	var name=p.name||p.type;
	$('<input type="checkbox" class="widget_header_visibility" title="tick this to show the widget title on the front-end" />')
		.click(widget_header_visibility)
		.appendTo(h4);
	$('<span class="name">'+name+'</span>')
		.click(widget_rename)
		.appendTo(h4);
	$('<span class="panel-opener">&darr;</span>')
		.appendTo(h4)
		.click(showWidgetForm);
	return widget;
}
function panel_remove(i){
	var p=ww_panels[i];
	var id=p.id;
	if(!confirm('Deleting this panel will remove the configurations of its contained widgets. Are you /sure/ you want to remove this? Note that your panel will be recreated (without its widgets) if the site theme has it defined.'))return;
	$.get('/ww.plugins/panels/admin/remove-panel.php?id='+id,function(){
		$('#panel'+id).remove();
	});
}
function panel_toggle_disabled(i){
	var p=ww_panels[i];
	p.disabled=p.disabled?0:1;
	var panel=$('#panel'+p.id);
	panel.removeClass().addClass('panel-wrapper '+(p.disabled?'disabled':'enabled'));
	$('.controls .disabled',panel).text(p.disabled?'disabled':'enabled');
	ww_panels[i]=p;
	$.get('/ww.plugins/panels/admin/save-disabled.php?id='+p.id+'&disabled='+p.disabled);
}
function panel_visibility(id){
	$.get('/ww.plugins/panels/admin/get-visibility.php',{'id':id},function(options){
		var d=$('<form><p>This panel will be visible in <select name="panel_visibility_pages[]" multiple="multiple">'+options+'</select>. If you want it to be visible in all pages, please choose <b>none</b> to indicate that no filtering should take place.</p></form>');
		d.dialog({
			width:300,
			height:400,
			close:function(){
				$('#panel_visibility_pages').remove();
				d.remove();
			},
			buttons:{
				'Save':function(){
					var arr=[];
					$('input[name="panel_visibility_pages[]"]:checked').each(function(){
						arr.push(this.value);
					});
					$.get('/ww.plugins/panels/admin/save-visibility.php?id='+id+'&pages='+arr);
					d.dialog('close');
				},
				'Close':function(){
					d.dialog('close');
				}
			}
		});
		$('select').inlinemultiselect({
			'separator':', ',
			'endSeparator':' and '
		});
	});
}
function panels_init(panel_column){
	for(var i=0;i<ww_panels.length;++i){
		var p=ww_panels[i];
		$('<div class="panel-wrapper '+(p.disabled?'disabled':'enabled')+'" id="panel'+p.id+'">'
				+'<h4><span class="name">'+p.name+'</span></h4>'
				+'<span class="controls" style="display:none">'
					+'<a title="remove panel" href="javascript:panel_remove('
					  +i+');" class="remove">remove</a>, '
					+'<a href="javascript:panel_visibility('
					  +p.id+');" class="visibility">visibility</a>, '
					+'<a href="javascript:panel_toggle_disabled('
					  +i+');" class="disabled">'+(p.disabled?'disabled':'enabled')+'</a>'
				+'</span></div>'
			)
			.data('widgets',p.widgets.widgets)
			.appendTo(panel_column);
	}
}
function showWidgetForm(w){
	if(!w.length)w=$(this).closest('.widget-wrapper');
	var f=$('form',w);
	if(f.length){
		f.remove();
		return;
	}
	var form=$('<form></form>').appendTo(w);
	var p=w.data('widget');
	if(ww_widgetForms[p.type]){
		$('<button style="float:right">Save</button>')
			.click(function(){
				w.find('input,select').each(function(i,el){
					p[el.name]=$(el).val();
				});
				w.data('widget',p);
				updateWidgets(form.closest('.panel-wrapper'));
				return false;
			})
			.appendTo(form);
		var fholder=$('<div style="clear:both;border-bottom:1px solid #416BA7">loading...</div>').prependTo(form);
		p.panel=$('h4>span.name',form.closest('.panel-wrapper')).eq(0).text();
		fholder.load(ww_widgetForms[p.type],p);
	}
	else $('<p>automatically configured</p>').appendTo(form);
	$('<a href="javascript:;" title="remove widget">remove</a>')
		.click(function(){
			if(!confirm('Are you sure you want to remove this widget from this panel?'))return;
			var panel=w.closest('.panel-wrapper');
			w.remove();
			updateWidgets(panel);
		})
		.appendTo(form);
	$('<span>, </span>').appendTo(form);
	$('<a href="javascript:;">visibility</a>')
		.click(widget_visibility)
		.appendTo(form);
	$('<span>, </span>').appendTo(form);
	$('<a class="disabled" href="javascript:;">'+(p.disabled?'disabled':'enabled')+'</a>')
		.click(widget_toggle_disabled)
		.appendTo(form);
}
function updateWidgets(panel){
	var id=panel[0].id.replace(/panel/,'');
	var w_els=$('.widget-wrapper',panel);
	var widgets=[];
	for(var i=0;i<w_els.length;++i){
		widgets.push($(w_els[i]).data('widget'));
	}
	panel.data('widgets',widgets);
	var json=json_encode({'widgets':widgets});
	$.post('/ww.plugins/panels/admin/save.php',{'id':id,'data':json});
}
function widget_header_visibility(ev){
	var el=ev.target,vis=[];
	var w=$(el).closest('.widget-wrapper');
	var p=w.data('widget');
	p.header_visibility=el.checked;
	w.data('widget',p);
	updateWidgets(w.closest('.panel-wrapper'));
}
function widget_rename(ev){
	var h4=$(ev.target);
	var p=h4.closest('.widget-wrapper').data('widget');
	var newName=prompt('What would you like to rename the widget to?',p.name||p.type);
	if(!newName)return;
	p.name=newName;
	h4.closest('.widget-wrapper').data('widget',p);
	updateWidgets($(h4).closest('.panel-wrapper'));
	h4.text(newName);
}
function widget_toggle_disabled(ev){
	var el=ev.target,vis=[];
	var w=$(el).closest('.widget-wrapper');
	var p=w.data('widget');
	p.disabled=p.disabled?0:1;
	w.removeClass().addClass('widget-wrapper '+(p.disabled?'disabled':'enabled'));
	$('.disabled',w).text(p.disabled?'disabled':'enabled');
	w.data('widget',p);
	updateWidgets(w.closest('.panel-wrapper'));
}
function widget_visibility(ev){
	var el=ev.target,vis=[];
	var w=$(el).closest('.widget-wrapper');
	var wd=w.data('widget');
	if(wd.visibility)vis=wd.visibility;
	$.get('/ww.plugins/panels/admin/get-visibility.php?visibility='+vis,function(options){
		var d=$('<form><p>This panel will be visible in <select name="panel_visibility_pages[]" multiple="multiple">'+options+'</select>. If you want it to be visible in all pages, please choose <b>none</b> to indicate that no filtering should take place.</p></form>');
		d.dialog({
			width:300,
			height:400,
			close:function(){
				$('#panel_visibility_pages').remove();
				d.remove();
			},
			buttons:{
				'Save':function(){
					var arr=[];
					$('input[name="panel_visibility_pages[]"]:checked').each(function(){
						arr.push(this.value);
					});
					wd.visibility=arr;
					w.data('widget',wd);
					updateWidgets(w.closest('.panel-wrapper'));
					d.dialog('close');
				},
				'Close':function(){
					d.dialog('close');
				}
			}
		});
		$('select').inlinemultiselect({
			'separator':', ',
			'endSeparator':' and '
		});
	});
}
function widgets_init(widget_column){
	for(var i=0;i<ww_widgets.length;++i){
		var p=ww_widgets[i];
		$('<div class="widget-wrapper"><h4>'+p.type+'</h4><p>'+p.description+'</p></div>')
			.appendTo(widget_column)
			.data('widget',p);
		ww_widgetsByName[p.type]=p;
	}
}
$(function(){
	var panel_column=$('#panels');
	panels_init(panel_column);
	var widget_column=$('#widgets');
	ww_widgetsByName={};
	widgets_init(widget_column);
	$('<span class="panel-opener">&darr;</span>')
		.appendTo('.panel-wrapper h4')
		.click(function(){
			var $this=$(this);
			var panel=$this.closest('div');
			if($('.panel-body',panel).length){
				$('.controls',panel).css('display','none');
				return $('.panel-body',panel).remove();
			}
			$('.controls',panel).css('display','block');
			var widgets_container=$('<div class="panel-body"></div>');
			widgets_container.appendTo(panel);
			var widgets=panel.data('widgets');
			for(var i=0;i<widgets.length;++i){
				var p=widgets[i];
				var w=buildRightWidget(p);
				w.appendTo(widgets_container);
				if(p.header_visibility)$('input.widget_header_visibility',w)[0].checked=true;
			}
			$('<br style="clear:both" />').appendTo(panel);
			$('.panel-body').sortable({
				'stop':function(){
					updateWidgets($(this).closest('.panel-wrapper'));
				}
			});
		});
	$('#widgets').sortable({
		'connectWith':'.panel-body',
		'stop':function(ev,ui){
			var item=ui.item;
			var panel=item.closest('.panel-wrapper');
			if(!panel.length)return $(this).sortable('cancel');
			var p=ww_widgetsByName[$('h4',ui.item).text()];
			var clone=buildRightWidget({'type':p.type});
			panel.find('.panel-body').append(clone);
			$(this).sortable('cancel');
			updateWidgets(panel);
		}
	})
	$('<br style="clear:both" />').appendTo(widget_column);
});
