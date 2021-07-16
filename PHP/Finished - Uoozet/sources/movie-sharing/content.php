<?php
$pt->page        = 'movie-sharing';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;

 $home_top_videos = $db->where('privacy', 0)->orderby('views', 'DESC')->get(T_VIDEOS, 6);
 $top_videos_html = '';

 foreach ($home_top_videos as $key => $video) {
     $video = PT_GetVideoByID($video, 0, 0, 0);
     $top_videos_html .= PT_LoadPage('video-sharing/top-videos', array(
         'ID' => $video->id,
         'TITLE' => $video->title,
         'VIEWS' => $video->views,
         'USER_DATA' => $video->owner,
         'THUMBNAIL' => $video->thumbnail,
         'URL' => $video->url,
     ));
 }

$limit = ($pt->theme_using == 'youplay') ? 10 : 6;
$pt->videos_array = array();
//$db->where('converted', '2','<>');
if ($pt->theme_using == 'default') {
    $video_obj = $db->where('featured', '1')->where('is_movie', 1)->where('privacy', 0)->orderBy('RAND()')->get(T_VIDEOS,3);
    foreach ($video_obj as $key => $video) {
        $pt->videos_array[] = PT_GetVideoByID($video, 0, 1, 0);
    }
}
else{
    $video_obj = $db->where('featured', '1')->where('is_movie', 1)->where('privacy', 0)->orderBy('RAND()')->getOne(T_VIDEOS);
    $get_video = PT_GetVideoByID($video_obj, 0, 1, 0);
}



if (empty($get_video)) {

//    $db->where('converted', '2','<>');
    $get_video = PT_GetVideoByID($db->where('privacy', 0)->where('is_movie', 1)->orderBy('id', 'DESC')->getOne(T_VIDEOS), 0, 1, 0);
    if ($pt->theme_using == 'default') {
        $pt->videos_array[] = $get_video;
    }
}

if (empty($get_video)) {
    $pt->content = PT_LoadPage('video-sharing/no-content');
    return;
}

$user_data   = $get_video->owner;
$save_button = '<i class="fa fa-floppy-o fa-fw"></i> ' . $lang->save;
$is_saved    = 0;


if (IS_LOGGED == true) {
    $db->where('video_id', $get_video->id);
    $db->where('user_id', $user->id);
    $is_saved = $db->getValue(T_SAVED, "count(*)");
}

if ($is_saved > 0) {
    $save_button = '<i class="fa fa-check fa-fw"></i> ' . $lang->saved;
}

$trending_list = '';

if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('time', time() - 1296000, '>');
    $db->where('privacy', 0);
    $trending_data = $db->where('is_movie', 1)->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
}

if (empty($trending_data)) {
    $db->where('time', time() - 1296000, '>');
    $db->where('privacy', 0);
    $trending_data = $db->where('is_movie', 1)->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
} 
$data = $db->where('is_movie', 1)->orderBy('id', 'DESC')->get(T_VIDEOS);
$cnt = 0;
foreach ($data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if ($videoItem->cover == ''){
        continue;
    }
//    $trending_list .= PT_LoadPage('video-sharing/list', array(
//        'ID' => $videoItem->id,
//        'TITLE' => $videoItem->title,
//        'VIEWS' => $videoItem->views,
//        'VIEWS_NUM' => number_format($videoItem->views),
//        'USER_DATA' => $videoItem->owner,
//        'THUMBNAIL' => $videoItem->thumbnail,
//        'URL' => $videoItem->url,
//        'TIME' => $videoItem->time_ago,
//        'DURATION' => $videoItem->duration,
//        'VIDEO_ID' => $videoItem->video_id_,
//        'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
//    ));
    $trending_list .= '<div class="keep-padding item" data-id="299">
	<div class="">
		<div class="video-list-image">
			<a href="' . ($videoItem->url) .'">
				<img src="' . ($videoItem->cover) .'" alt="' . ($videoItem->title) .'">
				<span>' . (truncateString($videoItem->title,30,1)) .'</span>
			</a>
		</div>
	</div>
</div>';
}

$top_list = '';

if (!empty($pro_users)){
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('views', 'DESC');
    $top_data = $db->where('is_movie', 0)->get(T_VIDEOS, 4);
}

if (empty($top_data)) {
    $db->where('privacy', 0);
    $top_data = $db->where('is_movie', 1)->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);
}

$cnt = 0;
foreach ($top_data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if($cnt == 4){
        continue;
    }
    $cnt++;
    $top_list .= PT_LoadPage('video-sharing/list', array(
        'ID' => $videoItem->id,
        'TITLE' => $videoItem->title,
        'VIEWS' => $videoItem->views,
        'VIEWS_NUM' => number_format($videoItem->views),
        'USER_DATA' => $videoItem->owner,
        'THUMBNAIL' => $videoItem->thumbnail,
        'URL' => $videoItem->url,
        'TIME' => $videoItem->time_ago,
        'DURATION' => $videoItem->duration,
        'VIDEO_ID' => $videoItem->video_id_,
        'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
    ));
}

$latest_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $latest_data = $db->where('is_movie', 1)->get(T_VIDEOS, $limit);
}

if (empty($latest_data)) {
    $db->where('privacy', 0);
    $latest_data = $db->where('is_movie', 1)->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
}

$cnt = 0;
foreach ($latest_data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if(/*$videoItem->owner->admin ||*/ $cnt == 4){
        continue;
    }
    $cnt++;
//    $latest_list .= PT_LoadPage('video-sharing/list', array(
//        'ID' => $videoItem->id,
//        'TITLE' => $videoItem->title,
//        'VIEWS' => $videoItem->views,
//        'VIEWS_NUM' => number_format($videoItem->views),
//        'USER_DATA' => $videoItem->owner,
//        'THUMBNAIL' => $videoItem->thumbnail,
//        'URL' => $videoItem->url,
//        'TIME' => $videoItem->time_ago,
//        'DURATION' => $videoItem->duration,
//        'VIDEO_ID' => $videoItem->video_id_,
//        'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
//    ));
    $latest_list .= '<div class="carousel-cell"><img src="' . ($videoItem->slide_img) .'" alt="' . ($videoItem->title) .'">
    <div class="SliderContent"><span class="title">' . ($videoItem->title) .'</span><a class="moreInfo" href="' . ($videoItem->url) .'">اطلاعات بیشتر</a></div></div>';
}

$video_categories_html = '';

foreach ($categories as $cat_key => $cat_name) {
    $db->where("category_id = '$cat_key'");
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $pt->cat_videos = $db->where('is_movie', 1)->get(T_VIDEOS, 8);
    if (!empty($pt->cat_videos)) {
        $video_categories_html .= PT_LoadPage('video-sharing/categories',array(
            'CATEGORY_ONE_NAME' => $cat_name,
            'CATEGORY_ONE_ID' => $cat_key
        ));
    }
}
$pt->video_240 = 0;
$pt->video_360 = 0;
$pt->video_480 = 0;
$pt->video_720 = 0;

if ($pt->config->ffmpeg_system == 'on') {
    $explode_video = explode('_video', $get_video->video_location);
    if ($get_video->{"240p"} == 1) {
        $pt->video_240 = $explode_video[0] . '_video_240p_converted.mp4';
    }
    if ($get_video->{"360p"} == 1) {
        $pt->video_360 = $explode_video[0] . '_video_360p_converted.mp4';
    }
    if ($get_video->{"480p"} == 1) {
        $pt->video_480 = $explode_video[0] . '_video_480p_converted.mp4';
    }
    if ($get_video->{"720p"} == 1) {
        $pt->video_720 = $explode_video[0] . '_video_720p_converted.mp4';
    }
}

$pt->subscriptions = false;
$get_subscriptions_videos_html = '';
if (IS_LOGGED == true) {
    $get = $db->where('subscriber_id', $user->id)->get(T_SUBSCRIPTIONS);
    $userids = array();
    foreach ($get as $key => $userdata) {
        $userids[] = $userdata->user_id;
    }
    $get_subscriptions_videos = false;
    $userids = implode(',', ToArray($userids));
    if (!empty($userids)) {
        $get_subscriptions_videos = $db->rawQuery("SELECT * FROM " . T_VIDEOS . " WHERE user_id IN ($userids) AND is_movie != 1 AND is_movie != 2 AND is_movie != 3 AND privacy = 0 ORDER BY `id` DESC LIMIT $limit");
    }
    if (!empty($get_subscriptions_videos)) {
        $pt->subscriptions = true;
        $pt->cat_videos = $get_subscriptions_videos;
        $get_subscriptions_videos_html = PT_LoadPage('video-sharing/categories',array(
            'CATEGORY_ONE_NAME' => $lang->subscriptions,
            'CATEGORY_ONE_ID' => 'subscriptions'
        ));
        
    }
}

$pt->content = PT_LoadPage('movie-sharing/content', array(
    'ID' => $get_video->id,
    'THUMBNAIL' => $get_video->thumbnail,
    'DURATION' => $get_video->duration,
    'TITLE' => $get_video->title,
    'DESC' => $get_video->markup_description,
    'URL' => $get_video->url,
    'VIDEO_LOCATION_240' => $pt->video_240,
    'VIDEO_LOCATION' => $get_video->video_location,
    'VIDEO_LOCATION_480' => $pt->video_480,
    'VIDEO_LOCATION_720' => $pt->video_720,
    'VIDEO_TYPE' => $get_video->video_type,
    'VIDEO_MAIN_ID' => $get_video->video_id,
    'VIDEO_ID' => $get_video->video_id_,
    'USER_DATA' => $user_data,
    'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
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
    'SAVED_BUTTON' => $save_button,
    'IS_SAVED' => ($is_saved > 0) ? 'saved="true"' : '',
    'ENCODED_URL' => urlencode($get_video->url),
    'CATEGORY' => $get_video->category_name,
    'TIME' => $get_video->time_alpha,
    'TRENDING_LIST' => $trending_list,
    'TOP_LIST' => $top_list,
    'LATEST_LIST' => $latest_list,
    'HOME_PAGE_VIDEOS' => $video_categories_html,
    'SUBSC_HTML' => $get_subscriptions_videos_html,
    'VIDEO_ID_' => PT_Slug($get_video->title, $get_video->video_id),
    'COVER' => $get_video->cover,
));

function truncateString($str, $chars, $to_space, $replacement="...") {
    if($chars > strlen($str)) return $str;

    $str = substr($str, 0, $chars);
    $space_pos = strrpos($str, " ");
    if($to_space && $space_pos >= 0)
        $str = substr($str, 0, strrpos($str, " "));

    return($str . $replacement);
}