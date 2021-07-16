<?php
if (empty($_GET['page'])) {
    header("Location: " . PT_Link('404'));
    exit();
}
$page         = PT_Secure($_GET['page']);
$limit        = 20;
$pt->rss_feed = false;
$pt->exp_feed = true;
$pages        = array(
    'trending',
    'category',
    'latest',
    'top'
);

if (!in_array($page, $pages)) {
    header("Location: " . PT_Link('404'));
    exit();
}
// pagination system
$pt->page_number = isset($_GET['page_id']) && is_numeric($_GET['page_id']) && $_GET['page_id'] > 0 ? $_GET['page_id'] : 1;
$pt->limit_per_page = !empty($pt->config->videos_load_limit) && is_numeric($pt->config->videos_load_limit) && $pt->config->videos_load_limit > 0 ? (int) $pt->config->videos_load_limit : 20;
$db->pageLimit = $pt->limit_per_page;
// pagination system

if (!empty($_GET['feed']) && $_GET['feed'] == 'rss') {
    $limit        = 50;
    $pt->rss_feed = true;

}
$pt->page_url_ = $pt->config->site_url.'/movies2/'.$page.'?page_id='.$pt->page_number;
$cateogry_id = '';
$videos = array();
if ($page == 'trending') {
    $title  = $lang->trending_movies;
    // $db->where('privacy', 0);
    // $videos = $db->where('time', time() - 172800, '>')->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);

    // pagination system
    $videos = $db->where('privacy', 0)->where('time', time() - 1296000, '>')->where('is_movie',1)->orderBy('views', 'DESC')->objectbuilder()->paginate(T_VIDEOS, $pt->page_number);
    $pt->total_pages = $db->totalPages;
    // pagination system
}

else if ($page == 'latest') {
    $title  = $lang->latest_movies;
    // $db->where('privacy', 0);
    // $videos = $db->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);

    // pagination system
    $videos = $db->where('privacy', 0)->where('is_movie',1)->orderBy('id', 'DESC')->objectbuilder()->paginate(T_VIDEOS, $pt->page_number);
    $pt->total_pages = $db->totalPages;
    // pagination system
}

else if ($page == 'top') {
    $title  = $lang->top_movies;
    // $db->where('privacy', 0);
    // $videos = $db->orderBy('views', 'DESC')->get(T_VIDEOS, $limit);

    // pagination system
    $videos = $db->where('privacy', 0)->where('is_movie',1)->orderBy('views', 'DESC')->objectbuilder()->paginate(T_VIDEOS, $pt->page_number);
    $pt->total_pages = $db->totalPages;
    // pagination system
}

else if ($page == 'category') {

    if (!empty($_GET['id'])) {
        if (in_array($_GET['id'], array_keys($pt->movies_categories))) {
            $pt->page_url_ = $pt->config->site_url.'/movies2/'.$page.'/'.$_GET['id'].'?page_id='.$pt->page_number;
            $cateogry = $_GET['id'];
            $title    = $pt->movies_categories[$cateogry];
            $cateogry_id = "data-category='$cateogry'";

            if (!empty($_GET['sub_id'])) {
                 $is_found = $db->where('type',PT_Secure($_GET['id']))->where('lang_key',PT_Secure($_GET['sub_id']))->getValue(T_LANGS,'COUNT(*)');
                if ($is_found > 0) {
                    $pt->page_url_ = $pt->config->site_url.'/movies2/'.$page.'/'.$_GET['id'].'/'.$_GET['sub_id'].'?page_id='.$pt->page_number;
                    $db->where('sub_category', PT_Secure($_GET['sub_id']));
                }
            }
            //$db->where('privacy', 0);
            //$category_old = str_replace('category__', '', $cateogry);
            // $videos   = $db->where('category_id = "' . $cateogry . '" OR category_id = "' . $category_old . '"')->orderBy('id', 'DESC')->get(T_VIDEOS, $limit);
            // pagination system
            $videos = $db->where('privacy', 0)->where('category_id = "' . $cateogry.'"')->where('is_movie',1)->orderBy('id', 'DESC')->objectbuilder()->paginate(T_VIDEOS, $pt->page_number);
//            $videos = $db->join('videos v','c.media_id=v.id','LEFT')
//                ->where('v.privacy', 0)
//                ->where('c.category_id = "' . $cateogry.'"')
//                ->where('v.is_movie',0)
//                ->orderBy('v.id', 'DESC')
//                ->objectbuilder()
//                ->paginate('categories_media c', $pt->page_number);

            $pt->total_pages = $db->totalPages;
            // pagination system

        } else {
            header("Location: " . PT_Link('404'));
            exit();
        }
    }
}

use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Channel;


//Export rss feed
if ($pt->rss_feed) {
    $rss_feed_xml   = "";
    $fl_rss_feed    = new Feed();
    $fl_rss_channel = new Channel();


    $fl_rss_channel
        ->title($pt->config->title)
        ->description($pt->config->description)
        ->url($pt->config->site_url)
        ->appendTo($fl_rss_feed);

    if (is_array($videos)) {
        foreach ($videos as $feed_item_data) {
            $feed_item_data = PT_GetVideoByID($feed_item_data, 0, 0, 0);
            $fl_rss_item    = new Item();
            $fl_rss_item
             ->title($feed_item_data->title)
             ->description($feed_item_data->markup_description)
             ->url($feed_item_data->url)
             ->pubDate($feed_item_data->time)
             ->guid($feed_item_data->url,true)
             ->media(array(
                'attr'  => 'url',
                'ns'    => 'thumbnail',
                'link'  => PT_GetMedia($feed_item_data->org_thumbnail)))
             ->appendTo($fl_rss_channel);
        }
    }

    header('Content-type: text/rss+xml');
    echo($fl_rss_feed);
    exit();
}



$pt->show_sub = false;
$pt->sub_categories_array = array();
foreach ($pt->sub_categories as $cat_key => $subs) {
    $pt->sub_categories_array["'".$cat_key."'"] = '<option value="">'.$lang->all.'</option>';
    foreach ($subs as $sub_key => $sub_value) {
        $pt->sub_categories_array["'".$cat_key."'"] .= '<option value="'.array_keys($sub_value)[0].'" '.((!empty($_GET['sub_id']) && $_GET['sub_id'] == array_keys($sub_value)[0]) ? "selected" : "") .'>'.$sub_value[array_keys($sub_value)[0]].'</option>';
    }
    if (!empty($_GET['id']) && $_GET['id'] == $cat_key) {
        $pt->show_sub = true;
    }
}


$html_videos = '';
$adminArr = [];
if (!empty($videos)) {
    foreach ($videos as $key => $video) {
        $videoItem = PT_GetVideoByID($video, 0, 0, 0);

        $html_videos .= PT_LoadPage('videos/list', array(
            'ID' => $videoItem->id,
            'VID_ID' => $videoItem->id,
	        'TITLE' => $videoItem->title,
	        'VIEWS' => $videoItem->views,
            'VIEWS_NUM' => number_format($videoItem->views),
	        'USER_DATA' => $videoItem->owner,
	        'ADMIN'=>$videoItem->owner->admin?'admin':'user',
	        'THUMBNAIL' => $videoItem->thumbnail,
	        'URL' => $videoItem->url,
	        'TIME' => $videoItem->time_ago,
            'DURATION' => $videoItem->duration,
            'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
        ));
    }
}

if (empty($videos)) {
	$html_videos = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-video-off"><path d="M16 16v1a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2h2m5.66 0H14a2 2 0 0 1 2 2v3.34l1 1L23 7v10"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>' . $lang->no_videos_found_for_now . '</div>';
}
$pt->videos_count= count($videos);
$pt->page        = $page;
$pt->title       = $title . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('movies2/content', array(
    'TITLE' => $title,
    'VIDEOS' => $html_videos,
    'TYPE' => $page,
    'CATEGORY_ID' => $cateogry_id
));
