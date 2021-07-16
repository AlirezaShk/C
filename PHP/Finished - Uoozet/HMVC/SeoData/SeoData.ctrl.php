<?php


class SeoData extends \lib\Controller
{
    const ADMIN_PAGE_NAME = "manage-seo-data";

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param string $url
     * @param string $pageName : page name to which the description should be added
     * @param int $cid : content id
     * @param string $desc : the new meta description
     * @param array|null $canonical
     * @param bool $overWrite
     * @return bool: success => TRUE | failure => FALSE
     * @throws Exception
     */
    public function addOne(string $url, string $pageName, $cid = NULL, $desc = NULL, array $canonical = NULL, bool $overWrite = FALSE): bool
    {
        if ($cid !== NULL) $cid = intval($cid);
        $cond = array("url"=>$url, "page"=>$pageName, "content_id"=>$cid);
        $exists = $this->model->getOne($cond);
        if (is_array($exists) AND (count($exists) > 0)) {
            if ($overWrite) $this->model->delOne($cond);
            else {
                if($this->debugMode) throw new Exception("Record already exists");
                else return FALSE;
            };
        }
        $data = array("url" => $url, "page" => $pageName, "content_id" => $cid, "desc" => $desc,
            "canonical" => (!is_null($canonical) ? json_encode($canonical) : NULL)
        );
        return ((bool) $this->model->addOne($data));
    }

    /**
     * @param string|null $url
     * @param string $page
     * @param int $cid
     * @param int $returnType
     * @return string | array:
     * returns the description as a string if it is set to RETURN_RAW;
     * O.W. it returns according to given type.
     * @throws Exception
     */
    public function getOne(string $url = NULL, string $page = NULL, $cid = NULL, int $returnType = self::RETURN_ARRAY)
    {
        if ($cid !== NULL) $cid = intval($cid);
        if (!is_null($url)) $cond = array("url"=>$url);
        elseif (!is_null($page)) $cond = array("page"=>$page, "content_id"=>$cid);
        else {
            if ($this->debugMode) throw new Exception("Either `page` or `url` should be set.");
            else return FALSE;
        }
        $r = $this->model->getOne($cond);
        if (!is_array($r) OR count($r) == 0) return $this->returnData([], $returnType);
        return $this->returnData($r, $returnType);
    }

    /**
     * @param int $id
     * @param int $returnType
     * @return string | array:
     * returns the description as a string if it is set to RETURN_RAW;
     * O.W. it returns according to given type.
     */
    public function getOneById(int $id, int $returnType = self::RETURN_ARRAY)
    {
        $cond = array("id"=>$id);
        $r = $this->model->getOne($cond)[0];
        return $this->returnData($r, $returnType);
    }

    /**
     * @param string|null $url
     * @param string $page
     * @param int $cid
     * @return mixed
     * @throws Exception
     */
    public function delOne(string $url = NULL, string $page = NULL, $cid = NULL)
    {
        if ($cid !== NULL) $cid = intval($cid);
        if (!is_null($url)) $cond = array("url"=>$url);
        elseif (!is_null($page)) $cond = array("page"=>$page, "content_id"=>$cid);
        else {
            if ($this->debugMode) throw new Exception("Either `page` or `url` should be set.");
            else return FALSE;
        }
        return $this->model->delOne($cond);
    }

    public function delOneById(int $id)
    {
        return $this->model->delOne(["id"=>$id]);
    }

    /**
     * @param string|null $url
     * @param string|null $page
     * @param null $cid
     * @param string $newDesc
     * @param array|null $newCanonical
     * @return mixed
     * @throws Exception
     */
    public function setOne(string $url = NULL, string $page = NULL, $cid = NULL, string $newDesc = NULL, array $newCanonical = NULL)
    {
        if ($cid !== NULL) $cid = intval($cid);
        if (!is_null($url)) $cond = array("url"=>$url);
        elseif (!is_null($page)) $cond = array("page"=>$page, "content_id"=>$cid);
        else {
            if ($this->debugMode) throw new Exception("Either `page` or `url` should be set.");
            else return FALSE;
        }
        $data = array("desc"=>$newDesc, "canonical" => (!is_null($newCanonical) ? json_encode($newCanonical) : NULL));
        return $this->model->setOne($data, $cond);
    }

    public function setOneById(int $id, string $newDesc = NULL, array $newCanonical = NULL)
    {
        $cond = array("id"=>$id);
        $data = array("desc"=>$newDesc, "canonical"=>$newCanonical);
        return $this->model->setOne($data, $cond);
    }

    public function getAll(int $returnType = self::RETURN_RAW)
    {
        return $this->returnData($this->model->getAll(), $returnType, TRUE, FALSE);
    }

    public function extractPageInfoFromURL($url) {
        global $site_url;
        if(strpos($url, "watch") !== FALSE) {
            $video_id = explode("_", substr($url, 0, strlen($url) - 5))[1];
            $vm = new Video();
            $vm->setBinary_conditions(['video_id'=>$video_id]);
            $video = $vm->getMatches();
            return ['watch', $video['id']];
        } elseif (strpos($url, "articles") !== FALSE) {
            $aid = explode("_", substr($url, 0, strlen($url) - 5))[1];
            return ['articles/read', $aid];
        } elseif (strpos($url, '@') !== FALSE) {
            if (strpos($url, 'vid=') !== FALSE) {
                $cid = explode("&", explode("vid=", $url)[1])[0];
                $page = explode($site_url . "/", $url)[1];
                $page = explode("?", $page)[0];
                return [$page, $cid];
            } else {
                $page = explode($site_url . "/", $url)[1];
                $page = explode("?", $page)[0];
                return [$page, NULL];
            }
        } else {
            $site_url = ((substr($site_url, strlen($site_url) - 1) === "/") ? (substr($site_url, 0, strlen($site_url) - 1)) : ($site_url));
            $page = str_replace($site_url . "/", "", $url);
            return [$page, NULL];
        }
    }

    public function generateAdminViewList($sD_array){
        $string = "";
        foreach ($sD_array as $sData) {
            $string .= \PT_LoadAdminPage(self::ADMIN_PAGE_NAME . "/list", [
                'id' => $sData['id'],
                'url' => $sData['url'],
                'page' => $sData['page'],
                'content_id' => ($sData['content_id'] === NULL) ? ("NULL") : ($sData['content_id']),
                'desc'=>$sData['desc']
            ]);
        }
        return $string;
    }
}