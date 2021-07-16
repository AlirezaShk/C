<?php


namespace Model;


class Movie extends Video
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

    //TODO: Remove this and add something more classy.
    public function rawQuery($query, $user = "")
    {
        $run = $this->db->prepare("SELECT DISTINCT `videos`.* FROM `".$this->getTableName()."`, `users` WHERE `is_movie` = 0". ((strlen($query) > 0) ? (" AND " . $query) : ("")). ((strlen($user) > 0) ? (" AND " . $user) : ("")) . " ORDER BY `id` DESC");
//        echo "SELECT * FROM `".$this->getTableName()."` WHERE `is_movie` = 1". ((strlen($query) > 0) ? (" AND " . $query) : (""));
        $run->execute();
        return $run->fetchAll();
    }

    public function getAll()
    {
        $getAll = $this->db->prepare("SELECT * FROM `" . $this->tableName . "` WHERE `is_movie` = 1");
        $getAll->execute();
        return $getAll->fetchAll();
    }
}