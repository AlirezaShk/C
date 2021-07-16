<?php 
if (IS_LOGGED == false || $pt->config->upload_system != 'on') {
    $data = array('status' => 400, 'error' => 'Not logged in');
    echo json_encode($data);
    exit();
}

if ($pt->user->is_pro != 1 && $pt->user->uploads >= 1073741824){
    $data = array('status' => 401);
    echo json_encode($data);
    exit();
}
sleep(10);
if (!empty($_FILES['video']['tmp_name'])) {
    if ($_FILES['video']['size'] > $pt->config->max_upload) {
        $max  = pt_size_format($pt->config->max_upload);
        $data = array('status' => 402,'message' => ($lang->file_is_too_big .": $max"));
        echo json_encode($data);
        exit();
    }

	$file_info = array(
        'file' => $_FILES['video']['tmp_name'],
        'size' => $_FILES['video']['size'],
        'name' => $_FILES['video']['name'],
        'type' => $_FILES['video']['type'],
        'allowed' => 'mp4,mov,webm,mpeg'
    );
    $file_upload = PT_ShareFile($file_info);
    if (!empty($file_upload['filename'])) {
    	$data   = array('status' => 200, 'file_path' => $file_upload['filename'], 'file_name' => $file_upload['name']);
        $update = array('uploads' => ($pt->user->uploads += $file_info['size']));
        $db->where('id',$pt->user->id)->update(T_USERS,$update);
    } 

    else if (!empty($file_upload['error'])) {
        $data = array('status' => 400, 'error' => $file_upload['error']);
    }
}
?>