<?php 
if (IS_LOGGED == false) {
    $data = array(
        'status' => 400,
        'error' => 'Not logged in'
    );
    echo json_encode($data);
    exit();
}

if (empty($_POST['id'])) {
	$data = array('status' => 400);
} 

else {
	$id = PT_Secure($_POST['id']);
	$check_if_owner = $db->where('id', $id)->where('user_id', $user->id)->getValue(T_COMMENTS, 'count(*)');
	if ($check_if_owner > 0) {
		$delete_comment = $db->where('id', $id)->delete(T_COMMENTS);
		if ($delete_comment) {
			$delete_comments_likes   = $db->where('comment_id', $id)->delete(T_COMMENTS_LIKES);
			$comments_replies        = $db->where('comment_id', $id)->get(T_COMM_REPLIES);
			$delete_comments_replies = $db->where('comment_id', $id)->delete(T_COMM_REPLIES);
			foreach ($comments_replies as $reply) {
				$db->where('reply_id', $reply->id)->delete(T_COMMENTS_LIKES);
			}

			if ($delete_comments_likes && $delete_comments_replies) {
				$data = array('status' => 200);
			}
		}
	}
}

?>