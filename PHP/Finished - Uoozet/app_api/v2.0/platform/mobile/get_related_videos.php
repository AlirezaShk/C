<?php
if(!isset($_GET['video_id'])) {
    $response_data       = array(
        'api_status'     => '400',
        'api_version'    => $api_version,
        'errors'         => array(
            'error_id'   => '1',
            'error_text' => 'Bad Request, Invalid or missing parameter'
        )
    );
} else {
    $vidId = intval($_GET['video_id']);
    $titleSearch = (bool) intval($_POST['title']);
    $descSearch = (bool) intval($_POST['desc']);
    $catSearch = (bool) intval($_POST['cat']);
    $tagSearch = (bool) intval($_POST['tag']);
    $userSearch = (bool) intval($_POST['user']);
    $vm = new Video();
    $relatives = $vm->getRelatedVideos($vidId, $titleSearch, $descSearch, $catSearch, $tagSearch, $userSearch, TRUE, FALSE, Video::RETURN_ARRAY);
    if (count($relatives) > 0 AND ((count($relatives[0]) > 0) OR (count($relatives[1]) > 0) OR (count($relatives[2]) > 0))) {
        $count = intval($_POST['count']);
        if ($count <= 0) $count = 1;
        $cnt = 0;
        $data = array(array(), array(), array());
        foreach ($relatives as $type => $eachRelCat) {
            foreach ($eachRelCat as $eachVid) {
                if ($cnt === $count) break;
                $data[intval($type)][] = PT_GetVideoByID($eachVid, 0, 0, 2);
                $cnt++;
            }
        }
        $response_data       = array(
            'api_status'     => '200',
            'api_version'    => $api_version,
            'data'           => $data,
        );
    } else {
        $response_data       = array(
            'api_status'     => '204',
            'api_version'    => $api_version,
        );
    }
}
