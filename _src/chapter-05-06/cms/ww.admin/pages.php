<?php
require 'header.php';
echo '<h1>Pages</h1>';

// { perform any actions
if(isset($_REQUEST['action'])){
	if($_REQUEST['action']=='Update Page Details'
		|| $_REQUEST['action']=='Insert Page Details'){
		require 'pages/action.edit.php';
	}
	else if($_REQUEST['action']=='delete'){
		'pages/action.delete.php';
	}
}
// }
// { load menu
echo '<div class="left-menu">';
require 'pages/menu.php';
echo '</div>';
// }
// { load main page
echo '<div class="has-left-menu">';
require 'pages/forms.php';
echo '</div>';
// }

echo '<style type="text/css">@import "pages/css.css";</style>';
require 'footer.php';
