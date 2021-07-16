<?php


class Video extends \lib\Controller
{
    public $binary_conditions = array();
    public $like_conditions = array();
    public $orderBy = array();
    public $countLimit = NULL;
    public $reset = TRUE;

    const COLS = array("r" => "rating", "desc" => "description", "g" => "categories", "t" => "title", "ac" => "stars", "aw" => "awards", "dura" => "duration");
    const PAGE_NAME = "video-sharing";
    const RELATION_ACCURACY = array(0.25, 0.55, 0.85);
    public function __construct()
    {
        parent::__construct();
    }

    public function getAll(int $returnType = self::RETURN_ARRAY)
    {
        $result = $this->model->getAll();
        if($result) {
            return $this->returnData($result, $returnType);
        }
        return FALSE;
    }

    public function catHasVid($catId)
    {
        $catId = intval($catId);
        if($catId === 0) {
            return FALSE;
        }
        return ( ( count($this->model->getAllByCat($catId)) === 0 )?( FALSE ):( TRUE ) );
    }

    /**
     * This Function checks not only the category which the id is given, it'll also check the children of it.
     * @param $catId
     * @return bool: FALSE if no video is found and TRUE o.w.
     */
    public function catBranchHasVid($catId) : bool
    {
        $catId = intval($catId);
        if($catId === 0) {
            return FALSE;
        }
        $status = ( ( count($this->model->getAllByCat($catId)) === 0 )?( FALSE ):( TRUE ) );
        if ($status) return $status;
        $lm = new \Lang();
        $subCats = $lm->getSubCats($catId);
        foreach ($subCats as $subCat) {
            $status = ( ( count($this->model->getAllByCat($subCat['id'])) === 0 )?( FALSE ):( TRUE ) );
            if ($status) return $status;
        }
        return ( ( count($this->model->getAllByCat($catId)) === 0 )?( FALSE ):( TRUE ) );
    }

    public function getAllForCatsMigrationFromLangKeyToId()
    {
        return $this->returnData($this->model->getAllForCatsMigrationFromLangKeyToId(), self::RETURN_OBJECT);
    }

    public function setOne($data, $cond, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->setOne($data, $cond),
            $returnType, FALSE);
    }

    public function setReset(bool $value)
    {
        $this->reset = $value;
    }

    /**
     * @return int | NULL
     */
    public function getCountLimit()
    {
        return $this->countLimit;
    }

    /**
     * @return array|bool
     */
    public function getBinary_conditions(): array
    {
        return $this->binary_conditions;
    }

    /**
     * @return array|bool
     */
    public function getLike_conditions(): array
    {
        return $this->like_conditions;
    }

    /**
     * @return array|bool
     */
    public function getOrder(): array
    {
        return $this->orderBy;
    }

    /**
     * @param int $L
     * @return bool: Success => TRUE | Failure => FALSE
     */
    public function setCountLimit($L): bool
    {
        if ($L > 0 AND is_int($L)) {
            $this->countLimit = $L;
            return TRUE;
        }
        return FALSE;
    }

    /**
     * @param array $cond
     * @return bool: Success => TRUE | Failure => FALSE
     */
    public function setBinary_conditions(array $cond): bool
    {
        $cols = $this->model->getCols();
        foreach ($cond as $col => $val) {
            if (array_search($col, $cols) === FALSE) {
                return FALSE;
            }
        }
        foreach ($cond as $col => $val) {
            $this->binary_conditions[$col] = $val;
        }
        return TRUE;
    }

    /**
     * @param array $cond
     * @return bool: Success => TRUE | Failure => FALSE
     */
    public function setLike_conditions(array $cond): bool
    {
        $cols = $this->model->getCols();
        foreach ($cond as $col => $val) {
            if (array_search($col, $cols) === FALSE) {
                return FALSE;
            }
        }
        foreach ($cond as $col => $val) {
            $this->like_conditions[$col] = $val;
        }
        return TRUE;
    }

    /**
     * @param array $order
     * @return bool: Success => TRUE | Failure => FALSE
     */
    public function setOrder(array $order): bool
    {
        $cols = $this->model->getCols();
        foreach ($order as $col => $val) {
            if (array_search($col, $cols) === FALSE) {
                return FALSE;
            }
            if ((strtoupper($val) !== "DESC") AND (strtoupper($val) !== "ASC")) {
                return FALSE;
            }
        }
        foreach ($order as $col => $val) {
            $this->orderBy[$col] = strtoupper($val);
        }
        return TRUE;
    }

    public function resetCountLimit()
    {
        $this->countLimit = NULL;
    }

    public function resetBinary_conditions()
    {
        $this->binary_conditions = array();
    }

    public function resetLike_conditions()
    {
        $this->binary_conditions = array();
    }

    public function resetOrder()
    {
        $this->orderBy = array();
    }

    public function getMatches(int $returnType = self::RETURN_ARRAY, $kill_reset = FALSE)
    {
        $binary_conds = $this->getBinary_conditions();
        $like_conds = $this->getLike_conditions();
        $orders = $this->getOrder();
        $count = $this->getCountLimit();
        if ( (count($binary_conds) === 0) AND (count($like_conds) === 0) ) return FALSE;
        $res = $this->model->getOne($binary_conds, NULL, $orders, $count, $like_conds);
        if ($this->reset AND !$kill_reset) {
            $this->resetBinary_conditions();
            $this->resetLike_conditions();
            $this->resetOrder();
            $this->resetCountLimit();
        }
        if ($res !== FALSE) return $this->returnData($res, $returnType, FALSE);
        return $res;
    }

    public function runSearchQuery($query, $user_kw = "", $returnType = self::RETURN_ARRAY)
    {
        $cols = self::COLS;
        $uid = "";
        foreach ($cols as $k => $v) {
            $query = str_replace($k."|", "`$v`", $query);
        }
        if (strlen($user_kw) > 0) {
            $userManager = new \User();
            $uid = $userManager->getOne($user_kw, \User::BY_KEYWORD);
            $uid = "`user_id` = ".$uid['id'];
        }
        return $this->returnData($this->model->rawQuery($query, $uid) , $returnType);
    }

    public function generateViewList(array $vid_array)
    {
        $string = "";
        foreach ($vid_array as $vid) {
            $videoItem = PT_GetVideoByID(intval($vid['id']), 0, 0, 2);
            $string .= \PT_LoadPage(self::PAGE_NAME . "/list", [
                'ID' => $videoItem->id,
                'TITLE' => $videoItem->title,
                'VIEWS' => $videoItem->views,
                'VIEWS_NUM' => number_format($videoItem->views),
                'USER_DATA' => $videoItem->owner,
                'THUMBNAIL' => $videoItem->thumbnail,
                'URL' => $videoItem->url,
                'TIME' => $videoItem->time_ago,
                'DURATION' => $videoItem->duration,
                'VIDEO_ID' => $videoItem->video_id_,
                'VIDEO_ID_' => PT_Slug($videoItem->title, $videoItem->video_id)
            ]);
        }
        return $string;
    }

//    TODO: ADD a WEIGHT function instead of just boolean arguments.
    public function getRelatedVideos($vidId,
                                     bool $titleSearch = TRUE, bool $descSearch = FALSE, bool $catSearch = FALSE,
                                     bool $tagSearch = FALSE, bool $userSearch = FALSE, bool $returnIDOnly = TRUE,
                                     bool $returnSelf = FALSE, int $returnType = self::RETURN_ARRAY)
    {
        $this->setBinary_conditions(array("id"=>$vidId));
        $video = $this->getMatches();
        $preProcessRelative = array();
        $relative = array(array(), array(), array()); // 2 => Close Relation, 0 => Far Relation
        $videoList = $this->getAll();
        $condString = array($titleSearch, $descSearch, $catSearch, $tagSearch, $userSearch);
        $cnt = 0;
        for($i = 0; $i < 5; $i++) {
            //Should we search for anything?
            if ($condString[$i]) {
                $target = NULL;
                $cnt++;
                //Set parameters to find the target of search
                switch($i) {
                    case 0:
                        list($dl, $col) = [" ", 'title'];
                        break;
                    case 1:
                        list($dl, $col) = [" ", 'description'];
                        break;
                    case 2:
                        list($dl, $col) = ["|", 'categories'];
                        break;
                    case 3:
                        list($dl, $col) = ["|", 'tags'];
                        break;
                    case 4:
                        list($dl, $col) = ["", 'user_id'];
                        $target = array($video[$col]);
                        break;
                }
                if (is_null($target))
                    $target = explode($dl, $video[$col]);
                //Check to see if there's anything to search for
                if ((count($target) > 0) AND (strlen($target[0]) > 0)) {
                    //Base points given to each reltion is based on the number of targets to search for
                    switch(count($target)) {
                        case 1:
                            $basePoint = 3;
                            break;
                        case 2:
                            $basePoint = 2;
                            break;
                        default:
                            $basePoint = 1;
                    }
                    //Loop through each video
                    foreach ($videoList as $vid) {
                        if (!$returnSelf)
                            if (intval($vid['id']) === $vidId)
                                continue;
                        $point = -1;
                        $cnt_ = 0;
                        //Loop through each target to find related properties
                        foreach ($target as $trgt) {
                            if(strlen($trgt) === 0) continue;
                            /* In order to avoid cases such as:
                             * for e.g.: searching Tags:
                             * target = 'act'
                             * so we need to search for 'act,' in the 'tags' column of each vid
                             * BUT
                             * the haystack could contain something like: 'pact,'
                             * so we instead search for ',act,'
                             * and add a ',' at the beginning
                             * */
                            $vid[$col] = $dl . $vid[$col] . $dl;
                            if ((strpos($vid[$col], $dl.$trgt.$dl) !== FALSE)) {
                                $cnt_++;
                                //Set the point from 1-3 by judging the counter of successful relations
                                switch ($point) {
                                    case 3: break;
                                    case -1:
                                        if ($cnt_ >= (self::RELATION_ACCURACY[$basePoint-1])*(count($target)))
                                            $point = $basePoint;
                                        break;
                                    case 1:
                                        if ($cnt_ >= (self::RELATION_ACCURACY[1])*(count($target)))
                                            $point = 2;
                                        break;
                                    case 2:
                                        if ($cnt_ >= (self::RELATION_ACCURACY[2])*(count($target)))
                                            $point = 3;
                                        break;
                                }
                            }
                        }
                        //Has there been any success?
                        if ($point != -1) {
                            //Then create or increase the points of such vid[id] in the preProcessRelative array
                            if (isset($preProcessRelative[intval($vid['id'])])) {
                                $preProcessRelative[intval($vid['id'])][0] += $point;
                            } else $preProcessRelative[intval($vid['id'])] =
                                array($point, (($returnIDOnly) ? (intval($vid['id'])) : ($vid)));
                        }
                    }
                }
            }
        }
        //We should first normalize the points
        //Form an array and categorizing IDs into different points groups
        $points_array = array();
        if ($this->debugMode)
            print_r($preProcessRelative);
        foreach ($preProcessRelative as $id => $related) {
            if (isset($points_array[$related[0]])) $points_array[$related[0]][] = $id;
            else $points_array[$related[0]] = array($id);
        }
        //Find the max where we should normalize them based on it
        $max = $cnt*3;
        for ($i = ($cnt*3); $i >= 0; $i--) {
            $max = $i;
            if (isset($points_array[$i])) {
                break;
            }
        }
        if ($this->debugMode)
            print_r($points_array);
        //Now we'll begin normalizing the points
        foreach ($points_array as $point => $id_array) {
            foreach ($id_array as $id) {
                $preProcessRelative[$id][0] = (($cnt*3)/$max) * $preProcessRelative[$id][0];
            }
        }
        //Now we should calculate and categorize each video by the amount of points it has scored during our process
        if ($this->debugMode)
            print_r($preProcessRelative);
        foreach ($preProcessRelative as $id => $related) {
            $points = $related[0];
            $val = $related[1];
            if ($points >= (self::RELATION_ACCURACY[2])*($cnt * 3)) {
                $relative[2][] = $val;
            } elseif ($points >= (self::RELATION_ACCURACY[1])*($cnt * 3)) {
                $relative[1][] = $val;
            } elseif ($points >= (self::RELATION_ACCURACY[0])*($cnt * 3)) {
                $relative[0][] = $val;
            }
        }
        if ($this->debugMode)
            print_r($relative);
        return $this->returnData($relative, $returnType, FALSE);
    }

    public function updateSuggestedVideos($user_id,$video_id)
    {
        $video_id = strval($video_id);
        $videos = $this->model->getSuggestedVideos($user_id);
//        if ($videos == -2){
//            return false;
//        }
        if ($videos == -1){
            $this->model->insertUserSuggestedVideosRow($user_id);
        }
        $videosObj =  json_decode($videos,true);
        $new = 1;

        foreach($videosObj as $key => $value) {
            if ($key == $video_id){
                //add point
                $this->model->addPointToSuggestedVideos($user_id,$video_id,$value);
                $new = 0;
            }
        }
        if ($new){
            return $this->model->InsertVideoToSuggestedVideos($user_id,$video_id);
        }
    }

    public function removeSuggestedVideos($user_id,$video_id)
    {
//        print_r($video_id);
        $video_id = strval($video_id);
        $this->model->deleteVideoFromSuggestedVideos($user_id,$video_id);
    }

    public function formatTags($str)
    {
        $str = str_replace('#', ',', trim($str));
        $str = str_replace(' ', '_', $str);
        $str = str_replace('-', '_', $str);
        $tags = explode(',', $str);
        foreach($tags as $k => $tag) if(strlen($tag) === 0) unset($tags[$k]);
        $tags = "|" . implode('|', $tags) . "|";
        return $tags;
    }

    public function formatCats($str, $isChannelVid)
    {
        $category_id = 0;
        $categories = "";
        $cats = str_replace(":"," --",$str);
        $cats = explode(",", $cats);
        foreach ($cats as $cat) {
            if (intval($isChannelVid) == 0) {
                $cats_ = new Lang();
                $allCats = $cats_->getAllCats();
                $cat = PT_Secure($cat);
                $cat_id = NULL;
                foreach ($allCats as $cat__) {
                    if ($cat === $cat__[$_SESSION['lang']]) {
                        $cat_id = $cat__['id'];
                        break;
                    }
                }
                if (!is_null($cat_id)) {
                    $cats_ = $cats_->getOneById($cat_id, Lang::RETURN_OBJECT);
                    $category_id = $cats_->id;
                    $categories .= $cats_->id . "|";
                }
            } else {
                $disassemble = explode(" -- ", $cat);
                $key_ = $disassemble[0];
                $val_ = $disassemble[1];
                if (count($disassemble)) {
                    $category_id = $key_;
                    $categories .= $key_ . "|";
                }
            }
        }
        return [$category_id, "|" . $categories];
    }

    public function modifyPaidList($vid, $uid, $transaction_id, bool $action) {
        $this->setBinary_conditions(['id'=>$vid]);
        $video = $this->getMatches();
        $paid_list = json_decode($video['paid_for']);
        $target = key_exists($uid, (array) $paid_list);
        if ($target && !$action) {
            /* EXISTS && DELETE */
            unset($paid_list[$uid]);
            $this->model->setOne(['paid_for'=>json_encode($paid_list)], ['id'=>$vid]);
            return TRUE;
        } elseif (!$target && $action) {
            /* DOESN'T EXIST && ADD */
            $paid_list->$uid = $transaction_id;
            $this->model->setOne(['paid_for'=>json_encode($paid_list)], ['id'=>$vid]);
            return TRUE;
        } else return FALSE;
    }

    public function fixAllDur()
    {
        $getID3 = new getID3;
        $this->setBinary_conditions(array("duration"=>"00:00:00"));
        $vids = $this->getMatches();
        foreach ($vids as $vid) {
            if ($vid['240p'] == 1) $res = "240p";
            elseif ($vid['360p'] == 1) $res = "360p";
            elseif ($vid['480p'] == 1) $res = "480p";
            elseif ($vid['720p'] == 1) $res = "720p";
            elseif ($vid['1080p'] == 1) $res = "1080p";
            else continue;

            $file = $getID3->analyze(substr($vid['video_location'], 0, -4) . "_$res" . "_converted.mp4");
            $s = intval($file['playtime_seconds']);
            $m = intval($s/60);
            $h = intval($m/60);
            $s = $s % 60;
            $m = $m % 60;
            if ($s < 10) $s = "0" . $s;
            if ($m < 10) $m = "0" . $m;
            if ($h < 10) $h = "0" . $h;
            $dur = "$h:$m:$s";
            $cond = array("id"=>$vid['id']);
            $data = array("duration"=>$dur);
            $this->setOne($data, $cond);
        }
    }
}