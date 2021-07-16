<?php
if (empty($_GET['id'])) {
    header("Location: " . PT_Link(''));
    exit();
}
$username = PT_Secure($_GET['id']);
$user_id  = $db->where('username', $username)->getOne(T_USERS);

$lists    = false;
if (empty($user_id)) {
    header("Location: " . PT_Link(''));
    exit();
}
$pt->second_page = 'videos';
if (!empty($_GET['page'])) {
    switch ($_GET['page']) {
        case 'liked-videos':
            $pt->second_page = 'liked-videos';
            break;
        case 'about':
            $pt->second_page = 'about';
            break;
        case 'play-lists':
            $pt->second_page = 'play-lists';
            $lists           = true;
            break;

    }
}
$user_data   = PT_UserData($user_id, array(
    'data' => true
));
$pt->isowner = false;
if (IS_LOGGED == true) {
    if ($user_data->id == $user->id) {
        $pt->isowner = true;
    }
}
$videos_html = '';
$videos_count = 0;
$get_video_query = 1;
if ($pt->second_page == 'videos') {
	$videos = $db->where('user_id', $user_data->id)->orderBy('id', 'DESC')->get(T_VIDEOS, 20, 'video_id');
}
if ($pt->second_page == 'liked-videos') {
    $videos = $db->where('user_id', $user_data->id)->where('type', 1)->orderBy('id', 'DESC')->get(T_DIS_LIKES, 20);
    $get_video_query = 2;
}
if ($pt->second_page == 'play-lists') {
    if ($pt->isowner === true) {
        $playlists = $db->where('user_id', $user_data->id)->get(T_LISTS);
    }
    else{
      $playlists   =  $db->where('user_id', $user_data->id)->where('privacy', 1)->get(T_LISTS);  
    }
	
}
// echo "<pre>";
// print_r($videos);
// exit();
if (!empty($videos) && !$lists) {
	$videos_count = count($videos);
	foreach ($videos as $key => $video) {
		$video_get = PT_GetVideoByID($video->video_id, 0, 0, $get_video_query);
        $video_id  = $video_get->id;
		if ($get_video_query == 2) {
			$video_id = $video->id;
		}
		$videos_html .= PT_LoadPage('videos/list', array(
            'ID' => $video_id,
            'VID_ID' => $video_get->id,
	        'TITLE' => $video_get->title,
	        'VIEWS' => $video_get->views,
            'VIEWS_NUM' => number_format($video_get->views),
	        'USER_DATA' => $video_get->owner,
	        'THUMBNAIL' => $video_get->thumbnail,
	        'URL' => $video_get->url,
	        'TIME' => $video_get->time_ago,
            'DURATION' => $video_get->duration
        ));
	}
}
elseif(!empty($playlists) && $lists){

    foreach ($playlists as $key => $list) {
        $list_id       = $list->list_id;
        $video         = $db->where('list_id', $list->list_id)->orderBy('id', 'asc')->getOne(T_PLAYLISTS);
        if (isset($video->video_id)) {
            $video_get = $db->where('id', $video->video_id)->getOne(T_VIDEOS);
            $vid_count = $db->where('user_id', $user_id->id)->where('list_id', $list_id)->getValue(T_PLAYLISTS, 'count(*)');
            if (!empty($video_get)) {
                $videos_html .= PT_LoadPage('playlist/list', array(
                    'ID' => $list->id,
                    'TITLE' => $list->name,
                    'VIEWS' => $list->views,
                    'VIEWS_NUM' => number_format($video_get->views),
                    'THUMBNAIL' => $video_get->thumbnail,
                    'COUNT' => $vid_count,
                    'URL' => PT_Link('watch/' . PT_Slug($video_get->title, $video_get->video_id) . "/list/$list_id"),
                ));
            }
        }
    }
}




if(empty($videos_html)){
	$videos_html = '<div class="text-center no-content-found">' . $lang->no_videos_found_for_now . '</div>';
}


$pt->profile_user = $user_data;
$pt->videos_count = $videos_count;
$pt->page        = 'timeline';
$pt->title       = $user_data->name . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = $pt->config->keyword;
$pt->content     = PT_LoadPage('timeline/content', array(
    'USER_DATA' => $user_data,
    'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
    'SECOND_PAGE' => PT_LoadPage('timeline/pages/' . $pt->second_page, array(
        'VIDEOS' => $videos_html,
        'USER_DATA' => $user_data
    ))
));