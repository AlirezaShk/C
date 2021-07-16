<?php

$pt->page        = 'home';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;

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

$user_data = $get_video->owner;


$save_button = '<i class="fa fa-floppy-o fa-fw"></i> ' . $lang->save;
$is_saved = 0;
if (IS_LOGGED == true) {
    $is_saved = $db->where('video_id', $get_video->id)->where('user_id', $user->id)->getValue(T_SAVED, "count(*)");
}
if ($is_saved > 0) {
    $save_button = '<i class="fa fa-check fa-fw"></i> ' . $lang->saved;
}

$trending_list = '';
$trending_data = $db->where('time', time() - 172800, '>')->orderBy('views', 'DESC')->get(T_VIDEOS, 4); 
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
$top_data = $db->orderBy('views', 'DESC')->get(T_VIDEOS, 4);
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
$latest_data = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
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
$category_one_data = $db->where('category_id', 1)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
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
$category_two_data = $db->where('category_id', 2)->get(T_VIDEOS, 4);
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
$category_three_data = $db->where('category_id', 3)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
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
$category_four_data = $db->where('category_id', 4)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
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
$category_five_data = $db->where('category_id', 5)->orderBy('id', 'DESC')->get(T_VIDEOS, 4);
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
));