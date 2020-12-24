<?php
require 'header.php';
echo '<h1>Plugin Management</h1>';

echo '<div class="left-menu">';
echo '<a href="/ww.admin/users.php">Users</a>';
echo '<a href="/ww.admin/themes.php">Themes</a>';
echo '<a href="/ww.admin/plugins.php">Plugins</a>';
echo '</div>';

echo '<div class="has-left-menu">';
echo '<h2>Plugin Management</h2>';
require 'plugins/list.php';
echo '</div>';

require 'footer.php';
