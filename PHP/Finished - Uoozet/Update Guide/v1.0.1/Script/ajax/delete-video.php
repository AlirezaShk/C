<?php
if (IS_LOGGED == false) {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}

$id         = PT_Secure($_POST['id']);
$video      = $db->where('id', $id)->getOne(T_VIDEOS);
$can_delete = false;

if (PT_IsAdmin() == false) {
	if ($db->where('user_id', $user->id)->where('id', $id)->getValue(T_VIDEOS, 'count(*)') > 0) {
		$can_delete = true;
	}
} 

else {
	$can_delete = true;
}

if ($can_delete == true && !empty($video)) {
	$delete = PT_DeleteVideo($id);
	$data   = array('status' => 200);
}