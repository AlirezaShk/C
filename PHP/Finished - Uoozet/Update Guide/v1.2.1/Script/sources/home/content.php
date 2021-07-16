<?php

$pt->page        = 'home';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');


$home_top_videos = $db->orderby('views', 'DESC')->get(T_VIDEOS, 6);
$top_videos_html = '';

foreach ($home_top_videos as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $top_videos_html .= PT_LoadPage('home/top-videos', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
    ));
}

$get_video = PT_GetVideoByID($db->where('featured', '1')->orderBy('RAND()')->getOne(T_VIDEOS), 1, 1, 0);
if (empty($get_video)) {
    $get_video = PT_GetVideoByID($db->orderBy('id', 'DESC')->getOne(T_VIDEOS), 1, 1, 0);
}

if (empty($get_video)) {
    $pt->content = PT_LoadPage('home/no-content');
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
    $db->where('time', time() - 172800, '>');
    $trending_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, 4);
}

if (empty($trending_data)) {
    $db->where('time', time() - 172800, '>');
    $trending_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, 4);
} 

foreach ($trending_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $trending_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$top_list = '';

if (!empty($pro_users)){
    $db->where('user_id', $pro_users, 'IN');
    $db->orderBy('views', 'DESC');
    $top_data = $db->get(T_VIDEOS, 4);
}

if (empty($top_data)) {
    $top_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($top_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $top_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$latest_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->orderBy('id', 'DESC');
    $latest_data = $db->get(T_VIDEOS, 4);
}

if (empty($latest_data)) {
    $latest_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($latest_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $latest_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_one_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 1);
    $db->orderBy('id', 'DESC');
    $category_one_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_one_data)) {
    $category_one_data = $db->where('category_id', 1)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($category_one_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_one_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_two_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 2);
    $db->orderBy('id', 'DESC');
    $category_two_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_two_data)) {
    $category_two_data = $db->where('category_id', 2)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($category_two_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_two_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_three_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 3);
    $db->orderBy('id', 'DESC');
    $category_three_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_three_data)) {
    $db->where('category_id', 3);
    $category_three_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($category_three_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_three_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_four_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 4);
    $db->orderBy('id', 'DESC');
    $category_four_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_four_data)) {
    $db->where('category_id', 4);
    $category_four_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}

foreach ($category_four_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_four_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_five_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 5);
    $db->orderBy('id', 'DESC');
    $category_five_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_five_data)) {
    $db->where('category_id', 5);
    $category_five_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}


foreach ($category_five_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_five_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_six_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 6);
    $db->orderBy('id', 'DESC');
    $category_six_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_six_data)) {
    $db->where('category_id', 6);
    $category_six_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}


foreach ($category_six_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_six_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_seven_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 7);
    $db->orderBy('id', 'DESC');
    $category_seven_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_seven_data)) {
    $db->where('category_id', 7);
    $category_seven_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}


foreach ($category_seven_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_seven_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_eight_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 8);
    $db->orderBy('id', 'DESC');
    $category_eight_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_eight_data)) {
    $db->where('category_id', 8);
    $category_eight_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}


foreach ($category_eight_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_eight_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}

$category_nine_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('category_id', 9);
    $db->orderBy('id', 'DESC');
    $category_nine_data = $db->get(T_VIDEOS, 4);
}

if (empty($category_nine_data)) {
    $db->where('category_id', 9);
    $category_nine_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
}


foreach ($category_nine_data as $key => $video) {
    $video = PT_GetVideoByID($video, 0, 0, 0);
    $category_nine_list .= PT_LoadPage('home/list', array(
        'ID' => $video->id,
        'TITLE' => $video->title,
        'VIEWS' => $video->views,
        'VIEWS_NUM' => number_format($video->views),
        'USER_DATA' => $video->owner,
        'THUMBNAIL' => $video->thumbnail,
        'URL' => $video->url,
        'TIME' => $video->time_ago,
        'DURATION' => $video->duration
    ));
}
$pt->content = PT_LoadPage('home/content', array(
    'TOP_VIDEOS' => $top_videos_html,
    'ID' => $get_video->id,
    'THUMBNAIL' => $get_video->thumbnail,
    'TITLE' => $get_video->title,
    'DESC' => $get_video->description,
    'URL' => $get_video->url,
    'VIDEO_LOCATION' => $get_video->video_location,
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

    'CATEGORY_ONE_NAME' => $categories[1],
    'CATEGORY_ONE_LIST' => $category_one_list,

    'CATEGORY_TWO_NAME' => $categories[2],
    'CATEGORY_TWO_LIST' => $category_two_list,

    'CATEGORY_THREE_NAME' => $categories[3],
    'CATEGORY_THREE_LIST' => $category_three_list,

    'CATEGORY_FOUR_NAME' => $categories[4],
    'CATEGORY_FOUR_LIST' => $category_four_list,

    'CATEGORY_FIVE_NAME' => $categories[5],
    'CATEGORY_FIVE_LIST' => $category_five_list,

    'CATEGORY_SIX_NAME' => $categories[6],
    'CATEGORY_SIX_LIST' => $category_six_list,

    'CATEGORY_SEVEN_NAME' => $categories[7],
    'CATEGORY_SEVEN_LIST' => $category_seven_list,

    'CATEGORY_EIGHT_NAME' => $categories[8],
    'CATEGORY_EIGHT_LIST' => $category_eight_list,

    'CATEGORY_NINE_NAME' => $categories[9],
    'CATEGORY_NINE_LIST' => $category_nine_list,
));