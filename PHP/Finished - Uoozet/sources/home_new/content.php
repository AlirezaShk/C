<?php
$pt->page        = 'home_new';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');

$pt->page_url_ = $pt->config->site_url;
if (!isset($_GET['cat_q']))
    $pt->content = PT_LoadPage('home_new/content');
else {
    $results = "";
    $list_class = "r1-1";
    $url = $site_url;
    switch(intval($_GET['cat_q'])) {
        case 0:
            /* VIDEO SHARING */
            $aid = 1;
            $vm = new Video();
            $vm->setBinary_conditions(array("is_movie"=>0, "privacy"=>0, "is_channel" => 0));
            $vm->setOrder(array("id"=>'DESC'));
            $vm->setCountLimit(12);
            $res_array = $vm->getMatches();
            $results = $vm->generateViewList($res_array);
            $list_class = "c1-1-1-1 r1-1-1";
            $url .= "/video-sharing";
            break;
        case 1:
            /* TV */
            $aid = 2;
            $um = new User();
            $res_array = $um->getAllChannels();
            $res_target = array();
            for ($i = 0; $i < 2; $i++)
                if (isset($res_array[$i])) $res_target[$i] = $res_array[$i];
            if (count($res_target) > 0)
                $results = $um->generateViewList($res_target, 6);
            $list_class = "r1-1";
            $url .= "/tv";
            break;
        case 2:
            /* MOVIES */
            $aid = 3;
            $mm = new Movie();
            $mm->setCountLimit(5);
            $res_array = $mm->getMatches();
            $results = $mm->generateViewList($res_array);
            $url .= "/movie-sharing";
            break;
        case 3:
            /* ARTICLES */
            $aid = 4;
            $am = new Article();
            $res_array = $am->getAll(3);
            $results = $am->generateViewList($res_array);
            break;
        case 4:
            /* AUDIO BOOKS */
            $aid = 5;
            break;
        case 5:
            /* RADIO AND MUSIC */
            $aid = 6;
            break;
        default:
            exit;
    }
    $am = new Alumni();
    $langManager = new Lang();
    $info = $am->getOne($aid);
    $finisher_txt = $langManager->getOne('HM_finisher_goto');
    $content = PT_LoadPage('home/category_info', array(
        'TITLE' => $langManager->getOneById($info['title'])[$_SESSION['lang']],
        'DESCRIPTION' => $langManager->getOneById($info['description'])[$_SESSION['lang']],
        'IMAGE' => $info['thumbnail'],
        'LIST_CLASS' => $list_class,
        'CAT_QUEUE' => intval($_GET['cat_q']),
        'RESULTS' => $results,
        'CAT_URL' => $url,
        'CAT_URL_TEXT' => ((strlen($finisher_txt[$_SESSION['lang']]) > 0 AND count($finisher_txt) > 0) ? ($finisher_txt[$_SESSION['lang']]) : ('View More')),
        'SHOW' =>
            (($aid === 2) ? (
                ((count($res_target) <= 1) ? ('hidden') : (''))
            ) : (''))
    ));
    if (strlen($results) > 0)
        echo json_encode([
            "status" => TRUE,
            "content" => $content
        ]);
    else
        echo json_encode([
            'status' => FALSE
        ]);
    exit;
}
//if($pt->theme_using == 'mana')
