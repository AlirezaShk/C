<?php
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
error_reporting(0);
@ini_set('max_execution_time', 0);
require 'config.php';
require 'phpMailer_config.php';
require 'assets/import/DB/vendor/autoload.php';
require 'assets/import/getID3-1.9.14/getid3/getid3.php';
require 'assets/import/youtube-sdk/vendor/autoload.php';
require 'assets/import/php-rss/vendor/autoload.php';
require 'assets/import/s3/aws-autoloader.php';
$pt     = ToObject(array());
// Connect to MySQL Server
$mysqli     = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);
$sqlConnect = $mysqli;


$pt->script_version = '1.6.1';

// Handling Server Errors
$ServerErrors = array();
if (mysqli_connect_errno()) {
    $ServerErrors[] = "Failed to connect to MySQL: " . mysqli_connect_error();
}
if (!function_exists('curl_init')) {
    $ServerErrors[] = "PHP CURL is NOT installed on your web server !";
}
if (!extension_loaded('gd') && !function_exists('gd_info')) {
    $ServerErrors[] = "PHP GD library is NOT installed on your web server !";
}
if (!extension_loaded('zip')) {
    $ServerErrors[] = "ZipArchive extension is NOT installed on your web server !";
}

if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}
$query = $mysqli->query("SET NAMES utf8");
$query = $mysqli->query("SET character_set_client=utf8mb4");
$query = $mysqli->query("SET character_set_connection=utf8mb4");
$query = $mysqli->query("SET character_set_results=utf8mb4");

// Connecting to DB after verfication

$db = new MysqliDb($mysqli);


$http_header = 'http://';
if (!empty($_SERVER['HTTPS'])) {
    $http_header = 'https://';
}

$pt->site_pages           = array('home');
$pt->actual_link          = $http_header . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);

$config                   = PT_GetConfig();
$pt->loggedin             = false;
$config['user_statics']   = stripslashes(htmlspecialchars_decode($config['user_statics']));
$config['videos_statics'] = stripslashes(htmlspecialchars_decode($config['videos_statics']));
$config['theme_url']      = $site_url . '/themes/' . $config['theme'];
$config['site_url']       = $site_url;
$config['script_version'] = $pt->script_version;

$pt->extra_config = array();
$get_nodejs_config = file_get_contents('nodejs/config.json');
$config['hostname'] = '';
$config['server_port'] = '';
if (!empty($get_nodejs_config)) {
    $pt->extra_config = json_decode($get_nodejs_config);
    $config['hostname']  = $pt->extra_config->server_ip;
    $config['server_port']  = $pt->extra_config->server_port;
} else {
    exit('Please make sure the file: nodejs/config.json exists and readable.');
}

$site = parse_url($site_url);
if (empty($site['host'])) {
    $config['hostname'] = $site['scheme'] . '://' .  $site['host'];
}


$pt->config               = ToObject($config);
$langs                    = pt_db_langs();
$pt->langs                = $langs;
$pt->config->registerStep = 0;
if (PT_IsLogged() == true) {
    $session_id        = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $pt->user_session  = PT_GetUserFromSessionID($session_id);
    $user = $pt->user  = PT_UserData($pt->user_session);
    $user->wallet      = number_format($user->wallet,2);
    
    if (!empty($user->language) && in_array($user->language, $langs)) {
        $_SESSION['lang'] = $user->language;
    }

    if ($user->id < 0 || empty($user->id) || !is_numeric($user->id) || PT_UserActive($user->id) === false) {
        header("Location: " . PT_Link('logout'));
    }
    $pt->loggedin   = true;
}

else if (!empty($_POST['user_id']) && !empty($_POST['s'])) {
    $platform       = ((!empty($_POST['platform'])) ? $_POST['platform'] : 'phone');
    $s              = PT_Secure($_POST['s']);
    $user_id        = PT_Secure($_POST['user_id']);
    $verify_session = verify_api_auth($user_id, $s, $platform);
    if ($verify_session === true) {
        $user = $pt->user  = PT_UserData($user_id);
        if (empty($user) || PT_UserActive($user->id) === false) {
            $json_error_data = array(
                'api_status' => '400',
                'api_text' => 'authentication_failed',
                'errors' => array(
                    'error_id' => '1',
                    'error_text' => 'Error 400 - The user does not exist'
                )
            );

            echo json_encode($json_error_data, JSON_PRETTY_PRINT);
            exit();
        }

        $pt->loggedin = true;
    } 
    else {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'authentication_failed',
            'errors' => array(
                'error_id' => '1',
                'error_text' => 'Error 400 - Session does not exist'
            )
        );
        echo json_encode($json_error_data, JSON_PRETTY_PRINT);
        exit();
    }  
} else if (!empty($_GET['user_id']) && !empty($_GET['s'])) {
    $platform       = ((!empty($_GET['platform'])) ? $_GET['platform'] : 'phone');
    $s              = PT_Secure($_GET['s']);
    $user_id        = PT_Secure($_GET['user_id']);
    $verify_session = verify_api_auth($user_id, $s, $platform);
    if ($verify_session === true) {
        $user = $pt->user  = PT_UserData($user_id);
        if (empty($user) || PT_UserActive($user->id) === false) {
            $json_error_data = array(
                'api_status' => '400',
                'api_text' => 'authentication_failed',
                'errors' => array(
                    'error_id' => '1',
                    'error_text' => 'Error 400 - The user does not exist'
                )
            );

            echo json_encode($json_error_data, JSON_PRETTY_PRINT);
            exit();
        }

        $pt->loggedin = true;
    } 
    else {
        $json_error_data = array(
            'api_status' => '400',
            'api_text' => 'authentication_failed',
            'errors' => array(
                'error_id' => '1',
                'error_text' => 'Error 400 - Session does not exist'
            )
        );
        echo json_encode($json_error_data, JSON_PRETTY_PRINT);
        exit();
    }  
}

elseif (!empty($_GET['cookie']) && $pt->loggedin != true) {
    $session_id            = $_GET['cookie'];
    $pt->user_session      = PT_GetUserFromSessionID($session_id);
    if (!empty($pt->user_session) && is_numeric($pt->user_session)) {
        $user = $pt->user  = PT_UserData($pt->user_session);
        $pt->loggedin      = true;

        if (!empty($user->language)) {
            if (file_exists(__DIR__ . '/../langs/' . $user->language . '.php')) {
                $_SESSION['lang'] = $user->language;
            }
        }
        setcookie("user_id", $session_id, time() + (10 * 365 * 24 * 60 * 60), "/");
    }
}


if (isset($_GET['lang']) AND !empty($_GET['lang'])) {
    $lang_name = PT_Secure(strtolower($_GET['lang']));

    if (in_array($lang_name, $langs)) {
        $_SESSION['lang'] = $lang_name;
        if ($pt->loggedin == true) {
            $db->where('id', $user->id)->update(T_USERS, array('language' => $lang_name));
        }
    }
}

if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = $pt->config->language;
}

if (isset($_SESSION['user_id'])) {
    if (empty($_COOKIE['user_id'])) {
        setcookie("user_id", $_SESSION['user_id'], time() + (10 * 365 * 24 * 60 * 60), "/");
    }
}

$pt->language      = $_SESSION['lang'];
$pt->language_type = 'ltr';

// Add rtl languages here.
$rtl_langs           = array(
    'arabic',
    'farsi'
);

// checking if corrent language is rtl.
foreach ($rtl_langs as $lang) {
    if ($pt->language == strtolower($lang)) {
        $pt->language_type = 'rtl';
    }
}


// Include Language File
$lang_file = 'assets/langs/' . $pt->language . '.php';
if (file_exists($lang_file)) {
    require($lang_file);
}



$lang_array = pt_get_langs($pt->language);

if (empty($lang_array)) {
    $lang_array = pt_get_langs();
}

$lang       = ToObject($lang_array);
$pt->all_lang = $lang;

$pt->exp_feed    = false; 
$pt->userDefaultAvatar = 'upload/photos/d-avatar.jpg';
$pt->categories  = ToObject($categories);
$pt->ucategories  = ToObject($ucategories);
$pt->alboums  = ToObject($alboums);
$pt->singers  = ToObject($singers);
$pt->musicCategories  = ToObject($musicCategories);
$pt->radioCategories  = ToObject($radioCategories);
$categories = array();
$ucategories = array();
$alboums = array();
$musicCategories = array();
$radioCategories = array();
$sub_categories = array();
$sub_ucategories = array();
$singers = array();
$pt->navbarAvailable = array(
    "home"=>true,
    "cinema"=>false,
    "audio-book"=>false,
    "radio-music"=>false,
    "articles"=>true,
    "tv"=>true,
    "video-share"=>true,
    );
//--------------- categories ---------------//
try {
    $all_categories = $db->where('type','category')->get(T_LANGS);
    $sub_categories = array();
    foreach ($all_categories as $key => $value) {
        $array_keys = array_keys($all_categories);
        if ($value->lang_key != 'other') {
            if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
                $catHasVideo = $db->where('category_id',$value->lang_key)->get(T_VIDEOS);
//                if(count($catHasVideo) > 0)
                $categories[$value->lang_key] = $lang->{$value->lang_key};
            }
            $all_sub_categories = $db->where('type',$value->lang_key)->get(T_LANGS);

            if (!empty($all_sub_categories)) {
                foreach ($all_sub_categories as $key => $sub) {
                    $array = array();
                    if (!empty($sub->lang_key) && !empty($lang->{$sub->lang_key})) {
                        $array[$sub->lang_key] = $lang->{$sub->lang_key};
                        $sub_categories[$value->lang_key][] = $array;
                    }
                }
            }
        }
        if (end($array_keys) == $key) {
            $categories['other'] = $lang->other;
        }
        
    }
} catch (Exception $e) {

}
//--------------- channel - categories ---------------//
try {
//    $all_categories = $db->where('type','category')->get(T_LANGS);
    $all_ucategories = $db->rawQuery("SELECT * FROM `" . T_LANGS . "` WHERE `type` LIKE 'cat-%' ORDER BY `type`");
    $sub_ucategories = array();
    foreach ($all_ucategories as $key => $value) {
        $array_keys = array_keys($all_ucategories);
        if ($value->lang_key != 'other') {
            if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
                $catHasVideo = $db->where('category_id',$value->lang_key)->get(T_VIDEOS);
//                if(count($catHasVideo) > 0)
                if(!is_array($ucategories[explode("-",$value->type)[1]]))
                    $ucategories[explode("-",$value->type)[1]] = array();
                $ucategories[explode("-",$value->type)[1]][$value->lang_key] = $lang->{$value->lang_key};
            }
            $all_sub_ucategories = $db->where('type',$value->lang_key)->get(T_LANGS);

            if (!empty($all_sub_ucategories)) {
                foreach ($all_sub_ucategories as $key => $sub) {
                    $array = array();
                    if (!empty($sub->lang_key) && !empty($lang->{$sub->lang_key})) {
                        $array[$sub->lang_key] = $lang->{$sub->lang_key};
                        $sub_ucategories[$value->lang_key][] = $array;
                    }
                }
            }
        }
        if (end($array_keys) == $key) {
            $ucategories['other'] = $lang->other;
        }

    }
} catch (Exception $e) {
    echo $e->getMessage();
}
$pt->categories  = ToObject($categories);
$pt->ucategories  = ToObject($ucategories);
$pt->sub_categories = $sub_categories;
$pt->sub_ucategories = $sub_ucategories;
try {
    $all_alboums = $db->where('type','alboum')->get(T_LANGS);
    foreach ($all_alboums as $key => $value) {
        $array_keys = array_keys($all_alboums);
        if ($value->lang_key != 'single_music') {
            if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
                $alboums[$value->lang_key] = $lang->{$value->lang_key};
            }
        }
        if (end($array_keys) == $key) {
            $alboums['single_music'] = $lang->single_music;
        }

    }
} catch (Exception $e) {

}

$pt->alboums  = ToObject($alboums);

$movies_categories = array();
try {
    $all_movies_categories = $db->where('type','movie_category')->get(T_LANGS);
    if (!empty($all_movies_categories)) {
    
        foreach ($all_movies_categories as $key => $value) {
            $array_keys = array_keys($all_movies_categories);
            if ($value->lang_key != 'other') {
                if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
                    $movies_categories[$value->lang_key] = $lang->{$value->lang_key};
                }
            }
            if (end($array_keys) == $key) {
                $movies_categories['other'] = $lang->other;
            }
        }
    }
    else{
        $movies_categories['other'] = $lang->other;
    }
} catch (Exception $e) {

}
$pt->movies_categories = $movies_categories;

$pt->musicCategories  = ToObject($musicCategories);
$pt->radioCategories  = ToObject($radioCategories);

$music_categories = array();
try {
    $all_music_categories = $db->where('type','music_category')->get(T_LANGS);
    if (!empty($all_music_categories)) {

        foreach ($all_music_categories as $key => $value) {
            $array_keys = array_keys($all_music_categories);
            if ($value->lang_key != 'other') {
                if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
//                    $catHasVideo = $db->where('category_id',$value->lang_key)->get(T_VIDEOS);
//                    if(count($catHasVideo) > 0)
                    $musicCategories[$value->lang_key] = $lang->{$value->lang_key};
                }
            }
            if (end($array_keys) == $key) {
                $musicCategories['other'] = $lang->other;
            }
        }
    }
    else{
        $musicCategories['other'] = $lang->other;
    }
} catch (Exception $e) {

}
$pt->musicCategories = $musicCategories;

$radio_categories = array();
try {
    $all_radio_categories = $db->where('type','radio_category')->get(T_LANGS);
    if (!empty($all_radio_categories)) {

        foreach ($all_radio_categories as $key => $value) {
            $array_keys = array_keys($all_radio_categories);
            if ($value->lang_key != 'other') {
                if (!empty($value->lang_key) && !empty($lang->{$value->lang_key})) {
//                    $catHasVideo = $db->where('category_id',$value->lang_key)->get(T_VIDEOS);
//                    if(count($catHasVideo) > 0)
                    $radioCategories[$value->lang_key] = $lang->{$value->lang_key};
                }
            }
            if (end($array_keys) == $key) {
                $radioCategories['other'] = $lang->other;
            }
        }
    }
    else{
        $radioCategories['other'] = $lang->other;
    }
} catch (Exception $e) {

}
$pt->radioCategories = $radioCategories;

$pt->singers  = ToObject($singers);

$singers_list = array();
try {
    $all_singers = $db->get(T_SINGERS);
    if (!empty($all_singers)) {

        foreach ($all_singers as $key => $value) {
            $array_keys = array_keys($all_singers);
            $singers[$value->id] = $value->name;
        }
    }
    else{
        $singers['other'] = $lang->other;
    }
} catch (Exception $e) {

}
$pt->singers = $singers;


$error_icon   = '<i class="fa fa-exclamation-circle"></i> ';
$success_icon = '<i class="fa fa-check"></i> ';
define('IS_LOGGED', $pt->loggedin);
define('none', null);
define('HasSignedUp', PT_HasUserSignedup($pt->user->mobile));


if (pt_is_banned($_SERVER["REMOTE_ADDR"]) === true) {
    $banpage = PT_LoadPage('terms/ban');
    exit($banpage);
}


if ($pt->config->user_ads == 'on') {

    if (!isset($_COOKIE['_uads'])) {
        setcookie('_uads', htmlentities(serialize(array(
            'date' => strtotime('+1 day'),
            'uaid_' => array()
        ))), time() + (10 * 365 * 24 * 60 * 60),'/');
    }

    $pt->user_ad_cons = array(
        'date' => strtotime('+1 day'),
        'uaid_' => array()
    );

    if (!empty($_COOKIE['_uads'])) {
        $pt->user_ad_cons = unserialize(html_entity_decode($_COOKIE['_uads']));
    }

    if (!is_array($pt->user_ad_cons) || !isset($pt->user_ad_cons['date']) || !isset($pt->user_ad_cons['uaid_'])) {
        setcookie('_uads', htmlentities(serialize(array(
            'date' => strtotime('+1 day'),
            'uaid_' => array()
        ))), time() + (10 * 365 * 24 * 60 * 60),'/');
    }

    if (is_array($pt->user_ad_cons) && isset($pt->user_ad_cons['date']) && $pt->user_ad_cons['date'] < time()) {
        setcookie('_uads', htmlentities(serialize(array(
            'date' => strtotime('+1 day'),
            'uaid_' => array()
        ))),time() + (10 * 365 * 24 * 60 * 60),'/');
    }
}

$pt->mode = (!empty($_COOKIE['mode'])) ? $_COOKIE['mode'] : null;
if ($pt->config->night_mode == 'night_default' && empty($pt->mode)) {
    $pt->mode = 'night';
}
if (empty($_COOKIE['mode']) || !in_array($_COOKIE['mode'], array('night','day')) && empty($pt->mode)) {
    $pt->mode = ($pt->config->night_mode == 'night_default' || $pt->config->night_mode == 'night') ? 'night' : 'day';
    setcookie("mode", $pt->mode, time() + (10 * 365 * 24 * 60 * 60), "/");
}

if (!empty($_POST['mode']) && in_array($_POST['mode'], array('night','day'))) {
    setcookie("mode", $_POST['mode'], time() + (10 * 365 * 24 * 60 * 60), "/");
    $pt->mode = $_POST['mode'];
}

if (!empty($_GET['mode']) && in_array($_GET['mode'], array('night','day'))) {
    setcookie("mode", $_GET['mode'], time() + (10 * 365 * 24 * 60 * 60), "/");
    $pt->mode = $_GET['mode'];
}

if ($pt->config->night_mode == 'light') {
    $pt->mode = 'light';
}

$site_url    = $pt->config->site_url;
$request_url = $_SERVER['REQUEST_URI'];
$fl_currpage = "{$site_url}{$request_url}";


if (empty($_SESSION['uploads'])) {

    $_SESSION['uploads'] = array();

    if (empty($_SESSION['uploads']['videos'])) {
        $_SESSION['uploads']['videos'] = array();
    }

    if (empty($_SESSION['uploads']['images'])) {
        $_SESSION['uploads']['images'] = array();
    }
}

$pt->theme_using = 'mana';
$path_to_details = './themes/' . $config['theme'] . '/fonts/info.json';
if (file_exists($path_to_details)) {
    $get_theme_info = file_get_contents($path_to_details);
    $decode_json = json_decode($get_theme_info, true);
    if (!empty($decode_json['name'])) {
        $pt->theme_using = $decode_json['name'];
    }
}

$pt->continents = array('Asia','Australia','Africa','Europe','America','Atlantic','Pacific','Indian');


require 'assets/includes/paypal_config.php';
require 'assets/import/ftp/vendor/autoload.php';
require 'context_data.php';
require_once('assets/includes/onesignal_config.php');