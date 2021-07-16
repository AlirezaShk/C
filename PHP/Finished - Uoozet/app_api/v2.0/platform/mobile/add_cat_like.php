<?php

if (!IS_LOGGED) {
    $response_data    = array(
        'api_status'  => '400',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
    );
} else {
    $CAM = new CatAffiliation();
    $user_id     = intval($user->id);
    $status = '204';
    if (isset($_POST['g_vid_cat']) AND strlen($_POST['g_vid_cat']) > 0) {
        $CAM->addLike($user_id, json_decode($_POST['g_vid_cat']), CatAffiliation::LIKE);
        $status = '200';
    }
    if (isset($_POST['b_vid_cat']) AND strlen($_POST['b_vid_cat']) > 0) {
        $CAM->addLike($user_id, json_decode($_POST['b_vid_cat']), CatAffiliation::DISLIKE);
        $status = '200';
    }
    if (isset($_POST['r_g_vid_cat']) AND strlen($_POST['r_g_vid_cat']) > 0) {
        $CAM->delLike($user_id, json_decode($_POST['r_g_vid_cat']), CatAffiliation::LIKE);
        $status = '200';
    }
    if (isset($_POST['r_b_vid_cat']) AND strlen($_POST['r_b_vid_cat']) > 0) {
        $CAM->delLike($user_id, json_decode($_POST['r_b_vid_cat']), CatAffiliation::DISLIKE);
        $status = '200';
    }
    $response_data    = array(
        'api_status'  => $status,
        'api_version' => $api_version
    );
}