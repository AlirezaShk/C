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
	$check_if_owner   = $db->where('id', $id)->where('user_id', $user->id)->getValue(T_COMM_REPLIES, 'count(*)');
	if ($check_if_owner > 0) {
		$delete_reply = $db->where('id', $id)->delete(T_COMM_REPLIES);
		if ($delete_reply) {
			$delete_reply_likes = $db->where('reply_id', $id)->delete(T_COMMENTS_LIKES);
			$data               = array('status' => 200);
		}
	}
}

?>