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
/*  TODO: add LIKED_CATS
 *  CURRENT VERSION: Featured (=> Trending), Top, Latest
 */
$table                = T_VIDEOS;
$response_data        = array(
    'api_status'      => '200',
    'api_version'     => $api_version,
    'data'            => array(
        'featured'    => array(),
        'top'         => array(),
        'latest'      => array(),
    )
);

$get_params           = array(
    'featured_offset' => 0,
    'top_offset'      => 0,
    'latest_offset'   => 0,
    'limit'           => 20
);

foreach ($get_params as $key => $value) {
    if (!empty($_GET[$key]) && is_numeric($_GET[$key])) {
        $get_params[$key] = $_GET[$key];
    }
}




# Home Page Featured Videos

// ================= OLD API ================= //
//if (!empty($get_params['featured_offset'])) {
//	$db->where('id', $get_params['featured_offset'],'<');
//}
//
//$db->where('featured', '1')->orderBy('RAND()');
//$featured = array();
$limit    = ((!empty($get_params['limit'])) ? $get_params['limit'] : 20);
//$featured = $db->get($table,$limit,array('video_id','user_id'));
//
//if (empty($featured)) {
//	if (!empty($get_params['featured_offset'])) {
//		$db->where('id', $get_params['featured_offset'],'<');
//	}
//    $featured = $db->where('is_movie', 1, '!=')->where('is_movie', 2, '!=')->where('is_movie', 3, '!=')->orderBy('id', 'DESC')->get(T_VIDEOS,$limit,array('video_id','user_id'));
//}
// ================= END OF OLD API ================= //

if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
//    $db->where('time', time() - 1296000, '>');
    $db->where('privacy', 0);
//    $trending_data = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 24 * 3600), '>')->orderBy('views', 'DESC')->get(T_VIDEOS);
    $trending = $db->where('privacy', 0)->where('is_movie',0)->where('is_channel', 1, '!=')->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
    $trending = sortVideosByTrendScore($trending);
}

if (empty($trending)) {
//    $db->where('time', time() - 1296000, '>');
//    $db->where('privacy', 0);
//    $trending_data = $db->where('privacy', 0)->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 24 * 3600), '>')->where('is_movie',0)->orderBy('views', 'DESC')->get(T_VIDEOS);
//    $trending_data = $db->where('privacy', 0)->where('is_movie',0)->orderBy('id', 'DESC')->get(T_VIDEOS);
    $trending = $db->where('privacy', 0)->where('is_movie',0)->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 30 * 24 * 3600), '>')->where('is_channel', 1, '!=')->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
    $trending = sortVideosByTrendScore($trending);
}
$cnt = 0;
foreach ($trending as $video) {
    $videoItem = PT_GetVideoByID($video->video_id);
    if($videoItem->owner->admin == 1 || $cnt == $limit){
        continue;
    } else {
        $cnt++;
    }
    if (!empty($videoItem)) {
//        unset($videoItem->owner);
        $videoItem->owner = array_intersect_key(ToArray($videoItem->owner), array_flip($user_public_data));
//		$videoItem->title = $videoItem;
        $response_data['data']['featured'][] = $videoItem;
    }
}

#Home Page Top Videos

// ================= OLD API ================= //
//if (!empty($get_params['top_offset'])) {
//	$db->where('id', $get_params['top_offset'],'<');
//}
//
$limit = ((!empty($get_params['limit'])) ? $get_params['limit'] : 6);
//$top   = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->orderby('views', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));
// ================= END OF OLD API ================= //

if (!empty($pro_users)){
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('views', 'DESC');
    $top = $db->where('is_movie', 0)->where('is_channel', 1, '!=')->get(T_VIDEOS, $limit,array('video_id','user_id'));
}

if (empty($top)) {
    $db->where('privacy', 0);
    $top = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('is_channel', 1, '!=')->orderBy('views', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));
}
$cnt = 0;
foreach ($top as $video) {
    $videoItem = PT_GetVideoByID($video->video_id, 0, 0, 1);
    if(($videoItem->owner->admin == 1) || ($cnt == $limit)){
        continue;
    } else {
        $cnt++;
    }
    if (!empty($videoItem)) {
//        unset($videoItem->owner);
        $videoItem->owner = array_intersect_key(ToArray($videoItem->owner), array_flip($user_public_data));
//		$videoItem->title = $videoItem;
        $response_data['data']['top'][] = $videoItem;
    }
}

$db->where('id', $pt->user->id)->update(T_USERS, array('last_active' => time()));


#Home Page Latest Videos

// ================= OLD API ================= //
//if (!empty($get_params['latest_offset'])) {
//	$db->where('id', $get_params['latest_offset'],'<');
//}
//
$limit  = ((!empty($get_params['limit'])) ? $get_params['limit'] : 10);
//$latest = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->orderby('id', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));
// ================= END OF OLD API ================= //

if (!empty($pro_users)) {
    $db->where('user_id', $pro_users, 'IN');
    $db->where('privacy', 0);
    $db->orderBy('id', 'DESC');
    $latest = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('is_channel', 1, '!=')->get(T_VIDEOS, $limit, array('video_id','user_id'));
}

if (empty($latest_data)) {
    $db->where('privacy', 0);
    $latest = $db->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('is_channel', 1, '!=')->orderBy('id', 'DESC')->get(T_VIDEOS, $limit,array('video_id','user_id'));
}
$cnt = 0;
foreach ($latest as $video) {
    $videoItem = PT_GetVideoByID($video->video_id, 0, 0, 1);
    if(($videoItem->owner->admin == 1) || ($cnt == $limit)){
        continue;
    } else {
        $cnt++;
    }
    if (!empty($videoItem)) {
//        unset($videoItem->owner);
        $videoItem->owner = array_intersect_key(ToArray($videoItem->owner), array_flip($user_public_data));
//		$videoItem->title = $videoItem;
        $response_data['data']['latest'][] = $videoItem;
    }
}

$response_data['data'] = utf8ize($response_data['data']);
//var_dump($response_data);exit;

//var_dump($response_data);exit;

//top home videos

if (1) {
//    $db->where('time', time() - 1296000, '>');
//    $db->where('privacy', 0);
//    $trending_data = $db->where('privacy', 0)->where('time', time() - (TRENDING_VIDEO_DAY_PERIOD * 24 * 3600), '>')->where('is_movie',0)->orderBy('views', 'DESC')->get(T_VIDEOS);
//    $trending_data = $db->where('privacy', 0)->where('is_movie',0)->orderBy('id', 'DESC')->get(T_VIDEOS);
    $slider = $db->where('featured', '1')->where('is_movie', 1, '!=')->where('is_movie', 3, '!=')->where('is_movie', 2, '!=')->where('privacy', 0)->orderBy('RAND()')->get(T_VIDEOS,3);

}
$cnt = 0;
foreach ($slider as $video) {
    $videoItem = PT_GetVideoByID($video->video_id);
    if($videoItem->owner->admin == 1 || $cnt == $limit){
        continue;
    } else {
        $cnt++;
    }
    if (!empty($videoItem)) {
//        unset($videoItem->owner);
        $videoItem->owner = array_intersect_key(ToArray($videoItem->owner), array_flip($user_public_data));
//		$videoItem->title = $videoItem;
        $response_data['data']['slider'][] = $videoItem;
    }
}
