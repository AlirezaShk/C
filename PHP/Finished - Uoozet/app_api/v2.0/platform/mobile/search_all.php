<?php
$response_data = array(
    'api_status' => 400,
    'api_version'  => $api_version
    );
if (empty($_GET['target'])) {
    $target = NULL;
} else {
    $target = explode("-", ($_GET['target']));
}
if (!empty($_GET['keyword'])) {
    $response_data['data'] = array();
    $search_value = PT_Secure($_GET['keyword']);
    if (is_null($target) OR in_array("0", $target)) {
        $search_result_vid = $db->rawQuery("SELECT * FROM " . T_VIDEOS . " WHERE (title LIKE '%$search_value%' OR tags LIKE '%$search_value%' OR description LIKE '%$search_value%') AND privacy = 0");
        if (!empty($search_result_vid)) {
            foreach ($search_result_vid as $key => $search) {
                $vid = PT_GetVideoByID($search, 0, 0, 0);
                $response_data['data'][] = $vid;
            }
            $response_data['api_status'] = 200;
        }
    } elseif (is_null($target) OR in_array("1", $target)) {
        $users = $db->rawQuery("SELECT * FROM " . T_USERS . " WHERE username LIKE '%$search_value%' OR email LIKE '%$search_value%' OR first_name LIKE '%$search_value%' OR last_name LIKE '%$search_value%'");
        if (!empty($users)) {
            $UM = new User();
//            $VM = new Video();
            foreach ($users as $key => $user) {
                $user = $UM->getOne($user->id, User::BY_ID);
//                $VM->setBinary_conditions(array("user_id" => $user['id']));
//                $vids = $VM->getMatches();
//                if ($vids)
                    $response_data['data'][] = array('user' => $user/*, 'vid' => $vids*/);
            }
            $response_data['api_status'] = 200;
        }
    }
    if ($response_data['api_status'] === 400 OR count($response_data['data']) > 0) {
        unset($response_data['data']);
        $response_data['api_status'] = 204;
    }
}
?>