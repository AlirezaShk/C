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
function PT_LoadPage($page_url = '', $data = array(), $set_lang = true) {
    global $pt, $lang_array, $config;
    $page         = './themes/' . $config['theme'] . '/layout/' . $page_url . '.html';
    if (!file_exists($page)) {
        return false;
    }
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    if ($set_lang == true) {
        $page_content = preg_replace_callback("/{{LANG (.*?)}}/", function($m) use ($lang_array) {
            return (isset($lang_array[$m[1]])) ? $lang_array[$m[1]] : '';
        }, $page_content);
    }
    if (!empty($data) && is_array($data)) {
        foreach ($data as $key => $replace) {
            if ($key == 'USER_DATA') {
                $replace = ToArray($replace);
                $page_content = preg_replace_callback("/{{USER (.*?)}}/", function($m) use ($replace) {
                    return (isset($replace[$m[1]])) ? $replace[$m[1]] : '';
                }, $page_content);
            } else {
                $object_to_replace = "{{" . $key . "}}";
                $page_content      = str_replace($object_to_replace, $replace, $page_content);
            }
        }
    }
    if (IS_LOGGED == true) {
        $replace = ToArray($pt->user);
        $page_content = preg_replace_callback("/{{ME (.*?)}}/", function($m) use ($replace) {
            return (isset($replace[$m[1]])) ? $replace[$m[1]] : '';
        }, $page_content);
    }
    $page_content = preg_replace("/{{LINK (.*?)}}/", PT_Link("$1"), $page_content);
    $page_content = preg_replace_callback("/{{CONFIG (.*?)}}/", function($m) use ($config) {
        return (isset($config[$m[1]])) ? $config[$m[1]] : '';
    }, $page_content);
    return $page_content;
}
function PT_LoadAdminPage($page_url = '', $data = array(), $set_lang = true) {
    global $pt, $lang_array, $config, $db;
    $page         = './admin-panel/pages/' . $page_url . '.html';
    if (!file_exists($page)) {
        return false;
    }
    $page_content = '';
    ob_start();
    require($page);
    $page_content = ob_get_contents();
    ob_end_clean();
    if ($set_lang == true) {
        $page_content = preg_replace_callback("/{{LANG (.*?)}}/", function($m) use ($lang_array) {
            return (isset($lang_array[$m[1]])) ? $lang_array[$m[1]] : '';
        }, $page_content);
    }
    if (!empty($data) && is_array($data)) {
        foreach ($data as $key => $replace) {
            if ($key == 'USER_DATA') {
                $replace = ToArray($replace);
                $page_content = preg_replace_callback("/{{USER (.*?)}}/", function($m) use ($replace) {
                    return (isset($replace[$m[1]])) ? $replace[$m[1]] : '';
                }, $page_content);
            } else {
                $object_to_replace = "{{" . $key . "}}";
                $page_content      = str_replace($object_to_replace, $replace, $page_content);
            }
        }
    }
    if (IS_LOGGED == true) {
        $replace = ToArray($pt->user);
        $page_content = preg_replace_callback("/{{ME (.*?)}}/", function($m) use ($replace) {
            return (isset($replace[$m[1]])) ? $replace[$m[1]] : '';
        }, $page_content);
    }
    $page_content = preg_replace("/{{LINK (.*?)}}/", PT_Link("$1"), $page_content);
    $page_content = preg_replace_callback("/{{CONFIG (.*?)}}/", function($m) use ($config) {
        return (isset($config[$m[1]])) ? $config[$m[1]] : '';
    }, $page_content);
    return $page_content;
}
function PT_Link($string) {
    global $site_url;
    return $site_url . '/' . $string;
}
function PT_Slug($string, $video_id) {
    global $pt;
    if ($pt->config->seo_link != 'on') {
        return $video_id;
    }
    $slug = url_slug($string, array(
        'delimiter' => '-',
        'limit' => 100,
        'lowercase' => true,
        'replacements' => array(
            '/\b(an)\b/i' => 'a',
            '/\b(example)\b/i' => 'Test'
        )
    ));
    return $slug . '_' . $video_id . '.html';
}

function PT_LoadAdminLinkSettings($link = '') {
    global $site_url;
    return $site_url . '/admin-cp/' . $link;
}
function PT_LoadAdminLink($link = '') {
    global $site_url;
    return $site_url . '/admin-cdn/' . $link;
}
function url_slug($str, $options = array()) {
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str      = mb_convert_encoding((string) $str, 'UTF-8', mb_list_encodings());
    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false
    );
    // Merge options
    $options  = array_merge($defaults, $options);
    $char_map = array(
    // Latin
    'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C', 'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH', 'ß' => 'ss', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c', 'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th', 'ÿ' => 'y',
    // Latin symbols
    '©' => '(c)',
    // Greek
    'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8', 'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P', 'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W', 'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I', 'Ϋ' => 'Y', 'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8', 'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p', 'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w', 'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's', 'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
    // Turkish
    'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G', 'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
    // Russian
    'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh', 'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C', 'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu', 'Я' => 'Ya', 'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    // Ukrainian
    'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G', 'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
    // Czech
    'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U', 'Ž' => 'Z', 'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u', 'ž' => 'z',
    // Polish
    'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z', 'Ż' => 'Z', 'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z', 'ż' => 'z',
    // Latvian
    'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N', 'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z', 'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n', 'š' => 's', 'ū' => 'u', 'ž' => 'z');
    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
    // Transliterate characters to ASCII
    if($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }
    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);
    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}
function br2nl($st) {
    $breaks = array("<br />","<br>","<br/>");  
    return str_ireplace($breaks, "\r\n", $st);
}

function ToObject($array) {
    $object = new stdClass();
    foreach ($array as $key => $value) {
        if (is_array($value)) {
            $value = ToObject($value);
        }
        if (isset($value)) {
            $object->$key = $value;
        }
    }
    return $object;
}
function ToArray($obj) {
    if (is_object($obj))
        $obj = (array) $obj;
    if (is_array($obj)) {
        $new = array();
        foreach ($obj as $key => $val) {
            $new[$key] = ToArray($val);
        }
    } else {
        $new = $obj;
    }
    
    return $new;
}

function PT_Secure($string, $censored_words = 1) {
    global $mysqli;
    $string = trim($string);
    $string = mysqli_real_escape_string($mysqli, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace('\r\n', " <br>", $string);
    $string = str_replace('\n\r', " <br>", $string);
    $string = str_replace('\r', " <br>", $string);
    $string = str_replace('\n', " <br>", $string);
    $string = str_replace('&amp;#', '&#', $string);
    $string = stripslashes($string);
    if ($censored_words == 1) {
        global $config;
        $censored_words = @explode(",", $config['censored_words']);
        foreach ($censored_words as $censored_word) {
            $censored_word = trim($censored_word);
            $string        = str_replace($censored_word, '****', $string);
        }
    }
    return $string;
}

function PT_IsLogged() {
    if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
        $id = PT_GetUserFromSessionID($_SESSION['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else if (!empty($_COOKIE['user_id']) && !empty($_COOKIE['user_id'])) {
        $id = PT_GetUserFromSessionID($_COOKIE['user_id']);
        if (is_numeric($id) && !empty($id)) {
            return true;
        }
    } else {
        return false;
    }
}

function PT_GetUserFromSessionID($session_id, $platform = 'web') {
    global $db;
    if (empty($session_id)) {
        return false;
    }
    $platform   = PT_Secure($platform);
    $session_id = PT_Secure($session_id);
    $return     = $db->where('session_id', $session_id);
    $return     = $db->where('platform', $platform);
    return $db->getValue(T_SESSIONS, 'user_id');
}

function PT_GenerateKey($minlength = 20, $maxlength = 20, $uselower = true, $useupper = true, $usenumbers = true, $usespecial = false) {
    $charset = '';
    if ($uselower) {
        $charset .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($useupper) {
        $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($usenumbers) {
        $charset .= "123456789";
    }
    if ($usespecial) {
        $charset .= "~@#$%^*()_+-={}|][";
    }
    if ($minlength > $maxlength) {
        $length = mt_rand($maxlength, $minlength);
    } else {
        $length = mt_rand($minlength, $maxlength);
    }
    $key = '';
    for ($i = 0; $i < $length; $i++) {
        $key .= $charset[(mt_rand(0, strlen($charset) - 1))];
    }
    return $key;
}

function PT_Resize_Crop_Image($max_width, $max_height, $source_file, $dst_dir, $quality = 80) {
    $imgsize = @getimagesize($source_file);
    $width   = $imgsize[0];
    $height  = $imgsize[1];
    $mime    = $imgsize['mime'];
    switch ($mime) {
        case 'image/gif':
            $image_create = "imagecreatefromgif";
            $image        = "imagegif";
            break;
        case 'image/png':
            $image_create = "imagecreatefrompng";
            $image        = "imagepng";
            break;
        case 'image/jpeg':
            $image_create = "imagecreatefromjpeg";
            $image        = "imagejpeg";
            break;
        default:
            return false;
            break;
    }
    $dst_img    = @imagecreatetruecolor($max_width, $max_height);
    $src_img    = $image_create($source_file);
    $width_new  = $height * $max_width / $max_height;
    $height_new = $width * $max_height / $max_width;
    if ($width_new > $width) {
        $h_point = (($height - $height_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
    } else {
        $w_point = (($width - $width_new) / 2);
        @imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
    }
    @imagejpeg($dst_img, $dst_dir, $quality);
    if ($dst_img)
        @imagedestroy($dst_img);
    if ($src_img)
        @imagedestroy($src_img);
}

function FL_GetLanguages() {
    $data           = array();
    $dir            = scandir(__DIR__ . '/../../assets/langs');
    $languages_name = array_diff($dir, array(
        ".",
        "..",
        "error_log",
        "index.html",
        ".htaccess",
        "_notes",
        "extra"
    ));
    return $languages_name;
}

function PT_CompressImage($source_url, $destination_url, $quality) {
    $info = getimagesize($source_url);
    if ($info['mime'] == 'image/jpeg') {
        $image = @imagecreatefromjpeg($source_url);
        @imagejpeg($image, $destination_url, $quality);
    } elseif ($info['mime'] == 'image/gif') {
        $image = @imagecreatefromgif($source_url);
        @imagegif($image, $destination_url, $quality);
    } elseif ($info['mime'] == 'image/png') {
        $image = @imagecreatefrompng($source_url);
        @imagepng($image, $destination_url);
    }  
}


function PT_Time_Elapsed_String($ptime) {
    global $pt, $lang;
    $etime = time() - $ptime;
    if ($etime < 1) {
        return '0 seconds';
    }
    $a        = array(
        365 * 24 * 60 * 60 => $lang->year,
        30 * 24 * 60 * 60 => $lang->month,
        24 * 60 * 60 => $lang->day,
        60 * 60 => $lang->hour,
        60 => $lang->minute,
        1 => $lang->second
    );
    $a_plural = array(
        $lang->year => $lang->years,
        $lang->month => $lang->months,
        $lang->day => $lang->days,
        $lang->hour => $lang->hours,
        $lang->minute => $lang->minutes,
        $lang->second => $lang->seconds
    );
    foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
            $r = round($d);
            if ($pt->language_type == 'rtl') {
                $time_ago = $lang->time_ago . ' ' . $r . ' ' . ($r > 1 ? $a_plural[$str] : $str);
            } else {
                $time_ago = $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ' . $lang->time_ago;
            }
            return $time_ago;
        }
    }
}
function check_($check)
{
    $siteurl = urlencode($_SERVER['SERVER_NAME']);
    $file    = file_get_contents('http://www.playtubescript.com/purchase.php?code=' . $check . '&url=' . $siteurl);
    $check   = json_decode($file, true);
    return $check;
}
function check_success($check)
{
    $siteurl = urlencode($_SERVER['SERVER_NAME']);
    $file    = file_get_contents('http://www.playtubescript.com/purchase.php?code=' . $check . '&success=true&url=' . $siteurl);
    $check   = json_decode($file, true);
    return $check;
}
function PT_EditMarkup($text, $link = true) {
    if ($link == true) {
        $link_search = '/\[a\](.*?)\[\/a\]/i';
        if (preg_match_all($link_search, $text, $matches)) {
            foreach ($matches[1] as $match) {
                $match_decode     = urldecode($match);
                $match_decode_url = $match_decode;
                $count_url        = mb_strlen($match_decode);
                $match_url = $match_decode;
                if (!preg_match("/http(|s)\:\/\//", $match_decode)) {
                    $match_url = 'http://' . $match_url;
                }
                $text = str_replace('[a]' . $match . '[/a]', $match_decode_url, $text);
            }
        }
    }
    return $text;
}

function PT_Markup($text, $link = true) {
    if ($link == true) {
        $link_search = '/\[a\](.*?)\[\/a\]/i';
        if (preg_match_all($link_search, $text, $matches)) {
            foreach ($matches[1] as $match) {
                $match_decode     = urldecode($match);
                $match_decode_url = $match_decode;
                $count_url        = mb_strlen($match_decode);
                if ($count_url > 50) {
                    $match_decode_url = mb_substr($match_decode_url, 0, 30) . '....' . mb_substr($match_decode_url, 30, 20);
                }
                $match_url = $match_decode;
                if (!preg_match("/http(|s)\:\/\//", $match_decode)) {
                    $match_url = 'http://' . $match_url;
                }
                $text = str_replace('[a]' . $match . '[/a]', '<a href="' . strip_tags($match_url) . '" target="_blank" class="hash" rel="nofollow">' . $match_decode_url . '</a>', $text);
            }
        }
    }
    return $text;
}

function covtime($youtube_time) {
    preg_match_all('/(\d+)/',$youtube_time,$parts);

    // Put in zeros if we have less than 3 numbers.
    if (count($parts[0]) == 1) {
        array_unshift($parts[0], "0", "0");
    } elseif (count($parts[0]) == 2) {
        array_unshift($parts[0], "0");
    }

    $sec_init = $parts[0][2];
    $seconds = $sec_init%60;
    $seconds_overflow = floor($sec_init/60);

    $min_init = $parts[0][1] + $seconds_overflow;
    $minutes = ($min_init)%60;
    $minutes_overflow = floor(($min_init)/60);

    $hours = $parts[0][0] + $minutes_overflow;

    if($hours != 0)
        return $hours.':'.$minutes.':'.$seconds;
    else
        return $minutes.':'.$seconds;
}

function PT_CreateSession() {
    $hash                = sha1(rand(1111, 9999));
    if (!empty($_SESSION['hash_id'])) {
          $_SESSION['hash_id'] = $_SESSION['hash_id'];
          return $_SESSION['hash_id'];
    }
    $_SESSION['hash_id'] = $hash;
    return $hash;
}

function PT_ShortText($text = "", $len = 100) {
    if (empty($text) || !is_string($text) || !is_numeric($len) || $len < 1) {
        return "****";
    }
    if (strlen($text) > $len) {
        $text = mb_substr($text, 0, $len, "UTF-8") . "..";
    }
    return $text;
}