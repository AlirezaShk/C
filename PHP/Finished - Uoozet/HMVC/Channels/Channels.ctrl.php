<?php


class Channels extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function registerUser(int $channel_id, int $user_id, bool $subscribe = TRUE)
    {
        $ch = $this->getOne(['id'=>$channel_id], self::RETURN_OBJECT);
        if (strpos($ch->paid_for, "|" . $user_id . "|") !== FALSE) {
            $paid_for = $ch->paid_for . $user_id . "|";
            $cond = ['id'=>$channel_id];
            $data = ['paid_for'=>$paid_for];
            $this->model->setOne($data, $cond);
            return true;
        } else
            return false;
    }

    public function getOne(array $cond, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne($cond), $returnType);
    }

    public function addOne(array $data)
    {
        return (bool) $this->model->addOne($data);
    }

    public function generateViewList(array $ch_array)
    {
        $string = "";
        $subs = new Subscriptions();
        foreach ($ch_array as $ch) {
            $ch = (object) $ch;
            $string .= PT_LoadPage('lists/channel-list', array(
                'ID' => $ch->id,
                'VIEWS' => '',
                'VIEWS_COUNT' => '',
                'SUB' => $subs->subscribeCount($ch->id, Subscriptions::SUB_TYPE_CHANNELS),
                'ACTIVE_TIME' => ''
            ));
        }
        return $string;
    }

    public function getAll($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAll(), $returnType);
    }
}