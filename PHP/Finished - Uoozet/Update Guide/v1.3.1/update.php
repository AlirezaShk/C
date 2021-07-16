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

if (!file_exists('./nodejs/config.json')) {
    die('Please upload the script files first, the file: ./nodejs/config.json is missing.');
}

$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'added_new_video')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'messages')");
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_sure_you_want_delete_chat')"); // Are you sure that you want to delete the conversation?
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_messages_found_hi')"); // No messages were found, say Hi!
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_messages_found_channel')"); // No messages were found, please choose a channel to chat.
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_match_found')"); // No match found
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'message')"); // Message
$query = mysqli_query($mysqli, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'write_message')"); // Write your message and hit enter..

$query = mysqli_query($mysqli, "CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `seen` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$query = mysqli_query($mysqli, "ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `to_id` (`to_id`),
  ADD KEY `seen` (`seen`),
  ADD KEY `time` (`time`);");
$query = mysqli_query($mysqli, "ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$query = mysqli_query($mysqli, "CREATE TABLE `conversations` (
  `id` int(11) NOT NULL,
  `user_one` int(11) NOT NULL DEFAULT '0',
  `user_two` int(11) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
$query = mysqli_query($mysqli, "ALTER TABLE `conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_one` (`user_one`),
  ADD KEY `user_two` (`user_two`),
  ADD KEY `time` (`time`);");
$query = mysqli_query($mysqli, "ALTER TABLE `conversations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
$query = mysqli_query($mysqli, "CREATE TABLE `typings` ( `id` INT NOT NULL AUTO_INCREMENT , `user_one` INT NOT NULL DEFAULT '0' , `user_two` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB;");
$query = mysqli_query($mysqli, "ALTER TABLE `typings` ADD `time` INT NOT NULL DEFAULT '0' AFTER `user_two`, ADD INDEX (`time`);");
$query = mysqli_query($mysqli, "ALTER TABLE `messages` ADD `from_deleted` INT NOT NULL DEFAULT '0' AFTER `time`, ADD `to_deleted` INT NOT NULL DEFAULT '0' AFTER `from_deleted`, ADD INDEX (`from_deleted`), ADD INDEX (`to_deleted`);");
$query = mysqli_query($mysqli, "ALTER TABLE `notifications` ADD INDEX(`seen`);");
$query = mysqli_query($mysqli, "ALTER TABLE `notifications` ADD INDEX(`notifier_id`);");
$query = mysqli_query($mysqli, "ALTER TABLE `notifications` ADD INDEX(`time`);");
$query = mysqli_query($mysqli, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'server', 'ajax');");

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
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'نشر فيديو جديد');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'رسائل');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'هل تريد بالتأكيد حذف المحادثة؟');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'لم يتم العثور على رسائل، ويقول مرحبا!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'لم يتم العثور على أية رسائل، يرجى اختيار قناة للدردشة.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'لا يوجد تطابق');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'رسالة');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'اكتب رسالتك واضغط على إنتر ..');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'nieuwe video toegevoegd');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'berichten');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Weet je zeker dat je het gesprek wilt verwijderen?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'Er zijn geen berichten gevonden, bijvoorbeeld Hallo!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'Er zijn geen berichten gevonden. Kies een kanaal om te chatten.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'Geen overeenkomst gevonden');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Bericht');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Schrijf je bericht en druk op enter ..');
    } else if ($value == 'french') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'Ajout d\'une nouvelle vidéo');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'messages');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Êtes-vous sûr de vouloir supprimer la conversation?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'Aucun message n\'a été trouvé, dites Salut!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'Aucun message n\'a été trouvé, veuillez choisir une chaîne pour discuter.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'Pas de résultat trouvé');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Message');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Rédigez votre message et appuyez sur Entrée.');
    } else if ($value == 'german') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'neues Video hinzugefügt');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Mitteilungen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Möchten Sie die Unterhaltung wirklich löschen?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'Keine Nachrichten gefunden, sagen Hallo!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'Es wurden keine Nachrichten gefunden. Wähle einen Chat-Kanal aus.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'Keine Übereinstimmung gefunden');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Botschaft');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Schreibe deine Nachricht und drücke Enter.');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'добавлено новое видео');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Сообщения');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Вы уверены, что хотите удалить разговор?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'Сообщения не найдены, скажите Привет!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'Сообщения не найдены, выберите канал для чата.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'Не найдено совпадений');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Сообщение');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Напишите свое сообщение и нажмите enter.');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'video nuevo agregado');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Mensajes');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', '¿Estás seguro de que quieres eliminar la conversación?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'No se encontraron mensajes, decir Hola!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'No se encontraron mensajes, elija un canal para chatear.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'No se encontraron coincidencias');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Mensaje');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Escriba su mensaje y presione enter ...');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'yeni video eklendi');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Mesajlar');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Sohbeti silmek istediğinizden emin misiniz?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'Mesaj bulunamadı, merhaba deyin!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'Hiçbir mesaj bulunamadı, lütfen sohbet etmek için bir kanal seçin.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'Eşleşme bulunamadı');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Mesaj');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Mesajınızı yazın ve enter tuşuna basın ..');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'added a new video');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Messages');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Are you sure that you want to delete the conversation?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'No messages were found, say Hi!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'No messages were found, please choose a channel to chat.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'No match found');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Message');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Write your message and hit enter..');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'added_new_video', 'added a new video');
        $lang_update_queries[] = PT_UpdateLangs($value, 'messages', 'Messages');
        $lang_update_queries[] = PT_UpdateLangs($value, 'are_you_sure_you_want_delete_chat', 'Are you sure that you want to delete the conversation?');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_hi', 'No messages were found, say Hi!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_messages_found_channel', 'No messages were found, please choose a channel to chat.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_match_found', 'No match found');
        $lang_update_queries[] = PT_UpdateLangs($value, 'message', 'Message');
        $lang_update_queries[] = PT_UpdateLangs($value, 'write_message', 'Write your message and hit enter..');
    }
}
$json = array();

$json['db_hostname'] = $sql_db_host;
$json['db_username'] = $sql_db_user;
$json['db_password'] = $sql_db_pass;
$json['db_dbname'] = $sql_db_name;
$json['site_url'] = $site_url;
$json['server_ip'] = (!empty($_SERVER['SERVER_ADDR'])) ? (($_SERVER['SERVER_ADDR'] == '::1') ? 'localhost' : $_SERVER['SERVER_ADDR']) : $json['site_url'];
$json['server_port'] = 4545;
$json['amazon'] = ($pt->config->s3_upload == 'on') ? true : false;
$json['amazon_bucket'] = $pt->config->s3_bucket_name;
$json['nodejs_message_update_interval'] = 500;
$json['ajax_message_update_interval'] = 3000;
$json['ajax_message_update_interval'] = 3000;
$json['ssl'] = false;
$json['ssl_privatekey_full_path'] = '';
$json['ssl_cert_full_path'] = '';
$encode = json_encode($json, JSON_PRETTY_PRINT);

if (!empty($encode)) {
  $put_file = @file_put_contents('./nodejs/config.json', $encode);
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.3.1!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();