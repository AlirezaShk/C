<?php
if (IS_LOGGED == false || $pt->config->sell_videos_system == 'off' ) {
    header("Location: " . PT_Link('login'));
    exit();
}
$langs_ = new Lang();
$cats_ = $langs_->getAllCats();
$CAM = new CatAffiliation();
$likedCats = $CAM->getOne($user->id, CatAffiliation::LIKE);
$dislikedCats = $CAM->getOne($user->id, CatAffiliation::DISLIKE);

$likeButton = '<span class="like-pick-cats" onclick="chooseLikeCat(this, 1)">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up">
    <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
</svg>
</span>';
$dislikeButton = '<span class="dislike-pick-cats" onclick="chooseLikeCat(this, 0)">
<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down">
    <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
</svg>
</span>';
$resetButton = '<span class="reset-pick-cats" onclick="chooseLikeCat(this, 2)">
<svg width="18px" height="18px" viewBox="0 0 12 12" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
    <g id="Web" stroke="currentColor" stroke-width="1" fill="none" fill-rule="evenodd">
        <g id="Desktop-HD-Copy" transform="translate(-654.000000, -40.000000)">
            <g id="ic_close_24px" transform="translate(650.000000, 36.000000)">
                <g id="Group">
                    <polygon id="Shape" points="0 0 20 0 20 20 0 20"></polygon>
                    <polygon id="Shape" fill="#FFFFFF" points="15.8333333 5.34166667 14.6583333 4.16666667 10 8.825 5.34166667 4.16666667 4.16666667 5.34166667 8.825 10 4.16666667 14.6583333 5.34166667 15.8333333 10 11.175 14.6583333 15.8333333 15.8333333 14.6583333 11.175 10"></polygon>
                </g>
            </g>
        </g>
    </g>
</svg>
</span>';
$pt->page_url_ = $pt->config->site_url;
$htmlArray = array("", "", ""); //Liked, disliked, rest
$catArray = array($likedCats, $dislikedCats, $cats_); //Liked, disliked, rest
//print_r($catArray);
$className = array(" liked", " disliked", "");
for($i = 0; $i < 3; $i++) {
    foreach ($catArray[$i] as $k => $cat) {
        if(!$cat) {
            $htmlArray[$i] = "";
            continue;
        }
        if ($i < 2) {
            for ($j = 0; $j < count($catArray[2]); $j++) {
                if($cat == $catArray[2][$j]['id']) {
                    unset($catArray[2][$j]);
                    break;
                }
            }
            $cat = $langs_->getOneById($cat);
        }
        $htmlArray[$i] .= '<div class="pick-cats'.$className[$i].'" id="'. $cat['id'] .'">
<span class="title">' . $cat[$_SESSION["lang"]] . '</span>';
        if ($i === 0) {
            $htmlArray[$i] .= $resetButton . $dislikeButton;
        } else if ($i === 1) {
            $htmlArray[$i] .= $likeButton . $resetButton;
        } else {
            $htmlArray[$i] .= $likeButton . $dislikeButton;
        }
        $htmlArray[$i] .= '</div>';
    }
}
$pt->page        = 'pick-category';
$pt->title       = $pt->config->title;
$pt->description = $pt->config->description;
$pt->keyword     = @$pt->config->keyword;
$pro_users       = array();
$pro_system      = ($pt->config->go_pro == 'on');
$pt->content     = PT_LoadPage('pick-category/content', array(
    'LIKED' => $htmlArray[0],
    'DISLIKED' => $htmlArray[1],
    'REST' => $htmlArray[2]
));