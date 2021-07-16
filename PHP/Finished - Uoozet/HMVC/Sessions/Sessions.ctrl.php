<?php


class Sessions extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOne($session_key, $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("session_id"=>$session_key)), $returnType);
    }
}