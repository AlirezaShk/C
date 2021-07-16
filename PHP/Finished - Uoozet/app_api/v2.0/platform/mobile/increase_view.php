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
    $response_data = array(
        'api_status' => '400',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
    );
} else {

    $video_id = PT_Secure($_GET['video_id']);
    $video = $db->where('video_id', $video_id)->getOne(T_VIDEOS, array('video_id', 'user_id', 'views'));

    if (empty($video)) {
        $response_data = array(
            'api_status' => '404',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '2',
                'error_text' => 'Video does not exist'
            )
        );
    } else {

        $v = $video->views += 1;
        $update = array('views' => ($v));
        $db->where('video_id', $video_id)->update(T_VIDEOS, $update);
        $response_data     = array(
            'api_status'   => '200',
            'api_version'  => $api_version,
            'views'         => $v
        );
    }
}
