<?php 
if (IS_LOGGED == false || $pt->config->upload_system != 'on') {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}
if (!empty($_FILES['video']['tmp_name'])) {
	$file_info = array(
        'file' => $_FILES['video']['tmp_name'],
        'size' => $_FILES['video']['size'],
        'name' => $_FILES['video']['name'],
        'type' => $_FILES['video']['type'],
        'allowed' => 'mp4,mov,webm,mpeg'
    );
    $file_upload = PT_ShareFile($file_info);
    if (!empty($file_upload['filename'])) {
    	$data = array('status' => 200, 'file_path' => $file_upload['filename'], 'file_name' => $file_upload['name']);
    } else if (!empty($file_upload['error'])) {
        $data = array('status' => 400, 'error' => $file_upload['error']);
    }
}
?>