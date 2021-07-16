<?php
/*if (!IS_LOGGED) {
    $response_data    = array(
        'api_status'  => '400',
        'api_version' => $api_version,
        'errors' => array(
            'error_id' => '1',
            'error_text' => 'Not logged in'
        )
    );
} else */{
    $likedCats = new CatAffiliation();
    $videoManager = new Video();
    $user_id = intval($_GET['user_id']);
    $likedCats = $likedCats->getOne($user_id, CatAffiliation::LIKE);
    $ids_array = array();
    $likedCats_data = array();
    if($likedCats) {
        foreach ($likedCats as $k => $v) {
            $ids_array[] = "%" . $v . "|%";
        }
        $videoManager->setLike_conditions(array("categories"=>$ids_array));
        $videoManager->setBinary_conditions(array("is_movie"=>0, "privacy"=>0));
        $videoManager->setOrder(array("id"=>"DESC"));
        $likedCats_data = $videoManager->getMatches();
    }
    $cnt = 0;
    $cnt_limit = (isset($_POST['count']) ? intval($_POST['count']) : -1);
    if (!empty($likedCats_data)) {
        $likedCats_list = array();
        foreach ($likedCats_data as $key => $video) {
            $videoItem = PT_GetVideoByID($video['id'], 0, 0, 2);
            if($cnt == $cnt_limit){
                break;
            }
            $cnt++;
            $likedCats_list[] = $videoItem;
        }
        if(count($likedCats_list) === 0) {
            $response_data    = array(
                'api_status'  => '204',
                'api_version' => $api_version
            );
        } else {
            $response_data    = array(
                'api_status'  => '200',
                'api_version' => $api_version,
                'data' => $likedCats_list
            );
        }
    } else {
        $response_data    = array(
            'api_status'  => '204',
            'api_version' => $api_version
        );
    }
}