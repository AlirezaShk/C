<?php
$pt->page        = 'shared-media';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;

if (empty($get_video)) {

    $db->where('converted', '2','<>');
    $get_video = PT_GetVideoByID($db->where('privacy', 0)->where('is_movie', 0)->orderBy('id', 'DESC')->getOne(T_VIDEOS), 0, 1, 0);
//    print_r($get_video);
    if ($pt->theme_using == 'default') {
        $pt->videos_array[] = $get_video;
    }
}


if (empty($get_video)) {
    $pt->content = PT_LoadPage('shared-media/no-content');
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

$latest_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $latest_data = $db->where('is_movie', 2)->get(T_VIDEOS, $limit);
}

if (empty($latest_data)) {
    $db->where('privacy', 0);
    $latest_data = $db->where('is_movie', 2)->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
}

$shared_media = $db->where('to_user_id', $user->id)->orderBy('id', 'DESC')->get(T_SHAREDMEDIA, 10);

foreach ($shared_media as $key => $v) {
    if($v->type == 0){
        $userSharer = PT_UserData($v->from_user_id);
        $video = PT_GetMediaByID($v->media_id, 0, 0, 2);
        $latest_list .= PT_LoadPage('shared-media/list', array(
            'ID' => $video->id,
            'TITLE' => $video->title,
            'SINGERID' => $video->singer,
            'SINGER' => $video->singer_name,
            'ALBOUMID' => $video->country,
            'ALBOUM' =>$video->alboum_name,
            'VIEWS' => $video->views,
            'VIEWS_NUM' => number_format($video->views),
            'USER_DATA' => $video->owner,
            'THUMBNAIL' => $video->thumbnail,
            'URL' => $video->url,
            'TYPE_MEDIA'=>$video->is_movie < 2? 'watch':'listen',
            'TIME' => $video->time_ago,
            'DURATION' => $video->duration,
            'VIDEO_ID' => $video->video_id_,
            'VIDEO_ID_' => PT_Slug($video->title, $video->video_id),
            'SHARER_USERNAME'=>$userSharer->username,
            'SHARER_URL'=>$userSharer->url
        ));
    }else{
        $post_id = PT_GetIdFromURL($v->media_id);
        $pt->user_article = $db->where('id',$post_id)->getOne(T_POSTS);
        $db->where('id',$post_id);
        $article = $db->getOne(T_POSTS);


        $userSharer = PT_UserData($v->from_user_id);
        $slug       = PT_URLSlug($article->title,$article->id);
        $latest_list .= PT_LoadPage('shared-media/article-list', array(
            'ID' => $article->id,
            'TITLE' => $article->title,
            'VIEWS' => $article->views,
            'VIEWS_NUM' => number_format($article->views),
            'THUMBNAIL' => $article->image,
            'URL' => PT_Link("articles/read/$slug"),
            'SHARER_USERNAME'=>$userSharer->username,
            'SHARER_URL'=>$userSharer->url
        ));
    }
}


$pt->content = PT_LoadPage('shared-media/content', array(
    'ID' => $get_video->id,
    'THUMBNAIL' => $get_video->thumbnail,
    'DURATION' => $get_video->duration,
    'TITLE' => $get_video->title,
    'DESC' => $get_video->markup_description,
    'URL' => $get_video->url,
    'SINGERID' => $get_video->singer,
    'SINGER' => $get_video->singer_name,
    'ALBOUMID' => $get_video->country,
    'ALBOUM' =>$get_video->alboum_name,
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
    'TOP_LIST_SINGERS' => $top_list_singers,
    'LATEST_LIST' => $latest_list,
    'HOME_PAGE_VIDEOS' => $video_categories_html,
    'SUBSC_HTML' => $get_subscriptions_videos_html,
    'VIDEO_ID_' => PT_Slug($get_video->title, $get_video->video_id)
));
