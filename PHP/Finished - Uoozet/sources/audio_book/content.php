<?php
$pt->page_url_ = $pt->config->site_url.'/audio_book';
$pt->page = 'کتاب صوتی';
$pt->title = 'کتاب صوتی | ' . $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword = $pt->config->keyword;
$pt->content = PT_LoadPage('audio_book/content');