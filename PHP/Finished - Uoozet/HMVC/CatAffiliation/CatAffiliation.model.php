<?php


namespace Model;


class CatAffiliation extends \lib\Model
{
    const TABLE_NAME = "cat_affiliation";

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