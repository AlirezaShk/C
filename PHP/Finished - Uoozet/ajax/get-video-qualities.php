<?php
require "config.php";
$dsn = "mysql:host=localhost;dbname=$sql_db_name";
$user = "$sql_db_user";
$passwd = "$sql_db_pass";
$pdo = new PDO($dsn, $user, $passwd);
$pdo->exec("SET NAMES 'utf8';");
$vid = $_POST['id'];
try {
    $rs = $pdo->prepare("SELECT * FROM `videos` WHERE `id` = :id");
    $rs->bindParam(":id",$vid);
    $rs->execute();
    $video = $rs->fetch();
}
catch(PDOException $e){
    echo $e->getMessage();
    exit();
}
$Qs = array();
$Qs["240p"] = $video["240p"];
$Qs["360p"] = $video["360p"];
$Qs["480p"] = $video["480p"];
$Qs["720p"] = $video["720p"];
$Qs["1080p"] = $video["1080p"];
$Qs["2048p"] = $video["2048p"];
$Qs["4096p"] = $video["4096p"];
echo json_encode($Qs);