<?php
if (IS_LOGGED == false) {
    $data = array(
        'status' => 400,
        'error' => 'Not logged in'
    );
    echo json_encode($data);
    exit();
}
if (PT_IsAdmin() == false) {
    $data = array(
        'status' => 400,
        'error' => 'Not admin'
    );
    echo json_encode($data);
    exit();
}
if ($first == 'save-settings') {
    $submit_data = array();
    foreach ($_POST as $key => $settings_to_save) {
        $submit_data[$key] = $settings_to_save;
    }
    $update = false;
    if (!empty($submit_data)) {
        foreach ($submit_data as $key => $value) {
            $update = $db->where('name', $key)->update(T_CONFIG, array('value' => PT_Secure($value, 0)));
        }
    }
    if ($update) {
        $data = array('status' => 200);
    }
}

if ($first == 'delete-user') {
    if (!empty($_POST['id'])) {
        $delete = PT_DeleteUser(PT_Secure($_POST['id']));
        if ($delete) {
            $data = array('status' => 200);
        }
    }
}

if ($first == 'delete-video') {
    if (!empty($_POST['id'])) {
        $delete = PT_DeleteVideo(PT_Secure($_POST['id']));
        if ($delete) {
            $data = array('status' => 200);
        }
    }
}

if ($first == 'delete-video_ad') {
    if (!empty($_POST['id'])) {
        $delete = $db->where('id', PT_Secure($_POST['id']))->delete(T_VIDEO_ADS);
        if ($delete) {
            $data = array('status' => 200);
        }
    }
}

if ($first == 'delete-multi-videos') {
    if (!empty($_POST['ids'])) {
        foreach ($_POST['ids'] as $key => $id) {
            $delete = PT_DeleteVideo(PT_Secure($id));
        }
        if ($delete) {
            $data = array('status' => 200);
        }
    }
}

if ($first == 'load-more-youtube')  {
    if (!empty($_POST['query']) && !empty($_POST['pageToken'])) {
        $query = PT_Secure(urlencode($_POST['query']));
        $limit = 50;
        if (!empty($_POST['limit']) && $limit < 51) {
            $limit = (int) PT_Secure($_POST['limit']);
        }
        $token = PT_Secure($_POST['pageToken']);
        try {
            $youtube = new Madcoda\Youtube(array('key' => $pt->config->yt_api));
            $get_videos = $youtube->searchAdvanced(array(
                'q'             => $query,
                'type'          => 'video',
                'part'          => 'id',
                'maxResults'    => $limit,
                'pageToken'     => $token,
            ), true);
            if (!empty($get_videos)) {
                if ($get_videos['info']['totalResults'] > 0) {
                    $next_token = $get_videos['info']['nextPageToken'];
                    $ids        = array();
                    foreach ($get_videos['results'] as $key => $video) {
                        $check_if_exists = $db->where('youtube', $video->id->videoId)->getValue(T_VIDEOS, 'count(*)');
                        if ($check_if_exists == 0) {
                            $ids[] = $video->id->videoId;
                        }
                    }
                    $ids_implode = implode(',', $ids);
                    if (!empty($ids_implode)) {
                        $youtube_call_url = "https://www.googleapis.com/youtube/v3/videos?part=contentDetails,snippet&id=$ids_implode&key={$pt->config->yt_api}";
                        $get_videos = file_get_contents($youtube_call_url);
                        $get_videos = json_decode($get_videos);
                        if (!empty($get_videos)) {
                            $videos_html = '';
                            foreach ($get_videos->items as $key => $video) {
                                $thumb = PT_GetMedia('upload/photos/thumbnail.jpg');
                                if (!empty($video->snippet->thumbnails->medium->url)) {
                                    $thumb = $video->snippet->thumbnails->medium->url;
                                }
                                $tags = '';
                                if (!empty($video->snippet->tags)) {
                                    $tags_array = array();
                                    if (is_array($video->snippet->tags)) {
                                        $tag_count = 0;
                                        foreach ($video->snippet->tags as $key => $tag) {
                                            if ($tag_count < 11) {
                                                $tags_array[] = $tag;
                                            }
                                            $tag_count++;
                                        }
                                        $tags = implode(',', $tags_array);
                                    }
                                }
                                $duration = '00:00';
                                if (!empty(covtime($video->contentDetails->duration))) {
                                    $duration = covtime($video->contentDetails->duration);
                                }
                                $array_data = array(
                                    'ID' => $video->id,
                                    'TITLE' => $video->snippet->title,
                                    'DESC' => $video->snippet->description,
                                    'THUMB' => $thumb,
                                    'TAGS' => $tags,
                                    'DURATION' => $duration,
                                    'open_from_home_folder' => 1
                                );
                                $videos_html .= PT_LoadAdminPage('import-from-youtube/list', $array_data);
                            }
                            if (!empty($videos_html)) {
                                $data = array('status' => 200, 'html' => $videos_html, 'token' => $next_token);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            
        }

    }
}
if ($first == 'load-more-daily')  {
    if (!empty($_POST['query']) && !empty($_POST['pageToken'])) {
        $query = PT_Secure(urlencode($_POST['query']));
        $limit = 50;
        if (!empty($_POST['limit']) && $limit < 101) {
            $limit = (int) PT_Secure($_POST['limit']);
        }
        $page_id = PT_Secure($_POST['pageToken']);
        $call_url = "https://api.dailymotion.com/videos/?search=$query&page=$page_id&limit=$limit&fields=thumbnail_url,title,duration,description,tags,id";
        $get_videos = file_get_contents($call_url);
        $get_videos = json_decode($get_videos);
        if (!empty($get_videos)) {
            if (!empty($get_videos->total)) {
                $ids = array();
                foreach ($get_videos->list as $key => $video) {
                    $check_if_exists = $db->where('daily', $video->id)->getValue(T_VIDEOS, 'count(*)');
                    if ($check_if_exists == 0) {
                        $ids[] = $video->id;
                    }
                }
                $next_token = ($page_id < 100) ? ($page_id + 1) : 1;
                if (!empty($ids)) {
                    $videos_html = '';
                    foreach ($get_videos->list as $key => $video) {
                        $thumb = PT_GetMedia('upload/photos/thumbnail.jpg');
                        if (!empty($video->thumbnail_url)) {
                            $thumb = $video->thumbnail_url;
                        }
                        $tags = '';
                        if (is_array($video->tags)) {
                            $tags_array = array();
                            $tag_count = 0;
                            foreach ($video->tags as $key => $tag) {
                                if ($tag_count < 11) {
                                    $tags_array[] = $tag;
                                }
                                $tag_count++;
                            }
                            $tags = implode(',', $tags_array);
                        }
                        $duration = '00:00';
                        if (!empty($video->duration)) {
                            $duration = gmdate("i:s", $video->duration);
                        }
                        $array_data = array(
                            'ID' => $video->id,
                            'TITLE' => $video->title,
                            'DESC' => $video->description,
                            'THUMB' => $thumb,
                            'TAGS' => $tags,
                            'DURATION' => $duration,
                            'open_from_home_folder' => 1
                        );
                        $videos_html .= PT_LoadAdminPage('import-from-dailymotion/list', $array_data);
                    }
                    if (!empty($videos_html)) {
                        $data = array('status' => 200, 'html' => $videos_html, 'token' => $next_token);
                    }
                } 
            }
        }
    }
}
if ($first == 'import-daily-videos') {
    if (!empty($_POST['videos'])) {
        $ids = array();
        $category_id = 0;
        if (!empty($_POST['category_id'])) {
            if (in_array($_POST['category_id'], array_keys(ToArray($pt->categories)))) {
                $category_id = $_POST['category_id'];
            }
        }
        foreach ($_POST['videos'] as $key => $data_fro_ajax) {
            $video_id  = PT_GenerateKey(15, 15);
            $video_id_ = $data_fro_ajax['video_id'];
            $title = $data_fro_ajax['title'];
            $description = $data_fro_ajax['description'];
            $duration = $data_fro_ajax['duration'];
            $thumb = $data_fro_ajax['thumb'];
            $tags = $data_fro_ajax['tags'];
            $insert = false;
            if (strpos($thumb, 'upload/photos/thumbnail')) {
                $thumb = 'upload/photos/thumbnail.jpg';
            }
            $check_for_video = $db->where('video_id', $video_id)->getValue(T_VIDEOS, 'count(*)');
            if ($check_for_video > 0) {
                $video_id = PT_GenerateKey(15, 15);
            }
            $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
            $i          = 0;
            preg_match_all($link_regex, $description, $matches);
            foreach ($matches[0] as $match) {
                $match_url           = strip_tags($match);
                $syntax              = '[a]' . urlencode($match_url) . '[/a]';
                $description = str_replace($match, $syntax, $description);
            }
            $data_insert = array(
                'video_id' => $video_id,
                'user_id' => $user->id,
                'title' => PT_Secure($title),
                'description' => PT_Secure($description),
                'tags' => PT_Secure($tags),
                'duration' => $duration,
                'category_id' => $category_id,
                'daily' => $video_id_,
                'thumbnail' => $thumb,
                'time' => time(),
                'registered' => date('Y') . '/' . intval(date('m'))
            );
            $insert      = $db->insert(T_VIDEOS, $data_insert);
            if ($insert) {
                if (empty($_SESSION['imported-videos'])) {
                    $_SESSION['imported-videos'] = 1;
                } else {
                    $_SESSION['imported-videos']++;
                }
            }
        }
        if ($insert) {
            $data = array('status' => 200);
        }
    }
}
if ($first == 'import-youtube-videos') {
    if (!empty($_POST['videos'])) {
        $ids = array();
        $category_id = 0;
        if (!empty($_POST['category_id'])) {
            if (in_array($_POST['category_id'], array_keys(ToArray($pt->categories)))) {
                $category_id = $_POST['category_id'];
            }
        }
        foreach ($_POST['videos'] as $key => $data_fro_ajax) {
            $video_id  = PT_GenerateKey(15, 15);
            $video_id_ = $data_fro_ajax['video_id'];
            $title = $data_fro_ajax['title'];
            $description = $data_fro_ajax['description'];
            $duration = $data_fro_ajax['duration'];
            $thumb = $data_fro_ajax['thumb'];
            $tags = $data_fro_ajax['tags'];
            $insert = false;
            if (strpos($thumb, 'upload/photos/thumbnail')) {
                $thumb = 'upload/photos/thumbnail.jpg';
            }
            $check_for_video = $db->where('video_id', $video_id)->getValue(T_VIDEOS, 'count(*)');
            if ($check_for_video > 0) {
                $video_id = PT_GenerateKey(15, 15);
            }
            $link_regex = '/(http\:\/\/|https\:\/\/|www\.)([^\ ]+)/i';
            $i          = 0;
            preg_match_all($link_regex, $description, $matches);
            foreach ($matches[0] as $match) {
                $match_url           = strip_tags($match);
                $syntax              = '[a]' . urlencode($match_url) . '[/a]';
                $description = str_replace($match, $syntax, $description);
            }
            $data_insert = array(
                'video_id' => $video_id,
                'user_id' => $user->id,
                'title' => PT_Secure($title),
                'description' => PT_Secure($description),
                'tags' => PT_Secure($tags),
                'duration' => $duration,
                'category_id' => $category_id,
                'youtube' => $video_id_,
                'thumbnail' => $thumb,
                'time' => time(),
                'registered' => date('Y') . '/' . intval(date('m'))
            );
            $insert      = $db->insert(T_VIDEOS, $data_insert);
            if ($insert) {
                if (empty($_SESSION['imported-videos'])) {
                    $_SESSION['imported-videos'] = 1;
                } else {
                    $_SESSION['imported-videos']++;
                }
            }
        }
        if ($insert) {
            $data = array('status' => 200);
        }
    }
}

if ($first == 'create-ads') {
    if (!empty($_POST['type'])) {
        if (empty($_POST['name']) || empty($_POST['link']) || empty($_POST['ad_link']) || empty($_POST['type'])) {
            $errors = 'Please check your details';
        } else {
            if (filter_var($_POST['link'], FILTER_VALIDATE_URL) === FALSE) {
                $errors = 'The Media is invalid';
            }
            if (filter_var($_POST['ad_link'], FILTER_VALIDATE_URL) === FALSE) {
                $errors = 'The URL is invalid';
            }
            if (!is_numeric($_POST['skip_seconds'])) {
                $errors = 'The skip seconds should be numeric';
            }
            if ($_POST['type'] == 'image') {
                if (!preg_match("([^\s]+(\.(?i)(jpe?g|png|gif|bmp))$)", $_POST['link'])) {
                    $errors = 'The image url is invalid';
                }
            }
            if ($_POST['type'] == 'vast') {
                if (!preg_match("([^\s]+(\.(?i)(xml))$)", $_POST['link'])) {
                    $errors = 'The XML url is invalid';
                }
            }
            if ($_POST['type'] == 'video') {
                if (!preg_match("([^\s]+(\.(?i)(mp4|webp|mp3|mpeg|mov))$)", $_POST['link'])) {
                    $errors = 'The video url is invalid';
                }
            }
        }
        if (empty($errors)) {
            $seconds = 0;
            if (!empty($_POST['skip_seconds'])) {
                $seconds = PT_Secure($_POST['skip_seconds']);
            }
            $insert_array = array(
                'user_id' => $user->id,
                'name' => PT_Secure($_POST['name']),
                'skip_seconds' => $seconds,
                'ad_link' => PT_Secure($_POST['ad_link']),
                'active' => 1,
            );
            if ($_POST['type'] == 'video') {
                $insert_array['ad_media'] = PT_Secure($_POST['link']);
            } elseif ($_POST['type'] == 'image') {
                $insert_array['ad_image'] = PT_Secure($_POST['link']);
            } elseif ($_POST['type'] == 'vast') {
                $insert_array['vast_xml_link'] = PT_Secure($_POST['link']);
                $insert_array['vast_type'] = ($_POST['vast_type'] == 'vast') ? 'vast' : 'vpaid';
            }
            $insert = $db->insert(T_VIDEO_ADS, $insert_array);
            if ($insert) {
                $data = array('status' => 200);
            }
        } else {
            $data = array('status' => 400, 'error' => $errors);
        }
    }
}

if ($first == 'edit-ads') {
    if (!empty($_POST['type']) && !empty($_POST['id'])) {
        $id = PT_Secure($_POST['id']);
        if (empty($_POST['name']) || empty($_POST['link']) || empty($_POST['ad_link']) || empty($_POST['type'])) {
            $errors = 'Please check your details';
        } else {
            if (filter_var($_POST['link'], FILTER_VALIDATE_URL) === FALSE) {
                $errors = 'The Media is invalid';
            }
            if (filter_var($_POST['ad_link'], FILTER_VALIDATE_URL) === FALSE) {
                $errors = 'The URL is invalid';
            }
            if (!is_numeric($_POST['skip_seconds'])) {
                $errors = 'The skip seconds should be numeric';
            }
            if ($_POST['type'] == 'image') {
                if (!preg_match("([^\s]+(\.(?i)(jpe?g|png|gif|bmp))$)", $_POST['link'])) {
                    $errors = 'The image url is invalid';
                }
            }
            if ($_POST['type'] == 'vast') {
                if (!preg_match("([^\s]+(\.(?i)(xml))$)", $_POST['link'])) {
                    $errors = 'The XML url is invalid';
                }
            }
            if ($_POST['type'] == 'video') {
                if (!preg_match("([^\s]+(\.(?i)(mp4|webp|mp3|mpeg|mov))$)", $_POST['link'])) {
                    $errors = 'The video url is invalid';
                }
            }
        }
        if (empty($errors)) {
            $seconds = 0;
            if (!empty($_POST['skip_seconds'])) {
                $seconds = PT_Secure($_POST['skip_seconds']);
            }
            $insert_array = array(
                'user_id' => $user->id,
                'name' => PT_Secure($_POST['name']),
                'skip_seconds' => $seconds,
                'ad_link' => PT_Secure($_POST['ad_link']),
                'active' => 1,
            );
            if ($_POST['type'] == 'video') {
                $insert_array['ad_media'] = PT_Secure($_POST['link']);
            } elseif ($_POST['type'] == 'image') {
                $insert_array['ad_image'] = PT_Secure($_POST['link']);
            } elseif ($_POST['type'] == 'vast') {
                $insert_array['vast_xml_link'] = PT_Secure($_POST['link']);
                $insert_array['vast_type'] = ($_POST['vast_type'] == 'vast') ? 'vast' : 'vpaid';
            }
            $insert = $db->where('id', $id)->update(T_VIDEO_ADS, $insert_array);
            if ($insert) {
                $data = array('status' => 200);
            }
        } else {
            $data = array('status' => 400, 'error' => $errors);
        }
    }
}

if ($first == 'update-ads') {
    $updated = false;
    foreach ($_POST as $key => $ads) {
        if ($key != 'hash_id') {
            $ad_data = array(
                'code' => htmlspecialchars(base64_decode($ads)),
                'active' => (empty($ads)) ? 0 : 1
            );
            $update = $db->where('placement', PT_Secure($key))->update(T_ADS, $ad_data);
            if ($update) {
                $updated = true;
            }
        }
    }
    if ($updated == true) {
        $data = array(
            'status' => 200
        );
    }
}

if ($first == 'submit-sitemap-settings') {
    if (!file_exists('./sitemaps')) {
        @mkdir('./sitemaps', 0777, true);
    }
    $dom = new DOMDocument();
    $filename = 'sitemaps/sitemap.xml';
    if (isset($_POST['percentage'])) {
        $start_from = (!empty($_POST['start_from'])) ? (int) $_POST['start_from'] : 0;
        $file_number = (!empty($_POST['file_number'])) ? (int) $_POST['file_number'] : 0;
        $added_videos = (!empty($_POST['added_videos'])) ? (int) $_POST['added_videos'] : 0;
        $mysql = $db->orderBy('id', 'ASC')->get(T_VIDEOS, array($start_from, 500));
        $total_videos =  $db->getValue(T_VIDEOS, 'count(*)');
        $percentage = round(($start_from / $total_videos) * 100, 2);
        if ($percentage > 100) {
            $percentage = 100;
        }
        $sitemap_numbers = ceil($total_videos / 20000);
        if ($_POST['percentage'] == 0) {
            $files = glob('./sitemaps/*');
            foreach($files as $file){ 
              if(is_file($file))
                unlink($file);
            }
            for ($i=1; $i <= $sitemap_numbers; $i++) { 
                $open_file = fopen("sitemaps/sitemap-" . $i . ".xml", "w");
            }
        }  else {
            $write_data = file_get_contents('sitemaps/sitemap-' . $file_number . '.xml');
        }
        
        if (filesize('sitemaps/sitemap-' . $file_number . '.xml') < 1) {
            $write_data = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        }
        foreach ($mysql as $key => $video) {
            $video = PT_GetVideoByID($video, 0, 0 , 0);
            $write_data .= '<url>
      <loc>' . $video->url . '</loc>
      <lastmod>' . date('c', $video->time). '</lastmod>
      <changefreq>monthly</changefreq>
      <priority>0.8</priority>
   </url>' . "\n";
            $added_videos++;
        }
        $url_set_end = "\n</urlset>";
        $file_number_final = $file_number;
        $file_ = file_put_contents('sitemaps/sitemap-' . $file_number . '.xml', $write_data);
        if ($percentage == 100) {
            $file_ = file_put_contents('sitemaps/sitemap-' . $file_number . '.xml', file_get_contents('sitemaps/sitemap-' . $file_number . '.xml') . $url_set_end);
            $files = glob('./sitemaps/*');
            $write_final_data = '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" >';
            foreach($files as $file){ 
                  if (is_file($file)) {
                      $write_final_data .= "\n<sitemap>
  <loc>" . $pt->config->site_url . '/' . str_replace('./', '', $file) . "</loc>
  <lastmod>" . date('c') . "</lastmod>
</sitemap>";
                  }
            }
            $write_final_data .= '</sitemapindex>';
            $file_final = file_put_contents('sitemap-main.xml', $write_final_data);
        } else if ($added_videos > 20000 && $added_videos < $total_videos) {
            $file_ = file_put_contents('sitemaps/sitemap-' . $file_number . '.xml', file_get_contents('sitemaps/sitemap-' . $file_number . '.xml') . $url_set_end);
            if (file_exists('sitemaps/sitemap-' . ($file_number + 1) . '.xml')) {
                $file_number_final = $file_number + 1;
                $added_videos = 0;
            }
        }
        if ($file_) {
            $data = array('status' => 201, 'start_from' => ($start_from + 500), 'percentage_full' => $percentage . '%', 'percentage' => $percentage, 'file_number' => $file_number_final, 'added_videos' => $added_videos);
            if ($percentage == 100) {
                $data['last_created'] = date('d-m-Y');
                $last_created_update =  $update = $db->where('name', 'last_created_sitemap')->update(T_CONFIG, array('value' => PT_Secure($data['last_created'], 0)));
            }
        }
    }
}

if ($first == 'save-design') {
    $saveSetting = false;
    if (isset($_FILES['logo']['name'])) {
        $fileInfo = array(
            'file' => $_FILES["logo"]["tmp_name"],
            'name' => $_FILES['logo']['name'],
            'size' => $_FILES["logo"]["size"]
        );
        $media    = PT_UploadLogo($fileInfo);
    }
    if (isset($_FILES['light-logo']['name'])) {
        $fileInfo = array(
            'file' => $_FILES["light-logo"]["tmp_name"],
            'name' => $_FILES['light-logo']['name'],
            'size' => $_FILES["light-logo"]["size"],
            'light-logo' => true
        );
        $media    = PT_UploadLogo($fileInfo);
    }
    if (isset($_FILES['favicon']['name'])) {
        $fileInfo = array(
            'file' => $_FILES["favicon"]["tmp_name"],
            'name' => $_FILES['favicon']['name'],
            'size' => $_FILES["favicon"]["size"],
            'favicon' => true
        );
        $media    = PT_UploadLogo($fileInfo);
    }
    $data['status'] = 200;
}

if ($first == 'save-terms') {
    $saveSetting = false;
    foreach ($_POST as $key => $value) {
        if ($key != 'hash_id') {
            $saveSetting = $db->where('type', $key)->update(T_TERMS, array('text' => PT_Secure(base64_decode($value), 0)));
        }
    }
    if ($saveSetting) {
        $data['status'] = 200;
    }
}