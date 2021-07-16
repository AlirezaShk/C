<?php


class User extends \lib\Controller
{
    const BY_ID = 0;
    const BY_USERNAME = 1;
    const BY_MOBILE = 2;
    const BY_IP = 3;
    const BY_KEYWORD = 4;

    const GET_COLS = array("id", "username", "mobile", "ip_address", "keyword");
    const COLS = array("u" => "username", "fn" => "first_name", "ln" => "last_name");
    const ACCESS_RIGHTS = 'admin';

    public function __construct()
    {
        parent::__construct();
    }

    public function toggleTable()
    {
        if($this->model->getTableName() == 'users')
            $this->model->setTableName('users2');
        else $this->model->setTableName('users');
    }

    public function generateRandomUsername()
    {
        do {
            $ts = (time() % 10000);
            $string = "user" . $ts;
        } while($this->exists(['username'=>$string]));
        return $string;
    }

    public function addOne(array $data)
    {
        return (bool) $this->model->addOne($data);
    }

    public function getOne($value, int $type, $returnType = self::RETURN_ARRAY)
    {
        if ($type !== self::BY_KEYWORD)
            return $this->returnData($this->model->getOne(array(self::GET_COLS[$type]=>$value)), $returnType);
        else
            return $this->returnData($this->model->getOne(NULL, NULL, NULL, NULL, array("username"=>$value, "first_name"=>$value, "last_name"=>$value), array("username"=>"OR", "first_name"=>"OR")), $returnType);
    }

    public function getAll($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAll(), $returnType);
    }

    public function getAllAdmins($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("admin"=>1)), $returnType);
    }

    public function getAllChannels($returnType = self::RETURN_ARRAY)
    {
        $admins = $this->getAllAdmins();
        foreach($admins as $key => $admin) {
            if (($admin['id'] == 1) OR (strtolower($admin['username']) == "alirezashk")) {
                unset($admins[$key]);
            }
        }
        return $this->returnData($admins, $returnType);
    }

    public function setRegisterCode($user_id, $code)
    {
        $update_data = array("registerCode" => $code);
        $update_cond = array("id" => $user_id);
        return ( ($this->model->setOne($update_data, $update_cond) === 1 ) ? ( TRUE ) : ( FALSE ) );
    }

    public function setRegisteredCode($user_id, $code)
    {
        $update_data = array("registeredCode" => $code);
        $update_cond = array("id" => $user_id);
        return ( ($this->model->setOne($update_data, $update_cond) === 1 ) ? ( TRUE ) : ( FALSE ) );
    }

    public function modifyPoints($user_id, $val)
    {
        $points = intval($this->getOne($user_id, self::BY_ID)['registerPoints']);
        $points += $val;
        $update_data = array("registerPoints" => $points);
        $update_cond = array("id" => $user_id);
        return ( ($this->model->setOne($update_data, $update_cond) === 1 ) ? ( TRUE ) : ( FALSE ) );
    }

    public function setPoints($user_id, $val)
    {
        $update_data = array("registerPoints" => $val);
        $update_cond = array("id" => $user_id);
        return ( ($this->model->setOne($update_data, $update_cond) === 1 ) ? ( TRUE ) : ( FALSE ) );
    }

    public function getOneBySessionKey($session_key, $returnType = self::RETURN_ARRAY)
    {
        $SM = new Sessions();
        $uid = $SM->getOne($session_key, self::RETURN_OBJECT)->user_id;
        return $this->getOne($uid, "id", $returnType);
    }

    public function buildVideoSearchQuery($query)
    {
        $cols = self::COLS;
        foreach ($cols as $k => $v) {
            $query = str_replace($k."|", "`$v`", $query);
        }
        return $query;
    }

    public function generateViewList(array $user_array, $includeVids = FALSE)
    {
        $string = "";
        $vm = new Video();
        foreach ($user_array as $user) {
            $user = PT_UserData($user['id']);
            if ($includeVids !== FALSE) {
                $vm->setBinary_conditions(array("user_id" => $user->id, "privacy" => 0, "approved" => 1, 'is_channel' => 1));
                $vm->setCountLimit($includeVids);
                $vm->setOrder(array("id" => 'DESC'));
                $vids_string = $vm->generateViewList($vm->getMatches());
                $string .= \PT_LoadPage('lists/user-vids-list', [
                    'ID' => $user->id,
                    'USER_DATA' => $user,
                    'VIDEO_LIST' => $vids_string
                ]);
            } else {
                $string .= \PT_LoadPage("search/user-list", [
                    'ID' => $user->id,
                    'USER_DATA' => $user
                ]);
            }
        }
        return $string;
    }

    public function modifyAdminRights($user_id, int $val) {
        $this->model->setOne([self::ACCESS_RIGHTS=>$val], ['id'=>$user_id]);
    }

    public function getBalance(int $uid) {
        return $this->returnData($this->model->getOne(['id'=>$uid], ['balance']), self::RETURN_RAW)['balance'];
    }

    public function setBalance(int $uid, string $val) {
        $this->model->setOne(['balance'=>$val], ['id'=>$uid]);
    }
}