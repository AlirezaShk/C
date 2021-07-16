<?php


namespace Model;


class Lang extends \lib\Model
{
    const TABLE_NAME = "langs";

    public function __construct()
    {
        $this->tableName = self::TABLE_NAME;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function getAllCats()
    {
        $userInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `type` = 'category'");
        if($userInfo->execute()) {
            return $userInfo->fetchAll();
        } else {
            return FALSE;
        }
    }

    public function getAllSubCatsOf(int $catId)
    {
        $userInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `type` = :cat");
        $userInfo->bindValue(":cat", $catId, \PDO::PARAM_STR);
        if($userInfo->execute()) {
            return $userInfo->fetchAll();
        } else {
            return FALSE;
        }
    }

    public function getAllUCatsOf(int $user_id)
    {
        $userInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `type` = 'cat-$user_id'");
        if($userInfo->execute()) {
            return $userInfo->fetchAll();
        } else {
            return FALSE;
        }
    }

    public function getAllUCats()
    {
        $userInfo = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `type` LIKE '%cat-%' ORDER BY `type`");
        if($userInfo->execute()) {
            return $userInfo->fetchAll();
        } else {
            return FALSE;
        }
    }
    
    public function deleteOne($key)
    {
        $deleteOne = $this->db->prepare("DELETE FROM `" . $this->getTableName() . "` WHERE `lang_key` = :v");
        $deleteOne->bindValue(":v", $key);
        return($deleteOne->execute());
    }
}