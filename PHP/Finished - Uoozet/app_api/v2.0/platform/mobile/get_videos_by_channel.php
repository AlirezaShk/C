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

if (empty($_GET['channel_id']) || !is_numeric($_GET['channel_id'])) {
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

	$category    = (!empty($_GET['category'])  && is_numeric($_GET['category']))  ? $_GET['category']  : null;
	$limit    = (!empty($_GET['limit'])  && is_numeric($_GET['limit']))  ? $_GET['limit']  : 10;
	$offset   = (!empty($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : null;
	$channel  = PT_Secure($_GET['channel_id']);
	$table    = T_VIDEOS;

	$page = $_GET['page']? $_GET['page']:1;


	if (!empty($offset)) {
		$db->where('id',$offset,'<');
	}



	$response_data    = array(
        'api_status'  => '200',
        'api_version' => $api_version,
        'data'        => array()
    );

	if(!$category) {
        $videos = $db->objectbuilder()->where('user_id', $channel)->where('is_movie', 0)->orderBy('id', 'DESC')->paginate($table, $page, array('video_id', 'user_id'));
        $short_videos = $db->objectbuilder()->where('user_id', $channel)->where('is_movie', 5)->orderBy('id', 'DESC')->paginate($table, $page, array('video_id', 'user_id'));
    }
	else {
        $videos = $db->objectbuilder()->where('user_id', $channel)->where('is_movie', 0)->where('category_id', $category)->orderBy('id', 'DESC')->paginate($table, $page, array('video_id', 'user_id'));
        $short_videos = $db->objectbuilder()->where('user_id', $channel)->where('is_movie', 5)->where('category_id', $category)->orderBy('id', 'DESC')->paginate($table, $page, array('video_id', 'user_id'));
    }
//
//    $categories  = $db->where('user_id',$channel)->groupBy('category_id')->get($table,1000,array('category_id'));
//
//    foreach ($categories as $item){
//        $response_data['categories'][] = $item->category_id;
//    }
//    exit;

    if ($videos)
        foreach ($videos as $video) {
            $video = PT_GetVideoByID($video->video_id);
            if (!empty($video)) {
                $video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
                $response_data['data']['videos'][] = $video;
            }
        }
    else
        $response_data['data']['videos'] = [];
    if ($short_videos)
        foreach ($short_videos as $video) {
            $video = PT_GetVideoByID($video->video_id);
            if (!empty($video)) {
                $video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
                $response_data['data']['short_videos'][] = $video;
            }
        }
    else
        $response_data['data']['short_videos'] = [];

}
