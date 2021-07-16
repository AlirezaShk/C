<?php


namespace Model;


class Info extends \lib\Model
{
    const TABLE_NAME = "info";

    public function __construct()
    {
        $this->tableName = self::TABLE_NAME;
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }
}