<?php 
if (IS_LOGGED == false || $pt->config->upload_system != 'on') {
	header("Location: " . PT_Link('login'));
	exit();
}
$content         = 'content';
if ($pt->user->is_pro != 1) {
	if ($pt->user->uploads >= 1073741824) {
		$content = "buy_pro";
	}
}

$pt->page        = 'upload-video';
$pt->title       = $lang->home . ' | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = $pt->config->keyword;
$pt->content     = PT_LoadPage("upload-video/$content");