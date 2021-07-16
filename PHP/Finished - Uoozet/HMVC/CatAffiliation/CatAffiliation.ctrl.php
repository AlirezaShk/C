<?php


class CatAffiliation extends \lib\Controller
{
    const LIKE = 1;
    const DISLIKE = 0;
    const ALL = -1;

    public function __construct()
    {
        parent::__construct();
    }

    public function addLike(int $user_id, $keys, $type)
    {
        if(!is_array($keys)) {
            if(intval($keys) === 0) {
                return FALSE;
            } else {
                if(count(explode("|", $keys)) > 1) {
                    $keys = explode("|", $keys);
                } else {
                    $keys = array($keys);
                }
            }
        }

        if($type !== self::LIKE) {
            $type = self::DISLIKE;
        }
        $userExists = $this->getOne($user_id, self::ALL);
        $col = ( $type === self::LIKE ) ? ("likes") : ("dislikes");
        if(!$userExists) {
            //insert a new row
            $insert_data = array();
            $insert_data['user_id'] = $user_id;
            $keysString = implode("|", $keys);
            $insert_data[$col] = $keysString;
            $status = ( $this->model->addOne($insert_data) ) ? (TRUE) : (FALSE);
        } else {
            //update an existing one
            $currentRules = $userExists[$col];
            $otherRules = $userExists[( $type !== self::LIKE ) ? ("likes") : ("dislikes")];
            $validKeys = array();
            for($i = 0; $i < count($keys); $i++)
            {
                if(array_search($keys[$i], $currentRules) === FALSE) {
                    $validKeys[] = $keys[$i];
                    //Delete past records if in the other category:
                    $currentStatus = $this->userLikes($user_id, $keys[$i]);
                    if (($currentStatus !== $type) && ($currentStatus !== FALSE)) {
                        $this->delLike($user_id, $keys[$i], (($type !== self::DISLIKE)?(self::DISLIKE):(self::LIKE)));
                    }
                }
            }
            $keysString = "";
            if (count($validKeys) !== 0) {
                if (count($currentRules) !== 0 && strlen($currentRules[0]) > 0) {
                    $keysString = "|";
                }
                $keysString .= implode("|", $validKeys);
            }
            $updateString = implode("|", $currentRules) . $keysString;
            $update_data = array($col => $updateString);
            $update_cond = array('user_id' => $user_id);
            $status = ( $this->model->setOne($update_data, $update_cond) ) ? (TRUE) : (FALSE);
        }
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Adding rule was $content");
    }

    public function delLike(int $user_id, $keys, $type)
    {
        if(!is_array($keys)) {
            if(intval($keys) === 0) {
                return FALSE;
            } else {
                if(count(explode("|", $keys)) > 1) {
                    $keys = explode("|", $keys);
                } else {
                    $keys = array($keys);
                }
            }
        }
        if($type !== self::LIKE) {
            $type = self::DISLIKE;
        }
        $userExists = $this->getOne($user_id, self::ALL);
        $col = ( $type === self::LIKE ) ? ("likes") : ("dislikes");
        if(!$userExists) {
            $status = FALSE;
        } else {
            //update an existing one
            $currentRules = $userExists[$col];
            $invalidKeys = array();
            for($i = 0; $i < count($keys); $i++)
            {
                if(array_search($keys[$i], $currentRules) !== FALSE) {
                    $invalidKeys[] = $keys[$i];
                    unset($currentRules[array_search($keys[$i], $currentRules)]);
                }
            }
            if(count($invalidKeys) !== 0) {
                $keysString = implode("|", $currentRules);
            } else {
                $keysString = "";
            }
            $updateString = $keysString;
            $update_data = array($col => $updateString);
            $update_cond = array('user_id' => $user_id);
            $updateRule = $this->model->setOne($update_data, $update_cond);
            $status = ( $updateRule ) ? (TRUE) : (FALSE);
        }
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Adding rule was $content");
    }

    //Deletes a user from table
    public function resetUser(int $user_id, int $returnType = self::RETURN_ARRAY)
    {
        $delete_data = array("user_id" => $user_id);
        $status = $this->model->delOne($delete_data);
        $content = ( $status ) ? ("successful") : ("unsuccessful");
        return array("status" => $status, "content" => "Adding rule was $content");
    }

    //Returns: 0 => Dislikes, 1 => Likes, FALSE => neither
    public function userLikes(int $user_id, string $catLangKey)
    {
        $all = $this->getOne($user_id, self::ALL, self::RETURN_ARRAY);
        $likes = $all['likes'];
        if(in_array($catLangKey, $likes) !== FALSE) {
            return self::LIKE;
        } else {
            $dislikes = $all['dislikes'];
            if(in_array($catLangKey, $dislikes) !== FALSE) {
                return self::DISLIKE;
            } else {
                return FALSE;
            }
        }
    }

    public function getUserValidVidCats(int $user_id, int $returnType = self::RETURN_ARRAY)
    {
        $dislikes = $this->getOne($user_id, self::DISLIKE);
        $cats = new Lang();
        $cats = $cats->getAllCats();
        foreach($cats as $k => $cat) {
            if(array_search($cat['id'], $dislikes) !== FALSE) {
                unset($cats[$k]);
            }
        }
        return $this->returnData($cats, $returnType, FALSE);
    }
    public function getOne(int $user_id, int $type = self::ALL, int $returnType = self::RETURN_ARRAY)
    {
        $result = $this->model->getOne(array('user_id'=>$user_id));
        if($result) {
            $result = $this->getter($result[0], $type, $returnType);
            return $this->returnData($result, $returnType, FALSE);
        }
        return FALSE;
    }

    public function getAll(int $user_id, int $type = self::ALL, int $returnType = self::RETURN_ARRAY)
    {
        $result = $this->model->getAll(array("user_id"=>$user_id));
        if($result) {
            $result = $this->getter($result, $type, $returnType);
            return $this->returnData($result, $returnType, FALSE);
        }
        return FALSE;
    }

    private function getter($array, int $type = self::ALL, int $returnType = self::RETURN_ARRAY)
    {
        $likes = explode("|", $array['likes']);
        $dislikes = explode("|", $array['dislikes']);
        $array = array();
        $array['likes'] = $likes;
        $array['dislikes'] = $dislikes;
        if($type === self::LIKE) {
            $array = $likes;
        } elseif($type === self::DISLIKE) {
            $array = $dislikes;
        }
        return $array;
    }
}