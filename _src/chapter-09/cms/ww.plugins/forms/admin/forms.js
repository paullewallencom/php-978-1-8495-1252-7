window.form_input_types=['input box','email','textarea','date','checkbox','selectbox','hidden'];
function formfieldsAddRow(){
	formfieldElements++;
	$('<li><table width="100%"><tr><td width="30%"><input name="formfieldElementsName['+formfieldElements+']" /></td><td width="30%"><select class="form-type" name="formfieldElementsType['+formfieldElements+']"><option>'+form_input_types.join('</option><option>')+'</option></select></td><td width="10%"><input type="checkbox" name="formfieldElementsIsRequired['+formfieldElements+']" /></td><td><textarea name="formfieldElementsExtra['+formfieldElements+']" class="small" style="display:none"></textarea></td></tr></table></li>').appendTo($('#form_fields'));
	$('#form_fields').sortable();
	$('#form_fields input,#form_fields select,#form_fields textarea').bind('click.sortable mousedown.sortable',function(ev){
		ev.target.focus();
	});
}
if(!formfieldElements)var formfieldElements=0;
$('select.form-type').live('change',function(){
	var val=$(this).val();
	var display=(val=='selectbox' || val=='hidden')
		?'inline':'none';
	$(this).closest('tr').find('textarea').css('display',display);
});
function form_export(id){
	if(!id)return alert('cannot export from an empty form database');
	if(!(+$('select[name="page_vars\\[forms_record_in_db\\]"]').val()))return alert('this form doesn\'t record to database');
	var d=$('#export_from').val();
	document.location='/ww.plugins/forms/admin/export.php?date='+d+'&id='+id;
}

$(function(){
	formfieldsAddRow();
	$('#export_from').datepicker({dateFormat:'yy-m-d'});
});
