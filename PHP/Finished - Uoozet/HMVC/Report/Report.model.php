<?php


namespace Model;


class Report extends \lib\Model
{
    const TABLE_NAME = "user_comms";

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