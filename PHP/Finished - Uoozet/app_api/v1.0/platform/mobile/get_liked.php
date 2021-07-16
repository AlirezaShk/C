<?php

if (!IS_LOGGED) {

	$response_data    = array(
	    'api_status'  => '400',
	    'api_version' => $api_version,
	    'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
	);
}
else{

	$user_id   = $user->id;
	$limit = (!empty($_POST['limit']) && is_numeric($_POST['limit']) && $_POST['limit'] > 0 && $_POST['limit'] <= 50) ? PT_Secure($_POST['limit']) : 20;
	$offset = (!empty($_POST['offset']) && is_numeric($_POST['offset']) && $_POST['offset'] > 0) ? PT_Secure($_POST['offset']) : 0;

	$get_history_videos = array();	
	if (!empty($offset)) {
		$offset_query = " AND id < {$offset} ";
	}

	$get = $db->rawQuery("SELECT * FROM ".T_DIS_LIKES." WHERE user_id = {$user_id} AND type = 1 {$offset_query} ORDER BY id DESC LIMIT {$limit}");

	$typeMedia = $_POST['mediaType'];
	if(empty($typeMedia))
	    $typeMedia = 'video';

	if (!empty($get)) {
	    foreach ($get as $key => $video_) {
	       $fetched_video = $db->where('id', $video_->video_id)->getOne(T_VIDEOS);
	       $video = PT_GetVideoByID($fetched_video, 0, 1, 0);
	       if(!empty($video)){
	           if($typeMedia == 'video' && $video->is_movie != 0)continue;
	           if($typeMedia == 'movie' && $video->is_movie != 1)continue;
	           if($typeMedia == 'music' && $video->is_movie != 2 && $video->is_movie != 3)continue;else $video = PT_GetMusicByID($fetched_video, 0, 1, 0);
		       $video->like_id = $video_->id;
		       $get_history_videos[] = $video;
	       }
	    }
	}
	$response_data     = array(
	    'api_status'   => '200',
	    'api_version'  => $api_version,
	    'success_type' => 'get_liked',
	    'data'      => $get_history_videos
	);
}