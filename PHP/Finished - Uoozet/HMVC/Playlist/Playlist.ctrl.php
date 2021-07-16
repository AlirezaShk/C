<?php


class Playlist extends \lib\Controller
{

    const TYPE_VIDEO = 1;
    const TYPE_MOVIE = 2;
    const TYPE_MUSIC = 3;
    const TYPE_CHANNEL = 4;

    public function __construct()
    {
        parent::__construct();
    }

    public function createOne (string $name, int $type, int $userId,
                              array $cats = NULL, array $vids = NULL, string $description = NULL, int $returnType = self::RETURN_RAW)
    {
        $data = array();
        $data['name'] = $name;
        $data['description'] = (is_null($vids) ? NULL : $description);
        $data['vids'] = (is_null($vids) ? NULL : (implode("|", $vids) . "|"));
        $data['category'] = (is_null($vids) ? NULL : (implode("|", $cats) . "|"));
        $data['type'] = $type;
        $data['user_id'] = $userId;
        return $this->returnData($this->model->addOne($data), $returnType, FALSE);
    }

    public function getOne (int $id, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("id"=>$id)), $returnType);
    }

    public function addCat (int $id, array $cids, int $returnType = self::RETURN_RAW)
    {
        $cond = array("id"=>$id);
        $plist = $this->getOne($id);
        foreach ($cids as $k => $cid) {
            if (strpos($plist['category'], $cid . "|") !== FALSE)
                unset ($cids[$k]);
        }
        $data = array("category"=>$plist['category'] . "|" . implode("|", $cids) . "|");
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }

    public function addVidTo (int $id, array $vids, int $returnType = self::RETURN_RAW)
    {
        $cond = array("id"=>$id);
        $plist = $this->getOne($id);
        foreach ($vids as $k => $vid) {
            if (strpos($plist['video_id'], $vid . "|") !== FALSE)
                unset ($vids[$k]);
        }
        $data = array("video_id"=>$plist['video_id'] . "|" . implode("|", $vids) . "|");
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }

    public function setCat (int $id, array $cids, int $returnType = self::RETURN_RAW)
    {
        $data = array("category"=>implode("|", $cids) . "|");
        $cond = array("id"=>$id);
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }

    public function setType(int $id, int $type, int $returnType = self::RETURN_RAW) {
        $data = array("type"=>$type);
        $cond = array("id"=>$id);
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }

    public function delOne (int $id, int $returnType = self::RETURN_RAW)
    {
        return $this->returnData($this->model->delOne(array("id"=>$id)), $returnType);
    }

    public function delCat (int $id, array $cids, int $returnType = self::RETURN_RAW)
    {
        $data = array();
        $cond = array("id"=>$id);
        $data['category'] = $this->getOne($id)['category'];
        foreach ($cids as $k => $cid) {
            $data['category'] = str_replace("$cid|", "", $data['category']);
        }
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }

    public function delVidFrom (int $id, array $vids, int $returnType = self::RETURN_RAW)
    {
        $data = array();
        $cond = array("id"=>$id);
        $data['video_id'] = $this->getOne($id)['video_id'];
        foreach ($vids as $k => $vid) {
            $data['video_id'] = str_replace("$vid|", "", $data['video_id']);
        }
        return $this->returnData($this->model->setOne($data, $cond), $returnType);
    }
}