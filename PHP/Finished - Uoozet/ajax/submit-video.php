<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (IS_LOGGED == false || $pt->config->upload_system != 'on') {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}
$getID3   = new getID3;
$vm = new Video();
$featured = ($user->is_pro == 1) ? 1 : 0;
$filesize = 0;

$is_channel = 1;
if(empty($_POST['channel'])){
    $is_channel = 0;
    $user_id = $user->id;
}
else if($_POST['channel'] == 0){
    $user_id = $user->id;
    $is_channel = 0;
}
else{
    $user_id = $_POST['channel'];
}

if(strlen($_POST['channel']) == 0 || $user->admin == 0)
    $isChannelVid = 0;
else
    $isChannelVid = 1;
if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {
//    echo $_POST['movie_title'].'<br>';
//    echo $_POST['movie_description'].'<br>';
//    echo $_POST['stars'].'<br>';
//    echo $_POST['producer'].'<br>';
//    echo $_POST['country'].'<br>';
//    echo $_POST['rating'].'<br>';
//    echo $_POST['release'].'<br>';
//    echo $_POST['category'].'<br>';exit;
    if (empty($_POST['movie_title']) || empty($_POST['movie_description']) || empty($_POST['stars']) || empty($_POST['producer']) || empty($_POST['country']) || empty($_POST['rating']) || !is_numeric($_POST['rating']) || $_POST['rating'] < 1 || $_POST['rating'] > 10 || empty($_POST['release']) || empty($_POST['category']) || !in_array($_POST['category'], array_keys($pt->movies_categories))) {
        $error = $error_icon . $lang->please_check_details;
    }
    // $cover = getimagesize($_FILES["thumbnail"]["tmp_name"]);
    // if ($cover[0] > 400 || $cover[1] > 570) {
    //     $error = $lang->cover_size;
    // }
    if (!empty($_FILES['thumbnail']['tmp_name'])) {
        if ($_FILES['thumbnail']['size'] > $pt->config->max_upload) {
            $max   = pt_size_format($pt->config->max_upload);
            $error = $lang->file_is_too_big .": $max";
        }
    }

}
else{
    if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['tags']) ) {
        $error = $lang->please_check_details;
    }
    if (empty($_POST['video-location'])) {
        $error = $lang->video_not_found_please_try_again;
    }

    if (!empty($_FILES['thumbnail']['tmp_name'])) {
        if ($_FILES['thumbnail']['size'] > $pt->config->max_upload) {
            $max   = pt_size_format($pt->config->max_upload);
            $error = $lang->file_is_too_big .": $max";
        }
    }

}
if (empty($error)) {
    $file     = $getID3->analyze($_POST['video-location']);
    $duration = '00:00:00';


    if (!empty($file['playtime_string']) ) {
        $s = intval($file['playtime_seconds']);
        $m = intval($s/60);
        $h = intval($m/60);
        $s = $s % 60;
        $m = $m % 60;
        if ($s < 10) $s = "0" . $s;
        if ($m < 10) $m = "0" . $m;
        if ($h < 10) $h = "0" . $h;
        $duration = "$h:$m:$s";
    }

    if (!empty($file['filesize'])) {
        $filesize = $file['filesize'];
    }

    $video_id        = PT_GenerateKey(15, 15);
    $check_for_video = $db->where('video_id', $video_id)->getValue(T_VIDEOS, 'count(*)');
    if ($check_for_video > 0) {
        $video_id = PT_GenerateKey(15, 15);
    }
    $thumbnail = 'upload/photos/thumbnail.jpg';

    if (isset($_POST['image-base64']) && $_POST['image-base64'] != '' && strlen($_POST['image-base64']) > 20){
        $image = $_POST['image-base64'];
        list($type, $image) = explode(';',$image);
        list(, $image) = explode(',',$image);

        $filepath                   = explode('.', $_POST['video-location'])[0];
        $image = base64_decode($image);
        $thumbnail = PT_Secure(time().'.png', 0);
        $video_output = str_replace('upload/videos','upload/photos',$filepath). $thumbnail;
        $thumbnail = $video_output;
        file_put_contents($video_output, $image);

    }
    elseif (!empty($_FILES['thumbnail']['tmp_name'])) {
        if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {
            $file_info   = array(
                'file' => $_FILES['thumbnail']['tmp_name'],
                'size' => $_FILES['thumbnail']['size'],
                'name' => $_FILES['thumbnail']['name'],
                'type' => $_FILES['thumbnail']['type']
            );
        }
        else{
            $file_info   = array(
                'file' => $_FILES['thumbnail']['tmp_name'],
                'size' => $_FILES['thumbnail']['size'],
                'name' => $_FILES['thumbnail']['name'],
                'type' => $_FILES['thumbnail']['type'],
                'crop' => array(
                    'width' => 1076,
                    'height' => 604
                )
            );
        }
        $file_upload = PT_ShareFile($file_info);
        if (!empty($file_upload['filename'])) {
            $thumbnail = PT_Secure($file_upload['filename'], 0);
        }

    }
    else{
        if(isset($_POST['cover_auto_time']))
            $time_cover = $_POST['cover_auto_time'];
        else
            $time_cover = '00:00:05';
        $filepath                   = explode('.', $_POST['video-location'])[0];
        $video_output = str_replace('upload/videos','upload/photos',$filepath). "_image.png";
        $time                       = time();
        $shell     = shell_exec("ffmpeg -ss ".$time_cover." -i ". 'https://www.uoozet.com/'.$_POST['video-location']." -vframes 1 -q:v 2 $video_output");
        $thumbnail = PT_Secure($video_output, 0);
    }
    //cover and slide-img
    $cover = '';
    $slide = '';

//    if (!empty($_FILES['cover']['tmp_name'])) {
//        if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {
//            $cover_info   = array(
//                'file' => $_FILES['cover']['tmp_name'],
//                'size' => $_FILES['cover']['size'],
//                'name' => $_FILES['cover']['name'],
//                'type' => $_FILES['cover']['type']
//            );
//            $cover_upload = PT_ShareFile($cover_info);
//            if (!empty($cover_upload['filename'])) {
//                $cover = PT_Secure($cover_upload['filename'], 0);
//            }
//        }
//    }
//
//    if (!empty($_FILES['slide_img']['tmp_name'])) {
//        if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {
//            $slide_info   = array(
//                'file' => $_FILES['slide_img']['tmp_name'],
//                'size' => $_FILES['slide_img']['size'],
//                'name' => $_FILES['slide_img']['name'],
//                'type' => $_FILES['slide_img']['type']
//            );
//            $slide_upload = PT_ShareFile($slide_info);
//            if (!empty($slide_upload['filename'])) {
//                $slide = PT_Secure($slide_upload['filename'], 0);
//            }
//        }
//    }

    if (isset($_POST['image-base64slide']) && $_POST['image-base64slide'] != '' && strlen($_POST['image-base64slide']) > 20){
        $image = $_POST['image-base64slide'];
        list($type, $image) = explode(';',$image);
        list(, $image) = explode(',',$image);

        $filepath = explode('.', $_POST['video-location'])[0];
        $image = base64_decode($image);
        $slide = PT_Secure(time().'.png', 0);
        $video_output = str_replace('upload/videos','upload/photos',$filepath). 'slide' . $slide;
        $slide = $video_output;
        file_put_contents($video_output, $image);
    }
    if (isset($_POST['image-base64cover']) && $_POST['image-base64cover'] != '' && strlen($_POST['image-base64cover']) > 20){
        $image2 = $_POST['image-base64cover'];
        list($type, $image2) = explode(';',$image2);
        list(, $image2) = explode(',',$image2);

        $filepath3 = explode('.', $_POST['video-location'])[0];
        $image2 = base64_decode($image2);
        $cover = PT_Secure(time().'.png', 0);
        $video_output2 = str_replace('upload/videos','upload/photos',$filepath3).'cover'. $cover;
        $cover = $video_output2;
        file_put_contents($video_output2, $image2);
    }
    $subtitle = '';
    if (!empty($_FILES['subtitle']['tmp_name'])) {
        if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {
            $file_info   = array(
                'file' => $_FILES['subtitle']['tmp_name'],
                'size' => $_FILES['subtitle']['size'],
                'name' => $_FILES['subtitle']['name'],
                'type' => $_FILES['subtitle']['type']
            );
        }
        $file_upload = PT_ShareFile($file_info);
        $subtitle = $file_upload;
        if (!empty($file_upload['filename'])) {
            $subtitle = PT_Secure($file_upload['filename'], 0);
        }

    }
    // ******************************
    if (PT_IsAdmin() && !empty($_POST['is_movie']) && $_POST['is_movie'] == 1) {

        $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
        $i          = 0;
        preg_match_all($link_regex, PT_Secure($_POST['movie_description']), $matches);
        foreach ($matches[0] as $match) {
            $match_url            = strip_tags($match);
            $syntax               = '[a]' . urlencode($match_url) . '[/a]';
            $_POST['movie_description'] = str_replace($match, $syntax, $_POST['movie_description']);
        }
        /* PRICE */
        $price_coeff = intval($_POST['pricing']);
        $price = $price_coeff * intval($_POST['cost']);
        /* END PRICE */
        $data_insert = array(
            'title' =>  PT_Secure($_POST['movie_title']),
            'category_id' => PT_Secure($_POST['category']),
            'stars' => PT_Secure($_POST['stars']),
            'producer' => PT_Secure($_POST['producer']),
            'country' => PT_Secure($_POST['country']),
            'movie_release' => PT_Secure($_POST['release']),
            'quality' => PT_Secure($_POST['quality']),
            'duration' => $duration,
            'description' => PT_Secure($_POST['movie_description']),
            'rating' => PT_Secure($_POST['rating']),
            'is_movie' => 1,
            'video_id' => $video_id,
            'converted' => '2',
            'size' => $filesize,
            'thumbnail' => $thumbnail,
            'cover' => $cover,
            'slide_img' => $slide,
            'subtitle' =>$subtitle,
            'user_id' => $user_id,
            'is_channel' => $is_channel,
            'time' => time(),
            'registered' => date('Y') . '/' . intval(date('m')),
            'video_location' => PT_Secure($_POST['video-location'], 0),
            'categories'=>$_POST['category'],
            'price'=>$price,
            'currency'=>'IR',
            'paid_for'=>'{}'
        );
        if (!empty($_POST['buy_price']) && is_numeric($_POST['buy_price']) && $_POST['buy_price'] > 0) {
            $data_insert['sell_video'] = PT_Secure($_POST['buy_price']);
        }

    }
    else{
        $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
        $i          = 0;
        preg_match_all($link_regex, PT_Secure($_POST['description']), $matches);
        foreach ($matches[0] as $match) {
            $match_url           = strip_tags($match);
            $syntax              = '[a]' . urlencode($match_url) . '[/a]';
            $_POST['description'] = str_replace($match, $syntax, $_POST['description']);
        }
        $video_privacy = 0;
        if (!empty($_POST['privacy'])) {
            if (in_array($_POST['privacy'], array(0, 1, 2))) {
                $video_privacy = PT_Secure($_POST['privacy']);
            }
        }
        $age_restriction = 1;
        if (!empty($_POST['age_restriction'])) {
            if (in_array($_POST['age_restriction'], array(1, 2))) {
                $age_restriction = PT_Secure($_POST['age_restriction']);
            }
        }

        $sub_category = 0;

        if (!empty($_POST['sub_category_id'])) {
            $is_found = $db->where('type',PT_Secure($_POST['category_id']))->where('lang_key',PT_Secure($_POST['sub_category_id']))->getValue(T_LANGS,'COUNT(*)');
            if ($is_found > 0) {
                $sub_category = PT_Secure($_POST['sub_category_id']);
            }
        }

        $continents_list = array();
        if (!empty($_POST['continents-list'])) {
            foreach ($_POST['continents-list'] as $key => $value) {
                if (in_array($value, $pt->continents)) {
                    $continents_list[] = $value;
                }
            }
        }
        list($category_id, $categories) = $vm->formatCats($_POST['category_id'], $isChannelVid);
        $tags = $vm->formatTags(PT_Secure($_POST['tags']));
        /* PRICE */
        $price_coeff = intval($_POST['pricing']);
        $price = $price_coeff * intval($_POST['cost']);
        /* END PRICE */
        $data_insert = array(
            'video_id' => $video_id,
            'user_id' => $user_id,
            'is_channel' => $is_channel,
            'title' => PT_Secure($_POST['title']),
            'description' => PT_Secure($_POST['description']),
            'tags' => $tags,
            'duration' => $duration,
            'video_location' => PT_Secure($_POST['video-location'], 0),
            'category_id' => $category_id,
            'thumbnail' => $thumbnail,
            'time' => time(),
            'registered' => date('Y') . '/' . intval(date('m')),
            'featured' => $featured,
            'size' => $filesize,
            'privacy' => $video_privacy,
            'age_restriction' => $age_restriction,
            'sub_category' => $sub_category,
            'geo_blocking' => (!empty($continents_list) ? json_encode($continents_list) : ''),
            'is_movie'=>$_POST['short_video'],
            'categories'=>$categories,
            'price'=>$price,
            'currency'=>'IR',
            'paid_for'=>'{}'
        );
        if (!empty($_POST['set_p_v']) && is_numeric($_POST['set_p_v']) && $_POST['set_p_v'] > 0) {
            $data_insert['sell_video'] = PT_Secure($_POST['set_p_v']);
        }


        if ( ($pt->config->approve_videos == 'on' && !PT_IsAdmin()) || ($pt->config->auto_approve_ == 'no' && $pt->config->sell_videos_system == 'on' && !PT_IsAdmin() && !empty($data_insert['sell_video'])) ) {
            $data_insert['approved'] = 0;
        }
    }
    $insert      = $db->insert(T_VIDEOS, $data_insert);

//    test multi size
    if ($insert) {
        $data = array(
            'status' => 200,
            'video_id' => $video_id,
            'link' => PT_Link("watch/$video_id")

        );
        pt_push_channel_notifiations($video_id);
    }


   // insert cats
    $cats =  explode(",",PT_Secure($_POST['category_id']));
      foreach ($cats as $cat) {
          $catId = array_search($cat, ToArray($pt->categories));
          if($catId){
              $data_cat_insert = array(
                  'media_id' => $insert,
                  'category_id' => array_search($cat, ToArray($pt->categories)),
              );
              $insert_cat = $db->insert(T_CAT_VIDEOS, $data_cat_insert);
          }
      }
    //    test multi size


    $ffmpeg_b                   = $pt->config->ffmpeg_binary_file;
    $full_dir                   = str_replace('ajax', '', __DIR__);
    $filepath                   = explode('.', $_POST['video-location'])[0];
    $video_output_full_path_240 = $full_dir . $filepath . "_240p_converted.mp4";
    $video_output_full_path_360 = $full_dir . $filepath . "_360p_converted.mp4";
    $video_output_full_path_480 = $full_dir . $filepath . "_480p_converted.mp4";
    $video_output_full_path_720 = $full_dir . $filepath . "_720p_converted.mp4";
    $video_output_full_path_1080 = $full_dir . $filepath . "_1080p_converted.mp4";
    $video_output_full_path_2048 = $full_dir . $filepath . "_2048p_converted.mp4";
    $video_output_full_path_4096 = $full_dir . $filepath . "_4096p_converted.mp4";

    $video_file_full_path       = $full_dir . $_POST['video-location'];
    // demo Video
    $video_time = '';
    $demo_video = '';

    // demo Video
    $shell     = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=426:-2 -crf 26 $video_output_full_path_240 2>&1");
//    $shell     = shell_exec("$ffmpeg_b -y -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=426:-2 -crf 26 $video_output_full_path_240 2>&1");
    $upload_s3 = PT_UploadToS3($filepath . "_240p_converted.mp4");
    $db->where('id', $insert);
    $db->update(T_VIDEOS, array(
        '240p' => 1,
    ));
    $video_res = (!empty($file['video']['resolution_x'])) ? $file['video']['resolution_x'] : 0;

    if ($video_res >= 3840) {
        $shell     = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=3840:-2 -crf 26 $video_output_full_path_4096 2>&1");
        $upload_s3 = PT_UploadToS3($filepath . "_4096p_converted.mp4");
        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '4096p' => 1
        ));

    }
    if ($video_res >= 2048) {
        $shell     = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=2048:-2 -crf 26 $video_output_full_path_2048 2>&1");
        $upload_s3 = PT_UploadToS3($filepath . "_2048p_converted.mp4");
        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '2048p' => 1
        ));

    }
    if ($video_res >= 1920 || $video_res == 0) {
        $shell     = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=1920:-2 -crf 26 $video_output_full_path_1080 2>&1");
        $upload_s3 = PT_UploadToS3($filepath . "_1080p_converted.mp4");
        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '1080p' => 1
        ));

    }
    if ($video_res >= 1280 || $video_res == 0) {

        $shell = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=1280:-2 -crf 26 $video_output_full_path_720 2>&1");
        $upload_s3 = PT_UploadToS3($filepath . "_720p_converted.mp4");


        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '720p' => 1
        ));

    }
    if ($video_res >= 854 || $video_res == 0) {
        $shell     = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=854:-2 -crf 26 $video_output_full_path_480 2>&1");
        $upload_s3 = PT_UploadToS3($filepath . "_480p_converted.mp4");
        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '480p' => 1
        ));

    }
    if ($video_res >= 640 || $video_res == 0) {
        $shell                      = shell_exec("ffmpeg -i $video_file_full_path -vcodec libx264 -preset {$pt->config->convert_speed} -filter:v scale=640:-2 -crf 26 $video_output_full_path_360 2>&1");
        $upload_s3                  = PT_UploadToS3($filepath . "_360p_converted.mp4");
        $db->where('id', $insert);
        $db->update(T_VIDEOS, array(
            '360p' => 1,
        ));

    }
}
else {
    $data = array(
        'status' => 400,
        'message' => $error_icon . $error
    );
}

function convertTime($dec)
{
    // start by converting to seconds
    $seconds = $dec;
    // we're given hours, so let's get those the easy way
    $hours = floor($dec/3600);
    // since we've "calculated" hours, let's remove them from the seconds variable
    $seconds -= $hours * 3600;
    // calculate minutes left
    $minutes = floor($seconds / 60);
    // remove those from seconds as well
    $seconds -= $minutes * 60;
    // return the time formatted HH:MM:SS
    return lz($hours).":".lz($minutes).":".lz($seconds);
}

// lz = leading zero
function lz($num)
{
    return (strlen(round($num)) < 2) ? "0{$num}" : $num;
}
?>
