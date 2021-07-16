<?php
if (IS_LOGGED == false) {
    header("Location: " . PT_Link('login'));
    exit();
}
$user_id       = $pt->user->id;
$type          = (!empty($_POST['get'])) ? PT_Secure($_POST['get']) : 'all';
$show_all      = (!empty($_POST['count'])) ? PT_Secure($_POST['count']) : false;
$html          = "";
$t_notif       = T_NOTIFICATIONS;

$notif_set     = pt_get_notification(array(
    'recipient_id' => 24,
    'type' => $type
));

if (!empty($show_all)) {
    $response_data['count_unread_notifications'] = (!empty($notif_set)) ? count($notif_set) : 0;
}

$update = array();
$new    = 0;
if (is_array($notif_set)) {
    foreach ($notif_set as $data_row) {
        $data_row['video'] = '';
        if (!empty($data_row['video_id'])) {
            $data_row['video'] = PT_GetVideoByID($data_row['video_id'],0,1,2);
        }
        $data_row['notifier'] = PT_UserData($data_row['notifier_id']);
        $data_row['notifier']->is_subscribed_to_channel = $db->where('user_id', $data_row['notifier_id'])->where('subscriber_id', $pt->user->id)->getValue(T_SUBSCRIPTIONS, "count(*)");
        unset($comment->comment_user_data->password);
        unset($data_row['notifier']->password);
        $icon                 = $pt->notif_data[$data_row['type']]['icon'];
        $title                = $pt->notif_data[$data_row['type']]['text'];
        $response_data['notifica