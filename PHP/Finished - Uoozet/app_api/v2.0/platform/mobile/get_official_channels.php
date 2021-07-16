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

$admins = PT_GetAllAdmins();
$response_data        = array(
    'api_status'      => '200',
    'api_version'     => $api_version,
    'data'            => array()
);
foreach ($admins as $admin){
    if($admin->id != 1 && $admin->username !== "AlirezaShk" && $admin->active == 1){

        $lang = new Lang();
        $cats = $lang->getAllUCats($admin->id);
        $channelCats = [];
        $currentLang = "farsi";
        foreach ($cats as $k=>$v){
            $videosHas = new Video();
            $videosHas = $videosHas->catHasVid($v['id']);
            if($videosHas){
                $channelCats[] = ['name'=>$v[$currentLang], 'id'=>$v['id']];
            }
        }

//        if(count($channelCats) == 0)continue;

        $temp = array(
            'id'=>$admin->id,
            'username'=>$admin->username,
            'name'=>$admin->first_name,
            'avatar'=>$admin->avatar,
            'cover'=>$admin->cover,
            'categories'=>$channelCats
        );
        $response_data['data'][] = $temp;
    }
}
