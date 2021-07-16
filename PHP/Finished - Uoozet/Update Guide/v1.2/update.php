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


$query = mysqli_query($mysqli, "ALTER TABLE `comments` ADD `post_id` INT(11) NOT NULL DEFAULT '0' AFTER `video_id`, ADD INDEX (`post_id`);");
$query = mysqli_query($mysqli, "ALTER TABLE `likes_dislikes` ADD `post_id` INT(11) NOT NULL DEFAULT '0' AFTER `video_id`, ADD INDEX (`post_id`);");
$query = mysqli_query($mysqli, "ALTER TABLE `comments_likes` ADD `video_id` INT(15) NOT NULL DEFAULT '0' AFTER `comment_id`, ADD INDEX (`video_id`);");
$query = mysqli_query($mysqli, "ALTER TABLE `comments_likes` ADD `post_id` INT(15) NOT NULL DEFAULT '0' AFTER `video_id`, ADD INDEX (`post_id`);");
$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `is_pro` INT(11) NOT NULL DEFAULT '0' AFTER `registered`, ADD INDEX (`is_pro`);");
$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `imports` INT(11) NOT NULL DEFAULT '0' AFTER `is_pro`;");

$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `uploads` INT(11) NOT NULL DEFAULT '0' AFTER `imports`;");
$query = mysqli_query($mysqli, "ALTER TABLE `videos` ADD `size` INT(50) NOT NULL DEFAULT '0' AFTER `duration`;");
$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES 
  (NULL, 'pro_pkg_price', '10'), 
  (NULL, 'payment_currency', 'USD'),
  (NULL, 'go_pro', 'on'),
  (NULL, 'paypal_id', ''), 
  (NULL, 'paypal_secret', ''),
  (NULL, 'paypal_mode', 'sandbox');");
$query = mysqli_query($mysqli, "CREATE TABLE `payments` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `type` varchar(200) NOT NULL DEFAULT '',
 `amount` int(11) NOT NULL DEFAULT '0',
 `date` varchar(100) NOT NULL DEFAULT '',
 `expire` varchar(30) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `expire` (`expire`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8");
$query = mysqli_query($mysqli, "CREATE TABLE `watch_later` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `video_id` int(30) NOT NULL DEFAULT '0',
 `time` varchar(50) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`),
 KEY `video_id` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");
$query = mysqli_query($mysqli, "CREATE TABLE `pt_posts` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `title` varchar(300) NOT NULL DEFAULT '',
 `description` varchar(500) NOT NULL DEFAULT '',
 `category` int(11) NOT NULL DEFAULT '0',
 `image` varchar(3000) NOT NULL DEFAULT '',
 `text` text,
 `tags` varchar(500) NOT NULL DEFAULT '',
 `time` varchar(50) NOT NULL DEFAULT '0',
 `user_id` int(11) NOT NULL DEFAULT '0',
 `active` enum('0','1') NOT NULL DEFAULT '0',
 `views` int(20) NOT NULL DEFAULT '0',
 `shared` int(20) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `views` (`views`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'last_backup', '00-00-0000');");
$query = mysqli_query($mysqli, "ALTER TABLE `comments_likes` ADD `reply_id` INT(30) NOT NULL DEFAULT '0' AFTER `comment_id`, ADD INDEX (`reply_id`);");
$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES 
  (NULL, 'user_ads', 'on'),
  (NULL, 'rss_export', 'on'),
  (NULL, 'max_upload', '6000000'),
  (NULL, 's3_upload', 'off'), 
  (NULL, 's3_bucket_name', ''), 
  (NULL, 'amazone_s3_key', ''), 
  (NULL, 'amazone_s3_s_key', ''), 
  (NULL, 'region', ''),
  (NULL, 'ad_v_price', '0.1'),
  (NULL, 'ad_c_price', '0.5'),
  (NULL, 'pub_price', '0.2'),
  (NULL, 'usr_v_mon', 'on');");
$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `wallet` VARCHAR(200) 
  CHARACTER SET utf8 COLLATE utf8_general_ci 
  NOT NULL DEFAULT '0' AFTER `uploads`, ADD INDEX (`wallet`);");
$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `balance` VARCHAR(100) 
  CHARACTER SET utf8 COLLATE utf8_general_ci 
  NOT NULL DEFAULT '0' AFTER `wallet`, ADD INDEX (`balance`);");
$query = mysqli_query($mysqli, "ALTER TABLE `users` ADD `video_mon` INT(10) 
  NOT NULL DEFAULT '0' AFTER `balance`, ADD INDEX (`video_mon`);");
$query = mysqli_query($mysqli, "CREATE TABLE `comm_replies` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `comment_id` int(15) NOT NULL DEFAULT '0',
 `video_id` int(15) NOT NULL DEFAULT '0',
 `post_id` int(15) NOT NULL DEFAULT '0',
 `text` text,
 `time` varchar(50) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `profile_fields` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
 `description` text COLLATE utf8_unicode_ci,
 `options` varchar(3000) CHARACTER SET utf8 NOT NULL DEFAULT '',
 `type` text COLLATE utf8_unicode_ci,
 `length` int(11) NOT NULL DEFAULT '0',
 `placement` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'profile',
 `registration_page` int(11) NOT NULL DEFAULT '0',
 `profile_page` int(11) NOT NULL DEFAULT '0',
 `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`),
 KEY `registration_page` (`registration_page`),
 KEY `active` (`active`),
 KEY `profile_page` (`profile_page`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
$query = mysqli_query($mysqli, "CREATE TABLE `user_ads` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(500) NOT NULL DEFAULT '',
 `results` int(11) NOT NULL DEFAULT '0',
 `spent` varchar(20) NOT NULL DEFAULT '0',
 `status` int(1) NOT NULL DEFAULT '1',
 `audience` text,
 `category` varchar(50) NOT NULL DEFAULT '',
 `media` varchar(1000) NOT NULL DEFAULT '',
 `url` varchar(3000) NOT NULL DEFAULT '',
 `user_id` int(11) NOT NULL DEFAULT '0',
 `placement` varchar(50) NOT NULL DEFAULT '',
 `posted` varchar(50) NOT NULL DEFAULT '0',
 `headline` varchar(1000) NOT NULL DEFAULT '',
 `description` varchar(1000) NOT NULL DEFAULT '',
 `location` varchar(1000) NOT NULL DEFAULT '',
 `type` varchar(50) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `usr_prof_fields` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `fid_2` varchar(32) NOT NULL DEFAULT '',
 `fid_3` varchar(32) NOT NULL DEFAULT '',
 PRIMARY KEY (`id`),
 KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "CREATE TABLE `withdrawal_requests` (
 `id` int(20) NOT NULL AUTO_INCREMENT,
 `user_id` int(11) NOT NULL DEFAULT '0',
 `email` varchar(200) NOT NULL DEFAULT '',
 `amount` varchar(100) NOT NULL DEFAULT '0',
 `currency` varchar(20) NOT NULL DEFAULT '',
 `requested` varchar(100) NOT NULL DEFAULT '',
 `status` int(5) NOT NULL DEFAULT '0',
 PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


echo 'The script is successfully updated to v1.2!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();