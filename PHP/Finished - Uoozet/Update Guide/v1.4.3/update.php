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

$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `1080p` INT NOT NULL DEFAULT '0' AFTER `720p`, ADD `4096p` INT NOT NULL DEFAULT '0' AFTER `1080p`, ADD INDEX (`1080p`), ADD INDEX (`4096p`);");

$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `2048p` INT NOT NULL DEFAULT '0' AFTER `1080p`, ADD INDEX (`2048p`);");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_host', 'localhost'), (NULL, 'ftp_port', '21');");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_username', ''), (NULL, 'ftp_password', '');");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_upload', 'off');");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_endpoint', 'storage.wowonder.com');");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_path', './');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `notifications` ADD `video_id` INT NOT NULL DEFAULT '0' AFTER `recipient_id`, ADD INDEX (`video_id`);");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'watermark', '');");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_message')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_dismiss')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_link')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'terms_accept')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'terms_agreement')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'no_notifications')");

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
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'يستخدم موقع الويب هذا ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على موقعنا.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'فهمتك!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'أعرف أكثر');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'يرجى الموافقة على شروط الاستخدام وسياسة الخصوصية');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'عن طريق إنشاء حسابك ، فإنك توافق على');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'ليس لديك أي إخطارات');
    } else if ($value == 'dutch') {
         $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'Deze website maakt gebruik van cookies om ervoor te zorgen dat u de beste ervaring op onze website krijgt.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Begrepen!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Kom meer te weten');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Ga akkoord met de gebruiksvoorwaarden en het privacybeleid');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'Door uw account aan te maken, gaat u akkoord met onze');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'Je hebt geen meldingen');
    } else if ($value == 'french') {
         $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'Ce site utilise des cookies pour vous assurer la meilleure expérience sur notre site.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Je lai!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Apprendre encore plus');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Veuillez accepter les conditions dutilisation et la politique de confidentialité');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'En créant votre compte, vous acceptez notre');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'Vous navez aucune notification');
    } else if ($value == 'german') {
         $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'Diese Website verwendet Cookies, um sicherzustellen, dass Sie die beste Erfahrung auf unserer Website erhalten.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Ich habs!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Erfahren Sie mehr');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Bitte stimme den Nutzungsbedingungen und Datenschutzrichtlinien zu');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'Mit der Erstellung Ihres Benutzerkontos stimmen Sie unseren Nutzungsbedingungen zu');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'Sie haben keine Benachrichtigungen');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'На этом веб-сайте используются файлы cookie, чтобы вы могли получить лучший опыт на нашем веб-сайте.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Понял!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Выучить больше');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Пожалуйста, соглашайтесь с Условиями использования и Политикой конфиденциальности');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'Создав свою учетную запись, вы соглашаетесь с нашими');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'У вас нет уведомлений');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'Este sitio web utiliza cookies para garantizar que obtenga la mejor experiencia en nuestro sitio web.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', '¡Lo tengo!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Aprende más');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Acepta los Términos de uso y la Política de privacidad');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'Al crear su cuenta, usted acepta nuestra');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'No tienes ninguna notificación');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'Bu web sitesi, web sitemizde en iyi deneyimi yaşamanızı sağlamak için çerezleri kullanır.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Anladım!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Daha fazla bilgi edin');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Lütfen Kullanım Koşulları ve Gizlilik Politikasını kabul edin');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'Hesabınızı oluşturarak,');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'Bildiriminiz yok');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'This website uses cookies to ensure you get the best experience on our website.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Got It!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Learn More');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Please agree to the Terms of use & Privacy Policy');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'By creating your account, you agree to our');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'You do not have any notifications');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_message', 'This website uses cookies to ensure you get the best experience on our website.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_dismiss', 'Got It!');
        $lang_update_queries[] = PT_UpdateLangs($value, 'cookie_link', 'Learn More');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_accept', 'Please agree to the Terms of use & Privacy Policy');
        $lang_update_queries[] = PT_UpdateLangs($value, 'terms_agreement', 'By creating your account, you agree to our');
        $lang_update_queries[] = PT_UpdateLangs($value, 'no_notifications', 'You do not have any notifications');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.4.3!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();