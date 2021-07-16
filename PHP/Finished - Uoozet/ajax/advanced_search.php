<?php
$data = array('status' => 200);
$content = "";
$condString = $_POST['data'];
$target = $_POST['target'];
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
if (in_array(0, $target)) {
    $userCondString = ((strlen($_POST['user']) > 2) ? ($_POST['user']) : (""));
    $VM = new Video();
    $result = $VM->runSearchQuery($condString, $userCondString);
    $content = $VM->generateViewList($result);
}
if (in_array(1, $target)) {
    $MM = new Movie();
    $result = $MM->runSearchQuery($condString);
    $content = $MM->generateViewList($result);
}
$data['content'] = $content;
echo json_encode($data);
exit();
//print_r($result);
//$binary_cond = array();
//$like_cond = array();
//$binary_cond['is_movie'] = 1;
//$like_cond['categories'] = $dataString[''];