<?php
if(!isset($_POST['catId'])) {
    $return_arr = array(
        "result" => 'unsuccessful',
        "cus" => 'emptyCatId'
    );
}else {
    $getCats = new Video();
    $langManager = new Lang();
    if ($getCats->catHasVid($_POST['catId'])) {
        $cateogry = $_POST['catId'];

        $top_list = '';
        $videoManager = $getCats;
        $videoManager->setCountLimit(4);
        $videoManager->setLike_conditions(array("categories" => "%$cateogry|%"));
        $videoManager->setBinary_conditions(array("privacy" => 0, "is_movie" => 0, "is_channel" => 0));
        $videoManager->setOrder(array("id" => "DESC"));
        $video = $videoManager->getMatches(Video::RETURN_ARRAY);
//            print_r($video);
//        echo ;
//        $res = '';
//        $i =0;
        if (isset($video[0]) and is_array($video[0])) {

            foreach ($video as $key => $videoP) {
//                print_r($videoP);
                $videoItem = PT_GetVideoByID($videoP['id'], 0, 0, 2);
//                print_r($videoItem);
                $likedCats_list .= PT_LoadPage('video-sharing/list', array(
                    'ID' => $videoItem->id,
                    'TITLE' => $videoItem->title,
                    'VIEWS' => $videoItem->views,
                    'VIEWS_NUM' => number_format($videoItem->views),
                    'USER_DATA' => $videoItem->owner,
                    'THUMBNAIL' => $videoItem->thumbnail,
                    'URL' => $videoItem->url,
                    'TIME' => $videoItem->time_ago,
                    'DURATION' => $videoItem->duration,
                    'VIDEO_ID' => $videoItem->video_id_,
                    'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
                ));
            }

            $return_arr = array(
                "result" => 'successful',
                "videos" =>$likedCats_list
            );
        }else{
            $return_arr = array(
                "result" => 'unsuccessful',
                "cus" => 'noVideos'
            );
        }
    } else {
        $return_arr = array(
            "result" => 'unsuccessful',
            "cus" => 'noVideos'
        );
    }
}
echo json_encode($return_arr);