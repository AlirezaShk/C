<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+

$table                = T_VIDEOS;
$response_data        = array(
    'api_status'      => '200',
    'api_version'     => $api_version,
    'data'            => array(
    )
);

$get_params           = array(
	'media_type' => null,
	'sort'      => null,
	'page'   => null,
    'music_type' =>  null
);

foreach ($get_params as $key => $value) {
	if (!empty($_GET[$key])) {
		$get_params[$key] = $_GET[$key];
	}
}


if(isset($get_params['music_type']) && $get_params['music_type'] == 'podcast')
    $music_type = 3;
else
    $music_type = 2;


#Home Page Top Videos
$limit = ((!empty($get_params['limit'])) ? $get_params['limit'] : 6);
if($get_params['media_type'] == 'music' && $get_params['sort'] == 'top' && !empty($get_params['page'])){
    $top   = $db->objectbuilder()->where('is_movie', $music_type, '=')->orderby('views', 'DESC')->paginate(T_VIDEOS, $get_params['page'],array('video_id','user_id'));
}
else
    $top   = $db->where('is_movie', $music_type, '=')->orderby('views', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));
foreach ($top as $video) {
	$video = PT_GetMusicByID($video->video_id, 0, 1);
	if (!empty($video)) {
		$video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
		$response_data['data']['top_musics'][] = removeForAPI($video);
	}
}
if($get_params['media_type'] == 'music' && $get_params['sort'] == 'top' && !empty($get_params['page']))
    return;




#Home Page Latest Videos
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
if($get_params['media_type'] == 'music' && $get_params['sort'] == 'latest' && !empty($get_params['page']))
    $latest = $db->objectbuilder()->where('is_movie', $music_type, '=')->orderby('id', 'DESC')->paginate(T_VIDEOS, $get_params['page'],array('video_id','user_id'));
else
    $latest = $db->where('is_movie', $music_type, '=')->orderby('id', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));

foreach ($latest as $video) {
	$video = PT_GetMusicByID($video->video_id, 0, 1);
	if (!empty($video)) {
		$video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
		$response_data['data']['latest_musics'][] = removeForAPI($video);
	}
}
if($get_params['media_type'] == 'music' && $get_params['sort'] == 'latest' && !empty($get_params['page'])){
    unset($response_data['data']['top_musics']);
    return;
}

#Home Page Latest singers
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
if($get_params['media_type'] == 'singer' && $get_params['sort'] == 'latest' && !empty($get_params['page']))
    $latest = $db->objectbuilder()->orderby('id', 'DESC')->paginate(T_SINGERS, $get_params['page'],array('id','name', 'image'));
else
    $latest = $db->orderby('id', 'DESC')->get(T_SINGERS, $limit,array('id','name', 'image'));

foreach ($latest as $video) {
    $video->image = PT_GetMedia($video->image);
    $response_data['data']['latest_singers'][] = $video;
}
if($get_params['media_type'] == 'singer' && $get_params['sort'] == 'latest' && !empty($get_params['page'])){
    unset($response_data['data']['latest_musics']);
    unset($response_data['data']['top_musics']);
    return;
}

#Home Page Top singers
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
if($get_params['media_type'] == 'singer' && $get_params['sort'] == 'top' && !empty($get_params['page']))
    $latest = $db->objectbuilder()->orderby('id', 'DESC')->paginate(T_SINGERS, $get_params['page'],array('id','name', 'image'));
else
    $latest = $db->orderby('id', 'DESC')->get(T_SINGERS, $limit,array('id','name', 'image'));
foreach ($latest as $video) {
    $video->image = PT_GetMedia($video->image);
    $response_data['data']['top_singers'][] = $video;
}
if($get_params['media_type'] == 'singer' && $get_params['sort'] == 'top' && !empty($get_params['page'])){
    unset($response_data['data']['latest_singers']);
    unset($response_data['data']['latest_musics']);
    unset($response_data['data']['top_musics']);
    return;
}


#Home Page Latest alboums
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
if($get_params['media_type'] == 'album' && $get_params['sort'] == 'latest' && !empty($get_params['page']))
    $latest = $db->objectbuilder()->where('type','alboum')->orderby('id', 'DESC')->paginate(T_LANGS, $get_params['page'],array('id','farsi'));
else
    $latest = $db->where('type','alboum')->orderby('id', 'DESC')->get(T_LANGS, $limit,array('id','farsi'));
foreach ($latest as $video) {
    $video->image = null;
    $video->name = $video->farsi;
    unset($video->farsi);
    $response_data['data']['latest_albums'][] = $video;
}
if($get_params['media_type'] == 'album' && $get_params['sort'] == 'latest' && !empty($get_params['page']))
{
    unset($response_data['data']['latest_singers']);
    unset($response_data['data']['top_singers']);
    unset($response_data['data']['latest_musics']);
    unset($response_data['data']['top_musics']);
    return;
}
#Home Page top alboums
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
if($get_params['media_type'] == 'album' && $get_params['sort'] == 'top' && !empty($get_params['page']))
    $latest = $db->objectbuilder()->where('type','alboum')->orderby('id', 'DESC')->paginate(T_LANGS, $get_params['page'],array('id','farsi'));
else
    $latest = $db->where('type','alboum')->orderby('id', 'DESC')->get(T_LANGS, $limit,array('id','farsi'));
foreach ($latest as $video) {
    $video->image = null;
    $video->name = $video->farsi;
    unset($video->farsi);
    $response_data['data']['top_albums'][] = $video;
}

if($get_params['media_type'] == 'album' && $get_params['sort'] == 'top' && !empty($get_params['page']))
{
    unset($response_data['data']['latest_albums']);
    unset($response_data['data']['latest_singers']);
    unset($response_data['data']['top_singers']);
    unset($response_data['data']['latest_musics']);
    unset($response_data['data']['top_musics']);
    return;
}

if($music_type == 2)
    $categories = $db->where('type', 'music_category')->get(T_LANGS, 500, array('lang_key', 'farsi'));
else
    $categories = $db->where('type', 'radio_category')->get(T_LANGS, 500, array('lang_key', 'farsi'));
foreach ($categories as $key=>$cat) {
    $cat->name = $cat->farsi;
    $cat->id = $cat->lang_key;
    unset($cat->farsi);
    unset($cat->lang_key);
    $response_data['data']['categories'][] = $cat;
}
