<?php


namespace Model;


class RegisterCode extends \lib\Model
{
    const TABLE_NAME = "register_code";

    public function __construct()
    {
        $this->tableName = self::TABLE_NAME;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function getOnePersonalByUserId(int $user_id)
    {
        $codeExists = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `user_id` = :uid LIMIT 1");
        $codeExists->bindValue(":uid", $user_id);
        if($codeExists->execute())
            return $codeExists->fetchAll();
        else
            return FALSE;
    }

    public function getAllPersonalByUserId(int $user_id)
    {
        $codeExists = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `user_id` = :uid");
        $codeExists->bindValue(":uid", $user_id);
        if($codeExists->execute())
            return $codeExists->fetchAll();
        else
            return FALSE;
    }

    public function getOneByCode(string $code)
    {
        $codeExists = $this->db->prepare("SELECT * FROM `" . $this->getTableName() . "` WHERE `code` = :code LIMIT 1");
        $codeExists->bindValue(":code", $code);
        if($codeExists->execute())
            return $codeExists->fetch();
        else
            return FALSE;
    }

}