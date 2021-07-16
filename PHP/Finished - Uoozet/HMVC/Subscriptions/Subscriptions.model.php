<?php


namespace Model;


class Subscriptions extends \lib\Model
{
    const TABLE_NAME = 'subscriptions2';

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