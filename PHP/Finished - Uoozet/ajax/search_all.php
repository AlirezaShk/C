<?php
$data = array('status' => 400);
print_r($_POST);
if (!empty($_POST['keyword'])) {
    $search_value = PT_Secure($_POST['keyword']);
    $search_result_vid = $db->rawQuery("SELECT * FROM " . T_VIDEOS . " WHERE (title LIKE '%$search_value%' OR tags LIKE '%$search_value%' OR description LIKE '%$search_value%') AND privacy = 0 LIMIT 10");
    $data['html'] = "";
    if (!empty($search_result_vid)) {
        $html = '';
        foreach ($search_result_vid as $key => $search) {
            $search = PT_GetVideoByID($search, 0, 0, 0);
            $html .= "<div class='search-result'><a href='$search->url'>$search->title</a></div>";
        }
//        $data = array('status' => 200, 'html' => $html);
        $data['status'] = 200;
        $data['html'] = $html;
    }
    $sql = "SELECT * FROM ".T_USERS." WHERE username LIKE '%$search_value%' OR email LIKE '%$search_value%' OR first_name LIKE '%$search_value%' OR last_name LIKE '%$search_value%' LIMIT 10";
    $users = $db->rawQuery($sql);
    if (!empty($users)) {
        $html = '';
        foreach ($users as $key => $user) {
            $user = PT_UserData($user->id);
            $user_name = '"'.$user->username.'"';
            $html .= "<div class='search-result-' style='padding:10px;'><a href='javascript:void(0)' onclick='add_to_input($user_name)' >$user->name</a></div>";
        }
//        $data = array('status' => 200, 'html' => $html);
        $data['status'] = 200;
        $data['html'] .= $html;
    }
}
?>