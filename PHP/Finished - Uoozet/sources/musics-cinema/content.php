<?php 

// pagination system 
$pt->page_number = isset($_GET['page_id']) && is_numeric($_GET['page_id']) && $_GET['page_id'] > 0 ? $_GET['page_id'] : 1;
$pt->limit_per_page = !empty($pt->config->videos_load_limit) && is_numeric($pt->config->videos_load_limit) && $pt->config->videos_load_limit > 0 ? (int) $pt->config->videos_load_limit : 20;
$db->pageLimit = $pt->limit_per_page;
// pagination system 
$final = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_movies_found . '</div>';
if (!empty($_GET['type_']) && $_GET['type_'] != 'all') {
    $db->where('is_movie', $_GET['type_']);
}else{
    $db->where('is_movie', 2);
    $db->orWhere('is_movie', 3);
}
if (!empty($_GET['category_']) && $_GET['category_'] != 'all') {
	$db->where('category_id', PT_Secure($_GET['category_']));
}
if (!empty($_GET['rating'])) {
	$db->where('rating', PT_Secure($_GET['rating']));
}
if (!empty($_GET['release'])) {
	$db->where('movie_release', PT_Secure($_GET['release']));
}
if (!empty($_GET['country'])) {
	$db->where('country', PT_Secure($_GET['country']));
}
if (!empty($_GET['keyword'])) {
	$db->where('title', '%'.PT_Secure($_GET['keyword']).'%','LIKE');
}
$videos = $db->orderBy('id', 'DESC')->objectbuilder()->paginate(T_VIDEOS, $pt->page_number);
$pt->total_pages = $db->totalPages;
if (!empty($videos)) {
	$final = '';
	$len = count($videos);
	foreach ($videos as $key => $video) {
		$video = PT_GetMusicByID($video, 0, 1, 0);
	    $pt->last_video = false;
	    if ($key == $len - 1) {
	        $pt->last_video = true;
	    }
	    $final .= PT_LoadPage('musics/list', array(
			        'ID' => $video->id,
			        'USER_DATA' => $video->owner,
			        'THUMBNAIL' => $video->thumbnail,
			        'URL'       => $video->url,
			        'TITLE'     => $video->title,
			        'DESC'      => $video->markup_description,
			        'VIEWS'     => number_format($video->views),
			        'TIME'      => $video->time_ago,
			        'VIDEO_ID_' => PT_Slug($video->title, $video->video_id),
			        'V_ID'      => $video->video_id,
			        'STARS'     => strlen($video->stars) > 30 ? substr($video->stars, 0,27).'..' : $video->stars,
			        'CAT'       => $pt->movies_categories[$video->category_id],
			        'PRODUCER'  => strlen($video->singer) > 30 ? substr($video->singer, 0,27).'..' : $video->singer,
			        'RATING'    => !empty($video->rating) ? round($video->rating) : 0 ,
			        'COUNTRY'   => !empty($countries_name[$video->country]) ? $countries_name[$video->country] : '',
			        'YEAR'   => !empty($video->music_release) ? $video->music_release : '',
			        'QUALITY'   => !empty($video->quality) ? $video->quality : ''
			    ));
	}
}

$pt->page_url_ = $pt->config->site_url.'/musics';
$pt->page = 'musics';
$pt->videos  = $videos;
$pt->title = $lang->musics . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword = $pt->config->keyword;
$pt->content = PT_LoadPage('musics/content', array('VIDEOS' => $final, 'PUBLISH_YEAR'=> $lang->PUBLISH_YEAR, 'singer'=> $lang->singer));