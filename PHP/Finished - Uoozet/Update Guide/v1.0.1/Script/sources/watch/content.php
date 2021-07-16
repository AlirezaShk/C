<?php
if (empty($_GET['id'])) {
    header("Location: " . PT_Link('404'));
    exit();
}
$id = PT_Secure($_GET['id']);
if (strpos($id, '_') !== false) {
    $id_array = explode('_', $id);
    $id_html  = $id_array[1];
    $id       = str_replace('.html', '', $id_html);
}
$get_video = PT_GetVideoByID($id, 1, 1);
if (empty($get_video)) {
    header("Location: " . PT_Link('404'));
    exit();
}

$user_data = $get_video->owner;

$desc = str_replace('"', "'", $get_video->edit_description);
$desc = str_replace('<br>', "", $desc);
$desc = str_replace("\n", "", $desc);
$desc = str_replace("\r", "", $desc);
$desc = mb_substr($desc, 0, 220, "UTF-8");

$pt->get_video   = $get_video;
$pt->page        = 'watch';
$pt->title       = $get_video->title;
$pt->description = htmlspecialchars($desc);
$pt->keyword     = $get_video->tags;
$pt->is_list     = false;
$pt->get_id      = $id;
$pt->list_name   = "";
$list_id         = 0;
if (isset($_GET['list'])) {
    $list_id     = PT_Secure($_GET['list']);
    $pt->is_list = (
        ($db->where('list_id', $list_id)->getValue(T_LISTS, 'count(*)') > 0) &&
        ($db->where('list_id', $list_id)->where('video_id', $get_video->id)->getValue(T_PLAYLISTS, 'count(*)') > 0)
    );

    if (!$pt->is_list) {
        header("Location: " . PT_Link("watch/$id"));
        exit();
    }
}

$related_videos = array();

if (!$pt->is_list || 1) {
    $query_video_title = PT_Secure($get_video->title);

    $related_videos = $db->rawQuery("SELECT * FROM " . T_VIDEOS . " WHERE MATCH (title) AGAINST ('$query_video_title') AND id <> '{$get_video->id}' LIMIT 20");

    if (empty($related_videos)) {
        $related_videos = $db->where('category_id', $get_video->category_id)->where('id', $get_video->id, '<>')->get(T_VIDEOS, 20);
    }

    if (empty($related_videos)) {
        $related_videos_num = $db->getValue(T_VIDEOS, 'count(*)');
        $randomlySelected   = array();
        $count_from         = 5;
        if ($related_videos_num > 9) {
            $count_from = 10;
        }
        for ($a = 0; $a < $count_from; $a++) {
            $rand = rand(1, $related_videos_num);
            if (!in_array($rand, $randomlySelected)) {
                $randomlySelected[] = $rand;
            }
        }
        $related_videos = $db->where('id', $randomlySelected, 'IN')->where('id', $get_video->id, '<>')->get(T_VIDEOS);
    }
}



$video_sidebar  = '';
$next_video     = '';
$next           = 0;
$list_sidebar   = '';
$list_user_name = '';
$list_count     = 0;
$video_index    = 0;
$pt->list_owner = false;
if ($pt->is_list === true) {
    
    $pt->list_data  = $db->where("list_id", $list_id)->getOne(T_LISTS);
    $pt->list_name  = $pt->list_data->name;
    $videos         = $db->where('list_id', $list_id)->get(T_PLAYLISTS,null,'video_id');
    $video_list     = array();
    $list_count     = count($videos);
    $list_user_data = PT_UserData($pt->list_data->user_id);
    
    if (!empty($list_user_data)) {
        $list_user_name = $list_user_data->name;
    }

    if (IS_LOGGED === true && ($pt->list_data->user_id == $pt->user->id)) {
        $pt->list_owner = true;
    }

    foreach ($videos as $vid) {
        $video_list[] = $vid->video_id;
    }

    $play_list_videos = $db->where('id', array_values($video_list), 'IN')->orderBy('id','asc',array_values($video_list))->get(T_VIDEOS);
    $vid_number       = 1;
    foreach ($play_list_videos as $key => $pl_vid) {
        $pl_vid         = PT_GetVideoByID($pl_vid, 0, 0, 0);
        $pl_vid->url    = PT_Link('watch/' . PT_Slug($pl_vid->title, $pl_vid->video_id) . "/list/$list_id");
        $list_sidebar .= PT_LoadPage('watch/video-list', array(
            'TITLE' => $pl_vid->title,
            'URL' => $pl_vid->url,
            'LIST_ID' => $list_id,
            'VID_ID' => $pl_vid->id,
            'ID' => $pl_vid->video_id,
            'THUMBNAIL' => $pl_vid->thumbnail,
            'VID_NUMBER' => ($pl_vid->video_id == $id) ? "<i class='fa fa-circle'></i>" : $vid_number,
            'VIEWS' => $pl_vid->views,
        ));
        if ($pl_vid->video_id == $id) {
            $video_index = $vid_number;
        }
        $vid_number++;
    }
}

foreach ($related_videos as $key => $related_video) {
    $related_video  = PT_GetVideoByID($related_video, 0, 0, 0);
    $video_sidebar .= PT_LoadPage('watch/video-sidebar', array(
        'ID' => $related_video->id,
        'TITLE' => $related_video->title,
        'URL' => $related_video->url,
        'THUMBNAIL' => $related_video->thumbnail,
        'USER_NAME' => $related_video->owner->name,
        'VIEWS' => $related_video->views,
    ));
    if ($next == 0 &&  $pt->config->autoplay_system == 'on') {
        $next_video = $video_sidebar;
        $video_sidebar = '';
    }
    $next++;
}  



$comments = '<div class="text-center no-comments-found">' . $lang->no_comments_found . '</div>';
$get_video_comments = $db->where('video_id', $get_video->id)->orderBy('id', 'DESC')->get(T_COMMENTS, $pt->config->comments_default_num);
if (!empty($get_video_comments)) {
    $comments = '';
    foreach ($get_video_comments as $key => $comment) {
        $is_liked_comment = 0;
        $pt->is_comment_owner = false;
        if (IS_LOGGED == true) {
            $is_liked_comment = $db->where('comment_id', $comment->id)->where('user_id', $user->id)->getValue(T_COMMENTS_LIKES, 'count(*)');
            if ($user->id == $comment->user_id) {
                $pt->is_comment_owner = true;
            }
        }
        $comments     .= PT_LoadPage('watch/comments', array(
            'ID' => $comment->id,
            'TEXT' => PT_Markup($comment->text),
            'TIME' => PT_Time_Elapsed_String($comment->time),
            'USER_DATA' => PT_UserData($comment->user_id),
            'LIKES' => $db->where('comment_id', $comment->id)->getValue(T_COMMENTS_LIKES, 'count(*)'),
            'IS_LIKED' => ($is_liked_comment > 0) ? 'active' : '',
            'LIKED_ATTR' => ($is_liked_comment > 0) ? 'liked="true"' : ''
        ));
    }
}
$pt->count_comments = $db->where('video_id', $get_video->id)->getValue(T_COMMENTS, 'count(*)');

$save_button = '<i class="fa fa-floppy-o fa-fw"></i> ' . $lang->save;
$is_saved = 0;
if (IS_LOGGED == true) {
    $is_saved = $db->where('video_id', $get_video->id)->where('user_id', $user->id)->getValue(T_SAVED, "count(*)");
    if ($pt->config->history_system == 'on') {
        $is_in_history = $db->where('video_id', $get_video->id)->where('user_id', $user->id)->getValue(T_HISTORY, 'count(*)');
        if ($is_in_history == 0) {
            $insert_to_history = array(
                'user_id' => $user->id,
                'video_id' => $get_video->id,
                'time' => time()
            );
            $insert_to_history_query = $db->insert(T_HISTORY, $insert_to_history);
        }
    }
}
if ($is_saved > 0) {
    $save_button = '<i class="fa fa-check fa-fw"></i> ' . $lang->saved;
}
$checked = '';
if (!empty($_SESSION['autoplay'])) { 
    if ($_SESSION['autoplay'] == 2) {
        $checked = 'checked';
    } 
}
$ad_media = '';
$ad_link = '';
$ad_skip = 'false';
$ad_skip_num = 0;
$is_video_ad = '';
$is_vast_ad = '';
$vast_url = '';
$vast_type = '';
$last_ads = 0;
$ad_image = '';
$ad_link = '';
if (!empty($_COOKIE['last_ads_seen'])) {
    if ($_COOKIE['last_ads_seen'] > (time() - 600)) {
        $last_ads = 1;
    }
}
if ($last_ads == 0) {
    $count_ads = $db->where('active', 1)->getValue(T_VIDEO_ADS, 'count(*)');
    if ($count_ads > 0) {
        $get_random_ad =  $db->where('active', 1)->orderBy('RAND()')->getOne(T_VIDEO_ADS);
        if (!empty($get_random_ad)) {
            if (!empty($get_random_ad->ad_media)) {
                $ad_media = $get_random_ad->ad_media;
                $ad_link = PT_Link('redirect/' . $get_random_ad->id . '?type=video');
                $is_video_ad = ",'ads'";
            }
            if (!empty($get_random_ad->vast_xml_link)) {
                $vast_url = $get_random_ad->vast_xml_link;
                $vast_type = $get_random_ad->vast_type;
                $is_vast_ad = ",'vast'";
            }
            if ($get_random_ad->skip_seconds > 0) {
                $ad_skip = 'true';
                $ad_skip_num = $get_random_ad->skip_seconds;
            }
            if (!empty($get_random_ad->ad_image)) {
                $ad_image = $pt->ad_image = $get_random_ad->ad_image;
                $ad_link = PT_Link('redirect/' . $get_random_ad->id . '?type=image');
            }
            $update_clicks = $db->where('id', $get_random_ad->id)->update(T_VIDEO_ADS, array(
                'views' => $db->inc(1)
            ));
            $cookie_name = 'last_ads_seen';
            $cookie_value = time();
            setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
        }
    } 
}

$content_page = ($pt->is_list === true) ? "playlist" : "content";
$pt->content = PT_LoadPage("watch/$content_page", array(
    'ID' => $get_video->id,
    'KEY' => $get_video->video_id,
    'THUMBNAIL' => $get_video->thumbnail,
    'TITLE' => $get_video->title,
    'DESC' => $get_video->markup_description,
    'URL' => $get_video->url,
    'VIDEO_LOCATION' => $get_video->video_location,
    'VIDEO_TYPE' => $get_video->video_type,
    'VIDEO_MAIN_ID' => $get_video->video_id,
    'VIDEO_ID' => $get_video->video_id_,
    'USER_DATA' => $user_data,
    'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
    'VIDEO_SIDEBAR' => $video_sidebar,
    'LIST_SIDEBAR' => $list_sidebar,
    'LIST_OWNERNAME' => $list_user_name,
    'VID_INDEX' => $video_index,
    'LIST_COUNT' => $list_count,
    'LIST_NAME' => $pt->list_name,
    'VIDEO_NEXT_SIDEBAR' => $next_video,
    'COOKIE' => $checked,
    'VIEWS' => $get_video->views,
    'LIKES' => number_format($get_video->likes),
    'DISLIKES' => number_format($get_video->dislikes),
    'LIKES_P' => $get_video->likes_percent,
    'DISLIKES_P' => $get_video->dislikes_percent,
    'RAEL_LIKES' => $get_video->likes,
    'RAEL_DISLIKES' => $get_video->dislikes,
    'ISLIKED' => ($get_video->is_liked > 0) ? 'liked="true"' : '',
    'ISDISLIKED' => ($get_video->is_disliked > 0) ? 'disliked="true"' : '',
    'LIKE_ACTIVE_CLASS' => ($get_video->is_liked > 0) ? 'active' : '',
    'DIS_ACTIVE_CLASS' => ($get_video->is_disliked > 0) ? 'active' : '', 
    'COUNT_COMMENTS' => $pt->count_comments,
    'COMMENTS' => $comments,
    'SAVED_BUTTON' => $save_button,
    'IS_SAVED' => ($is_saved > 0) ? 'saved="true"' : '',
    'ENCODED_URL' => urlencode($get_video->url),
    'CATEGORY' => $get_video->category_name,
    'CATEGORY_ID' => $get_video->category_id,
    'TIME' => $get_video->time_alpha,

    'VAST_URL' => $vast_url,
    'VAST_TYPE' => $vast_type,

    'AD_MEDIA' => "'$ad_media'",
    'AD_LINK' => "'$ad_link'",

    'AD_P_LINK' => "$ad_link",

    'AD_SKIP' => $ad_skip,
    'AD_SKIP_NUM' => $ad_skip_num,

    'ADS' => $is_video_ad,
    'VAT' => $is_vast_ad,

    'AD_IMAGE' => $ad_image,

    'COMMENT_AD' => PT_GetAd('watch_comments'),
    'WATCH_SIDEBAR_AD' => PT_GetAd('watch_side_bar'),

));

