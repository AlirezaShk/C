<?php


class Audio extends Video
{const COLS = array("r" => "rating", "y" => "movie_release", "g" => "categories", "t" => "title", "ac" => "stars", "aw" => "awards", "dura" => "duration");
    const COND_CLAUSE = array("یا"=>"OR", "or"=>"OR", "و"=>"AND", "and"=>"AND");

    const PAGE_NAME = "music-sharing";

    public function __construct()
    {
        parent::__construct();
    }

    public function getMatches(int $returnType = self::RETURN_ARRAY, $kill_reset = FALSE)
    {
        if (!isset($this->binary_conditions['is_movie'])) $this->setBinary_conditions(array("is_movie"=>[2, 3]));
        return parent::getMatches($returnType = self::RETURN_ARRAY, $kill_reset);
    }

    public function getRelatedMovies($vidId,
                                     bool $titleSearch = TRUE, bool $descSearch = FALSE, bool $catSearch = FALSE,
                                     bool $tagSearch = FALSE, bool $userSearch = FALSE, bool $returnIDOnly = TRUE,
                                     bool $returnSelf = FALSE, int $returnType = self::RETURN_ARRAY)
    {
        $this->setBinary_conditions(array("is_movie"=>[2, 3]));
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
                        list($dl, $col) = [",", 'tags'];
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

    public function runSearchQuery($query, $user_kw = "", $returnType = self::RETURN_ARRAY)
    {
        $query = "(" . $query . ") AND (`is_movie` = 2) OR (`is_movie` = 3)";
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

    public function generateViewList(array $music_array)
    {
        $string = "";
        foreach ($music_array as $music) {
            $videoItem = PT_GetVideoByID(intval($music['id']), 0, 0, 2);
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


    public function fixAllDur()
    {
        $getID3 = new getID3;
        $this->setBinary_conditions(array("duration"=>"00:00:00"));
        $audios = $this->getMatches();
        foreach ($audios as $audio) {
            $file = $getID3->analyze($audio['video_location']);
            $s = intval($file['playtime_seconds']);
            $m = intval($s/60);
            $h = intval($m/60);
            $s = $s % 60;
            $m = $m % 60;
            if ($s < 10) $s = "0" . $s;
            if ($m < 10) $m = "0" . $m;
            if ($h < 10) $h = "0" . $h;
            $dur = "$h:$m:$s";
            $cond = array("id"=>$audio['id']);
            $data = array("duration"=>$dur);
            $this->setOne($data, $cond);
        }
    }
}