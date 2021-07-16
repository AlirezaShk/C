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

$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `privacy` INT NOT NULL DEFAULT '0' AFTER `registered`, ADD INDEX (`privacy`);");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'download_videos', 'on');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `facebook` VARCHAR(100) NOT NULL DEFAULT '' AFTER `daily`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` CHANGE `thumbnail` `thumbnail` VARCHAR(500) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'upload/photos/thumbnail.jpg';");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'facebook_import', 'on');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `short_id` VARCHAR(10) NOT NULL DEFAULT '' AFTER `user_id`, ADD INDEX (`short_id`);");
$query = mysqli_query($sqlConnect, "ALTER TABLE `users` ADD `instagram` VARCHAR(100) NOT NULL DEFAULT '' AFTER `twitter`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `age_restriction` INT NOT NULL DEFAULT '1' AFTER `privacy`, ADD INDEX (`age_restriction`);");
$query = mysqli_query($sqlConnect, "ALTER TABLE `users` ADD `age` INT NOT NULL DEFAULT '0' AFTER `country_id`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `users` ADD `age_changed` INT NOT NULL DEFAULT '0' AFTER `video_mon`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `users` ADD `donation_paypal_email` VARCHAR(100) NOT NULL DEFAULT '' AFTER `age_changed`;");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'donate_system', 'on');");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'approve_videos', 'off');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `videos` ADD `approved` INT NOT NULL DEFAULT '1' AFTER `type`, ADD INDEX (`approved`);");
$query = mysqli_query($sqlConnect, "INSERT INTO `config` (`id`, `name`, `value`) VALUES (NULL, 'videos_upload_limit', '0');");

$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'unlisted')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'video_private_text')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'instagram')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'original')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'age_restriction')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'all_ages')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'only_18')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'not_allowed_change_age')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'age_restrict_text')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'age_restrcit_text_2')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'donation_paypal_email')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'download')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'donate')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'video_approve_text')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, '_reached_upload_limit')");
$query = mysqli_query($sqlConnect, "INSERT INTO `langs` (`id`, `lang_key`) VALUES (NULL, 'delete_some_videos')");

$data  = array();
$query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM `langs`");
while ($fetched_data = mysqli_fetch_assoc($query)) {
    $data[] = $fetched_data['Field'];
}

unset($data[0]);
unset($data[1]);

function PT_UpdateLangs($lang, $key, $value) {
    global $sqlConnect;
    $update_query         = "UPDATE langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
    $update_replace_array = array(
        "{lang}",
        "{lang_text}",
        "{lang_key}"
    );
    return str_replace($update_replace_array, array(
        $lang,
        mysqli_real_escape_string($sqlConnect, $value),
        $key
    ), $update_query);
}

$lang_update_queries = array();
foreach ($data as $key => $value) {
    $value = ($value);
    if ($value == 'arabic') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'غير مدرج');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'هذا فيديو خاص ، يمكن للناشر فقط مشاهدته.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'إينستاجرام');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'أصلي');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'شرط العمر أو السن');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'يمكن لجميع الأعمار مشاهدة هذا الفيديو');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'فقط +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'لا يسمح لك بتغيير عمرك أكثر من مرة');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'هذا الفيديو مقيّد بالعمر للمشاهدين تحت +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'قم بإنشاء حساب أو تسجيل الدخول لتأكيد عمرك.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'التبرع البريد الإلكتروني بأي بال');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'تحميل');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'تبرع');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'هذا الفيديو قيد المراجعة ، يرجى معاودة التحقق لاحقًا.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'لقد وصلت إلى حد التحميل الخاص بك.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'يرجى حذف بعض مقاطع الفيديو التابعة لك وقادرة على تحميل المزيد.');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'geheim');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'Dit is video-privé, alleen de uitgever kan het bekijken.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'origineel');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Leeftijdsbeperking');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Alle leeftijden kunnen deze video bekijken');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Alleen +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'Het is niet toegestaan ​​om je leeftijd meer dan één keer te veranderen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Deze video is leeftijdsbeperkend voor kijkers onder +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Maak een account aan of log in om uw leeftijd te bevestigen.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Donatie PayPal E-mail');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Download');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'schenken');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Deze video wordt beoordeeld. Kom later nog eens terug.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Je hebt je uploadlimiet bereikt.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Verwijder enkele van je video\'s in staat om meer te uploaden.');
    } else if ($value == 'french') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Non listé');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'C\'est de la vidéo privée, seul l\'éditeur peut le voir.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'Original');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Restriction d\'âge');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Tous les âges peuvent voir cette vidéo');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Seulement +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'Vous n\'êtes pas autorisé à changer votre âge plus d\'une fois');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Cette vidéo est limitée à l’âge pour les téléspectateurs de moins de 18 ans.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Créez un compte ou connectez-vous pour confirmer votre âge.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Don PayPal Email');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Télécharger');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'Faire un don');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Cette vidéo est en cours de révision, veuillez vérifier plus tard.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Vous avez atteint votre limite de téléchargement.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Veuillez supprimer certaines de vos vidéos pour pouvoir en télécharger davantage.');
    } else if ($value == 'german') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Nicht gelistet');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'Dies ist Video privat, nur der Herausgeber kann es anzeigen.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'Original');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Altersbeschränkung');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Alle Altersgruppen können dieses Video ansehen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Nur +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'Sie dürfen Ihr Alter nicht mehr als einmal ändern');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Dieses Video ist für Zuschauer unter +18 Altersbeschränkung');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Erstelle ein Konto oder logge dich ein, um dein Alter zu bestätigen.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Spende PayPal E-Mail');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Herunterladen');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'Spenden');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Dieses Video wird gerade überprüft. Bitte schauen Sie später noch einmal vorbei.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Sie haben Ihr Upload-Limit erreicht.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Bitte lösche einige deiner Videos, um mehr hochladen zu können.');
    } else if ($value == 'russian') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Unlisted');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'Это видео конфиденциально, только издатель может его просмотреть.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'оригинал');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Ограничение возраста');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Все возрасты могут просматривать это видео');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Только +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'Вам не разрешается менять свой возраст более одного раза');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Это видео ограничено для зрителей под +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Создайте учетную запись или авторизуйтесь, чтобы подтвердить свой возраст.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Пожертвование PayPal Email');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Скачать');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'жертвовать');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Это видео просматривается, пожалуйста, зайдите позже.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Вы достигли предела загрузки.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Пожалуйста, удалите некоторые из ваших видеороликов, которые могут загрузить больше.');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'No estante en la lista');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'Este es un video privado, solo el editor puede verlo.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'Original');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Restricción de edad');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Todas las edades pueden ver este video');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Solo +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'No puedes cambiar tu edad más de una vez');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Este video está restringido para menores de 18 años.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Crea una cuenta o inicia sesión para confirmar tu edad.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Donación Correo electrónico de PayPal');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Descargar');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'Donar');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Este video está siendo revisado, por favor revise más tarde.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Has alcanzado tu límite de carga.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Elimina algunos de tus videos para poder subir más.');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Liste dışı');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'Bu video özel, sadece yayıncı bunu görüntüleyebilir.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'orijinal');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Yaş kısıtlaması');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'Her yaştan bu videoyu görüntüleyebilir');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Sadece +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'Yaşınızı bir kereden fazla değiştiremezsiniz');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'Bu video, +18 yaşın altındaki görüntüleyenler için kısıtlanmış');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Yaşınızı onaylamak için bir hesap oluşturun veya giriş yapın.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Bağış PayPal Email');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'İndir');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'bağışlamak');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'Bu video inceleniyor, lütfen daha sonra tekrar kontrol edin.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'Yükleme sınırınıza ulaştınız.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Lütfen daha fazla yükleme yapabileceğiniz videolarınızın bir kısmını silin.');
    } else if ($value == 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Unlisted');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'This is video private, just the publisher can view it.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'Original');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Age Restriction');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'All ages can view this video');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Only +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'You are not allowed to change your age more than one time');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'This video is age restricted for viewers under +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Create an account or login to confirm your age.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Donation PayPal Email');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Download');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'Donate');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'This video is being reviewed, please check back later.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'You have reached your upload limit.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Please delete some of your videos in able to upload more.');
    } else if ($value != 'english') {
        $lang_update_queries[] = PT_UpdateLangs($value, 'unlisted', 'Unlisted');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_private_text', 'This is video private, just the publisher can view it.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'instagram', 'Instagram');
        $lang_update_queries[] = PT_UpdateLangs($value, 'original', 'Original');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restriction', 'Age Restriction');
        $lang_update_queries[] = PT_UpdateLangs($value, 'all_ages', 'All ages can view this video');
        $lang_update_queries[] = PT_UpdateLangs($value, 'only_18', 'Only +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'not_allowed_change_age', 'You are not allowed to change your age more than one time');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrict_text', 'This video is age restricted for viewers under +18');
        $lang_update_queries[] = PT_UpdateLangs($value, 'age_restrcit_text_2', 'Create an account or login to confirm your age.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donation_paypal_email', 'Donation PayPal Email');
        $lang_update_queries[] = PT_UpdateLangs($value, 'download', 'Download');
        $lang_update_queries[] = PT_UpdateLangs($value, 'donate', 'Donate');
        $lang_update_queries[] = PT_UpdateLangs($value, 'video_approve_text', 'This video is being reviewed, please check back later.');
        $lang_update_queries[] = PT_UpdateLangs($value, '_reached_upload_limit', 'You have reached your upload limit.');
        $lang_update_queries[] = PT_UpdateLangs($value, 'delete_some_videos', 'Please delete some of your videos in able to upload more.');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($mysqli, $query);
    }
}

echo 'The script is successfully updated to v1.4.5!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();