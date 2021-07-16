<?php
if (IS_LOGGED == false) {
    $data = array(
        'status' => 400,
        'error' => 'Not logged in'
    );
    echo json_encode($data);
    exit();
}


if ($first == 'video') {
	$request = (
		!empty($_POST['video_id'])     && 
		is_numeric($_POST['video_id']) && 
		!empty($_POST['text'])         &&
		!empty($_POST['id'])           && 
		is_numeric($_POST['id']));
	if ($request === true) {

		$text     = PT_Secure($_POST['text']);
	    $video_id = PT_Secure($_POST['video_id']);
	    $comm_id  = PT_Secure($_POST['id']);
	    $verfiy_video = $db->where('id', $video_id)->getValue(T_VIDEOS, "count(*)");
	    $verfiy_comm  = $db->where('id', $comm_id)->getValue(T_COMMENTS, "count(*)");
	    if ($verfiy_video > 0 && $verfiy_comm > 0) {
	        $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
	        $i          = 0;
	        preg_match_all($link_regex, $text, $matches);
	        foreach ($matches[0] as $match) {
	            $match_url = strip_tags($match);
	            $syntax    = '[a]' . urlencode($match_url) . '[/a]';
	            $text      = str_replace($match, $syntax, $text);
	        }

	        $insert_data     = array(
	            'user_id'    => $user->id,
	            'comment_id' => $comm_id,
	            'video_id'   => $video_id,
	            'text' => $text,
	            'time' => time()
	        );

	        $insert_reply = $db->insert(T_COMM_REPLIES, $insert_data);
	        if ($insert_reply) {
	            $get_reply = $db->where('id', $insert_reply)->getOne(T_COMM_REPLIES);
	            $pt->is_reply_owner = true;
	            $pt->is_ro_verified = ($user->verified == 1) ? true : false;
	            $reply     = PT_LoadPage('watch/replies', array(
	                'ID' => $get_reply->id,
	                'TEXT' => PT_Markup($get_reply->text),
	                'TIME' => PT_Time_Elapsed_String($get_reply->time),
	                'USER_DATA' => PT_UserData($get_reply->user_id),
	                'COMM_ID' => $comm_id,
	                'LIKES' => 0,
	                'DIS_LIKES' => 0,
	                'LIKED' => '',
                	'DIS_LIKED' => ''
	            ));
	            $data        = array(
	                'status' => 200,
	                'html' => $reply
	            );
	        }
	    }
	}

    
}

if ($first == 'article') {
	$request = (
		!empty($_POST['post_id'])     && 
		is_numeric($_POST['post_id']) && 
		!empty($_POST['text'])        &&
		!empty($_POST['id'])          && 
		is_numeric($_POST['id']));
	if ($request === true) {

		$text     = PT_Secure($_POST['text']);
	    $post_id  = PT_Secure($_POST['post_id']);
	    $comm_id  = PT_Secure($_POST['id']);
	    $verfiy_post  = $db->where('id', $post_id)->getValue(T_POSTS, "count(*)");
	    $verfiy_comm  = $db->where('id', $comm_id)->getValue(T_COMMENTS, "count(*)");
	    if ($verfiy_post > 0 && $verfiy_comm > 0) {
	        $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
	        $i          = 0;
	        preg_match_all($link_regex, $text, $matches);
	        foreach ($matches[0] as $match) {
	            $match_url = strip_tags($match);
	            $syntax    = '[a]' . urlencode($match_url) . '[/a]';
	            $text      = str_replace($match, $syntax, $text);
	        }

	        $insert_data     = array(
	            'user_id'    => $user->id,
	            'comment_id' => $comm_id,
	            'post_id'    => $post_id,
	            'text' => $text,
	            'time' => time()
	        );

	        $insert_reply = $db->insert(T_COMM_REPLIES, $insert_data);
	        if ($insert_reply) {
	            $get_reply = $db->where('id', $insert_reply)->getOne(T_COMM_REPLIES);
	            $pt->is_reply_owner = true;
	            $pt->is_ro_verified = ($user->verified == 1) ? true : false;
	            $reply     = PT_LoadPage('articles/includes/replies', array(
	                'ID' => $get_reply->id,
	                'TEXT' => PT_Markup($get_reply->text),
	                'TIME' => PT_Time_Elapsed_String($get_reply->time),
	                'USER_DATA' => PT_UserData($get_reply->user_id),
	                'COMM_ID' => $comm_id,
	                'LIKES' => 0,
	                'DIS_LIKES' => 0,
	                'LIKED' => '',
                	'DIS_LIKED' => ''
	            ));
	            $data        = array(
	                'status' => 200,
	                'html' => $reply
	            );
	        }
	    }
	}

    
}