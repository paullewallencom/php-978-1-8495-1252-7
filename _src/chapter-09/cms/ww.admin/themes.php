<?php
require 'header.php';
echo '<h1>Theme Management</h1>';

echo '<div class="left-menu">';
echo '<a href="/ww.admin/users.php">Users</a>';
echo '<a href="/ww.admin/themes.php">Themes</a>';
echo '<a href="/ww.admin/plugins.php">Plugins</a>';
echo '</div>';

echo '<div class="has-left-menu">';
echo '<h2>Theme Management</h2>';
require 'themes/list.php';
echo '</div>';

require 'footer.php';
