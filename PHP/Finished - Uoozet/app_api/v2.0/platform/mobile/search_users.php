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

if (empty($_GET['name'])) {
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

	$page    = (!empty($_GET['page'])  && is_numeric($_GET['page']))  ? $_GET['page']  : 1;
	$limit = 10;
	$offset = ($page-1)*$limit;
	$keyword  = PT_Secure($_GET['name']);
	$table    = T_USERS;
	$xsql     = '';

	//$sql      = "SELECT `video_id` FROM `$table` WHERE MATCH (`title`) AGAINST ('$keyword') {$xsql} ORDER BY id ASC LIMIT {$limit}";
	$sql      = "SELECT `id`, `username`, `first_name`, `last_name`, `avatar`  FROM `$table` WHERE first_name LIKE '%$keyword%' OR last_name LIKE '%$keyword%'  OR username LIKE '%$keyword%'  ORDER BY id ASC LIMIT {$limit}";
	$videos   = $db->rawQuery($sql);

	$response_data    = array(
        'api_status'  => '200',
        'api_version' => $api_version,
        'data'        => array()
    );

    foreach ($videos as $video) {
        $video->avatar = PT_GetMedia($video->avatar);
        $response_data['data'][] = $video;
    }
}