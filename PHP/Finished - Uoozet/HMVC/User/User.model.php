<?php


namespace Model;


class User extends \lib\Model
{
    const TABLE_NAME = "users";

    public function __construct()
    {
        $this->tableName = self::TABLE_NAME;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function setTableName($string)
    {
        $this->tableName = $string;
    }
}