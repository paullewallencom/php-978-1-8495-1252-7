<?php
$c.='<table id="image_gallery" class="image_gallery">';
if($n>$imagesPerPage){
	$prespage=$PAGEDATA->getRelativeURL();
	// { prev
		$c.='<th class="prev" style="text-align:left" id="image_gallery_prev_wrapper">';
		if($start>0){
			$l=$start-$imagesPerPage;
			if($l<0)$l=0;
			$c.='<a href="'.$prespage.'?start='.$l.'">&lt;-- prev</a>';
		}
		$c.='</th>';
	// }
	for($l=1;$l<$vars['image_gallery_x']-1;++$l)$c.='<th></th>';
	// { next
		$c.='<th class="next" style="text-align:right" id="image_gallery_next_wrapper">';
		if($start+$imagesPerPage<$n){
			$l=$start+$imagesPerPage;
			$c.='<a href="'.$prespage.'?start='.$l.'">next --&gt;</a>';
		}
		$c.='</th>';
	// }
}
$all=array();
$s=$start+$vars['image_gallery_x']*$vars['image_gallery_y'];
if($s>$n)$s=$n;
for($i=$start;$i<$s;++$i){
	$cap=$images[$i]['caption'];
	if(strlen($cap)>$vars['image_gallery_captionlength'])$cap=substr($cap,0,$vars['image_gallery_captionlength']-3).'...';
	$all[]=array(
		'url'=>'/kfmget/'.$images[$i]['id'],
		'thumb'=>'/kfmget/'.$images[$i]['id'].',width='.$vars['image_gallery_thumbsize'].',height='.$vars['image_gallery_thumbsize'],
		'title'=>$images[$i]['caption'],
		'caption'=>str_replace('\\\\n',"<br />",htmlspecialchars($cap))
	);
}
for($row=0;$row<$vars['image_gallery_y'];++$row){
	$c.='<tr>';
	for($col=0;$col<$vars['image_gallery_x'];++$col){
		$i=$row*$vars['image_gallery_x']+$col;
		$c.='<td id="igCell_'.$row.'_'.$col.'">';
		if(isset($all[$i]))$c.='<div style="text-align:center" class="gallery_image"><a href="'.$all[$i]['url'].'"><img src="'.$all[$i]['thumb'].'" /><br style="clear:both" /><span class="caption">'.$all[$i]['caption'].'</span></a></div>';
		$c.='</td>';
	}
	$c.='</tr>';
}
$c.='</table>';
