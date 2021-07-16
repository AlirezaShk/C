<?php


class Lang extends \lib\Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOneById($id, $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("id"=>$id)), $returnType);
    }

    public function getOne($lang_key, $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getOne(array("lang_key" => $lang_key)), $returnType);
    }

    public function getAll($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAll(), $returnType);
    }

    //Video and TV Cats:
    public function getAllCats($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAllCats(), $returnType);
    }

    public function getAllSubCats($returnType = self::RETURN_ARRAY)
    {
        $sub_cats = array();
        $cats = $this->getAllCats();
        foreach($cats as $k => $cat) {
            $catId = intval($cat['id']);
            $data = $this->returnData($this->model->getAllSubCatsOf($catId), self::RETURN_ARRAY);
            if ((is_array($data)) AND (count($data) > 0)) $sub_cats[$catId] = $data;
        }
        return $this->returnData($sub_cats, $returnType,false);
    }

    public function getSubUCatsOf(int $user_id, $returnType = self::RETURN_ARRAY)
    {
        $sub_ucats = array();
        $ucats = $this->getAllUCatsOf($user_id);
        foreach($ucats as $k => $cat) {
            $catId = intval($cat['id']);
            $data = $this->returnData($this->model->getAllSubCatsOf($catId), self::RETURN_ARRAY);
            if ((is_array($data)) AND (count($data) > 0)) $sub_ucats[$catId] = $data;
        }
        return $this->returnData($sub_ucats, $returnType,false);
    }

    public function getAllSubUCats($returnType = self::RETURN_ARRAY)
    {
//        $sub_ucats = array();
//        $ucats = $this->getAllUCats();
//        foreach($ucats as $k => $cat) {
//            $catId = intval($cat['id']);
//            $data = $this->returnData($this->model->getAllSubCatsOf($catId), self::RETURN_ARRAY);
//            if ((is_array($data)) AND (count($data) > 0)) $sub_ucats[$catId] = $data;
//        }
//        return $this->returnData($sub_ucats, $returnType,false);
        $ucats = $this->model->getAllUCats();
        $r = array();
        $last = NULL;
        foreach ($ucats as $key => $cat) {
            if (($cat['type'] !== $last['type']) OR ($last === NULL)) {
                $uid = intval(explode("-", $cat['type'])[1]);
            }
            $r[$uid] = array();
            $data = $this->getSubUCatsOf($uid, self::RETURN_ARRAY);
            if ((is_array($data)) AND (count($data) > 0)) $r[$uid] = $data;
            else unset($r[$uid]);
            $last = $cat;
        }
        return $this->returnData($r, $returnType);
    }

    public function getSubCats(int $catId,$returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAllSubCatsOf($catId), $returnType);
    }

    public function getAllUCatsOf(int $user_id, $returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAllUCatsOf($user_id), $returnType);
    }

    public function getAllUCats($returnType = self::RETURN_ARRAY)
    {
        return $this->returnData($this->model->getAllUCats(), $returnType);
    }
    //--------------------
    public function getCurrencies($returnType = self::RETURN_ARRAY)
    {
        $cond = array("type"=>"currency");
        return $this->returnData($this->model->getOne($cond), $returnType, TRUE, FALSE);
    }
    //--------------------
    public function getTimePeriods($returnType = self::RETURN_ARRAY)
    {
        $cond = array("type"=>"time_period");
        return $this->returnData($this->model->getOne($cond), $returnType);
    }
    //--------------------
    //Movie Genres:
    public function getAllGenres($returnType = self::RETURN_ARRAY)
    {
        $cond = array("type"=>"movie_category");
        return $this->returnData($this->model->getOne($cond), $returnType);
    }
    //--------------------
    public function addOne($lang_key, array $langs_data, string $type)
    {
        if($this->exists(['lang_key'=>$lang_key])) {
            if ($this->debugMode) throw new Exception('Specified `lang_key` already exists');
            else return FALSE;
        }
        $langs = $this->getLangs();
        $data = array("lang_key" => $lang_key, "type" => $type);
        for($i = 0; $i < count($langs); $i++) {
            $data[$langs[$i]] = (isset($langs_data[$langs[$i]]) ? $langs_data[$langs[$i]] : $langs_data[$i]);
        }
        return $this->model->addOne($data);
    }

    public function setOne($lang_key, array $langs_data, string $type)
    {
        $langs = $this->getLangs();
        $data = array("type" => $type);
        for($i = 0; $i < count($langs); $i++) {
            $data[$langs[$i]] = isset($langs_data[$langs[$i]]) ? $langs_data[$langs[$i]] : $langs_data[$i];
        }
        $cond = array("lang_key"=>$lang_key);
        return $this->model->setOne($data, $cond);
    }

    public function getLangs($returnType = self::RETURN_ARRAY)
    {
        $results = array();
        $getCols = $this->model->getCols();
        for($i = 3; $i < count($getCols); $i++) {
            $results[] = $getCols[$i];
        }
        return $this->returnData($results, $returnType, FALSE);
    }

    public function deleteOne($langKey)
    {
        return $this->model->deleteOne($langKey);
    }

    public function getParentCat($subCatId, $returnType = self::RETURN_ARRAY)
    {
        $subcat = $this->getOneById($subCatId, self::RETURN_OBJECT);
        $result = $this->getOneById($subcat->type);
        if(count($result) > 0)
            return $this->returnData($result, $returnType);
        else
            return FALSE;
    }

    public function generateCatView($selfID, $txt, $url_pattern = '*',$parentID = NULL) {
        $url = str_replace('*', $selfID, $url_pattern);
        if (!is_null($parentID))
            return '<div class="card child" data-id="'.$selfID.'" data-parent-id="' . $parentID . '"><a class="home" href="' . $url . '">' . $txt . '</a></div>';
        else
            return '<div class="card parent" data-id="'.$selfID.'"><a class="home" href="'.$url.'">'.$txt.'</a></div>';
    }

    public function formatCatArrayIDtoLANG(array $cat, $lang)
    {
        $res = [];
        if(!key_exists(0, $cat)) return $cat;
        foreach($cat as $v) {
            $res[$v['id']] = $v[$lang];
        }
        return $res;
    }

    public function formatSubCatArrayIDtoLANG(array $subCat, $lang)
    {
        $res = [];
        foreach($subCat as $k => $v) {
            if(key_exists(0, $v)) {
                $res[$k] = [];
                foreach ($v as $v_)
                    $res[$k][$v_['id']] = $v_[$lang];
            } else {
                $res[$k] = $v;
            }
        }
        return $res;
    }

    public function generateCategoryPanel($cats, $subCats, string $urlPattern)
    {
        if(key_exists(0, $cats))
            throw new Exception('First format cat array using formatCatArrayIDtoLANG');
        $content = '
<span class="channel-cats_open-btn" onclick="openCHCats(this)">
	<span>'.$this->getOne('CHANNEL_CATS_PANEL')[$_SESSION['lang']].'</span>
	<svg width="15px" height="15px" fill="currentColor" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
		 viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve">
	<g>
		<g>
			<path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124
				c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844
				L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412
				c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008
				c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788
				C492,219.198,479.172,207.418,464.344,207.418z"/>
		</g>
	</g>
	</svg>
</span>
<div class="channel-cats topper-1 closed" data-content-before="'.$this->getOne('CHANNEL_CATS_PANEL')[$_SESSION['lang']].'">
	<span class="close-btn" onclick="closeCHCats(this)">
		<svg width="50%" height="50%" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
		  <g>
			<path fill="currentColor" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
		  </g>
		</svg>
	</span>';
        foreach ($cats as $cid => $txt):
            $content .= $this->generateCatView($cid, $txt, $urlPattern);
            if (isset($subCats[$cid]) AND (count($subCats[$cid]) > 0)):
                $content .= '<div class="sub_cats-grid inactive subc-'.$cid.'" style="height: '.((count($subCats[$cid])*36) + 15).'px">';
                $array_keys = [];
                foreach ($subCats[$cid] as $scid => $txt2) {
                    if(key_exists(0, $subCats[$cid]))
                        throw new Exception('First format subCat array using formatSubCatArrayIDtoLANG');
                    $array_keys[$txt2] = $scid;
                }
                sort($subCats[$cid]);
                foreach ($subCats[$cid] as $txt2) $content .= $this->generateCatView($array_keys[$txt2], $txt2, $urlPattern, $cid);
                $content .= '</div>';
            endif;
        endforeach;
        return $content . '</div>
<script>
	$(".channel-cats > .card.parent").each(function (){
		let full = this.outerHTML;
		let cid = $(this).data("id");
		let cpanel = $(this).parent().parent();
		if($(cpanel).find(".subc-"+cid).length){
		    let new_full = `<div class="cat-row">`+full+`<span class="dropdown-toggle" onclick=\'$(this).parent().parent().find(".subc-`+cid+`").toggleClass("inactive");\'></span></div>`;
		    $(this).replaceWith(new_full);
		}
	});
	function closeCHCats(elmnt) {
		$(elmnt).parent().addClass("closed");
	}
	function openCHCats(elmnt) {
		$(elmnt).parent().find(".channel-cats").removeClass("closed");
	}
</script>
';
    }
}