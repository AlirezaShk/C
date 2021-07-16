<?php
error_reporting(0);
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


$pt->script_version = '1.2.1';

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
if (!version_compare(PHP_VERSION, '5.4.0', '>=')) {
    $ServerErrors[] = "Required PHP_VERSION >= 5.4.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n";
}
if (isset($ServerErrors) && !empty($ServerErrors)) {
    foreach ($ServerErrors as $Error) {
        echo "<h3>" . $Error . "</h3>";
    }
    die();
}
$query = $mysqli->query("SET NAMES utf8");
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
$config['user_statics']   = htmlspecialchars_decode($config['user_statics']);
$config['videos_statics'] = htmlspecialchars_decode($config['videos_statics']);
$config['theme_url']      = $site_url . '/themes/' . $config['theme'];
$config['site_url']       = $site_url;
$config['script_version'] = $pt->script_version;


$pt->config               = ToObject($config);




if (PT_IsLogged() == true) {
    $session_id        = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $pt->user_session  = PT_GetUserFromSessionID($session_id);
    $user = $pt->user  = PT_UserData($pt->user_session);
    $user->wallet      = number_format($user->wallet,2);
    if (!empty($user->language)) {
        if (file_exists(__DIR__ . '/../langs/' . $user->language . '.php')) {
            $_SESSION['lang'] = $user->language;
        }
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
}


if (isset($_GET['lang']) AND !empty($_GET['lang'])) {
    $lang_name = PT_Secure(strtolower($_GET['lang']));
    $lang_path = 'assets/langs/' . $lang_name . '.php';
    if (file_exists($lang_path)) {
        $_SESSION['lang'] = $lang_name;
        if ($pt->loggedin == true) {
            $db->where('id', $user->id)->update(T_USERS, array('language' => $lang_name));
        }
    }
}

if (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = $pt->config->language;
}

$pt->language      = $_SESSION['lang'];
$pt->language_type = 'ltr';

// Add rtl languages here.
$rtl_langs           = array(
    'arabic'
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

$lang            = ToObject($lang_array);
$pt->exp_feed    = false; 
$pt->userDefaultAvatar = 'upload/photos/d-avatar.jpg';
$pt->categories = ToObject($categories);


$error_icon   = '<i class="fa fa-exclamation-circle"></i> ';
$success_icon = '<i class="fa fa-check"></i> ';
define('IS_LOGGED', $pt->loggedin);
define('none', null);

$pt->months   = array(
    '1'  => 'January',
    '2'  => 'February',
    '3'  =>'March',
    '4'  =>'April',
    '5'  =>'May',
    '6'  =>'June',
    '7'  =>'July',
    '8'  =>'August',
    '9'  =>'September',
    '10' =>'October',
    '11' =>'November',
    '12' =>'December'
);

$pt->ads_media_types = array(
    'video/mp4',
    'video/mov',
    'video/mpeg',
    'video/flv',
    'video/avi',
    'video/webm',
    'video/quicktime',
    'image/png',
    'image/jpeg',
    'image/gif'
);

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


$site_url    = $pt->config->site_url;
$request_url = $_SERVER['REQUEST_URI'];
$fl_currpage = "{$site_url}{$request_url}";

require 'assets/includes/paypal_config.php';