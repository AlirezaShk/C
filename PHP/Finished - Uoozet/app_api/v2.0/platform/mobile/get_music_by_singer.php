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
if (empty($_GET['singer_id']) || !in_array($_GET['singer_id'], array_keys($singers))) {
	$response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '1',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
    );
}

else{

    if(isset($get_params['music_type']) && $get_params['music_type'] == 'podcast')
        $music_type = 3;
    else
        $music_type = 2;

	$response_data       = array(
        'api_status'     => '200',
        'api_version'    => $api_version,
        'data'           => array()
    );

	$offset = (!empty($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : null;
	$limit  = (!empty($_GET['limit'])  && is_numeric($_GET['limit']))  ? $_GET['limit']  : 20;
	$singerid  = PT_Secure($_GET['singer_id']);

	if (!empty($offset)) {
		$db->where('id', $offset, '<');
	}

	$videos = $db->where('singer',$singerid)->where('is_movie', $music_type)->orderBy('id','DESC')->get(T_VIDEOS,$limit,array('video_id','user_id'));

	foreach ($videos as $video) {
		$video        = PT_GetMusicByID($video->video_id);
		$video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
		$response_data['data'][] = removeForAPI($video);
	}
}



