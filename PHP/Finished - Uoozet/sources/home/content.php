<?php
//error_reporting(E_ALL);
if(!IS_LOGGED) {
    header('location: ' . PT_Link('intro'));
    exit;
}
$pt->page         = 'home';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;

$vids = new Video();
$count_array = [
    'subs_vids' => 0,
    'liked_vids' => 0,
    'history_vids' => 0,
    'shared' => 0,
    'saved_vids' => 0
];
/* SUBSCRIPTIONS: */
$no_result = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_subs . '</div>';
$subs = new Subscriptions();
$user_subs = $subs->getOne($user->id)[0];
$subs_content = [
    Subscriptions::SUB_TYPE_TAGS => '',
    Subscriptions::SUB_TYPE_SERIES => '',
    Subscriptions::SUB_TYPE_CHANNELS => '',
    Subscriptions::SUB_TYPE_LISTS => '',
    Subscriptions::SUB_TYPE_USERS => ''
];
foreach ($user_subs as $subType => $v) {
    if (!key_exists($subType, $subs_content)) continue;
    $targets = json_decode($v);
    $cond = [];
    foreach ($targets as $target) {
        $cond[] = explode(":", $target)[0];
    }
    if(count($cond) === 0) continue;
    switch ($subType) {
        case Subscriptions::SUB_TYPE_USERS:
            $vids->setBinary_conditions(['user_id'=>$cond, 'is_channel'=>0]);
            break;
        case Subscriptions::SUB_TYPE_CHANNELS:
            $vids->setBinary_conditions(['user_id'=>$cond, 'is_channel'=>1]);
            break;
        case Subscriptions::SUB_TYPE_LISTS:
            $subs_content[$subType] = $no_result;
            break;
        case Subscriptions::SUB_TYPE_SERIES:
            $subs_content[$subType] = $no_result;
            break;
        case Subscriptions::SUB_TYPE_TAGS:
            foreach ($cond as $k_ => $v_) $cond[$k_] = '%' . $vids->formatTags($v_) . '%';
            $vids->setBinary_conditions(['is_channel'=>0]);
            $vids->setLike_conditions(['tags'=>$cond]);
            break;
        default:
            continue;
    }
    if(!empty($subs_content[$subType])) continue;
    $vids->setCountLimit(4);
    $res_vids = $vids->getMatches();
    $count_array['subs_vids'] += ($len = count($res_vids));
    if ($len === 0) $subs_content[$subType] = $no_result;
    else {
        $subs_content[$subType] = printVids($res_vids);
    }
}
/* END SUBSCRIPTIONS */
/* SAVED VIDEOS: */
$no_result = '<div class="text-center no-content-found">' . $lang->no_videos_found_history . '</div>';
$saved_vids = [];
$saved_content = "";
$get = $db->where('user_id', $user->id)->orderby('id', 'DESC')->get(T_SAVED);
if (!empty($get)) {
    foreach ($get as $key => $video_) {
        $vids->setCountLimit(4);
        $vids->setBinary_conditions(['id'=>$video_->video_id]);
        $fetched_video = $vids->getMatches();
        $fetched_video->history_id = $video_->id;
        $saved_vids[] = $fetched_video;
    }
}
if (!empty($saved_vids)) {
    $count_array['saved_vids'] += count($saved_vids);
    $saved_content = printVids($saved_vids);
}
if (empty($saved_content)) {
    $saved_content = $no_result;
}
/* END SAVED VIDEOS */
/* HISTORY: */
$no_result = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_history . '</div>';
$history_vids = [];
$history_content = "";
$get = $db->where('user_id', $user->id)->orderby('id', 'DESC')->get(T_HISTORY);
if (!empty($get)) {
    foreach ($get as $key => $video_) {
        $vids->setBinary_conditions(['id'=>$video_->video_id]);
        $vids->setCountLimit(4);
        $fetched_video = $vids->getMatches(Video::RETURN_OBJECT);
        $fetched_video->history_id = $video_->id;
        $history_vids[] = $fetched_video;
    }
}
if (!empty($history_vids)) {
    $count_array['history_vids'] += count($history_vids);
    $history_content = printVids($history_vids);
}
if (empty($history_content)) {
    $history_content = $no_result;
}
/* END HISTORY */
/* LIKED VIDS: */
$no_result = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_liked . '</div>';
$liked_vids = [];
$liked_content = "";
$get = $db->where('user_id', $user->id)->where('type', 1)->orderby('id', 'DESC')->get(T_DIS_LIKES, 20);
if (!empty($get)) {
    foreach ($get as $key => $video_) {
        $vids->setCountLimit(4);
        $vids->setBinary_conditions(['id'=>$video_->video_id]);
        $fetched_video = $vids->getMatches(Video::RETURN_OBJECT);
        $fetched_video->history_id = $video_->id;
        $liked_vids[] = $fetched_video;
    }
}
if (!empty($liked_vids)) {
    $count_array['liked_vids'] += count($liked_vids);
    $liked_content = printVids($liked_vids);
}
if (empty($liked_content)) {
    $liked_content = $no_result;
}
/* END LIKED VIDS */
/* SHARED MEDIA: */
$no_result = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_liked . '</div>';
$shared_content = "";
$shared_media = $db->where('to_user_id', $user->id)->orderBy('id', 'DESC')->get(T_SHAREDMEDIA, 4);
foreach ($shared_media as $key => $v) {
    $count_array['shared']++;
    if($v->type == 0){
        $userSharer = PT_UserData($v->from_user_id);
        $video = PT_GetMediaByID($v->media_id, 0, 0, 2);
        $shared_content .= PT_LoadPage('shared-media/list', array(
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
        $shared_content .= PT_LoadPage('shared-media/article-list', array(
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
/* END SHARED MEDIA */
$pt->content = PT_LoadPage('home/content', array(
    'HISTORY_LIST' => $history_content,
    'LIKED_LIST' => $liked_content,
    'SAVED_LIST' => $saved_content,
    'SHARED_LIST' => $shared_content,
    'SUBSCRIPTION_LIST' => implode('', $subs_content),
));
function printVids($vids) {
    global $pt;
    $len = count($vids);
    $result = "";
    foreach ($vids as $key => $video) {
        $video = PT_GetVideoByID($video->id, 0, 0, 2);
        $pt->last_video = false;
        if ($key == $len - 1) {
            $pt->last_video = true;
        }
        $result .= PT_LoadPage('history/list', array(
            'ID' => $video->history_id,
            'USER_DATA' => $video->owner,
            'THUMBNAIL' => $video->thumbnail,
            'URL' => $video->url,
            'TITLE' => $video->title,
            'DESC' => $video->markup_description,
            'VIEWS' => $video->views,
            'TIME' => $video->time_ago,
            'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
        ));
    }
    return $result;
}
//$pt->content     =