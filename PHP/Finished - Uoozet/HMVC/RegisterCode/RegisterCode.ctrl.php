<?php


class RegisterCode extends \lib\Controller
{
    const TYPE_PERSONAL = 0;
    const TYPE_COMMERCIAL = 1;

    const BY_ID = 0;
    const BY_CODE = 1;
    const BY_USER_ID = 2;

    const DEFAULT_POINTS = 1;
    const DEFAULT_CODE_LENGTH = 10;

    public function __construct()
    {
        parent::__construct();
    }

    public function refreshPersonalCode($user_id)
    {
        do {
            $code = $this->generateCode();
        } while( $this->isCodeDuplicate($code) );
        $update_data = $update_cond = array();
        $update_data['code'] = $code;
        $update_cond['user_id'] = $user_id;
        $status = ( $this->model->setOne($update_data, $update_cond) ) ? (TRUE) : (FALSE);
        if ($status) {
            $setUserCode = new User();
            $setUserCode->setRegisterCode($user_id, $code);
        }
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Updating was $content");
    }

    private function generateCode($len = self::DEFAULT_CODE_LENGTH) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $characters_len = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $len; $i++) {
            $randomString .= $characters[rand(0, $characters_len - 1)];
        }
        return $randomString;
    }
    /*
     *  returns:
     *  1: OK
     *  0: One of Data indexes is not set
     *  -1: Wrong Type
     *  -2: DateTime Format is wrong
     *
     */
    private function isDataArrayValid(array $data, int $type)
    {
        $r = 1;
        if ($type === self::TYPE_PERSONAL) {
            if (!isset($data['user_id']) OR (intval($data['user_id']) === 0)) {
                $r =  0;
            }
        } elseif ($type === self::TYPE_COMMERCIAL) {
            if (!isset($data['start_datetime']) OR !isset($data['expiry_datetime'])) {
                $r = 0;
            } else {
                if(
                    (preg_match("/([0-9]{4}-((0[0-9])|(1[0-2]))-(([0-2][0-9])|(3[0-1]))) (([0-1][0-9])|(2[0-4])):([0-5][0-9]):([0-5][0-9])/g",
                            $data['start_datetime']) == 0)
                    OR
                    (preg_match("/([0-9]{4}-((0[0-9])|(1[0-2]))-(([0-2][0-9])|(3[0-1]))) (([0-1][0-9])|(2[0-4])):([0-5][0-9]):([0-5][0-9])/g",
                            $data['expiry_datetime']) == 0)
                ) {
                    $r = -2;
                }
            }
        } else {
            $r = -1;
        }
        return $r;
    }

    /*
     * Currently 2 Types of code can be created:
     * 1- Personal:
     *      Every User should have their own unique personal code.
     *      Required Data Array indexes:
     *      'user_id'
     *      'points' => Default: 1
     * 2- Commercial:
     *      Used for other purposes defined by Admin Only.
     *      Required Data Array indexes:
     *      'points' => Default: 1
     *      'start_datetime'
     *      'expiry_datetime'
     */
    public function createOne(array $data, int $type)
    {
        $insert_data = array();
        $status = $this->isDataArrayValid($data, $type);
        switch ($status) {
            case 0:
                throw new Exception("Missing one or more required Data Array Indexes.");
            case -1:
                throw new Exception("Wrong type given.");
            case -2:
                throw new Exception("Wrong DateTime format.");
            default:
                break;
        }
        if ($type === self::TYPE_PERSONAL) {
            if ( $this->userHasCode($data['user_id']) ) {
                throw new Exception("User Already Has A Code.");
            }
            $insert_data['user_id'] = $data['user_id'];
            date_default_timezone_set('Asia/Tehran');
            $update_data['start_datetime'] = Date("Y-m-d H:i:s");
            $update_data['expiry_datetime'] = NULL;
        } else {
            $insert_data['start_datetime'] = $data['start_datetime'];
            $insert_data['expiry_datetime'] = $data['expiry_datetime'];
        }
        if (!isset($data['points']) || intval($data['points']) == 0) {
            $insert_data['points'] = self::DEFAULT_POINTS;
        } else {
            $insert_data['points'] = $data['points'];
        }
        $insert_data['type'] = $type;
        do {
            $code = $this->generateCode();
        } while( $this->isCodeDuplicate($code) );
        $insert_data['code'] = $code;
        $status = ( $this->model->addOne($insert_data) ) ? (TRUE) : (FALSE);
        if (($status) AND ($type === self::TYPE_PERSONAL)) {
            $setUserCode = new User();
            $setUserCode->setRegisterCode($data['user_id'], $code);
        }
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Adding rule was $content", "code" => $code);
    }

    private function userHasCode($uid) {
        $byUID = $this->model->getOnePersonalByUserId($uid);
        return ((($byUID === FALSE) || (count($byUID) === 0)) ? ( FALSE ) : ( TRUE ));
    }

    public function updateOne(array $data, array $cond)
    {
        $update_data = $data;
        $update_cond = array();
        if( !(isset($cond['id']) || isset($cond['user_id'])) ) {
            throw new Exception("Either Id (general) or User_id (personal codes) is required");
        }
        if (isset($cond['id'])) {
            $update_cond['id'] = $cond['id'];
        } elseif (isset($cond['user_id'])) {
            $update_cond['user_id'] = $cond['user_id'];
        } elseif (isset($cond['code'])) {
            $update_cond['code'] = $cond['code'];
        } else {
            throw new Exception("Condition Array Missing one required condition");
        }
        if(isset($update_data['code'])) {
            $_code = $update_data['code'];
            while( $this->isCodeDuplicate($_code) ) {
                $_code = $this->generateCode();
            }
            $update_data['code'] = $_code;
        }
        $status = ( $this->model->setOne($update_data, $update_cond) ) ? (TRUE) : (FALSE);
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Updating was $content");
    }

    public function getOne($param, int $by, $returnType = self::RETURN_ARRAY)
    {
        switch($by){
            case self::BY_ID:
                return $this->returnData($this->model->getOne(array("id"=>$param)), $returnType);
            case self::BY_CODE:
                return $this->returnData($this->model->getOne(array("code"=>$param)), $returnType);
            case self::BY_USER_ID:
                return $this->returnData($this->model->getOne(array("user_id"=>$param)), $returnType);
            default:
                throw new Exception("Forbidden Getter Method.");
        }
    }

    public function isCodeDuplicate(string $code)
    {
        $res = $this->getOne($code, self::BY_CODE);
        return (($res === FALSE) || (count($res) === 0)) ? (FALSE) : (TRUE);
    }

    public function isCodeValid($param, $by = self::BY_CODE)
    {
        $res = $this->getOne($param, $by);
        return (($res['expiry_datetime'] > Date("Y-m-d H:i:s")) || ($res['expiry_datetime'] === NULL)) ? (TRUE) : (FALSE);
    }

    public function getAll(){
        return $this->model->getAll();
    }
}