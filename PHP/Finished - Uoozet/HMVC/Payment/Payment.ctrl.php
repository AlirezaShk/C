<?php


class Payment extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function purchase(int $uid, int $vid)
    {
        $videoManager = new Video();
        $videoManager->setBinary_conditions(['id'=>$vid]);
        $video = $videoManager->getMatches();
        try {
            $check = $this->checkBalance($uid, $video['price'] . "_" . $video['currency'], TRUE);
        } catch (Exception $e) {
            if ($this->debugMode) throw $e;
            else return FALSE;
        }
        if ($check !== FALSE) {
            $userManager = new User();
            $userManager->setBalance($uid, $this->formatPrice($check['v'], $check['c']));
            $this->model->setTableName(\Model\Payment::TABLE_VIDEO_TRANSACTION);
            $this->model->addOne([
                'user_id'=>$video['user_id'],
                'paid_id'=>$uid,
                'video_id'=>$vid,
                'amount'=>$video['price'],
                'admin_com'=>0,
                'currency'=>$video['currency'],
                'time'=>time(),
                'type'=>''
            ]);
            $videoManager->modifyPaidList($vid, $uid, $this->getLastRecordIDForUser($uid), TRUE);
            return TRUE;
        } else return FALSE;
    }

    public function checkBalance(int $uid, string $price = NULL, bool $returnNewBalance = FALSE) {
        $userManager = new User();
        $user = $this->deFormatPrice($userManager->getBalance($uid));
        if(is_null($price)) return $user['v'];
        else {
            $target = $this->deFormatPrice($price);
            if ($user['c'] === $target['c']) {
                if ($returnNewBalance) return ['v'=>$user['v'] - $target['v'], 'c'=>$user['c']];
                else return ($user['v'] >= $target['v']);
            }
            else throw new Exception('Currencies do not match!');
        }
    }

    private function deFormatPrice(string $price) {
        if (strlen($price) == 1) return 0;
        return ['v'=>intval(explode('_', $price)[0]), 'c'=>explode('_', $price)[1]];
    }

    private function formatPrice($price, $currency = NULL) {
        if (is_null($currency)) return implode("_", $price);
        else return $price . "_" . $currency;
    }

    public function getAllVT(int $returnType = self::RETURN_ARRAY)
    {
        $this->model->setTableName(\Model\Payment::TABLE_VIDEO_TRANSACTION);
        $r = $this->returnData($this->model->getAll(), $returnType);
        $this->model->setTableName('');
        return $r;
    }

    private function getLastRecordIDForUser($user_id)
    {
        $this->model->setTableName(\Model\Payment::TABLE_VIDEO_TRANSACTION);
        $r = $this->returnData($this->model->getOne(
            ['user_id'=>$user_id], ['id'], ['id'=>'DESC'], 1
        ), self::RETURN_ARRAY);
        $this->model->setTableName('');
        return $r['id'];
    }
}