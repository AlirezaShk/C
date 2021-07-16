<?php
if (IS_LOGGED == false) {
    header("Location: " . PT_Link('login'));
    exit();
}
if (empty($_GET['id'])) {
    header("Location: " . PT_Link('login'));
    exit();
}
$id    = PT_Secure($_GET['id']);
$video = $db->where('id', $id)->getOne(T_VIDEOS);
if (empty($video)) {
    header("Location: " . PT_Link('login'));
    exit();
}
if (!PT_IsAdmin()) {
    if (empty($db->where('id', $id)->where('user_id', $user->id)->getValue(T_VIDEOS, 'count(*)'))) {
        header("Location: " . PT_Link('login'));
        exit();
    }
}



$pt->sub_categories_array = array();
foreach ($pt->sub_categories as $cat_key => $subs) {
    $pt->sub_categories_array["'".$cat_key."'"] = '<option value="0">'.$lang->none.'</option>';
    foreach ($subs as $sub_key => $sub_value) {
        $pt->sub_categories_array["'".$cat_key."'"] .= '<option value="'.array_keys($sub_value)[0].'" '.(($video->sub_category == array_keys($sub_value)[0]) ? "selected" : "") .'>'.$sub_value[array_keys($sub_value)[0]].'</option>';
    }
}
//if(count($pt->sub_categories_array) == 0){
//    foreach ($pt->sub_ucategories as $cat_key_ => $subs_) {
//        foreach($subs_ as $cat_key => $subs){
//            $pt->sub_categories_array["'".$cat_key."'"] = '<option value="0">'.$lang->none.'</option>';
//            foreach ($subs as $sub_key => $sub_value) {
//                $pt->sub_categories_array["'".$cat_key."'"] .= '<option value="'.array_keys($sub_value)[0].'" '.(($video->sub_category == array_keys($sub_value)[0]) ? "selected" : "") .'>'.$sub_value[array_keys($sub_value)[0]].'</option>';
//            }
//        }
//    }
//}
//print_r($pt->sub_categories);
//exit();

$pt->page_url_ = $pt->config->site_url.'/edit-video/'.$id;
if($video->is_movie == 2 || $video->is_movie == 3)
    $video           = PT_GetMusicByID($video, 0, 0, 0);
else
    $video           = PT_GetVideoByID($video, 0, 0, 0);
$vidCatDetail = PT_getCategoryDetails($video->category_id);
if(strpos($vidCatDetail->lang_key,"ub__"))
    $vidCatDetail = PT_getMotherCategory($vidCatDetail->id);
if(strpos($vidCatDetail->type,"at-") || $video->owner->admin == 1)
    $isChannelVid = 1;
else
    $isChannelVid = 0;

$categoryInfoString = "";
$catArray = explode("|", $video->categories);
$langManager = new Lang();
$i = 0;
foreach ($catArray as $cat) {
    $parent = $langManager->getParentCat($cat);
    $catInfo = $langManager->getOneById($cat);
    if ($catInfo['type'] !== 'category') {
        $categoryInfoString .= $cat;
        if (is_array($parent) and ($parent !== FALSE) and (count($parent) > 0)) {
            $categoryInfoString .= " -- ";
        } else $categoryInfoString .= ": ";
    }
    $categoryInfoString .= $catInfo[$_SESSION['lang']];
    if ($i++ === (count($catArray) - 1)) break;
    else $categoryInfoString .= ",";
}
$pt->video       = $video;
$pt->page        = 'edit-video';
$pt->title       = $lang->edit_video . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = $pt->config->keyword;
$pt->ShowingUCATS = (PT_UserData($video->owner->id)->admin) ? true : false;
$pt->content     = PT_LoadPage('edit-video/content', array(
    'ID' => $video->id,
    'USER_DATA' => $video->owner,
    'THUMBNAIL' => $video->thumbnail,
    'URL' => $video->url,
    'TITLE' => $video->title,
    'DESC' => br2nl($video->edit_description),
    'DESC_2' => $video->markup_description,
    'VIEWS' => $video->views,
    'TIME' => $video->time_ago,
    'TAGS' => $video->tags,
    'CATEGORIES' => $categoryInfoString,
    'ChanneluserID' => $video->owner->id,
    'isChannelVid' => $isChannelVid
));
