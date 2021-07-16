<?php
if(!isset($_POST['vidId'])) {
    $data = array('status'=>400, 'error'=>'No Video ID Specified');
    echo json_encode($data);
    exit;
}
$vidId = intval($_POST['vidId']);
$titleSearch = (bool) intval($_POST['title']);
$descSearch = (bool) intval($_POST['desc']);
$catSearch = (bool) intval($_POST['cat']);
$tagSearch = (bool) intval($_POST['tag']);
$userSearch = (bool) intval($_POST['user']);
$HTMLView = (bool) intval($_POST['HTMLView']);
$count = intval($_POST['count']);
$vm = new Video();
$relatives = $vm->getRelatedVideos($vidId, $titleSearch, $descSearch, $catSearch, $tagSearch, $userSearch, TRUE, FALSE, Video::RETURN_ARRAY);

if (PT_MobileExists($_SESSION['mobile'])) {
    $user = PT_MobileDetail($_POST['mobile']);
}
$vm->removeSuggestedVideos($user->id,$_POST['vidId']);
if (count($relatives) > 0 AND ((count($relatives[0]) > 0) OR (count($relatives[1]) > 0) OR (count($relatives[2]) > 0))) {
    if (!$HTMLView) $data = array('status'=>200, 'related'=>$relatives);
    else {
        $j = 0;
        $video_sidebar = "";
        for ($i_ = 2; ($i_ >= 0 ) AND ($j <= $count); $i_--) {
            if(count($relatives[$i_]) === 0) continue;
            foreach ($relatives[$i_] as $related_video) {
                if (isset($user) and $user->id !== '') {
                    $vm->updateSuggestedVideos($user->id, $related_video);
                }
                $related_video  = PT_GetVideoByID($related_video, 0, 0, 2);

                $video_sidebar .= PT_LoadPage('watch/video-sidebar', array(
                    'ID' => $related_video->id,
                    'TITLE' => $related_video->title,
                    'URL' => $related_video->url,
                    'THUMBNAIL' => $related_video->thumbnail,
                    'USER_NAME' => $related_video->owner->name,
                    'VIEWS' => $related_video->views,
                    'TIME' => $related_video->time_alpha,
                    'V_ID' => $related_video->video_id
                ));
                if ($j++ == 0 && $pt->config->autoplay_system == 'on') {
                    $next_video = $video_sidebar;
                    $video_sidebar = '';
                }
            }
        }
        $data = array('status'=>200, 'related'=>$video_sidebar, 'next'=>$next_video);
    }
} else $data = array('status'=>204);
