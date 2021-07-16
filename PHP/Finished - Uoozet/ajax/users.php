<?php
/**
 * Created by PhpStorm.
 * User: naseri
 * Date: 12/21/2019
 * Time: 9:21 AM
 */
header('Access-Control-Allow-Origin: *');
$users = PT_GetAllUsers();
$data = array(
    'status' => 200,
    'users' => $users
);


header("Content-type: application/json");
if (isset($errors)) {
    echo json_encode(array(
        'errors' => $errors
    ));
    exit();
}
