<?php
$dir=$_REQUEST['image_gallery_directory'];

echo '<form action="/j/kfm/upload.php" method="POST" enctype="multipart/form-data">
	<input type="file" name="kfm_file[]" multiple="multiple" />
	<input type="hidden" name="MAX_FILE_SIZE" value="9999999999" />
	<input type="hidden" name="directory_name" value="'.htmlspecialchars($dir).'" />
	<input type="submit" name="upload" value="Upload" />
	</form>';
