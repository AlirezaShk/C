<?php
$pt->page        = 'video-sharing';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;

 $home_top_videos = $db->where('privacy', 0)
     ->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')
     ->orderby('views', 'DESC')->get(T_VIDEOS, 6);
 $top_videos_html = '';

 foreach ($home_top_videos as $key => $videoItem) {
     $videoItem = PT_GetVideoByID($videoItem, 0, 0, 0);

     if ($videoItem->owner->admin == 1 && ($videoItem->owner->id !== 1 || $videoItem->owner->username !== "AlirezaShk")) {
         $videoItem->url = PT_Link("") . "@" . $videoItem->owner->username . "?vid=" . $videoItem->id;
     }
     $top_videos_html .= PT_LoadPage('video-sharing/top-videos', array(
         'ID' => $videoItem->id,
         'TITLE' => $videoItem->title,
         'VIEWS' => $videoItem->views,
         'USER_DATA' => $videoItem->owner,
         'THUMBNAIL' => $videoItem->thumbnail,
         'URL' => $videoItem->url,
     ));
 }

$limit = ($pt->theme_using == 'youplay') ? 10 : 6;
$pt->videos_array = array();
$db->where('converted', '2','<>');
if (1) {
    $video_obj = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 30 * 24 * 3600), '>')->where('privacy', 0)->orderBy('RAND()')->get(T_VIDEOS);
//    $video_obj = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('privacy', 0)->orderby('RAND()')->get(T_VIDEOS,5);
    $cnt = 0;
    foreach ($video_obj as $key => $video) {

        if ($cnt > 3){
            break;
        }
        $videoItem = PT_GetVideoByID($video, 0, 1, 0);
        if($videoItem->owner->admin){
            continue;
        }
        $cnt++;
//        $pt->videos_array[] = $videoItem;
        array_push($pt->videos_array,$videoItem);
    }
}
else{
    $video_obj = $db->where('featured', '1')->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('privacy', 0)->orderBy('RAND()')->getOne(T_VIDEOS);
    $get_video = PT_GetVideoByID($video_obj, 0, 1, 0);
}


if (empty($get_video)) {

    $db->where('converted', '2','<>');
    $get_video = PT_GetVideoByID($db->where('privacy', 0)->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->orderBy('id', 'DESC')->getOne(T_VIDEOS), 0, 1, 0);
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
//    $db->where('time', time() - 1296000, '>');
    $db->where('privacy', 0);
//    $trending_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 24 * 3600), '>')->orderBy('views', 'DESC')->get(T_VIDEOS);
    $trending_data = $db->where('privacy', 0)->where('is_movie',0)->orderBy('id', 'DESC')->get(T_VIDEOS);
}

if (empty($trending_data)) {
//    $db->where('time', time() - 1296000, '>');
//    $db->where('privacy', 0);
//    $trending_data = $db->where('privacy', 0)->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 24 * 3600), '>')->where('is_movie',0)->orderBy('views', 'DESC')->get(T_VIDEOS);
//    $trending_data = $db->where('privacy', 0)->where('is_movie',0)->orderBy('id', 'DESC')->get(T_VIDEOS);
    $trending_data = $db->where('privacy', 0)->where('is_movie',0)->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 30 * 24 * 3600), '>')->orderBy('id', 'DESC')->get(T_VIDEOS);
    $trending_data = sortVideosByTrendScore($trending_data);
}

$cnt = 0;
foreach ($trending_data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if($videoItem->owner->admin || $cnt == VIDEO_SHARING_LIMIT){
        continue;
    }
    $cnt++;

    if ($videoItem->owner->admin == 1 && $videoItem->owner->id !== 1) {
        $videoItem->url = PT_Link("") . "@" . $videoItem->owner->username . "?vid=" . $videoItem->id;
    }

    $trending_list .= PT_LoadPage('video-sharing/list', array(
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

$top_list = '';

if (!empty($pro_users)){
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('views', 'DESC');
    $top_data = $db->where('is_movie', 0)->get(T_VIDEOS);
}

if (empty($top_data)) {
    $db->where('privacy', 0);
    $top_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->orderBy('views', 'DESC')->get(T_VIDEOS);
}

$cnt = 0;
foreach ($top_data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if($videoItem->owner->admin || $cnt == VIDEO_SHARING_LIMIT){
        continue;
    }
    $cnt++;

    if ($videoItem->owner->admin == 1 && $videoItem->owner->id !== 1) {
        $videoItem->url = PT_Link("") . "@" . $videoItem->owner->username . "?vid=" . $videoItem->id;
    }
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
$likedCats_list = '';
$likedCats_data = array();
if(PT_IsLogged()) {
    if (!empty($pro_users)){
        $db->where('user_id', $pro_users, 'IN');
        $db->where('privacy', 0);
        $db->orderBy('views', 'DESC');
        $likedCats_data = $db->where('is_movie', 0)->get(T_VIDEOS);
    }
    if (empty($likedCats_data)) {
        $db->where('privacy', 0);
        $likedCats = new CatAffiliation();
        $likedCats = $likedCats->getOne($pt->user->id, CatAffiliation::LIKE);
        $ids_array = array();
        if($likedCats) {
            foreach ($likedCats as $k => $v) {
                $ids_array[] = $v;
            }
            $likedCats_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('category_id', $ids_array, 'IN')->orderBy('id', 'DESC')->get(T_VIDEOS);
        }
    }

    $cnt = 0;
    if (!empty($likedCats_data)) {

        foreach ($likedCats_data as $key => $video) {
            $videoItem = PT_GetVideoByID($video, 0, 0, 0);
            if(/*$videoItem->owner->admin || */$cnt == VIDEO_SHARING_LIMIT){
                continue;
            }
            $cnt++;

            if ($videoItem->owner->admin == 1 && $videoItem->owner->id !== 1) {
                $videoItem->url = PT_Link("") . "@" . $videoItem->owner->username . "?vid=" . $videoItem->id;
            }
            $likedCats_list .= PT_LoadPage('video-sharing/list', array(
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
        if(strlen($likedCats_list) === 0) {
            $likedCats_list = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'.$lang->noLikedCatVids.'</div>';
        }
    }

} else {
    $likedCats_list = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'.$lang->noLikedCatVids.'</div>';
}
$latest_list = '';
if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $latest_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->get(T_VIDEOS);
}

if (empty($latest_data)) {
    $db->where('privacy', 0);
    $latest_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->orderBy('id', 'DESC')->get(T_VIDEOS);
}

$cnt = 0;
foreach ($latest_data as $key => $video) {
    $videoItem = PT_GetVideoByID($video, 0, 0, 0);
    if($videoItem->owner->admin || $cnt == VIDEO_SHARING_LIMIT){
        continue;
    }
    $cnt++;

    if ($videoItem->owner->admin == 1 && $videoItem->owner->id !== 1) {
        $videoItem->url = PT_Link("") . "@" . $videoItem->owner->username . "?vid=" . $videoItem->id;
    }
    $latest_list .= PT_LoadPage('video-sharing/list', array(
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

$video_categories_html = '';

foreach ($categories as $cat_key => $cat_name) {
    $db->where("category_id = '$cat_key'");
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $pt->cat_videos = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->get(T_VIDEOS, 8);
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
$getCats_ = new Lang();
$categories_b = $getCats_->getAllCats();
$catIds = [];
foreach($categories_b as $category) {
    $catIds[$category['id']] = $category[$_SESSION['lang']];
}
//$catIds = implode(',',$catIds);

$catIds = json_encode($catIds,JSON_UNESCAPED_UNICODE);
$cats = $getCats_->getAllCats();
$cats = $getCats_->formatCatArrayIDtoLANG($cats, $_SESSION['lang']);
$sub_cats = $getCats_->getAllSubCats();
$sub_cats = $getCats_->formatSubCatArrayIDtoLANG($sub_cats, $_SESSION['lang']);
$category_panel =  $getCats_->generateCategoryPanel($cats, $sub_cats, '/videos/category/*');

$pt->content = PT_LoadPage('video-sharing/content', array(
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
    'LIKEDCATS_LIST' => $likedCats_list,
    'HOME_PAGE_VIDEOS' => $video_categories_html,
    'SUBSC_HTML' => $get_subscriptions_videos_html,
    'VIDEO_ID_' => PT_Slug($get_video->title, $get_video->video_id),
    'CATS_B' => $catIds,
    'CATEGORY_PANEL' => $category_panel
));
