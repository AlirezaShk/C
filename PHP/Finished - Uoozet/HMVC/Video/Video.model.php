<?php


namespace Model;


use PDO;

class Video extends \lib\Model
{
    const TABLE_NAME = "videos";

    public function __construct()
    {
        $this->tableName = self::TABLE_NAME;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function getAllByCat(int $catId)
    {
        $vidInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `category_id` = :cid OR  `categories` LIKE :scid");
        $vidInfo->bindValue(":cid", $catId);
        $vidInfo->bindValue(":scid", '%'.$catId.'|%');
        if($vidInfo->execute())
            return $vidInfo->fetchAll();
        else
            return FALSE;
    }

    public function getAllForCatsMigrationFromLangKeyToId()
    {
        $vidInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `category_id` < 200");
        if($vidInfo->execute())
            return $vidInfo->fetchAll();
        else
            return FALSE;
    }

    //TODO: Remove this and add something more classy.
    public function rawQuery($query, $user = "")
    {
        $run = $this->db->prepare("SELECT DISTINCT `videos`.* FROM `".$this->getTableName()."`, `users` WHERE `is_movie` = 0". ((strlen($query) > 0) ? (" AND " . $query) : ("")). ((strlen($user) > 0) ? (" AND " . $user) : ("")) . " ORDER BY `id` DESC");
//        echo "SELECT DISTINCT `videos`.* FROM `".$this->getTableName()."`, `users` WHERE `is_movie` = 0". ((strlen($query) > 0) ? (" AND " . $query) : ("")). ((strlen($user) > 0) ? (" AND " . $user) : ("")) . " ORDER BY `id` DESC";
        $run->execute();
        return $run->fetchAll();
    }

    //SuggestedVideos
    public function getSuggestedVideos($userId)
    {
        try {
            $query = $this->db->prepare("select * from suggested_videos where user_id=:user_id");
            $query->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $query->execute();
            $result = $query->fetch();
        }catch (Exception $e){
            return -2;
        }

        if ($query->rowCount()){
            return $result['videos'];
        }else{
            return -1;
        }
    }

    public function insertUserSuggestedVideosRow($user_id)
    {
        try {
            $query = $this->db->prepare("insert into suggested_videos (user_id,videos) value (:user_id,'{}')");
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
        }catch (Exception $e){
            return 0;
        }

        if ($query->rowCount()){
            return 1;
        }else{
            return 0;
        }
    }

    public function addPointToSuggestedVideos($user_id,$video_id,$point)
    {
        try {
            $stamp = '$."'.$video_id.'"';
            $point = $point+1;
            $query = $this->db->prepare('UPDATE `suggested_videos` set videos = JSON_SET(videos,\''.$stamp.'\',"'.$point.'")           
                        where user_id=:user_id');
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
        }catch (Exception $e){
            return 0;
        }

        if ($query->rowCount()){
            return 1;
        }else{
            return 0;
        }
    }

    public function InsertVideoToSuggestedVideos($user_id,$video_id)
    {
        try {
            $stamp = '$."'.$video_id.'"';
            $point = 1;
            $query = $this->db->prepare('UPDATE `suggested_videos` set videos = 
                    JSON_INSERT(
                    `videos` ,
                    \''.$stamp.'\',
                    "'.$point.'"
                    )           
                    where user_id=:user_id');
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
        }catch (Exception $e){
            return 0;
        }

        if ($query->rowCount()){
            return 1;
        }else{
            return 0;
        }
    }

    public function deleteVideoFromSuggestedVideos($user_id,$video_id)
    {
        try {
            $stamp = '$."'.$video_id.'"';
            $point = 0;
            $query = $this->db->prepare('UPDATE `suggested_videos` SET videos =  
                    JSON_SET(
                    `videos` ,
                    \''.$stamp.'\',
                    "'.$point.'"
                    )           
                    where user_id=:user_id');
            $query->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $query->execute();
        }catch (Exception $e){
            return 0;
        }

        if ($query->rowCount()){
            return 1;
        }else{
            return 0;
        }
    }
}