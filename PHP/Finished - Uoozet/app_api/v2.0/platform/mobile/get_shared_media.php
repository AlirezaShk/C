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
$user_id   = (!empty($_GET['user_id'])) ? PT_Secure($_GET['user_id']) : 0;
$page = isset($_GET['page'])? $_GET['page']:1;
/*if (false) {

	$response_data    = array(
	    'api_status'  => '400',
	    'api_version' => $api_version,
	    'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
	);
}
else */if (empty($_GET['user_id'])) {

	$response_data    = array(
	    'api_status'  => '400',
	    'api_version' => $api_version,
	    'errors' => array(
            'error_id' => '20',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
	);
}
else{
	$list = $db->where('to_user_id', $user_id)->get(T_SHAREDMEDIA);
	if (empty($list)) {
		$response_data    = array(
		    'api_status'  => '400',
		    'api_version' => $api_version,
		    'errors' => array(
	            'error_id' => '2',
	            'error_text' => 'Bad Request, Invalid or missing parameter'
	        )
		);
	}
	else{
		$response_data     = array(
	        'api_status'   => '200',
	        'api_version'  => $api_version,
	    );

		$db->objectbuilder()->where('to_user_id',$user_id)->orderBy('id', 'desc');
		$play_list    = $db->paginate(T_SHAREDMEDIA, $page, array('id', 'media_id', 'from_user_id', 'to_user_id', 'seen_status', 'date_insert', 'type'));
		$res = [];
		foreach ($play_list as $key=>$row) {
		    $exists = 1;
			if($row->type == 0){
                $video = PT_GetVideoByID($row->media_id,0,0,2);
                if($video->is_movie == 2 )
                    $video = PT_GetMusicByID($row->media_id,0,0,2);

                if(!$video){
                    $exists = 0;
                }
                $row->media = $video;
            }
            else{
                $video   = $db->where('id', $row->media_id)->get(T_POSTS);
                foreach ($video as $key => $post) {
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
                }

                $row->media = $video[0];
            }
            $row->type = $row->type?'article':'media';
			if($exists)
			    $res[] = $row;
		}

		$response_data['data']   = $res;
	}
}
