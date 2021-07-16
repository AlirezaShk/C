<?php
$channels = '';
if(!isset($_POST['type']) or !isset($_POST['id'])) {
    $return_arr = array(
        "result" => 'unsuccessful',
        "cus" => 'emptyType'
    );
}else {

    if ($_POST['type'] == 'followers') {
        $userids2 = array();
        $get2 = $db->where('user_id', $_POST['id'])->get(T_SUBSCRIPTIONS);
        foreach ($get2 as $key2 => $userdata2) {
            $userids2[] = $userdata2->user_id;
        }
        foreach ($get2 as $key2 => $userdata2) {
            $userdata2 = PT_UserData($userdata2->subscriber_id);
            if (!empty($userdata2)) {
                $channels .= '<li class="followItem item"><div><a href="' . $userdata2->url . '"><img src="' . $userdata2->avatar . '"><span>' . $userdata2->username . '</span></a></div></li>';
            }
        }
    }else{
        $get = $db->where('subscriber_id', $_POST['id'])->get(T_SUBSCRIPTIONS);
        $userids = array();
        foreach ($get as $key => $userdata) {
            $userids[] = $userdata->user_id;
        }

        foreach ($get as $key => $userdata) {

            $userdata = PT_UserData($userdata->user_id);
            if (!empty($userdata)) {
                $channels .= '<li class="followItem item"><div><a href="' . $userdata->url . '"><img src="' . $userdata->avatar . '"><span>' . $userdata->username . '</span></a></div></li>';
            }
        }
    }
    $return_arr = array(
        "result" => 'successful',
        "videos" =>$channels
    );
}
echo json_encode($return_arr);