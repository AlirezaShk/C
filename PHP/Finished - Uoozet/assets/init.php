<?php
if (!version_compare(PHP_VERSION, '5.4.0', '>=')) {
    exit("Required PHP_VERSION >= 5.4.0 , Your PHP_VERSION is : " . PHP_VERSION . "\n");
}
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.playtubescript.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | PlayTube - The Ultimate Video Sharing Platform
// | Copyright (c) 2017 PlayTube. All rights reserved.
// +------------------------------------------------------------------------+
date_default_timezone_set('UTC');
session_start();
require('assets/includes/IPtoLang.php');
require('assets/includes/functions_general.php');
require('assets/includes/functions_extra.php');
require('assets/includes/tables.php');
require('assets/includes/functions_one.php');

define("VIDEO_SHARING_LIMIT", 4);
define("TRENDING_VIDEO_DAY_PERIOD", 5);
define("TRENDING_VIDEO_VIEWS_SCORE", 1);
define("TRENDING_VIDEO_LIKES_SCORE", 1);
define("TRENDING_VIDEO_TREND_SCORE_LOW_LIMIT", 80);
define("PATH_TO_CLASSES", "HMVC/");

//AUTOLOADER
function myAutoLoader($className)
{
    $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $prePrePath = "";
    if (strpos($url,'includes') !== false) {
        $prePrePath .= "../../";
    }
    $prePath = PATH_TO_CLASSES;
    $alreadyFull = explode("\\", $className);
    if(count($alreadyFull) !== 1) {
        $className = $alreadyFull[count($alreadyFull) - 1];
    }
    $fullPath_ctrl = $prePrePath . $prePath . "$className/$className.ctrl.php";
    $fullPath_model = $prePrePath . $prePath . "$className/$className.model.php";
    $fullPath_classLib = $prePrePath . $prePath . "lib/$className.lib.php";
    if (file_exists($fullPath_ctrl)) {
        @include_once $fullPath_model;
        require_once $fullPath_ctrl;
    } elseif(file_exists($fullPath_classLib)) {
        require_once $fullPath_classLib;
    } else {
        return false;
    }
}
require PATH_TO_CLASSES . "lib/config.lib.php";
spl_autoload_register("myAutoLoader", TRUE);