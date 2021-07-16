<?php


namespace Model;


class Channels extends \lib\Model
{
    const TABLE_NAME = 'channels';

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