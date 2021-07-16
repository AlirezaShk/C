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
}

else {
    die('Please put this file in the home directory !');
}


$query = mysqli_query($mysqli, "ALTER TABLE `users` CHANGE `username` `username` VARCHAR(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'show_less')");

$data  = array();
$query = mysqli_query($mysqli, "SHOW COLUMNS FROM `langs`");
while ($fetched_data = mysqli_fetch_assoc($query)) {
    $data[] = $fetched_data['Field'];
}

unset($data[0]);
unset($data[1]);

function PT_UpdateLangs($lang, $key, $value) {
    $update_query         = "UPDATE langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
    $update_replace_array = array(
        "{lang}",
        "{lang_text}",
        "{lang_key}"
    );
    return str_replace($update_replace_array, array(
        $lang,
        $value,
        $key
    ), $update_query);
}

$lang_update_queries = array();
foreach ($data as $key => $value) {
    $value = ($value);
    if ($value == 'arabic') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'عرض أقل');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Vis mindre');
    } else if ($value == 'french') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Montre moins');
    } else if ($value == 'german') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Zeige weniger');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Показывай меньше');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Muestra menos');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Daha az göster');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Show less');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'show_less', 'Show less');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.4.1!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();