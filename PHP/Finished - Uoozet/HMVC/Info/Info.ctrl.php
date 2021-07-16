<?php


class Info extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($cond, int $returnType = self::RETURN_ARRAY) {
        return $this->returnData($this->model->getOne($cond), $returnType);
    }

    public function addOne($data) {
        return (bool) $this->model->addOne($data);
    }
}