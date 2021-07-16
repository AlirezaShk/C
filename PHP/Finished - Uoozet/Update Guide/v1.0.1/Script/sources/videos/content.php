<?php
if (empty($_GET['page'])) {
    header("Location: " . PT_Link('404'));
    exit();
}
$page  = PT_Secure($_GET['page']);
$pages = array(
    'trending',
    'category',
    'latest',
    'top'
);
if (!in_array($page, $pages)) {
    header("Location: " . PT_Link('404'));
    exit();
}
$cateogry_id = '';
$videos = array();
if ($page == 'trending') {
    $title  = $lang->trending;
    $videos = $db->where('time', time() - 172800, '>')->orderBy('views', 'DESC')->get(T_VIDEOS, 20);
} else if ($page == 'latest') {
    $title  = $lang->latest_videos;
    $videos = $db->orderBy('id', 'DESC')->get(T_VIDEOS, 20);
} else if ($page == 'top') {
    $title  = $lang->top_videos;
    $videos = $db->orderBy('views', 'DESC')->get(T_VIDEOS, 20);
} else if ($page == 'category') {
    if (!empty($_GET['id'])) {
        if (in_array($_GET['id'], array_keys($categories))) {
            $cateogry = PT_Secure($_GET['id']);
            $title    = $categories[$cateogry];
            $cateogry_id = "data-category='$cateogry'";
            $videos   = $db->where('category_id', $cateogry)->orderBy('id', 'DESC')->get(T_VIDEOS, 20);
        }
    }
}

$html_videos = '';
if (!empty($videos)) {
    foreach ($videos as $key => $video) {
    	$video = PT_GetVideoByID($video, 0, 0, 0);
        $html_videos .= PT_LoadPage('videos/list', array(
            'ID' => $video->id,
            'VID_ID' => $video->id,
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
}

if (empty($videos)) {
	$html_videos = '<div class="text-center no-content-found">' . $lang->no_videos_found_for_now . '</div>';
}
$pt->videos_count= count($videos);
$pt->page        = $page;
$pt->title       = $title . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('videos/content', array(
    'TITLE' => $title,
    'VIDEOS' => $html_videos,
    'TYPE' => $page,
    'CATEGORY_ID' => $cateogry_id
));
