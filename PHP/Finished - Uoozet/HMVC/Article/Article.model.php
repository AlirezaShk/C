<?php


namespace Model;


class Article extends Video
{
    const TABLE_NAME = "pt_posts";

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