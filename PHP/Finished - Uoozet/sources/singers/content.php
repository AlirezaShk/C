<?php
if ($pt->config->article_system != 'on') {
    header('Location: ' .PT_Link('404'));
    exit();
}
$pt_cats      = array_keys(get_object_vars($pt->categories));
$html_singers   = '';
$html_p_singers = '';
$category     = 0;
$query        = false;
$pt->page_url_ = $pt->config->site_url.'/singers';
if (!empty($_POST['q'])) {
	$keyword = PT_Secure($_POST['q']);
	$sub_sql = '';
	$query   = true;

	$sql     = "(`name` LIKE '%$keyword%' OR `description` LIKE '%$keyword%') {$sub_sql}";
	$db->where($sql);
	$singers   = $db->orderBy('id', 'DESC')->get(T_SINGER,10);
	$pt->page_url_ = $pt->config->site_url.'/singers/category/'.$_GET['category_id'];
}

else{
	$singers   = $db->orderBy('id', 'DESC')->get(T_SINGER, 20);
	
}

$popular_singers = $db->orderBy('id', 'DESC')->get(T_SINGER, 7);


$pt->category = $category;

if (!empty($singers)) {
    foreach ($singers as $key => $singer) {
        $html_singers .= PT_LoadPage('singers/list', array(
            'ID' => $singer->id,
	        'NAME' => $singer->name,
	        'DESC'  => PT_ShortText($singer->description,190),
	        'THUMBNAIL' => PT_GetMedia($singer->image),
	        'URL' => PT_Link('singers/read/' . PT_URLSlug($singer->name,$singer->id)),
	        'SINGER_URL' => PT_URLSlug($singer->name,$singer->id)
        ));
    }
}

foreach ($popular_singers as $key => $singer) {
    $html_p_singers .= PT_LoadPage('singers/popular', array(
        'NAME' => $singer->name,
        'THUMBNAIL' => PT_GetMedia($singer->image),
        'URL' => PT_Link('singers/read/' . PT_URLSlug($singer->name,$singer->id)),
        'SINGER_URL' => PT_URLSlug($singer->name,$singer->id)
    ));
}

if ($query && empty($html_singers)) {
	$html_singers = PT_LoadPage('singers/404',array('QUERY' => $keyword));
}

else if(empty($html_singers)){
	$html_singers = '<div class="text-center no-content-found empty_state"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book-open"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>'.$lang->no_post_found.'</div>';
}
$sidebar_ad = '';
$sidebarAd              = pt_get_user_ads(2);
if (!empty($sidebarAd)) {
    $get_random_ad      = $sidebarAd;
    $random_ad_id       = $get_random_ad->id;
    $_SESSION['pagead'] = $random_ad_id;
    $sidebar_ad    = PT_LoadPage('ads/includes/sidebar',array(
        'ID' => $random_ad_id,
        'IMG' => PT_GetMedia($get_random_ad->media),
        'TITLE' => PT_ShortText($get_random_ad->headline,30),
        'NAME' => PT_ShortText($get_random_ad->name,20),
        'DESC' => PT_ShortText($get_random_ad->description,70),
        'URL' => PT_Link("redirect/$random_ad_id?type=pagead"),
        'URL_NAME' => pt_url_domain(urldecode($get_random_ad->url))
    ));
}

$pt->title       = $lang->singers . ' | ' . $pt->config->title;
$pt->page        = "singers";
$pt->description = $pt->config->description;
$pt->singers_count = count($singers);
$pt->keyword     = @$pt->config->keyword;
$pt->content     = PT_LoadPage('singers/content', array(
    'SINGERS'         => $html_singers,
    'POPULAR_SINGERS' => $html_p_singers,
    'CATEGORY'      => $category,
    'WATCH_SIDEBAR_AD' => $sidebar_ad
));
