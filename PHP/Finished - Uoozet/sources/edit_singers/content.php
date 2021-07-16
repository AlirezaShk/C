<?php
if (IS_LOGGED == false || $pt->config->all_create_articles != 'on') {
    header("Location: " . PT_Link('login'));
    exit();
}
if (empty($_GET['id'])) {
    header("Location: " . PT_Link('login'));
    exit();
}
$id    = PT_Secure($_GET['id']);
$singer = $db->where('id', $id)->getOne(T_SINGERS);
if (empty($singer) || $singer->user_id != $pt->user->id) {
    header("Location: " . PT_Link(''));
    exit();
}



$pt->page_url_ = $pt->config->site_url.'/edit_singers/'.$id;
$pt->article       = $singer;
$pt->page        = 'edit_singers';
$pt->title       = $lang->edit_article . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = $pt->config->keyword;
$pt->content     = PT_LoadPage('edit_singers/content',array(
    'ID' => $singer->id,
    'NAME' => $singer->name,
    'DESC' => $singer->description,
    'IMAGE' => PT_GetMedia($singer->image),
    'POST_ENCODED_URL' => urlencode(PT_Link('singers/read/' . PT_URLSlug($singer->name,$singer->id))),
));