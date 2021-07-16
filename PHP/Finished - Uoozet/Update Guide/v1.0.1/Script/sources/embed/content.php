<?php 
if ($pt->config->embed_system == 'off') {
    exit('Embed is disabled');
}
if (empty($_GET['id'])) {
   exit('Invalid URL');
}

$id = PT_Secure($_GET['id']);

$get_video = $db->where('video_id', $id)->getOne(T_VIDEOS);
if (empty($get_video)) {
	exit('Video not found');
}
if (strpos($get_video->thumbnail, 'upload/photos') !== false) {
    $get_video->thumbnail      = PT_GetMedia($get_video->thumbnail);
    $get_video->video_location = PT_GetMedia($get_video->video_location);
    $video_type                = 'video/mp4';
    $video_id_                 = $get_video->video_id;
}
if (!empty($get_video->youtube)) {
    $video_type                = 'video/youtube';
    $get_video->video_location = 'https://www.youtube.com/watch?v=' . $get_video->youtube;
    $video_id_                 = $get_video->youtube;
}
if (!empty($get_video->daily)) {
    $video_type = 'video/dailymotion';
    $video_id_  = $get_video->daily;
}
if (!empty($get_video->vimeo)) {
    $video_type = 'video/vimeo';
    $video_id_  = $get_video->vimeo;
}
$pt->get_video   = $get_video;
echo PT_LoadPage('embed/content', array('ID' => $get_video->id,
    'THUMBNAIL' => $get_video->thumbnail,
    'TITLE' => $get_video->title,
    'DESC' => $get_video->description,
    'URL' => PT_Link('watch/' . PT_Slug($get_video->title, $get_video->video_id)),
    'VIDEO_LOCATION' => $get_video->video_location,
    'VIDEO_TYPE' => $video_type,
    'VIDEO_ID' => $video_id_));
exit();
?>