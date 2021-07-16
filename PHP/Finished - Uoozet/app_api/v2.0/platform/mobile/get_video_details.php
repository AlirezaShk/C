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


  

if (empty($_GET['video_id'])) {
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

	$video_id = PT_Secure($_GET['video_id']);
	$video    = $db->where('id',$video_id)->getOne(T_VIDEOS);

	if (empty($video)) {
		$response_data       = array(
	        'api_status'     => '404',
	        'api_version'    => $api_version,
	        'errors'         => array(
	            'error_id'   => '2',
	            'error_text' => 'Video does not exist'
	        )
	    );
	}


	else{

		$video_data = PT_GetVideoByID($video->video_id,0,1);
		if (!empty($video_data)) {
			$t_videos                     = T_VIDEOS;
			$video_data->is_subscribed    = 0;
			$last_active = $video_data->owner->last_active;
			$video_data->owner            = array_intersect_key(
				ToArray($video_data->owner), 
				array_flip($user_public_data)
			);
			$video_data->owner['is_subscribed_to_channel'] = 0;
			$video_data->owner['last_active'] = $last_active;

			if (IS_LOGGED && $video_data->user_id != $user->id) {
				$db->where('subscriber_id',$user->id);
				$db->where('user_id',$video_data->user_id);
				$db->where('active',1);
				$subscribed = ($db->getValue(T_SUBSCRIPTIONS,'count(*)') > 0);

				if (($subscribed === true)) {
					$video_data->is_subscribed = 1;
                    $video_data->owner['is_subscribed_to_channel'] = 1;
				}
			}


			$video_title       = PT_Secure($video_data->title);

			/*$sql_query         = "
				SELECT * FROM `$t_videos` 
				WHERE MATCH (title) 
				AGAINST ('$video_title') 
				AND id <> '{$video_data->id}' 
				ORDER BY `id` DESC 
				LIMIT 20";

			$related_videos = $db->rawQuery($sql_query);

			foreach ($related_videos as $related_video) {
				$related_video         = PT_GetVideoByID($related_video, 0, 1, 0);
				$user_data             = PT_UserData($related_video->user_id);
				$related_video->owner  = array_intersect_key(
					ToArray($user_data), 
					array_flip($user_public_data)
				);

				$video_data->suggested_videos[] = $related_video;
			}*/

            $vidId = intval($video->id);
            $titleSearch = (bool) 1;
            $descSearch = (bool) 1;
            $catSearch = (bool) 1;
            $tagSearch = (bool) 1;
            $userSearch = (bool) 1;
//            $titleSearch = (bool) intval($_POST['title']);
//            $descSearch = (bool) intval($_POST['desc']);
//            $catSearch = (bool) intval($_POST['cat']);
//            $tagSearch = (bool) intval($_POST['tag']);
//            $userSearch = (bool) intval($_POST['user']);
            $vm = new Video();
            $relatives = $vm->getRelatedVideos($vidId, $titleSearch, $descSearch, $catSearch, $tagSearch, $userSearch, TRUE, FALSE, Video::RETURN_ARRAY);
            if (count($relatives) > 0 AND ((count($relatives[0]) > 0) OR (count($relatives[1]) > 0) OR (count($relatives[2]) > 0))) {
                $count = intval($_POST['count']);
                if ($count <= 0) $count = 1;
                $cnt = 0;
                $data = array(array(), array(), array());
                foreach ($relatives as $type => $eachRelCat) {
                    foreach ($eachRelCat as $eachVid) {
                        if ($cnt === $count) break;
                        $video_data->suggested_videos[] = PT_GetVideoByID($eachVid, 0, 0, 2);
                        $cnt++;
                    }
                }
            } else {
                $video_data->suggested_videos = array();
            }
			
			$response_data     = array(
		        'api_status'   => '200',
		        'api_version'  => $api_version,
		        'data'         => $video_data
		    );

//		    $update = array('views' => ($video_data->views += 1));
//		    $db->where('video_id',$video_id)->update($t_videos,$update);



		    //ads
            $get_random_ad = $db->where('active', 1)->orderBy('RAND()')->getOne(T_VIDEO_ADS);
            if (!empty($get_random_ad)) {

                if (!empty($get_random_ad->ad_media)) {
                    $response_data['ads']['type'] = 'video';
                    $response_data['ads']['path'] = $get_random_ad->ad_media;
                    $response_data['ads']['link'] = PT_Link('redirect/' . $get_random_ad->id . '?type=video');
                }

                if ($get_random_ad->skip_seconds > 0) {
                    $response_data['ads']['ad_skip'] = true;
                    $response_data['ads']['ad_skip_num'] = $get_random_ad->skip_seconds;
                }else{
                    $response_data['ads']['ad_skip'] = false;
                    $response_data['ads']['ad_skip_num'] = 0;
                }

                if (!empty($get_random_ad->ad_image)) {
                    $response_data['ads']['type'] = 'image';
                    $response_data['ads']['path'] = $get_random_ad->ad_image;
                    $response_data['ads']['link'] = PT_Link('redirect/' . $get_random_ad->id . '?type=image');
                    $response_data['ads']['ad_skip'] = false;
                    $response_data['ads']['ad_skip_num'] = 0;
                }

                $update_clicks = $db->where('id', $get_random_ad->id)->update(T_VIDEO_ADS, array(
                    'views' => $db->inc(1)
                ));
                $response_data['ads']['active'] = true;
            }else{
                $user_ads      = pt_get_user_ads(1, $get_video->user_id);
                if (!empty($user_ads)) {
                    $get_random_ad =  $user_ads;
                    $random_ad_id  = $get_random_ad->id;
                    $ad_skip       = 'true';
                    $ad_link       = urldecode($get_random_ad->url);
                    $ad_skip_num   = 5;

                    $response_data['ads']['type'] = $user_ads->category;
                    $response_data['ads']['path'] = PT_GetMedia($get_random_ad->media);
                    $response_data['ads']['link'] = urldecode($user_ads->url);
                    $response_data['ads']['ad_skip'] = false;
                    $response_data['ads']['ad_skip_num'] = 0;
                }else{
                    $response_data['ads']['active'] = false;
                    $response_data['ads']['type'] = '';
                    $response_data['ads']['path'] = '';
                    $response_data['ads']['link'] = '';
                    $response_data['ads']['ad_skip'] = false;
                    $response_data['ads']['ad_skip_num'] = 0;
                }
            }
            /////
		}

		else{

			$response_data       = array(
		        'api_status'     => '500',
		        'api_version'    => $api_version,
		        'errors'         => array(
		            'error_id'   => '3',
		            'error_text' => 'Error: an unknown error occurred. Please try again later'
		        )
		    );
		}
	}
}