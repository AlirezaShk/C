<?php
error_reporting(0);
require 'config.php';
require 'phpMailer_config.php';
require 'assets/import/DB/vendor/autoload.php';
require 'assets/import/getID3-1.9.14/getid3/getid3.php';
require 'assets/import/youtube-sdk/vendor/autoload.php';

$pt     = ToObject(array());
// Connect to MySQL Server
$mysqli = new mysqli($sql_db_host, $sql_db_user, $sql_db_pass, $sql_db_name);


$pt->script_version = '1.0.1';

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

$pt->site_pages = array('home');
$pt->actual_link = $http_header . $_SERVER['HTTP_HOST'] . urlencode($_SERVER['REQUEST_URI']);


$config          = PT_GetConfig();
$pt->loggedin = false;
$config['user_statics'] = htmlspecialchars_decode($config['user_statics']);
$config['videos_statics'] = htmlspecialchars_decode($config['videos_statics']);
$config['theme_url']      = $site_url . '/themes/' . $config['theme'];
$config['site_url']       = $site_url;
$config['script_version'] = $pt->script_version;


$pt->config               = ToObject($config);


if (PT_IsLogged() == true) {
    $session_id = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : $_COOKIE['user_id'];
    $pt->user_session = PT_GetUserFromSessionID($session_id);
    $user = $pt->user  = PT_UserData($pt->user_session);
    if (!empty($user->language)) {
        if (file_exists(__DIR__ . '/../langs/' . $user->language . '.php')) {
            $_SESSION['lang'] = $user->language;
        }
    }
    if ($user->id < 0 || empty($user->id) || !is_numeric($user->id) || PT_UserActive($user->id) === false) {
        header("Location: " . PT_Link('logout'));
    }
    $pt->loggedin = true;
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

$pt->userDefaultAvatar = 'upload/photos/d-avatar.jpg';
$pt->categories = ToObject($categories);


$error_icon   = '<i class="fa fa-exclamation-circle"></i> ';
$success_icon = '<i class="fa fa-check"></i> ';
define('IS_LOGGED', $pt->loggedin);