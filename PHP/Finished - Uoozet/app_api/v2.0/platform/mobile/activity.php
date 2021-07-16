<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+
if (!IS_LOGGED) {

    $response_data    = array(
        'api_status'  => '400',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
    );
}
elseif (empty($_POST['time'])) {
    $response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '3',
            'error_text' => 'Please feel the duration'
        )
    );
}
else{
    $activeTime = (PT_UserData($user->id)->active_time)+$_POST['time'];
    $updates = array('active_time' => ($activeTime));
    $db->where('id', $user->id)->update(T_USERS, $updates);
    $db->where('id', $user->id)->update(T_USERS, array('last_active' => time()));

    $response_data     = array(
        'api_status'   => '200',
        'api_version'  => $api_version,
        'success_type' => 'updated',
        'message'      => 'Successfully increas active_time',
        'data'         => array(
            'active_time' => PT_UserData($pt->user->id)->active_time
        )
    );
}