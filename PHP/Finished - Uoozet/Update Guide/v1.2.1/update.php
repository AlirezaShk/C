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


$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'apps_api_id', '" . md5(time()) . "'), (NULL, 'apps_api_key', '" . md5(microtime()) . "');");
$query = mysqli_query($mysqli, "ALTER TABLE `videos` CHANGE `video_location` `video_location` VARCHAR(3000) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '';");
$query = mysqli_query($mysqli, "ALTER TABLE `videos` ADD `type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `registered`;");
$query = mysqli_query($mysqli, "CREATE TABLE `announcements` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `text` text,
 `time` int(32) NOT NULL DEFAULT '0',
 `active` enum('0','1') NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 KEY `active` (`active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$query = mysqli_query($mysqli, "CREATE TABLE `announcement_views` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `announcement_id` int(11) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 KEY `announcement_id` (`announcement_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `banned` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `ip_address` varchar(100) DEFAULT '',
 `time` varchar(50) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `verification_requests` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `name` varchar(200) NOT NULL DEFAULT '',
 `message` text,
 `media_file` text,
 `time` varchar(100) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");

echo 'The script is successfully updated to v1.2.1!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();