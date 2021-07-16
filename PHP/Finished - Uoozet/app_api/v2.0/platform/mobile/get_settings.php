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
$lang = new Lang();
$cats = $lang->getAllCats();
$sub_cats = $lang->getAllSubCats();
if (!PT_IsAdmin()) {
	$config = array_intersect_key($config, array_flip($site_public_data));
}
if (empty($_GET['user_id'])) {

    $response_data       = array(
        'api_status'     => '200',
        'api_version'    => $api_version,
        'data'           => array(
            'site_settings'  => $config,
            'categories'     => $cats,
            'sub_categories' => $sub_cats
        )
    );
}else{
    $ucats = $lang->getAllUCats($_GET['user_id']);
    $sub_ucats = $lang->getSubUCatsOf($_GET['user_id']);
    $list = $db->where('to_user_id', $_GET['user_id'])->where('seen_status',0)->get(T_SHAREDMEDIA);
    $count = count($list);
    foreach ($list as $item){
        if($item->type == 0){
            $video = $db->where('id', $item->media_id)->getOne(T_VIDEOS);
            if(!$video){
                $count--;
            }
        }
    }

    $response_data       = array(
        'api_status'     => '200',
        'api_version'    => $api_version,
        'data'           => array(
            'site_settings'  => $config,
            'categories'     => $cats,
            'sub_categories'     => $sub_cats,
            'ucategories'    => $ucats,
            'sub_ucategories'=> $sub_ucats,
            'badge' =>['count'=>$count]
        )
    );
}

