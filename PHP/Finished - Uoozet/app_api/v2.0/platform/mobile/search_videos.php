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

if (empty($_GET['keyword']) || mb_strlen($_GET['keyword']) < 2) {
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

	$limit    = (!empty($_GET['limit'])  && is_numeric($_GET['limit']))  ? $_GET['limit']  : 10;
	$offset   = (!empty($_GET['offset']) && is_numeric($_GET['offset'])) ? $_GET['offset'] : null;
	$keyword  = PT_Secure($_GET['keyword']);
	$table    = T_VIDEOS;
	$xsql     = '';

	if (!empty($offset)) {
		$xsql = " AND `id` > '{$offset}' AND `id` <> '{$offset}' ";
	}

	//$sql      = "SELECT `video_id` FROM `$table` WHERE MATCH (`title`) AGAINST ('$keyword') {$xsql} ORDER BY id ASC LIMIT {$limit}";
	$sql      = "SELECT `video_id` FROM `$table` WHERE title LIKE '%$keyword%' AND privacy = 0 {$xsql} ORDER BY id ASC LIMIT {$limit}";
	$videos   = $db->rawQuery($sql);

	$response_data    = array(
        'api_status'  => '200',
        'api_version' => $api_version,
        'data'        => array(),
        'musics'      => array(),
        'articles'    => array(),
    );

    foreach ($videos as $video) {
        $video = PT_GetVideoByID($video->video_id);
		if (!empty($video)) {
            if($video->is_movie == 2){
                $video = PT_GetMusicByID($video->video_id);
                $video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
                $response_data['musics'][] = $video;
            }else{
                $video->owner = array_intersect_key(ToArray($video->owner), array_flip($user_public_data));
                $response_data['data'][] = $video;
            }
		}
    }

    $get_articles = $db->rawQuery("SELECT * FROM " . T_POSTS . " WHERE (`title` LIKE '%$keyword%' OR `description` LIKE  '%$keyword%') ORDER BY id ASC LIMIT 50");

    if (!empty($get_articles)) {
        foreach ($get_articles as $key => $post) {
            $post->text =  strip_tags(htmlspecialchars_decode($post->text));
            $post->views = number_format($post->views);
            $post->image = PT_GetMedia($post->image);
            $post->url = PT_Link('articles/read/' . PT_URLSlug($post->title,$post->id));
            $post->time_format = date('d-F-Y',$post->time);
            $post->text_time = PT_Time_Elapsed_String($post->time);
            $post->user_data = PT_UserData($post->user_id);
            unset($post->user_data->password);
            $post->comments_count     = $db->where('post_id', $post->id)->getValue(T_COMMENTS,'COUNT(*)');
            $post->likes     = $db->where('post_id', $post->id)->where('type', 1)->getValue(T_DIS_LIKES, "count(*)");
            $post->dislikes  = $db->where('post_id', $post->id)->where('type', 2)->getValue(T_DIS_LIKES, "count(*)");
            $u_like     = $db->where('post_id', $post->id)->where('user_id', $user->id)->where('type', 1)->getValue(T_DIS_LIKES, "count(*)");
            $post->liked      = ($u_like > 0) ? 1 : 0;

            $u_dislike  = $db->where('post_id', $post->id)->where('user_id', $user->id)->where('type', 2)->getValue(T_DIS_LIKES, "count(*)");
            $post->disliked   = ($u_dislike > 0) ? 1 : 0;
            $response_data['articles'][] = $post;
        }
    }
}
