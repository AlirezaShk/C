<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+
require_once('app_start.php');
use Aws\S3\S3Client;

function PT_UserData($user_id = 0, $options = array()) {
    global $db, $pt, $lang, $countries_name;
    if (!empty($options['data'])) {
        $fetched_data   = $user_id;
    } 

    else {
        $fetched_data   = $db->where('id', $user_id)->getOne(T_USERS);
    }

    if (empty($fetched_data)) {
        return false;
    }

    $fetched_data->name   = $fetched_data->username;
    $fetched_data->avatar = PT_GetMedia($fetched_data->avatar);
    $fetched_data->cover  = PT_GetMedia($fetched_data->cover);
    $fetched_data->url    = PT_Link('@' . $fetched_data->username);
    $fetched_data->about_decoded = br2nl($fetched_data->about);

    if (!empty($fetched_data->first_name)) {
        $fetched_data->name = $fetched_data->first_name . ' ' . $fetched_data->last_name;
    }

    $fetched_data->name_v   = $fetched_data->name;
    if ($fetched_data->verified == 1 && $pt->config->verification_badge == 'on') {
        $fetched_data->name_v = $fetched_data->name . ' <i class="fa fa-check-circle fa-fw verified"></i>';
    }
    
    $fetched_data->country_name  = $countries_name[$fetched_data->country_id];
    @$fetched_data->gender_text  = ($fetched_data->gender == 'male') ? $lang->male : $lang->female;
    return $fetched_data;
}

function PT_GetConfig() {
    global $db;
    $data  = array();
    $configs = $db->get(T_CONFIG);
    foreach ($configs as $key => $config) {
        $data[$config->name] = $config->value;
    }
    return $data;
}

function PT_GetAllUsers() {
    global $db;
    $data         = array();
    $fetched_data = $db->get(T_USERS);
    foreach ($fetched_data as $key => $value) {
        $data[] = PT_UserData($value->id);
    }
    return $data;
}

function PT_IsAdmin() {
    global $pt;
    if (IS_LOGGED == false) {
        return false;
    }
    if ($pt->user->admin == 1) {
        return true;
    }
    return false;
}

function PT_IsUpgraded(){
    global $pt;
    if (IS_LOGGED == false) {
        return false;
    }

    if ($pt->user->is_pro > 0) {
        return true;
    }

    return false;
}

function PT_GetSubscribeButton($user_id = 0) {
    global $pt, $db, $lang;
    if (empty($user_id)) {
        return false;
    }
    
    $button_text  = $lang->subscribe;
    $button_icon  = 'plus-square';
    $button_class = 'subscribe';
    if (IS_LOGGED == true) {
        if ($user_id == $pt->user->id) {
            return PT_LoadPage('buttons/manage-videos', array(
                'SUBS' => number_format($db->where('user_id', $pt->user->id)->getValue(T_SUBSCRIPTIONS, "count(*)"))
            ));
        }
        $check_if_sub = $db->where('user_id', $user_id)->where('subscriber_id', $pt->user->id)->getValue(T_SUBSCRIPTIONS, 'count(*)');
        if ($check_if_sub == 1) {
            $button_text  = $lang->subscribed;
            $button_icon  = 'check';
            $button_class = 'subscribed';
        }
    }
    return PT_LoadPage('buttons/subscribe', array(
        'IS_SUBSCRIBED_BUTTON' => $button_class,
        'IS_SUBSCRIBED_ICON' => $button_icon,
        'IS_SUBSCRIBED_TEXT' => $button_text,
        'USER_ID' => $user_id,
        'SUBS' => number_format($db->where('user_id', $user_id)->getValue(T_SUBSCRIPTIONS, "count(*)"))
    ));
}

function PT_GetVideoByID($video_id = '', $add_views = 0, $likes_dislikes = 0, $run_query = 1) {
    global $pt, $db, $categories;

    if (empty($video_id)) {
        return false;
    }

    if ($run_query == 1) {
        $get_video = $db->where('video_id', $video_id)->getOne(T_VIDEOS);
    } 

    else if ($run_query == 2) {
         $get_video = $db->where('id', $video_id)->getOne(T_VIDEOS);
    } 
    else {
        $get_video = $video_id;
    }

    if (!empty($get_video)) {
        $update_views = false;
        $data         = array();
        if (!empty($_COOKIE['views'])) {
            $data = unserialize(html_entity_decode($_COOKIE['views']));

            if ($data) {
                if (!in_array($get_video->id, $data)) {
                    $update_views = true;
                    $data[]       = $get_video->id;
                    $data_s       = htmlentities(serialize($data));
                }
            }
        } 

        else {
            $update_views = true;
            $data[]       = $get_video->id;
            $data_s       = htmlentities(serialize($data));
        }

        if ($update_views == true && $data_s && $add_views == 1) {
            $update_views = $db->where('id', $get_video->id)->update(T_VIDEOS, array(
                'views' => $db->inc(1)
            ));
            if ($update_views) {
                setcookie("views", $data_s, time() + 31556926, '/');
            }
        }

        $get_video->org_thumbnail = $get_video->thumbnail;
        if (strpos($get_video->thumbnail, 'upload/photos') !== false) {
            $get_video->thumbnail      = PT_GetMedia($get_video->thumbnail);  
            $get_video->video_id_      = $get_video->video_id;
            $get_video->source         = 'Uploaded';
            $get_video->video_type     = 'video/mp4';
            
            if ($get_video->type == 4) {
                $get_video->video_location = urldecode($get_video->video_location);
            }
            
            else{
                $get_video->video_location = PT_GetMedia($get_video->video_location);
            }

        }
        if (!empty($get_video->youtube)) {
            $get_video->video_type     = 'video/youtube';
            $get_video->video_location = 'https://www.youtube.com/watch?v=' . $get_video->youtube;
            $get_video->video_id_      = $get_video->youtube;
            $get_video->source         = 'YouTube';
        }
        if (!empty($get_video->daily)) {
            $get_video->video_type = 'video/dailymotion';
            $get_video->video_id_  = $get_video->daily;
            $get_video->source         = 'Dailymotion';
        }
        if (!empty($get_video->vimeo)) {
            $get_video->video_type = 'video/vimeo';
            $get_video->video_id_  = $get_video->vimeo;
            $get_video->source         = 'Vimeo';
        }
        $get_video->url                = PT_Link('watch/' . PT_Slug($get_video->title, $get_video->video_id));
        $get_video->edit_description   = PT_EditMarkup($get_video->description);
        $get_video->markup_description = PT_Markup($get_video->description);
        $get_video->owner              = PT_UserData($get_video->user_id);
        $get_video->is_liked           = 0;
        $get_video->is_disliked        = 0;
        $get_video->is_owner           = false;
        if (IS_LOGGED == true) {
            $get_video->is_liked    = $db->where('user_id', $pt->user->id)->where('video_id', $get_video->id)->where('type', 1)->getValue(T_DIS_LIKES, 'count(*)');
            $get_video->is_disliked = $db->where('user_id', $pt->user->id)->where('video_id', $get_video->id)->where('type', 2)->getValue(T_DIS_LIKES, 'count(*)');
            if ($get_video->owner->id == $pt->user->id || PT_IsAdmin()) {
                $get_video->is_owner           = true;
            }
        }
        $get_video->time_alpha    = gmdate('d M Y', $get_video->time);
        $get_video->time_ago      = PT_Time_Elapsed_String($get_video->time);
        $get_video->category_name = (!empty($categories[$get_video->category_id])) ? $categories[$get_video->category_id] : '';
        if ($likes_dislikes == 1) {
            $db->where('video_id', $get_video->id);
            $db->where('type', 1);
            $get_video->likes = $db->getValue(T_DIS_LIKES, 'count(*)');
            
            $db->where('video_id', $get_video->id);
            $db->where('type', 2);
            $get_video->dislikes = $db->getValue(T_DIS_LIKES, 'count(*)');
            
            $total                    = $get_video->likes + $get_video->dislikes;
            $get_video->likes_percent = 0;
            if ($get_video->likes > 0) {
                $get_video->likes_percent = round(($get_video->likes / $total) * 100);
            }
            $get_video->dislikes_percent = 0;
            if ($get_video->dislikes > 0) {
                $get_video->dislikes_percent = round(($get_video->dislikes / $total) * 100);
            }
            
            if ($get_video->likes_percent == 0 && $get_video->dislikes_percent == 0) {
                $get_video->dislikes_percent = 100;
                $get_video->likes_percent    = 0;
            }
        }
        return $get_video;
    }
    return array();
}

function PT_GetMedia($media = '', $is_upload = false){
    global $pt;
    if (empty($media)) {
        return '';
    }

    $media_url     = $pt->config->site_url . '/' . $media;
    if ($pt->config->s3_upload == 'on' && $is_upload == false) {
        $media_url = "https://" . $pt->config->s3_bucket_name . ".s3.amazonaws.com/" . $media;
    }

    return $media_url;
}

function PT_UserActive($user_id = 0) {
    global $db;
    $db->where('active', '1');
    $db->where('id', PT_Secure($user_id));
    return ($db->getValue(T_USERS, 'count(*)') > 0) ? true : false;
}

function PT_UserEmailExists($email = '') {
    global $db;
    return ($db->where('email', PT_Secure($email))->getValue(T_USERS, 'count(*)') > 0) ? true : false;
}

function PT_UsernameExists($username = '') {
    global $db;
    return ($db->where('username', PT_Secure($username))->getValue(T_USERS, 'count(*)') > 0) ? true : false;
}

function PT_ImportImageFromLogin($media) {
    global $pt;
    if (!file_exists('upload/photos/' . date('Y'))) {
        mkdir('upload/photos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y') . '/' . date('m'))) {
        mkdir('upload/photos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    $dir               = 'upload/photos/' . date('Y') . '/' . date('m');
    $file_dir          = $dir . '/' . PT_GenerateKey() . '_avatar.jpg';
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false
        )
    );
    $getImage          = file_get_contents($media, false, stream_context_create($arrContextOptions));
    if (!empty($getImage)) {
        $importImage = file_put_contents($file_dir, $getImage);
        if ($importImage) {
            PT_Resize_Crop_Image(400, 400, $file_dir, $file_dir, 100);
        }
    }
    if (file_exists($file_dir)) {
        return $file_dir;
    } else {
        return $pt->userDefaultAvatar;
    }
}

function PT_SendMessage($data = array()) {
    global $pt, $db, $mail;
    $email_from      = $data['from_email'] = PT_Secure($data['from_email']);
    $to_email        = $data['to_email'] = PT_Secure($data['to_email']);
    $subject         = $data['subject'];
    $data['charSet'] = PT_Secure($data['charSet']);
    if ($pt->config->smtp_or_mail == 'mail') {
        $mail->IsMail();
    } else if ($pt->config->smtp_or_mail == 'smtp') {
        $mail->isSMTP();
        $mail->Host        = $pt->config->smtp_host;
        $mail->SMTPAuth    = true;
        $mail->Username    = $pt->config->smtp_username;
        $mail->Password    = $pt->config->smtp_password;
        $mail->SMTPSecure  = $pt->config->smtp_encryption;
        $mail->Port        = $pt->config->smtp_port;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
    } else {
        return false;
    }
    $mail->IsHTML($data['is_html']);
    $mail->setFrom($data['from_email'], $data['from_name']);
    $mail->addAddress($data['to_email'], $data['to_name']);
    $mail->Subject = $data['subject'];
    $mail->CharSet = $data['charSet'];
    $mail->MsgHTML($data['message_body']);
    if ($mail->send()) {
        $mail->ClearAddresses();
        return true;
    }
}

function PT_ShareFile($data = array(), $type = 0) {
    global $pt, $mysqli;
    $allowed = '';
    if (!file_exists('upload/photos/' . date('Y'))) {
        @mkdir('upload/photos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/photos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/photos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y'))) {
        @mkdir('upload/videos/' . date('Y'), 0777, true);
    }
    if (!file_exists('upload/videos/' . date('Y') . '/' . date('m'))) {
        @mkdir('upload/videos/' . date('Y') . '/' . date('m'), 0777, true);
    }
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = PT_Secure($data['file']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = PT_Secure($data['name']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = PT_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    $allowed           = 'jpg,png,jpeg,gif,mp4,mov,webm,mpeg';
    if (!empty($data['allowed'])) {
        $allowed  = $data['allowed'];
    }
    $new_string        = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return array(
            'error' => 'File format not supported'
        );
    }
    if ($file_extension == 'jpg' || $file_extension == 'jpeg' || $file_extension == 'png' || $file_extension == 'gif') {
        $folder   = 'photos';
        $fileType = 'image';
    } else if ($file_extension == 'mp4' || $file_extension == 'webm' || $file_extension == 'flv') {
        $folder   = 'videos';
        $fileType = 'video';
    }
    if (empty($folder) || empty($fileType)) {
        return false;
    }
    $ar = array(
        'video/mp4',
        'video/mov',
        'video/mpeg',
        'video/flv',
        'video/avi',
        'video/webm',
        'audio/wav',
        'audio/mpeg',
        'video/quicktime',
        'audio/mp3',
        'image/png',
        'image/jpeg',
        'image/gif'
    );
    if (!in_array($data['type'], $ar)) {
        return array(
            'error' => 'File format not supported'
        );
    }
    $dir         = "upload/{$folder}/" . date('Y') . '/' . date('m');
    $filename    = $dir . '/' . PT_GenerateKey() . '_' . date('d') . '_' . md5(time()) . "_{$fileType}.{$file_extension}";
    $second_file = pathinfo($filename, PATHINFO_EXTENSION);
    if (move_uploaded_file($data['file'], $filename)) {
        if ($second_file == 'jpg' || $second_file == 'jpeg' || $second_file == 'png' || $second_file == 'gif') {
            if ($type == 1) {
                @PT_CompressImage($filename, $filename, 50);
                $explode2  = @end(explode('.', $filename));
                $explode3  = @explode('.', $filename);
                $last_file = $explode3[0] . '_small.' . $explode2;
                @PT_Resize_Crop_Image(400, 400, $filename, $last_file, 60);

                if ($pt->config->s3_upload == 'on' && !empty($last_file)) {
                    $upload_s3 = PT_UploadToS3($last_file);
                }
            } 

            else {
                if ($second_file != 'gif') {
                    if (!empty($data['crop'])) {
                        $crop_image = PT_Resize_Crop_Image($data['crop']['width'], $data['crop']['height'], $filename, $filename, 60);
                    }
                    @PT_CompressImage($filename, $filename, 90);
                }

                if ($pt->config->s3_upload == 'on' && !empty($filename)) {
                    $upload_s3 = PT_UploadToS3($filename);
                }
            }
        }

        else{
            if ($pt->config->s3_upload == 'on' && !empty($filename)) {
                $upload_s3 = PT_UploadToS3($filename);
            }
        }

        $last_data             = array();
        $last_data['filename'] = $filename;
        $last_data['name']     = $data['name'];
        return $last_data;
    }
}

function PT_DeleteUser($id = 0) {
    global $pt, $db;
    if (empty($id)) {
        return false;
    }
    if ($pt->user->id != $id) {
       if (PT_IsAdmin() == false) {
           return false;
       }
    }
    $get_videos = $db->where('user_id', $id)->get(T_VIDEOS, null, 'id');
    foreach ($get_videos as $key => $video) {
        $delete_video = PT_DeleteVideo($video->id);
    }
    $get_cover_and_avatar = PT_UserData($id);
    if ($get_cover_and_avatar->avatar != 'upload/photos/d-avatar.jpg') {
        @unlink($get_cover_and_avatar->avatar);
    }
    if ($get_cover_and_avatar->cover != 'upload/photos/d-cover.jpg') {
        @unlink($get_cover_and_avatar->cover);
    }
    $delete_user = $db->where('id', $id)->delete(T_USERS);
    $delete_user = $db->where('user_id', $id)->delete(T_USR_ADS);
    if ($delete_user) {
        return true;
    }
}

function PT_DeleteVideo($id = 0) {
    global $pt, $db;
    if (empty($id)) {
        return false;
    }

    $get_video = $db->where('id', $id)->getOne(T_VIDEOS);
    $s3        = ($pt->config->s3_upload == 'on') ? true : false;
    if (strpos($get_video->thumbnail, 'upload/photos') !== false) {
        if ($get_video->thumbnail != 'upload/photos/thumbnail.jpg') {
            if (file_exists($get_video->thumbnail)) {
                unlink($get_video->thumbnail);
            }

            else if ($pt->config->s3_upload != 'on') { 
                PT_DeleteFromToS3($get_video->thumbnail);
            }  
        }
        
    }

    if ($s3 && !empty($get_video->video_location) && file_exists($get_video->video_location)) {
        PT_DeleteFromToS3($get_video->video_location);
    }

    elseif (!empty($get_video->video_location) && file_exists($get_video->video_location)) {
        unlink($get_video->video_location);
    }


    $delete = $db->where('id', $id)->delete(T_VIDEOS);
    $get_comments = $db->where('video_id', $id)->get(T_COMMENTS);
    foreach ($get_comments as $key => $comment) {
        $delete  = $db->where('comment_id', $comment->id)->delete(T_COMMENTS_LIKES);
        $r_votes = $db->where('comment_id', $comment->id)->get(T_COMM_REPLIES);
        $delete  = $db->where('comment_id', $comment->id)->delete(T_COMM_REPLIES);
        foreach ($r_votes as $reply_vote) {
            $db->where('reply_id', $reply_vote->id)->delete(T_COMMENTS_LIKES);
        }
    }
    
    $delete = $db->where('video_id', $id)->delete(T_COMMENTS);
    $delete = $db->where('video_id', $id)->delete(T_HISTORY);
    $delete = $db->where('video_id', $id)->delete(T_DIS_LIKES);
    $delete = $db->where('video_id', $id)->delete(T_SAVED);
    $delete = $db->where('video_id', $id)->delete(T_PLAYLISTS);
    if ($delete) {
        return true;
    }
    return false;
}

function PT_UpdateAdminDetails() {
    global $pt, $db;

    $get_videos_count = $db->getValue(T_VIDEOS, 'count(*)');
    $update_videos_count = $db->where('name', 'total_videos')->update(T_CONFIG, array('value' => $get_videos_count));

    $get_views_count = $db->getValue(T_VIDEOS, 'SUM(views)');
    $update_views_count = $db->where('name', 'total_views')->update(T_CONFIG, array('value' => $get_views_count));

    $get_users_count = $db->getValue(T_USERS, 'count(*)');
    $update_users_count = $db->where('name', 'total_users')->update(T_CONFIG, array('value' => $get_users_count));

    $get_subs_count = $db->getValue(T_SUBSCRIPTIONS, 'count(*)');
    $update_subs_count = $db->where('name', 'total_subs')->update(T_CONFIG, array('value' => $get_subs_count));

    $get_comments_count = $db->getValue(T_COMMENTS, 'count(*)');
    $update_comments_count = $db->where('name', 'total_comments')->update(T_CONFIG, array('value' => $get_comments_count));

    $get_likes_count = $db->where('type', 1)->getValue(T_DIS_LIKES, 'count(*)');
    $update_likes_count = $db->where('name', 'total_likes')->update(T_CONFIG, array('value' => $get_likes_count));

    $get_dislikes_count = $db->where('type', 2)->getValue(T_DIS_LIKES, 'count(*)');
    $update_dislikes_count = $db->where('name', 'total_dislikes')->update(T_CONFIG, array('value' => $get_dislikes_count));

    $get_saved_count = $db->getValue(T_SAVED, 'count(*)');
    $update_saved_count = $db->where('name', 'total_saved')->update(T_CONFIG, array('value' => $get_saved_count));
    
    $user_statics = array();
    $videos_statics = array();

    $months = array('1','2','3','4','5','6','7','8','9','10','11','12');
    $date = date('Y');

    foreach ($months as $value) {
       $monthNum  = $value;
       $dateObj   = DateTime::createFromFormat('!m', $monthNum);
       $monthName = $dateObj->format('F'); 
       $user_statics[] = array('month' => $monthName, 'new_users' => $db->where('registered', "$date/$value")->getValue(T_USERS, 'count(*)'));
       $videos_statics[] = array('month' => $monthName, 'new_videos' => $db->where('registered', "$date/$value")->getValue(T_VIDEOS, 'count(*)'));
    }
    $update_user_statics = $db->where('name', 'user_statics')->update(T_CONFIG, array('value' => PT_Secure(json_encode($user_statics))));
    $update_videos_statics = $db->where('name', 'videos_statics')->update(T_CONFIG, array('value' => PT_Secure(json_encode($videos_statics))));
    
    
    $update_saved_count = $db->where('name', 'last_admin_collection')->update(T_CONFIG, array('value' => time()));
}

function PT_GetAd($type, $admin = true) {
    global $db;
    $type      = PT_Secure($type);
    $query_one = "SELECT `code` FROM " . T_ADS . " WHERE `placement` = '{$type}'";
    if ($admin === false) {
        $query_one .= " AND `active` = '1'";
    }
    $fetched_data = $db->rawQuery($query_one);
    if (!empty($fetched_data)) {
        return htmlspecialchars_decode($fetched_data[0]->code);
    }
    return '';
}

function PT_GetThemes() {
    global $pt;
    $themes = glob('themes/*', GLOB_ONLYDIR);
    return $themes;
}

function PT_UploadLogo($data = array()) {
    global $pt, $db;
    if (isset($data['file']) && !empty($data['file'])) {
        $data['file'] = PT_Secure($data['file']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = PT_Secure($data['name']);
    }
    if (isset($data['name']) && !empty($data['name'])) {
        $data['name'] = PT_Secure($data['name']);
    }
    if (empty($data)) {
        return false;
    }
    $allowed           = 'png';
    $new_string        = pathinfo($data['name'], PATHINFO_FILENAME) . '.' . strtolower(pathinfo($data['name'], PATHINFO_EXTENSION));
    $extension_allowed = explode(',', $allowed);
    $file_extension    = pathinfo($new_string, PATHINFO_EXTENSION);
    if (!in_array($file_extension, $extension_allowed)) {
        return false;
    }
    $logo_name = 'logo';
    if (!empty($data['light-logo'])) {
        $logo_name = 'logo-light';
    }
    if (!empty($data['favicon'])) {
        $logo_name = 'icon';
    }
    $dir      = "themes/" . $pt->config->theme . "/img/";
    $filename = $dir . "$logo_name.png";
    if (move_uploaded_file($data['file'], $filename)) {
        return true;
    }
}

function PT_GetTerms() {
    global $db;
    $data  = array();
    $terms = $db->get(T_TERMS);
    foreach ($terms as $key => $term) {
        $data[$term->type] = $term->text;
    }
    return $data;
}

function PT_CreateMainSession() {
    $hash = substr(sha1(rand(1111, 9999)), 0, 70);
    if (!empty($_SESSION['main_hash_id'])) {
        $_SESSION['main_hash_id'] = $_SESSION['main_hash_id'];
        return $_SESSION['main_hash_id'];
    }
    $_SESSION['main_hash_id'] = $hash;
    return $hash;
}

function PT_CheckMainSession($hash = '') {
    if (!isset($_SESSION['main_hash_id']) || empty($_SESSION['main_hash_id'])) {
        return false;
    }
    if (empty($hash)) {
        return false;
    }
    if ($hash == $_SESSION['main_hash_id']) {
        return true;
    }
    return false;
}

function PT_UploadToS3($filename, $config = array()) {
    global $pt;
    if ($pt->config->s3_upload != 'on') {
        return false;
    }

    $s3Config = (
        empty($pt->config->amazone_s3_key) || 
        empty($pt->config->amazone_s3_s_key) || 
        empty($pt->config->region) || 
        empty($pt->config->s3_bucket_name)
    );  
    
    if ($s3Config){
        return false;
    }

    $s3 = new S3Client([
        'version'     => 'latest',
        'region'      => $pt->config->region,
        'credentials' => [
            'key'    => $pt->config->amazone_s3_key,
            'secret' => $pt->config->amazone_s3_s_key,
        ]
    ]);

    $s3->putObject([
        'Bucket' => $pt->config->s3_bucket_name,
        'Key'    => $filename,
        'Body'   => fopen($filename, 'r+'),
        'ACL'    => 'public-read',
    ]);

    if (empty($config['delete'])) {
        if ($s3->doesObjectExist($pt->config->s3_bucket_name, $filename)) {
            if (empty($config['amazon'])) {
                @unlink($filename);
            }
            return true;
        }
    }

    else {
        return true;
    }

}

function PT_DeleteFromToS3($filename, $config = array()) {
    global $pt;
    if ($pt->config->s3_upload != 'on') { 
        return false;
    }

    $s3Config = (
        empty($pt->config->amazone_s3_key) ||
        empty($pt->config->amazone_s3_s_key) ||
        empty($pt->config->region) ||
        empty($pt->config->s3_bucket_name)
    );

    if (!$s3Config){
        return false;
    }

    $s3 = new S3Client([
        'version'     => 'latest',
        'region'      => $pt->config->region,
        'credentials' => [
            'key'    => $pt->config->amazone_s3_key,
            'secret' => $pt->config->amazone_s3_s_key,
        ]
    ]);

    $s3->deleteObject([
        'Bucket' => $pt->config->s3_bucket_name,
        'Key'    => $filename,
    ]);

    if (!$s3->doesObjectExist($pt->config->s3_bucket_name, $filename)) {
        return true;
    }
}

function PT_RegisterNewField($registration_data = array()) {
    global $pt, $mysqli;
    if (empty($registration_data)) {
        return false;
    }

    $fields      = '`' . implode('`, `', array_keys($registration_data)) . '`';
    $data        = '\'' . implode('\', \'', $registration_data) . '\'';
    $table       = T_FIELDS;
    $query       = mysqli_query($mysqli, "INSERT INTO  `$table` ({$fields}) VALUES ({$data})");

    if ($query) {
        $sql_id  = mysqli_insert_id($mysqli);
        $column  = 'fid_' . $sql_id;
        $table   = T_USR_PROF_FIELDS;
        $length  = $registration_data['length'];
        mysqli_query($mysqli, "ALTER TABLE `$table` ADD COLUMN `{$column}` varchar({$length}) NOT NULL DEFAULT ''");
        return true;
    }

    return false;
}

function PT_UpdateUserCustomData($user_id, $update_data, $loggedin = true) {
    global $pt, $sqlConnect;

    if ($loggedin == true) {
        if (IS_LOGGED == false) {
            return false;
        }
    }

    if (empty($user_id) || !is_numeric($user_id)) {
        return false;
    }

    if (empty($update_data)) {
        return false;
    }

    $user_id = PT_Secure($user_id);
    if ($loggedin == true) {
        if (PT_IsAdmin() === false) {
            if ($pt->user->id != $user_id) {
                return false;
            }
        }
    }

    $update = array();
    foreach ($update_data as $field => $data) {
        foreach ($data as $key => $value) {
            $update[] = '`' . $key . '` = \'' . PT_Secure($value, 0) . '\'';
        }
    }

    $impload     = implode(', ', $update);
    $table       = T_USR_PROF_FIELDS;
    $update_sql  = "UPDATE `$table` SET {$impload} WHERE `user_id` = {$user_id}";

    $usr_fields  = mysqli_query($sqlConnect, "SELECT COUNT(`id`) as count FROM `$table` WHERE `user_id` = {$user_id}");
    $usr_fields  = mysqli_fetch_assoc($usr_fields);
    $query       = false;

    if ($usr_fields['count'] == 1) {
        $query   = mysqli_query($sqlConnect, $update_sql);
    }

    else {
        $new_fid = mysqli_query($sqlConnect, "INSERT INTO `$table` (`user_id`) VALUES ({$user_id})");
        if ($new_fid) {
            $query = mysqli_query($sqlConnect, $update_sql);
        }
    }

    return $query;
}

