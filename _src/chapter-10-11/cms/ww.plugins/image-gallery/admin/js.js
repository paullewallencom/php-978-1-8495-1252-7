function image_gallery_add_price(){
	ig_price_count++;
	$('<input class="medium" name="page_vars[image_gallery_pricedescs_'+ig_price_count+']" value="description" /><input class="ig_price small" name="page_vars[image_gallery_prices_'+ig_price_count+']" value="0" /><br />')
		.insertBefore('#ig_prices_more');
}
$('#image-gallery-wrapper a').bind('click',function(){
	var $this=$(this);
	var id=$this[0].id.replace('image-gallery-dbtn-','');
	if(!$('#image-gallery-dchk-'+id+':checked').length){
		alert('you must tick the box before deleting');
		return;
	}
	$.get('/j/kfm/rpc.php?action=delete_file&id='+id,function(ret){
		$this.closest('div').remove();
	});
});
