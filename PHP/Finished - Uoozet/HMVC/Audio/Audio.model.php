<?php


namespace Model;


class Audio extends Video
{
    public function __construct()
    {
        parent::__construct();
        $this->tableName = parent::TABLE_NAME;
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    public function getAll()
    {
        $getAll = $this->db->prepare("SELECT * FROM `" . $this->tableName . "` WHERE (`is_movie` = 2) OR (`is_movie` = 3)");
        $getAll->execute();
        return $getAll->fetchAll();
    }
}