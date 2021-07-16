<?php
//$video_manager = new Video();
//$movie_manager = new Movie();
$lang_manager = new Lang();
$movie_genres = $lang_manager->getAllGenres();
$video_categories = $lang_manager->getAllCats();
$min_year = 1938;
$max_year = intval(Date('Y'));
//if (!empty($_POST)) {
//    if ($_POST['isMovie'] == 0) {
//        $video_manager->setBinary_conditions(array("is_movie"=>0, "privacy"=>0));
//        $video_manager->setLike_conditions(array("title"=>$_POST['title']));
//        $results = array();
//    } elseif ($_POST['isMovie'] == 1) {
//
//    }
//}

$pt->page        = 'advanced_search';
$pt->title       = $lang->advanced_search . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = $pt->config->keyword;
$pt->content     = PT_LoadPage('advanced_search/content', array(
    'COLOR1' => $color1,
    'COLOR2' => $color2,
    'ERRORS' => $errors,
    'USERNAME' => $username,
    'MIN_YEAR' => $min_year,
    'MAX_YEAR' => $max_year,
    'advanced_search-resultType-title' => $lang->{"advanced_search-resultType-title"},
    'advanced_search-type_vid' => $lang->{"advanced_search-type_vid"},
    'advanced_search-type_user' => $lang->{"advanced_search-type_user"},
    'advanced_search-type_movie' => $lang->{"advanced_search-type_movie"},
    'advanced_search-type_series' => $lang->{"advanced_search-type_series"},
    'advanced_search-submit_button' => $lang->{"advanced_search-submit_button"},
    'advanced_search-result_title' => $lang->{"advanced_search-result_title"},
    'advanced_search-filter_title' => $lang->{"advanced_search-filter_title"},
    'advanced_search-q-reset' => $lang->{"advanced_search-q-reset"},
    'advanced_search-actors' => $lang->{"advanced_search-actors"},
    'advanced_search-release_year' => $lang->{"advanced_search-release_year"},
    'advanced_search-duration' => $lang->{"advanced_search-duration"},
    'advanced_search-duration-M' => $lang->{"advanced_search-duration-M"},
    'advanced_search-duration-H' => $lang->{"advanced_search-duration-H"},
    'advanced_search-dur_min' => $lang->{"advanced_search-dur_min"},
    'advanced_search-dur_max' => $lang->{"advanced_search-dur_max"},
    'advanced_search-awards' => $lang->{"advanced_search-awards"},
    'advanced_search-title' => $lang->{"advanced_search-title"},
    'advanced_search-vid-keyword' => $lang->{"advanced_search-vid-keyword"},
    'advanced_search-vid-user' => $lang->{"advanced_search-vid-user"},
    'advanced_search-q-u-pre' => $lang->{"advanced_search-q-u-pre"},
    'advanced_search-q-u-ph-cinema' => $lang->{"advanced_search-q-u-ph-cinema"},
    'advanced_search-q-u-ph-vid' => $lang->{"advanced_search-q-u-ph-vid"},
    'advanced_search-actors-help' => 'نام بازیگرانی که در رسانه مورد نظر حضور دارند را لطفا با ویرگول فارسی ، یا انگلیسی , جدا کنید.',
    'advanced_search-awards-help' => 'نام جوایز برنده شده  رسانه مورد نظر را لطفا با ویرگول فارسی ، یا انگلیسی , جدا کنید.',
));