<?php
// test:
$user_ip = "93.117.180.13";
//-----------------------.
$file_list = array(0 => "IPList/ir.csv");
$lang_list = array(0 => "farsi");
$_SESSION['lang'] = NULL;
if(!isset($_COOKIE['user']) || isset($_SESSION['lang']) || !isset($_GET['lang'])){
    //whether ip is from share internet
    if(!isset($user_ip)){
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $user_ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        //whether ip is from proxy
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        //whether ip is from remote address
        else
        {
            $user_ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    for($i = 0; $i < 1; $i++){
        if (($handle = @fopen($file_list[$i], "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if(count($data) > 1)
                    if($user_ip < $data[1] && $user_ip > $data[0])
                    {
                        $_SESSION['lang'] = $lang_list[$i];
                    }
            }
            fclose($handle);
        }
    }
    if(is_null($_SESSION['lang']))
        $_SESSION['lang'] = $lang_list[0];
}