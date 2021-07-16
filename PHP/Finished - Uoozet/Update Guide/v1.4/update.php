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


$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'channels')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'recent_articles')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'read_more')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'removed_history')");

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
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'قنوات');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'أحدث المقالات');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'اقرأ أكثر');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'تمت إزالته من السجل');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'kanalen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Meest recente artikelen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lees verder');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Verwijderd uit de geschiedenis');
    } else if ($value == 'french') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Chaînes');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Articles les plus récents');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lire la suite');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Retiré de l\'histoire');
    } else if ($value == 'german') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Kanäle');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Neueste Artikel');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Weiterlesen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Aus dem Verlauf entfernt');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'каналы');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Последние статьи');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Прочитайте больше');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Удалено из истории');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Canales');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Artículos más recientes');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Lee mas');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Eliminado de la historia');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Kanallar');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'En yeni makaleler');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Daha fazla oku');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Geçmişten kaldırıldı');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Channels');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Most recent articles');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Read more');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Removed from History');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'channels', 'Channels');
        $lang_update_queries[] = PT_UpdateLangs($value, 'recent_articles', 'Most recent articles');
        $lang_update_queries[] = PT_UpdateLangs($value, 'read_more', 'Read more');
        $lang_update_queries[] = PT_UpdateLangs($value, 'removed_history', 'Removed from History');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.4!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();