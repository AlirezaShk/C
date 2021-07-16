<?php


namespace Model;


class Payment extends \lib\Model
{
    const TABLE_PAYMENT = 'payments';
    const TABLE_VIDEO_TRANSACTION = 'videos_transactions';

    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function setTableName($string) {
        $this->tableName = $string;
    }
}