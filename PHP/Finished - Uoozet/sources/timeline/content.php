<?php
if (empty($_GET['id'])) {
    header("Location: " . PT_Link(''));
    exit();
}
$langManager = new Lang();
$videoManager = new Video();
$userManager = new User();
$username = PT_Secure($_GET['id']);
$user_id = intval($userManager->getOne($username, User::BY_USERNAME)['id']);
$lists    = false;
if (empty($user_id)) {
    header("Location: " . PT_Link(''));
    exit();
}
if(isset($_GET['vid']))
    $vid = intval(PT_Secure($_GET['vid']));
else{
    $videoManager->setBinary_conditions(array("user_id"=>$user_id));
    $videoManager->setCountLimit(1);
    $videoManager->setOrder(array("id"=>"DESC"));
    $vid = $videoManager->getMatches()['id'];
}
$pt->page_url_ = $pt->config->site_url.'/@'.$username ;
$pt->second_page = 'videos';
if (!empty($_GET['page'])) {
    switch ($_GET['page']) {
        case 'liked-videos':
            $pt->second_page = 'liked-videos';
            break;
        case 'about':
            $pt->second_page = 'about';
            break;
        case 'play-lists':
            $pt->second_page = 'play-lists';
            $lists           = true;
            break;
        case 'short-videos':
            $pt->second_page = 'short-videos';
            break;

    }
    $pt->page_url_ = $pt->config->site_url.'/@'.$username."?page=".$pt->second_page;
}
//$cat = (count(explode("__",$_GET['cat'])) > 1) ? (explode("__",$_GET['cat'])[1]) : $_GET['cat'];
$cat = ((isset($_GET['cat'])) ? intval($_GET['cat']) : -1);
$ad_media = '';
$ad_link = '';
$ad_skip = 'false';
$ad_skip_num = 0;
$is_video_ad = '';
$is_vast_ad = '';
$vast_url = '';
$vast_type = '';
$last_ads = 0;
$ad_image = '';
$ad_link = '';
$sidebar_ad = PT_GetAd('watch_side_bar');
$is_pro  = false;
$user_ad_trans = '';
$ad_desc = '';
$ads_sys = ($pt->config->user_ads == 'on') ? true : false;
$vid_monit = true;

$user_data   = PT_UserData($user_id);
$pt->isowner = false;
if (IS_LOGGED == true) {
    if ($user_data->id == $user->id) {
        $pt->isowner = true;
    }
}
$videos_html       = '';
$videos_count      = 0;
$get_video_query   = 1;
$watch_later_list  = 0;

$playBox = '';

$cats = "";
if($user_data->admin == 1){
//    try {
//        $all_categories = $db->where('type',$username.'_category')->get(T_LANGS);
//        $sub_categories = array();
//        foreach ($all_categories as $key => $value) {
//            $array_keys = array_keys($all_categories);
//            if ($value->lang_key != 'other') {
//                if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
//                    $catHasVideo = $db->where('category_id',$value->lang_key)->get(T_VIDEOS);
////                if(count($catHasVideo) > 0)
//                    $categories[$value->lang_key] = $lang->{$value->lang_key};
//                }
//                $all_sub_categories = $db->where('type',$value->lang_key)->get(T_LANGS);
//
//                if (!empty($all_sub_categories)) {
//                    foreach ($all_sub_categories as $key => $sub) {
//                        $array = array();
//                        if (!empty($sub->lang_key) && !empty($lang->{$sub->lang_key})) {
//                            $array[$sub->lang_key] = $lang->{$sub->lang_key};
//                            $sub_categories[$value->lang_key][] = $array;
//                        }
//                    }
//                }
//            }
//            if (end($array_keys) == $key) {
//                $categories['other'] = $lang->other;
//            }
//
//        }
//    } catch (Exception $e) {
//
//    }
    //-------------- new Channel -  Categories -------------//

//    error_reporting(E_ALL);
//    ini_set("display_errors", 1);
    try {
//        $type = $langManager->getOneById($cat)['type'];
//        $owner_id = intval(explode("-",$type)[1]);
        $owner_id = $user_data->id;
        $all_ucats = $langManager->getAllUCatsOf($owner_id,Lang::RETURN_OBJECT);
        $sub_ucats = array();
        $ucats = array();
        foreach($all_ucats as $ucat_) {
            $catHasVideo = $videoManager->catHasVid($ucat_->id);
//            if (!$catHasVideo) continue;
//            if(!isset($ucats[explode("-",$ucat_->type)[1]]))
//                $ucats[explode("-",$ucat_->type)[1]] = array();
            $ucats[$ucat_->id] = $ucat_->{$_SESSION['lang']};
//            $ucats[explode("-",$ucat_->type)[1]][$ucat_->id] = $ucat_->{$_SESSION['lang']};
//            $hasAmother = $langManager->getParentCat($ucat_->id, Lang::RETURN_OBJECT);
//            if(!$hasAmother) {
            $all_sub_ucats = $langManager->getSubCats($ucat_->id, Lang::RETURN_OBJECT);
//            }
////                $all_sub_ucats = $db->where('type',$ucat_->id)->get(T_LANGS);
//            else
//            {
//                $all_sub_ucats = $langManager->getSubUCatsOf($hasAmother->id, Lang::RETURN_OBJECT);
////                $all_sub_ucats = $db->where('type',$hasAmother->id)->get(T_LANGS);
//                $ucat_->id = $hasAmother->id;
//            }
//            print_r($all_sub_ucats);
            if (!empty($all_sub_ucats)) {
                if (!isset($all_sub_ucats->{0})) {
                    {
                        $sub = $all_sub_ucats;
                        $array[$sub->id] = $langManager->getOneById($sub->id)[$_SESSION['lang']];
                        $sub_ucats[$ucat_->id][$sub->id] = $langManager->getOneById($sub->id)[$_SESSION['lang']];
                    }
                } else {
                    foreach ($all_sub_ucats as $key2 => $sub) {
                        //                    $array = array();
                        //                    if (!empty($sub->id) && !empty($lang->{$sub->lang_key})) {
                        //                        $array[$sub->id] = $lang->{$sub->lang_key};
                        $array[$sub->id] = $langManager->getOneById($sub->id)[$_SESSION['lang']];
                        $sub_ucats[$ucat_->id][$sub->id] = $langManager->getOneById($sub->id)[$_SESSION['lang']];
                        //                    }
                    }
                }
            }
        }
//        if (end($array_keys) == $key) {
//            $ucats['other'] = $lang->other;
//        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
//    print_r($ucats);
//    print_r($sub_ucats);
//--------------- channel - categories ---------------//
//    try {
//        $all_ucategories = $db->rawQuery("SELECT * FROM `" . T_LANGS . "` WHERE `type` LIKE 'cat-%' ORDER BY `type`");
//        $sub_ucategories = array();
//        $ucategories = array();
//        foreach ($all_ucategories as $key => $value) {
//            $array_keys = array_keys($all_ucategories);
//            if ($value->lang_key != 'other') {
//                if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
//                    $subcats__ = PT_getSubCategories($value->id);
//                    if(count($subcats__) > 0){
//                        $subcats__text = "(";
//                        foreach ($subcats__ as $k => $v){
//                            $subcats__text .= $v->id;
//                            if(end($subcats__) != $v)
//                                $subcats__text .= ", ";
//                        }
//                        $subcats__text .= ")";
////                        echo $subcats__text;
////                        echo $value->lang_key;
//                        $catHasVideo = $db->rawQuery("SELECT * FROM `" . T_VIDEOS . "` WHERE `category_id`='".$value->lang_key."' OR `category_id` IN ".$subcats__text);
//                    }
//                    else
//                    {
//                        $catHasVideo = $db->rawQuery("SELECT * FROM `" . T_VIDEOS . "` WHERE `category_id`='".$value->lang_key."'");
////                        print_r($catHasVideo);
//                    }
//                    if(count($catHasVideo) > 0)
//                    {
//                        if(!is_array($ucategories[explode("-",$value->type)[1]]))
//                            $ucategories[explode("-",$value->type)[1]] = array();
//                        $ucategories[explode("-",$value->type)[1]][$value->lang_key] = $lang->{$value->lang_key};
//                    }
//                    //--------------------------------
//                        $hasAmother = PT_getMotherCategory($cat);
//                        print_r($hasAmother);
//                    $cat_ = $value->lang_key;
////                        if(!$hasAmother)
//                    $all_sub_ucategories = $db->where('type',$cat_)->get(T_LANGS);
////                        else
////                        {
////                            $all_sub_ucategories = $db->where('type',$hasAmother->id)->get(T_LANGS);
////                            $cat_ = $hasAmother->id;
////                        }
//                    if (!empty($all_sub_ucategories)) {
//                        foreach ($all_sub_ucategories as $key2 => $sub) {
//                            $array = array();
//                            if (!empty($sub->lang_key) && !empty($lang->{$sub->lang_key})) {
//                                $array[$sub->lang_key] = $lang->{$sub->lang_key};
//                                $sub_ucategories[$cat_][] = $array;
//                            }
//                        }
//                    }
//                }
//            }
//            if (end($array_keys) == $key) {
//                $ucategories['other'] = $lang->other;
//            }
//        }
//        if(strlen($cat) > 0){
//            $hasAmother = PT_getMotherCategory($cat);
//            print_r($hasAmother);
//            $cat_ = $cat;
//            if(!$hasAmother)
//                $all_sub_ucategories = $db->where('type',$cat)->get(T_LANGS);
//            else
//            {
//                $all_sub_ucategories = $db->where('type',$hasAmother->id)->get(T_LANGS);
//                $cat_ = $hasAmother->id;
//            }
//            if (!empty($all_sub_ucategories)) {
//                foreach ($all_sub_ucategories as $key2 => $sub) {
//                    $array = array();
//                    if (!empty($sub->lang_key) && !empty($lang->{$sub->lang_key})) {
//                        $array[$sub->lang_key] = $lang->{$sub->lang_key};
//                        $sub_ucategories[$cat_][] = $array;
//                    }
//                }
//            }
//        }
//    }
//    catch (Exception $e) {
//        echo $e->getMessage();
//    }
    $cats  = ToObject($ucats);
    //--------------------------------

$query = array();
$query['cond'] = ' WHERE ';
if ($pt->second_page == 'videos') {
    if (IS_LOGGED == true) {
        if ($user_data->id != $user->id) {
            $videoManager->setBinary_conditions(array("privacy"=>0));
//            $db->where('privacy', 0);
//            $query['cond'] .= "`privacy`=0";
//            $query['cond'] .= " AND ";
        }
    } else {
        $videoManager->setBinary_conditions(array("privacy"=>0));
//        $db->where('privacy', 0);
//        $query['cond'] .= "`privacy`=0";
//        $query['cond'] .= " AND ";
    }
    if($cat !== -1)
    {
        $getChildren = $langManager->getSubCats($cat);
        $a = array("categories"=>array("%".$cat."|%"));
        if(count($getChildren) !== 0) {
            if (isset($getChildren['id'])) $getChildren = array($getChildren);
            foreach($getChildren as $childCat) {
                $a['categories'][] = "%".$childCat['id']."|%";
            }
        }
        $videoManager->setLike_conditions($a);
    }
    $videoManager->setBinary_conditions(array("is_movie"=>0, "user_id"=>$user_data->id));
    $videoManager->setOrder(array("id"=>"DESC"));
    $videos = $videoManager->getMatches(Video::RETURN_OBJECT);
    if(!isset($videos->id)) $headVideo = PT_GetVideoByID($videos->{0}->id);
    else $headVideo = PT_GetVideoByID($videos->id);
}
elseif ($pt->second_page == 'short-videos') {
    if (IS_LOGGED == true) {
        if ($user_data->id != $user->id) {
            $query['cond'] .= "`privacy`=0";
            $query['cond'] .= " AND ";
        }
    } else {
        $query['cond'] .= "`privacy`=0";
        $query['cond'] .= " AND ";
    }
    if($cat){
        $cat_array = array();
        array_push($cat_array,$cat);
        $k = 1;
        for($i = 0; $i < $k; $i++){
            $new_cats = PT_getSubCategories($cat_array[0]);
            if(count($new_cats) > 0)
            {
                array_push($cat_array,$new_cats);
                $k += count($new_cats);
            }
        }
        $cat_array = "(". implode(", ",$cat_array) .")";
        $query['cond'] .= "`category_id` IN " . $cat_array;
        $query['cond'] .= " AND ";
    }
//    $videos = $db->where('user_id', $user_data->id)->where('is_movie',5)->orderBy('id', 'DESC')->get(T_VIDEOS, 20, 'video_id');
    $query['cond'] .= "`user_id` = " . $user_data->id . " AND `is_movie` = 5 ORDER BY `id` DESC";
    $query['info'] = "SELECT `video_id` FROM `".T_VIDEOS."`";
    $videos = $db->rawQuery($query['info'] . $query['cond']);
    $headVideo = PT_GetVideoByID($videos[0]->id);
}

if ($pt->second_page == 'liked-videos') {
    $videos = $db->where('user_id', $user_data->id)->where('type', 1)->orderBy('id', 'DESC')->get(T_DIS_LIKES, 20);
    $get_video_query = 2;
}

if ($pt->second_page == 'play-lists') {

    if ($pt->isowner === true) {
        $playlists   = $db->where('user_id', $user_data->id)->get(T_LISTS);
        $watch_later = $db->where('user_id', $user_data->id)->orderBy('id', 'ASC')->getOne(T_WLATER);
        $wl_count    = $db->where('user_id', $user_data->id)->getValue(T_WLATER, 'count(*)');
        if (!empty($watch_later)) {
            $wl_video = PT_GetVideoByID($watch_later->video_id, 0, 0, 2);
            if (!empty($wl_video)) {
                $wl_video_id  = $watch_later->video_id;
                $videos_html .= PT_LoadPage('playlist/wl-list', array(
                    'TITLE' => "Watch Later",
                    'THUMBNAIL' => $wl_video->thumbnail,
                    'COUNT' => $wl_count,
                    'URL' => PT_Link('watch/' . PT_Slug($wl_video->title, $wl_video->video_id) . "?list=wl"),
                    'LIST_ID' => 'wl',
                    'VIDEO_ID_' => PT_Slug($wl_video->title, $wl_video->video_id)
                ));
            }
        }
    }

    else{
        $playlists   =  $db->where('user_id', $user_data->id)->where('privacy', 1)->get(T_LISTS);
    }

}
$headVideo = null;
$backup = null;
if (!empty($videos) && !$lists) {
    $videos = (array) $videos;
    if (isset($videos['id'])) {
        $videos = array($videos);
    }
    $videos_count = count($videos);
    foreach ($videos as $key => $video) {
        $video = (object) $video;
        $video_get = PT_GetVideoByID($video->id, 0, 0, 2);
        if($key == 0)
        {
            $backup = $video_get;
        }
        if($video_get->id == $vid)
            $headVideo = $video_get;
        $video_id  = $video_get->id;
        if ($get_video_query == 2) {
            $video_id = $video->id;
        }
        $videos_html .= PT_LoadPage('lists/timeline-videos-list', array(
            'ID' => $video_id,
            'VID_ID' => $video_get->id,
            'TITLE' => $video_get->title,
            'VIEWS' => $video_get->views,
            'VIDEO_LOCATION' => $video_get->video_location,
            'VIEWS_NUM' => number_format($video_get->views),
            'USER_DATA' => $video_get->owner,
            'THUMBNAIL' => $video_get->thumbnail,
            'URL' => $video_get->url,
            'TIME' => $video_get->time_ago,
            'DURATION' => $video_get->duration,
            'VIDEO_ID_' => PT_Slug($video_get->title, $video_get->video_id)
        ));
    }
    if(is_null($headVideo))
        $headVideo = $backup;
}
elseif(!empty($playlists) && $lists){

    foreach ($playlists as $key => $list) {
        $list_id       = $list->list_id;
        $video         = $db->where('list_id', $list->list_id)->orderBy('id', 'asc')->getOne(T_PLAYLISTS);
        if (isset($video->video_id)) {
            $video_get = PT_GetVideoByID($video->video_id, 0, 0, 2);
            $vid_count = $db->where('user_id', $user_id->id)->where('list_id', $list_id)->getValue(T_PLAYLISTS, 'count(*)');
            if (!empty($video_get)) {
                $videos_html .= PT_LoadPage('playlist/list', array(
                    'ID' => $list->id,
                    'TITLE' => $list->name,
                    'THUMBNAIL' => $video_get->thumbnail,
                    'COUNT' => $vid_count,
                    'URL' => PT_Link('watch/' . PT_Slug($video_get->title, $video_get->video_id) . "/list/$list_id"),
                    'LIST_ID' => $list_id,
                    'VIDEO_ID_' => PT_Slug($video_get->title, $video_get->video_id)
                ));
            }
        }
    }
}
$pt->video_240 = 0;
$pt->video_360 = 0;
$pt->video_480 = 0;
$pt->video_720 = 0;
if ($pt->config->ffmpeg_system == 'on') {
    $explode_video = explode('_video', $headVideo->video_location);
    if ($headVideo->{"240p"} == 1) {
        $pt->video_240 = $explode_video[0] . '_video_240p_converted.mp4';
    }
    if ($headVideo->{"360p"} == 1) {
        $pt->video_360 = $explode_video[0] . '_video_360p_converted.mp4';
    }
    if ($headVideo->{"480p"} == 1) {
        $pt->video_480 = $explode_video[0] . '_video_480p_converted.mp4';
    }
    if ($headVideo->{"720p"} == 1) {
        $pt->video_720 = $explode_video[0] . '_video_720p_converted.mp4';
    }
}
$likes     = $db->where('video_id', $headVideo->id)->where('type', 1)->getValue(T_DIS_LIKES, "count(*)");
$dislikes  = $db->where('video_id', $headVideo->id)->where('type', 2)->getValue(T_DIS_LIKES, "count(*)");


    //TAGS
    $vidTags = $headVideo->tags;
    $vidTags_array = array();
    $vidTags_array = explode("#",$vidTags);
    unset($vidTags_array[0]);
    $vidTags = "";
    foreach ($vidTags_array as $k => $tag) {
        $vidTags .= "<a href='" . PT_Link("search") . "?keyword=$tag'>#$tag</a><br>";
    }
//    print_r($vidTags);
//    exit;
    //END TAGS

    $pt->video_approved = true;
    $pt->video_type = 'public';

    if ($headVideo->privacy == 1) {
        if (!IS_LOGGED) {
            $pt->video_type = 'private';
        } else if (($headVideo->user_id != $user->id) && ($user->admin == 0)) {
            $pt->video_type = 'private';
        }
    }

    $pt->is_paid = 0;
    if ($headVideo->sell_video > 0) {
        if (!empty($user->id)) {
            $pt->is_paid = $db->where('video_id',$headVideo->id)->where('paid_id',$user->id)->getValue(T_VIDEOS_TRSNS,"count(*)");
        }
        $pt->purchased = $db->where('video_id',$headVideo->id)->getValue(T_VIDEOS_TRSNS,"count(*)");
    }

    $headVideo->age = false;
    if ($headVideo->age_restriction == 2) {
        if (!IS_LOGGED) {
            $headVideo->age = true;
        } else {
            if (($headVideo->user_id != $user->id) && !is_age($user->id)) {
                $headVideo->age = true;
            }
        }
    }
    $pt->converted = true;
    $playBox .= PT_LoadPage('timeline/pages/play-box', array(
        'HEADVIDEO'=>$headVideo->video_location,
        'HEADPIC'=>$headVideo->thumbnail,
        'LIKES'=>$likes,
        'DISLIKES'=>$dislikes,
        'VIDEO_LOCATION' => $headVideo->video_location,
        'ENCODED_URL' => urlencode($headVideo->url),
        'URL' => $headVideo->url,
        'ID' => $headVideo->id,
        'VIDEO_LOCATION_240' => $pt->video_240,
        'VIDEO_LOCATION_360' => $pt->video_360,
        'VIDEO_LOCATION_480' => $pt->video_480,
        'VIDEO_LOCATION_720' => $pt->video_720,
        'LIKE_ACTIVE_CLASS' => ($headVideo->is_liked > 0) ? 'active' : '',
        'DIS_ACTIVE_CLASS' => ($headVideo->is_disliked > 0) ? 'active' : '',
        'ISLIKED' => ($headVideo->is_liked > 0) ? 'liked="true"' : '',
        'ISDISLIKED' => ($headVideo->is_disliked > 0) ? 'disliked="true"' : '',
        'RAEL_LIKES' => $likes,
        'RAEL_DISLIKES' => $dislikes,
//

        'KEY' => $headVideo->video_id,
        'THUMBNAIL' => $headVideo->thumbnail,
        'TITLE' => $headVideo->title,
        'DESC' => $headVideo->markup_description,
        'TAGS' => $vidTags,

        'VIDEO_LOCATION_1080' => $pt->video_1080,
        'VIDEO_LOCATION_4096' => $pt->video_4096,
        'VIDEO_LOCATION_2048' => $pt->video_2048,
        'VIDEO_TYPE' => $video_type,
        'VIDEO_MAIN_ID' => $headVideo->video_id,
        'VIDEO_ID' => $headVideo->video_id_,
        'USER_DATA' => $user_data,
        'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
        'VIDEO_SIDEBAR' => $video_sidebar,
        'LIST_SIDEBAR' => $list_sidebar,
        'LIST_OWNERNAME' => $list_user_name,
        'VID_INDEX' => $video_index,
        'LIST_COUNT' => $list_count,
        'LIST_NAME' => $pt->list_name,
        'VIDEO_NEXT_SIDEBAR' => $next_video,
        'COOKIE' => $checked,
        'VIEWS' => number_format($headVideo->views),

        'VIDEO_COMMENTS' => PT_LoadPage('watch/video-comments',array(
            'COUNT_COMMENTS' => $pt->count_comments,
            'COMMENTS' => $comments,
            'PINNED_COMMENTS' => $pinned_comments,
            'URL' => $headVideo->url,
            'VIDEO_ID' => $headVideo->id
        )),

        'SAVED_BUTTON' => $save_button,
        'IS_SAVED' => ($is_saved > 0) ? 'saved="true"' : '',
        'CATEGORY' => $headVideo->category_name,
        'CATEGORY_ID' => $headVideo->category_id,
        'TIME' => $headVideo->time_alpha,
        'VAST_URL' => $vast_url,
        'VAST_TYPE' => $vast_type,
        'AD_MEDIA' => "'$ad_media'",
        'AD_LINK' => "'$ad_link'",
        'AD_P_LINK' => "$ad_link",
        'AD_SKIP' => $ad_skip,
        'AD_SKIP_NUM' => $ad_skip_num,
        'ADS' => $is_video_ad,
        'USER_ADS_DESC_OVERLAY' => $ad_desc,
        'VAT' => $is_vast_ad,
        'AD_IMAGE' => $ad_image,
        ////

        ///
        'COMMENT_AD' => PT_GetAd('watch_comments'),
        'WATCH_SIDEBAR_AD' => $sidebar_ad,
        'USR_AD_TRANS' => $user_ad_trans,
        'CURRENCY'   => $currency,
        'SUB_CATEGORY' => $pt->sub_category,
        'VIDEO_ID_' => $headVideo->video_id,
    ));

    $ucats = $langManager->getAllUCatsOf($user_data->id);
    $ucats = $langManager->formatCatArrayIDtoLANG($ucats, $_SESSION['lang']);
    $sub_ucats = $langManager->getSubUCatsOf($user_data->id);
    $sub_ucats = $langManager->formatSubCatArrayIDtoLANG($sub_ucats, $_SESSION['lang']);
    $category_panel =  $langManager->generateCategoryPanel($ucats, $sub_ucats, '?cat=*');
    $catButton = '<li>
				<a class="openCatButton active" onclick="openCatPanel()">دسته بندی ها</a>
			</li>';
}else{
    $videoManager->setBinary_conditions(array("is_movie"=>0, "user_id"=>$user_data->id));
    $videoManager->setOrder(array("id"=>"DESC"));
    $videos = $videoManager->getMatches(Video::RETURN_OBJECT);
    $videos = (array) $videos;
    if (isset($videos['id'])) {
        $videos = array($videos);
    }
    $videos_count = count($videos);
    $videos_html = "";
    foreach ($videos as $key => $video) {
        $video = (object) $video;
        $video_get = PT_GetVideoByID($video->id, 0, 0, 2);
        if($key == 0)
        {
            $backup = $video_get;
        }
        if($video_get->id == $vid)
            $headVideo = $video_get;
        $video_id  = $video_get->id;
        if ($get_video_query == 2) {
            $video_id = $video->id;
        }
        $videos_html .= PT_LoadPage('lists/timeline-videos-list', array(
            'ID' => $video_id,
            'VID_ID' => $video_get->id,
            'TITLE' => $video_get->title,
            'VIEWS' => $video_get->views,
            'VIDEO_LOCATION' => $video_get->video_location,
            'VIEWS_NUM' => number_format($video_get->views),
            'USER_DATA' => $video_get->owner,
            'THUMBNAIL' => $video_get->thumbnail,
            'URL' => $video_get->url,
            'TIME' => $video_get->time_ago,
            'DURATION' => $video_get->duration,
            'VIDEO_ID_' => PT_Slug($video_get->title, $video_get->video_id)
        ));
    }
    $playBox .= PT_LoadPage('timeline/pages/static-box', array(
        'COVER'       => $user_data->cover,
        'NAME'       => $user_data->name,
    ));
//    TODO: add category panel for normal users as well
/*    $cats = $langManager->getAllCats();
    $cats = $langManager->formatCatArrayIDtoLANG($cats, $_SESSION['lang']);
    $sub_cats = $langManager->getAllSubCats();
    $sub_cats = $langManager->formatSubCatArrayIDtoLANG($sub_cats, $_SESSION['lang']);
    $category_panel =  $langManager->generateCategoryPanel($cats, $sub_cats, '?cat=*');*/
    $category_panel = '';
    $catButton = '';
}


$empty_list = false;
if(empty($videos_html)){
    $empty_list = true;
    $videos_html = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_for_now . '</div>';
}

$pt->profile_fields = null;
$user__fields       = $db->where('profile_page','1')->where('active','1')->get(T_FIELDS);
$pt->profile_fields = !empty($user__fields) ? $user__fields : null;
$pt->user->fields   = $db->where('user_id',$user_data->id)->getOne(T_USR_PROF_FIELDS);
$pt->user->fields   = (is_object($pt->user->fields)) ? get_object_vars($pt->user->fields) : array();
$pt->custom_fields  = "";

if (!empty($pt->profile_fields)) {
    foreach ($pt->profile_fields as $field_data) {
        $field_data->fid  = 'fid_' . $field_data->id;
        $field_data->name = preg_replace_callback("/{{LANG (.*?)}}/", function($m) use ($pt) {
            return (isset($pt->lang->$m[1])) ? $pt->lang->$m[1] : '';
        }, $field_data->name);

        $field_data->description = preg_replace_callback("/{{LANG (.*?)}}/", function($m) use ($pt) {
            return (isset($pt->lang->$m[1])) ? $pt->lang->$m[1] : '';
        }, $field_data->description);

        if (!empty($pt->user->fields[$field_data->fid])) {
            $fid     = $pt->user->fields[$field_data->fid];
            $pt->fid = $fid;
            if ($field_data->type == 'select') {
                $options = @explode(',', $field_data->options);
                $fid     = $options[$pt->user->fields[$field_data->fid] - 1];
            }


            $pt->custom_fields .= PT_LoadPage('timeline/includes/custom-fields',array(
                "FID"  => $fid,
                "NAME" => $field_data->name,
                "DESC" => $field_data->description,
            ));
        }
    }
}
$followerCount = $db->where('user_id', $user_data->id)->getValue(T_SUBSCRIPTIONS, 'count(*)');
$followingCount = $db->where('subscriber_id', $user_data->id)->getValue(T_SUBSCRIPTIONS, 'count(*)');
$pt->profile_user  = $user_data;
$pt->videos_count  = $videos_count;
$pt->page          = 'timeline';
$pt->title         = $user_data->name . ' | ' . $pt->config->title;
$pt->description   = $pt->config->description;
$pt->keyword       = $pt->config->keyword;
$options = '';
foreach (PT_GetAllUsers() as $u){
    $options .= '<option value="'.$u->id.'">'.$u->name.'('.$u->username.')</option>';
}
$pt->content       = PT_LoadPage('timeline/content', array(
    'USER_DATA'       => $user_data,
    'SUBSCIBE_BUTTON' => PT_GetSubscribeButton($user_data->id),
    'MESSAGE_BUTTON'  => PT_GetMessageButton($user_data->username),
    'SECOND_PAGE'     => PT_LoadPage('timeline/pages/' . $pt->second_page, array(
        'VIDEOS'      => $videos_html,
        'USER_DATA'   => $user_data,
        'CUSTOM_FIELDS'   => $pt->custom_fields,
    )),
    'HEADVIDEO'=>$headVideo->video_location,
    'PLAY_BOX'=>$playBox,
    'USERS_LIST'=>$options,
    'EMPTY_LIST'=>$empty_list,
    'CATEGORY_PANEL' => $category_panel,
    'CAT_BUTTON' => $catButton,
    'USER_ID' => $user_data->id,
    'FOLLOWER' => $followerCount,
    'FOLLOWING' => $followingCount,
));
