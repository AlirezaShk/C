<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\View;

use App\Controller\ConfigController;
use App\Controller\ErrorController;
use App\Controller\LangsController;
use App\Controller\SessionsController;
use App\Controller\UsersController;
use App\Model\Entity\User;
use Cake\Controller\Exception\AuthSecurityException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\Query;
use Cake\View\View;
use Uoozet\AjaxResponse;
require_once ROOT . '/vendor/uoozet/AjaxResponse.php';
/**
 * Application View
 *
 * Your application's default view class
 *
 * @link https://book.cakephp.org/4/en/views.html#the-app-view
 */
class AppView extends View
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading helpers.
     *
     * e.g. `$this->loadHelper('Html');`
     *
     * @return void
     */
    public function initialize(): void
    {
        /* LOAD CONFIG */
        $uoozet = (object) array();
        $config_manager = new ConfigController();
        $lang_manager = new LangsController();
        try {
            $config = $config_manager->getAll();
        } catch(RecordNotFoundException $e) {
            $config = (object) array(
                "language"=>'farsi'
            );
        }
        $config->navbarAvailable = (object) array(
            "home" => CONFIG__NAVBAR_DISPLAY_HOME,
            "cinema" => CONFIG__NAVBAR_DISPLAY_CINEMA,
            "audio_book" => CONFIG__NAVBAR_DISPLAY_AUDIO_BOOK,
            "radio_music" => CONFIG__NAVBAR_DISPLAY_RADIO_MUSIC,
            "articles" => CONFIG__NAVBAR_DISPLAY_ARTICLES,
            "tv" => CONFIG__NAVBAR_DISPLAY_TV,
            "video_share" => CONFIG__NAVBAR_DISPLAY_VIDEO_SHARE,
        );
        $config->langs = (object) $lang_manager->getAvailableLangs();
        /* PAGE INFO */
        $page = (Object) ['cat'=>$this->getTemplatePath(), 'name'=>$this->getTemplate()];
        $uoozet->page = $page;
        /* AUTH */
        $user_manager = new UsersController();
        $logged_in = false;
        $user = NULL;
        try {
            if (isset($_SESSION['session_id']) && !empty($_SESSION['session_id'])) {
                $session_manager = new SessionsController();
                $user_id = $session_manager->get(
                    ['session_id' => $_SESSION['session_id']], false)[0]['Sessions__user_id'];
                $user = $user_manager->get(['id'=>$user_id]);
                $logged_in = true;
            } elseif (!empty($_COOKIE['session_id']) && !empty($_COOKIE['session_id'])) {
                $session_manager = new SessionsController();
                $user_id = $session_manager->get(
                    ['session_id' => $_COOKIE['session_id']],false)[0]['Sessions__user_id'];
                $user = $user_manager->get(['id'=>$user_id]);
                $logged_in = true;
            } elseif (!empty($_POST['user_id']) && !empty($_POST['s'])) {
                $session_manager = new SessionsController();
                $platform   = ((!empty($_POST['platform'])) ? $_POST['platform'] : 'phone');
                $session_id = $_POST['s'];
                $user_id = $_POST['user_id'];
                $result = $session_manager->get([
                    'session_id' => $session_id,
                    'user_id' => $user_id,
                    'platform' => $platform ], false)[0]['Sessions__user_id'];
                $aj_resp = new AjaxResponse();
                if ($result) {
                    $user = $user_manager->get(['id'=>$user_id])[0];
                    if ( ($user === false) || ($user['Users__active'] == 0) )
                        $aj_resp->err('session: user not found');
                    $logged_in = true;
                } else {
                    $aj_resp->err('session: sid not found');
                }
            } elseif (!empty($_GET['user_id']) && !empty($_GET['s'])) {
                $session_manager = new SessionsController();
                $platform   = ((!empty($_GET['platform'])) ? $_GET['platform'] : 'phone');
                $session_id = $_GET['s'];
                $user_id = $_GET['user_id'];
                $result = $session_manager->get([
                    'session_id' => $session_id,
                    'user_id' => $user_id,
                    'platform' => $platform ], false)[0]['Sessions__user_id'];
                $aj_resp = new AjaxResponse();
                if ($result) {
                    $user = $user_manager->get(['id'=>$user_id])[0];
                    if ( ($user === false) || ($user['Users__active'] == 0) )
                        $aj_resp->err('session: user not found');
                    $logged_in = true;
                } else {
                    $aj_resp->err('session: sid not found');
                }
            } elseif (!empty($_GET['cookie'])) {
                $session_manager = new SessionsController();
                $session_id            = $_GET['cookie'];
                $user_id = $session_manager->get(
                    ['session_id' => $_SESSION['session_id']], false)[0]['Sessions__user_id'];
                $user = $user_manager->get(['id'=>$user_id]);
                if (!empty($user->language)) {
                    if (file_exists(ROOT . '/resources/langs/' . $user->language . '.php')) {
                        $_SESSION['lang'] = $user->language;
                    }
                    setcookie("session_id", $session_id, time() + (10 * 365 * 24 * 60 * 60), "/");
                }
            }
        } catch (RecordNotFoundException $e) {
//            if (DEBUG_STATUS)
//                print_r($e->getFile() . " | L: " . $e->getLine());
        }
        if (isset($_GET['lang']) AND !empty($_GET['lang'])) {
            $lang_name = strtolower($_GET['lang']);
            if (in_array($lang_name, (array) $config->langs)) {
                $_SESSION['lang'] = $lang_name;
                if ($logged_in) {
                    $lang_manager->update(['id'=>$user->id], ['language'=>$lang_name]);
                }
            }
        }

        if (empty($_SESSION['lang'])) {
            $_SESSION['lang'] = $config->language;
        }
        $config->language_type = 'ltr';

        /* LANG */
//      Add rtl languages here.
        $rtl_langs = array(
            'arabic',
            'farsi'
        );

//      checking if corrent language is rtl.
        foreach ($rtl_langs as $lang) {
            if ($_SESSION['lang'] == strtolower($lang)) {
                $config->language_type = 'rtl';
            }
        }

        $lang_file = ROOT . '/resources/langs/' . $_SESSION['lang'] . '.php';
        if (file_exists($lang_file)) {
            require($lang_file);
        }

        if (isset($_SESSION['session_id'])) { //former: user_id
            if (empty($_COOKIE['session_id'])) {
                setcookie("session_id", $_SESSION['session_id'], time() + (10 * 365 * 24 * 60 * 60), "/");
            }
        }

//        $this->set('user', $user);
        $uoozet->user = $user;
        $uoozet->config = $config;
        $this->set('uoozet', $uoozet);
        define('IS_LOGGED', $logged_in);
        /*
         * Use the same procedure for each page. The required JS files are:
         * 'tag-it.min',
         * */
        switch ($this->getTemplate()){
            case "login":
            case "register":
                $this->assign('script', $this->Html->script('tag-it'));
                break;
            case 'watch':
            case 'timeline':
                $this->start('player');
                //main:
                $this->Html->script('/webroot/player/js/mediaelement-and-player.min');
                $this->Html->script('https://cdn.jsdelivr.net/npm/mediaelement@4.2.7/build/renderers/facebook.min.js', ['fullBase'=>true]);
                $this->Html->css(['/webroot/player/css/mediaelementplayer.min']);
                //plugins:
                $this->Html->script([
                    '/webroot/plugins/ads/ads.min', '/webroot/plugins/ads/ads-i18n.js',
                    '/webroot/plugins/facebook-pixel/facebook-pixel',
                    '/webroot/plugins/jump/jump-forward.min',
                    '/webroot/plugins/quality/quality.min',
                    '/webroot/plugins/speed/speed.min', '/webroot/plugins/speed/speed-i18n',
                    '/webroot/plugins/vast/ads-vast-vpaid.min'
                ]);
                $this->Html->css([
                    '/webroot/plugins/ads/ads.min',
                    '/webroot/plugins/jump/jump-forward.min',
                    '/webroot/plugins/quality/quality.min',
                    '/webroot/plugins/speed/speed.min'
                ]);
                $this->end();
                break;
        }
        /*
         * $this->assign('twitch', $this->Html->script('https://player.twitch.tv/js/embed/v1.js', ['fullBase'=>true]));
         * $this->assign('twitch', $this->Html->script('https://www.google.com/recaptcha/api.js', ['fullBase'=>true]));
         */
    }
}
