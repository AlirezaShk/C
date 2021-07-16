<?php


namespace Model;


class Alumni extends \lib\Model
{
    CONST TABLE_NAME = "alumni";

    public function __construct()
    {
        parent::__construct();
        $this->tableName = self::TABLE_NAME;
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}