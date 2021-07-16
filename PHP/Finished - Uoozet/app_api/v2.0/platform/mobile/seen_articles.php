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


if (empty($_GET['article_id'])) {
    $response_data = array(
        'api_status' => '400',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
    );
} else {

    $article_id = PT_Secure($_GET['article_id']);
    $article = $db->where('id', $article_id)->getOne(T_POSTS, array('id', 'user_id', 'views'));

    if (empty($article)) {
        $response_data = array(
            'api_status' => '404',
            'api_version' => $api_version,
            'errors' => array(
                'error_id' => '2',
                'error_text' => 'Video does not exist'
            )
        );
    } else {

        $v = $article->views += 1;
        $update = array('views' => ($v));
        $db->where('id', $article_id)->update(T_POSTS, $update);
        $response_data     = array(
            'api_status'   => '200',
            'api_version'  => $api_version,
            'views'         => $v
        );
    }
}