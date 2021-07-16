<?php


class Alumni extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setOne(int $id, int $type, int $name, int $description = NULL, int $user_id = NULL,
                           string $thumbnail = NULL, string $cover = NULL)
    {
        $cond = array("id"=>$id);
        $data = array(
            'type'=>$type,
            'name'=>$name
        );
        if (!is_null($description)) $data['description'] = $description;
        if (!is_null($user_id)) $data['user_id'] = $user_id;
        if (!is_null($thumbnail)) $data['thumbnail'] = $thumbnail;
        if (!is_null($cover)) $data['cover'] = $cover;
        return $this->model->setOne($data, $cond);
    }

    public function addOne(int $type, int $name, int $description = NULL, int $user_id = NULL,
                           string $thumbnail = NULL, string $cover = NULL)
    {
        $data = array(
            'type'=>$type,
            'name'=>$name
        );
        if (!is_null($description)) $data['description'] = $description;
        if (!is_null($user_id)) $data['user_id'] = $user_id;
        if (!is_null($thumbnail)) $data['thumbnail'] = $thumbnail;
        if (!is_null($cover)) $data['cover'] = $cover;
        return $this->model->addOne($data);
    }

    public function delOne(int $id)
    {
        return $this->model->delOne(array("id"=>$id));
    }

    public function getOne(int $id, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("id"=>$id)), $returnType);
    }
}