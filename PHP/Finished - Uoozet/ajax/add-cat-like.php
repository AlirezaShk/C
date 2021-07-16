<?php
//if (IS_LOGGED == false) {
//    $data = array(
//        'status' => 400,
//        'error' => 'Not logged in'
//    );
//    echo json_encode($data);
//    exit();
//}
if (!empty($_POST['g_vid_cat']) || !empty($_POST['b_vid_cat']) || !empty($_POST['r_g_vid_cat']) || !empty($_POST['r_b_vid_cat'])) {
    $liked_cats = $_POST['g_vid_cat'];
    $disliked_cats = $_POST['b_vid_cat'];
    $reset_liked_cats = $_POST['r_g_vid_cat'];
    $reset_disliked_cats = $_POST['r_b_vid_cat'];
    $CAM = new CatAffiliation();
//    print_r($_POST);
    if (strlen($liked_cats) === 0) {
        $liked_cats = NULL;
    } else {
        $CAM->addLike($user->id, $liked_cats, CatAffiliation::LIKE);
    }
    if (strlen($disliked_cats) === 0) {
        $disliked_cats = NULL;
    } else {
        $CAM->addLike($user->id, $disliked_cats, CatAffiliation::DISLIKE);
    }
    if (strlen($reset_liked_cats) === 0) {
        $reset_liked_cats = NULL;
    } else {
        $CAM->delLike($user->id, $reset_liked_cats, CatAffiliation::LIKE);
    }
    if (strlen($reset_disliked_cats) === 0) {
        $reset_disliked_cats = NULL;
    } else {
        $CAM->delLike($user->id, $reset_disliked_cats, CatAffiliation::DISLIKE);
    }
    $data = array(
        'status' => 200
    );
    echo json_encode($data);
    exit();
}