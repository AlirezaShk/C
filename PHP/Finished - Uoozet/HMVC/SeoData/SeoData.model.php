<?php


namespace Model;


class SeoData extends \lib\Model
{
    const TABLE_NAME = "seo_data";

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