<?php 

if (!empty($_GET['first'])) {
	
	if ($_GET['first'] == 'share' && !empty($_POST['post_id']) && is_numeric($_POST['post_id'])) {
		$post_id     = $_POST['post_id'];
		$get_post    = $db->where('id', $post_id)->getOne(T_POSTS);
		$data        = array('status' => 400);
		if (!empty($get_post)) {
			$shared  = ($get_post->shared += 1);
			$up_data = array('shared' => $shared);
			$db->where('id', $post_id)->update(T_POSTS,$up_data);
			$data['status'] = 200;
			$data['shared'] = $shared;
		}
	}
}