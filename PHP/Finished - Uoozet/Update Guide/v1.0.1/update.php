<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}


$query = mysqli_query($mysqli, "CREATE TABLE `lists` (
  `id` int(11) NOT NULL,
  `list_id` varchar(300) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL DEFAULT '',
  `description` varchar(500) NOT NULL DEFAULT '',
  `privacy` int(11) NOT NULL DEFAULT '1',
  `views` int(11) NOT NULL DEFAULT '0',
  `icon` varchar(3000) NOT NULL DEFAULT '',
  `time` int(30) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `play_list` (
  `id` int(11) NOT NULL,
  `list_id` varchar(500) NOT NULL DEFAULT '',
  `video_id` varchar(500) NOT NULL DEFAULT '',
  `user_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`list_id`(255)),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `privacy` (`privacy`);");
$query = mysqli_query($mysqli, "ALTER TABLE `play_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`list_id`(255)),
  ADD KEY `video_id` (`video_id`(255));");
$query = mysqli_query($mysqli, "ALTER TABLE `lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$query = mysqli_query($mysqli, "ALTER TABLE `play_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

echo 'The script is successfully updated to v1.0.1!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();