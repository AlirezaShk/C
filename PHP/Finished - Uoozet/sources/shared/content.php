<?php
if (empty($_GET['page'])) {
    header("Location: " . PT_Link('404'));
    exit();
}

$page         = PT_Secure($_GET['page']);
$limit        = 20;
$pt->rss_feed = false;
$pt->exp_feed = true;
$pages        = array(
    'media',
);

if (!in_array($page, $pages)) {
    header("Location: " . PT_Link('404'));
    exit();
}
// pagination system 
$pt->page_number = isset($_GET['page_id']) && is_numeric($_GET['page_id']) && $_GET['page_id'] > 0 ? $_GET['page_id'] : 1;
$pt->limit_per_page = !empty($pt->config->videos_load_limit) && is_numeric($pt->config->videos_load_limit) && $pt->config->videos_load_limit > 0 ? (int) $pt->config->videos_load_limit : 20;
$db->pageLimit = $pt->limit_per_page;
// pagination system 

$pt->page_url_ = $pt->config->site_url.'/musics/'.$page.'?page_id='.$pt->page_number;
$cateogry_id = '';
$videos = array();
if ($page == 'media') {
    $title  = $lang->shared_media;
    // $db->where('privacy', 0);
    // $videos = $db->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);

    // pagination system 
    $videos = $db->where('privacy', 0)->where('to_user_id',$user->id)->orderBy('id', 'DESC')->objectbuilder()->paginate(T_SHAREDMEDIA, $pt->page_number);
    $pt->total_pages = $db->totalPages;
    // pagination system 
}

$html_videos = '';
if (!empty($videos)) {
    foreach ($videos as $key => $video) {
    	$video = PT_GetMusicByID($video, 0, 0, 0);
        $html_videos .= PT_LoadPage('musics/list', array(
            'ID' => $video->id,
            'VID_ID' => $video->id,
	        'TITLE' => $video->title,
	        'SINGER' => $video->singer_name,
	        'ALBOUMID' => $video->country,
	        'ALBOUM' => $video->alboum_name != ''? ' | '.$video->alboum_name: '',
	        'SINGERID' => $video->singer,
	        'VIEWS' => $video->views,
            'VIEWS_NUM' => number_format($video->views),
	        'USER_DATA' => $video->owner,
	        'THUMBNAIL' => $video->thumbnail,
	        'URL' => $video->url,
	        'TIME' => $video->time_ago,
            'DURATION' => $video->duration,
            'VIDEO_ID_' => PT_Slug($video->title, $video->video_id)
        ));
    }
}

if (empty($videos)) {
	$html_videos = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_media_found_for_now . '</div>';
}
$pt->videos_count= count($videos);
$pt->page        = $page;
$pt->title       = $title . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('musics/content', array(
    'TITLE' => $title,
    'VIDEOS' => $html_videos,
    'TYPE' => $page,
    'CATEGORY_ID' => $cateogry_id
));
