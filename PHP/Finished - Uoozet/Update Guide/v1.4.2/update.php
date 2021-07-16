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


$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_users_found')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'social_links')");
$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'article_system', 'on');");

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
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'لم يتم العثور على أي مستخدم');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'روابط اجتماعية');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'Geen gebruikers gevonden');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Sociale links');
    } else if ($value == 'french') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'Aucun utilisateur trouvé');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Liens sociaux');
    } else if ($value == 'german') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'Keine Benutzer gefunden');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Soziale Verbindungen');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'Пользователи не найдены');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Социальные ссылки');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'No se encontraron usuarios');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Vínculos sociales');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'Kullanıcı bulunamadı');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Sosyal bağlantılar');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'No users found');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Social links');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_users_found', 'No users found');
        $lang_update_queries[] = PT_UpdateLangs($value, 'social_links', 'Social links');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.4.2!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();