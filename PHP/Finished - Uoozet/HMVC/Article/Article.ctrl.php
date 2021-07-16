<?php


class Article extends Video
{
    const PAGE_NAME = "articles";

    public function __construct()
    {
        parent::__construct();
    }

    public function getOne(int $id, int $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("id"=>$id)), $returnType);
    }

    public function getAll(int $count = NULL, int $returnType = self::RETURN_ARRAY)
    {
        $all = $this->model->getAll();
        if (is_null($count)) return $this->returnData($all, $returnType);
        else {
            $res = array();
            for ($i = 0; $i < $count; $i++) {
                $res[] = $all[count($all) - $i - 1];
            }
            return $this->returnData($res, $returnType);
        }
    }

    public function createOne()
    {
        $data = array();
        return $this->model->addOne($data);
    }

    public function generateViewList(array $article_array)
    {
        $string = "";
        foreach ($article_array as $article) {
            $post = $this->getOne((
            (is_int($article)) ? ($article) : ($article['id'])
            ), self::RETURN_OBJECT);
            $string .= PT_LoadPage('lists/article-list', array(
                'ID' => $post->id,
                'TITLE' => $post->title,
                'DESC'  => PT_ShortText($post->description,190),
                'VIEWS_NUM' => number_format($post->views),
                'THUMBNAIL' => PT_GetMedia($post->image),
                'CAT' => ($post->category),
                'URL' => PT_Link('articles/read/' . PT_URLSlug($post->title,$post->id)),
                'TIME' => date('d-F-Y',$post->time),
                'ARTICLE_URL' => PT_URLSlug($post->title,$post->id)
            ));
        }
        return $string;
    }

    public function getRelatedVideos($aId,
                                     bool $titleSearch = TRUE, bool $descSearch = FALSE, bool $catSearch = FALSE,
                                     bool $tagSearch = FALSE, bool $userSearch = FALSE, bool $returnIDOnly = TRUE,
                                     bool $returnSelf = FALSE, int $returnType = self::RETURN_ARRAY)
    {
        $article = $this->getOne($aId);
        $preProcessRelative = array();
        $relative = array(array(), array(), array()); // 2 => Close Relation, 0 => Far Relation
        $vm = new Video();
        $videoList = $vm->getAll();
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
                        $target = array($article[$col]);
                        break;
                }
                if (is_null($target))
                    $target = explode($dl, $article[$col]);
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

    public function getRelatedArticles($aId,
                                       bool $titleSearch = TRUE, bool $descSearch = FALSE, bool $catSearch = FALSE,
                                       bool $tagSearch = FALSE, bool $userSearch = FALSE, bool $returnIDOnly = TRUE,
                                       bool $returnSelf = FALSE, int $returnType = self::RETURN_ARRAY)
    {
        $article = $this->getOne($aId);
        $preProcessRelative = array();
        $relative = array(array(), array(), array()); // 2 => Close Relation, 0 => Far Relation
        $videoList = $this->getAll(); //articleList
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
                        $target = array($article[$col]);
                        break;
                }
                if (is_null($target))
                    $target = explode($dl, $article[$col]);
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
                            if (intval($vid['id']) === $aId)
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
}