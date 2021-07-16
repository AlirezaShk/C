<?php
function PT_IsMobileActive($mobile){
    global $pt, $db;
    $data   = array();
    $t_users = T_USERS;
    $mobile = PT_Secure($mobile);
    try {
        $query  = $db->rawQuery("SELECT `active` FROM `$t_users` WHERE `mobile` = '$mobile'");
    } catch (Exception $e) {

    }
    foreach ($query as $item) {
        return ($item->active == 1)?(true):(false);
    }
}
function PT_HasUserSignedup($mobile){
    global $pt, $db;
    $data   = array();
    $t_users = T_USERS;
    $mobile = PT_Secure($mobile);
    $query = "";
    if(strlen($mobile) == 0)
        return false;
    try {
        $query  = $db->rawQuery("SELECT `completeSignup` FROM `$t_users` WHERE `mobile` = '$mobile'");
    } catch (Exception $e) {
        echo $e->getMessage();
        return false;
    }
    foreach ($query as $item) {
        return ($item->completeSignup == 1)?(true):(false);
    }
}
function PT_getCategoryDetails($cat_id){
    global $db;
    return $db->where('id',$cat_id)->get(T_LANGS)[0];
}
function PT_getSubCategories($cat_id){
    global $db;
    return $db->where('type',$cat_id)->get(T_LANGS);
}
function PT_getMotherCategory($subcat_id){
    global $db;
    $subcat = $db->where('id', $subcat_id)->get(T_LANGS);
    $result = $db->where('id', $subcat[0]->type)->get(T_LANGS);
    if(count($result) > 0)
        return $db->where('id', $subcat[0]->type)->get(T_LANGS)[0];
    else
        return false;
}
function CalculateTrendingScore($vid)
{
    $likeScore = ($vid->likes - $vid->dislikes) * TRENDING_VIDEO_LIKES_SCORE;
    $viewScore = $vid->views * TRENDING_VIDEO_VIEWS_SCORE;
    $daysPast = intval(abs($vid->time_raw - time()) / (3600 * 24) );
    $timeScore = -1 * intval( $daysPast / TRENDING_VIDEO_DAY_PERIOD ) * TRENDING_VIDEO_TREND_SCORE_LOW_LIMIT;
    return $likeScore + $viewScore + $timeScore;
}
function sortVideosByTrendScore($vids)
{
    $trendScore = array();
    $result = array();
    foreach($vids as $key => $vid) {
        $get_video = PT_GetVideoByID($vid->id, 1, 1, 2);
        $trendScore[$key] = CalculateTrendingScore($get_video);
    }
    arsort($trendScore);
    $i = 0;
    foreach($trendScore as $key => $score) {
        $result[$i] = $vids[$key];
        $i++;
    }
    return $result;
}