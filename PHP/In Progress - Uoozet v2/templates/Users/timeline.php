<div class="profile-container">
    <div class="card-container">
        <div class="cover-container">

            {{PLAY_BOX}}

        </div>
        <?php if ($pt->isowner == true) { ?>
            <div class="edit-cover-container hidden">
                <!--<a href="{{LINK settings/avatar}}" data-load="?link1=settings&page=avatar"><i class="fa fa-camera"></i></a>-->
            </div>
        <?php } ?>
    </div>
    <div class="pt_chnl_info">
        <div class="avatar-container">
            <img src="{{USER avatar}}" alt="{{USER name}}">
        </div>
        <div class="info-container">
            <h4><a href="{{USER url}}" data-load="?link1=timeline&id={{USER username}}">{{USER name_v}}</a></h4>
            {{MESSAGE_BUTTON}}
        </div>
        <div class="subscribe-btn-container">
            {{SUBSCIBE_BUTTON}}
        </div>
    </div>
    <div class="links-container">
        <ul>
            <li>
                <a href="{{LINK @{{USER username}}?page=videos}}" class="<?php echo ($pt->second_page == 'videos') ? 'active' : ''?>" data-load="?link1=timeline&id={{USER username}}&page=videos">{{LANG videos}}</a>
            </li>
            <!--			<li>-->
            <!--				<a href="{{LINK @{{USER username}}?page=play-lists}}" class="<?php echo ($pt->second_page == 'play-lists') ? 'active' : ''?>" data-load="?link1=timeline&id={{USER username}}&page=play-lists">{{LANG play_lists}}</a>-->
            <!--			</li>-->
            <!--			<li>-->
            <!--				<a href="{{LINK @{{USER username}}?page=short-videos}}" class="<?php echo ($pt->second_page == 'short-videos') ? 'active' : ''?>" data-load="?link1=timeline&id={{USER username}}&page=short-videos">{{LANG short_video_lists}}</a>-->
            <!--			</li>-->
            <!--<li>-->
            <!--<a href="{{LINK @{{USER username}}?page=liked-videos}}" class="<?php echo ($pt->second_page == 'liked-videos') ? 'active' : ''?>" data-load="?link1=timeline&id={{USER username}}&page=liked-videos">{{LANG liked_videos}}</a>-->
            <!--</li>-->
            <!--			<li>-->
            <!--				<a href="{{LINK @{{USER username}}?page=about}}" class="<?php echo ($pt->second_page == 'about') ? 'active' : ''?>" data-load="?link1=timeline&id={{USER username}}&page=about">{{LANG about_profile}}</a>-->
            <!--			</li>-->
        </ul>
    </div>
    <div class="scrolling-wrapper" id="nav_main_header" style="margin-top: 10px">
        {{CHANNEL_CATS}}
    </div>
    <div class="scrolling-wrapper" id="nav_sub_header" style="margin-top: 10px">
        {{CHANNEL_SUBCATS}}
    </div>
    <div class="page-container content pt_shadow">
        {{SECOND_PAGE}}
    </div>
    <input type="hidden" id="profile-id" value="{{USER id}}">
</div>
<?php
global $user_data;
if ($user_data->admin == 1):
    ?>
    <span class="channel-cats_open-btn" onclick="openCHCats(this)">
	<span>پنل دسته بندی ها</span>
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
    <div class="channel-cats topper-1">
	<span class="close-btn" onclick="closeCHCats(this)">
		<svg width="50%" height="50%" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 64 64" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 64 64">
		  <g>
			<path fill="currentColor" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
		  </g>
		</svg>
	</span>
        <?php
        global $cats, $sub_ucats, $langManager;
        foreach ($cats as $cid => $txt):
            echo $langManager->generateCatView($cid, $txt);
            if (count($sub_ucats[$cid]) > 0):
                ?>
                <div class="sub_cats-grid">
            <?php
            endif;
            foreach ($sub_ucats[$cid] as $scid => $txt2):
                echo $langManager->generateCatView($scid, $txt2, $cid);
            endforeach;
            if (count($sub_ucats[$cid]) > 0):
                ?>
                </div>
            <?php
            endif;
        endforeach;
        ?>
    </div>
<?php
endif;
?>
<script>
    $(".mejs__qualities-selector-list-item").click(function (event){
        $(event).preventDefault();
        let Qs_src = $("#my-video_html5 source");
        $("#my-video_html5").attr('src', $(Qs_src[0]).attr("src"));
        // console.log($(this).attr('data-video'));
        // console.log($(this).attr('data-poster'));
        $("#my-video_html5").attr('poster', $(this).data('poster'));
        $("#my-video_html5")[0].load();
        $('#my-video_html5')[0].play();
    });
    $("a.open-video").click(function (e) {
        e.preventDefault();
        window.location.href = "{{CONFIG site_url}}/@{{USER username}}?vid="+$(this).parent().parent().data("id");
        return false;
        try {
            $("#my-video_html5").get(0).pause();
            let defQs = ["240p","360p","480p","720p"];
            var availableQs = []
            let Qs="";
            $.ajax({
                url: "{{CONFIG site_url}}/aj/get-video-qualities",
                data: {id: $(this).parent().parent().data("id")},
                method: "POST",
                dataType: "json",
                success: function (data) {
                    Qs = JSON.parse(data.responseText.substr(0,data.responseText.length - 2));
                },
                error: function (data) {
                    Qs = JSON.parse(data.responseText.substr(0,data.responseText.length - 2));
                }
            });

            let src = $(this).data('video');
            let extension = src.lastIndexOf(".");
            let src_1 = src.substr(0,extension);
            let src_2 = src.substr(extension,src.length);
            let Qs_src = [
                src_1+"_240p_converted"+src_2,
                src_1+"_360p_converted"+src_2,
                src_1+"_480p_converted"+src_2,
                src_1+"_720p_converted"+src_2
            ];

            $('#my-video_html5')[0].play();
            $("#my-video_html5").attr('src', Qs_src[0]);
            // console.log($(this).attr('data-video'));
            // console.log($(this).attr('data-poster'));
            $("#my-video_html5").attr('poster', $(this).data('poster'));
            $("#my-video_html5")[0].load();
            $('#my-video_html5')[0].play();
            let srcs = $("#my-video_html5 source");
            let parent = $("#my-video_html5");
            $(parent).empty();
            for(let i = 0; i < defQs.length; i++){
                if(Qs[defQs[i]] == 0)
                {
                    $("#mep_0-qualities-"+defQs[i]).parent().addClass("hidden");
                    $(srcs[i]).remove();
                }
                else  {
                    $("#mep_0-qualities-"+defQs[i]).parent().removeClass("hidden");
                    let targetQ = defQs[i];
                    if(true)
                    {
                        console.log(i);
                        let string = '<source src="'+ Qs_src[i] +'" type="video/mp4" data-quality="'+ targetQ +'" title="'+ targetQ +'" label="'+ targetQ +'" res="'+ targetQ +'">';

                        console.log(string);
                        $(parent).append(string);
                    }
                    else {
                        switch (targetQ){
                            case defQs[0]:
                                $(srcs[i]).attr('src',Qs_src[0]);
                                break;
                            case defQs[1]:
                                $(srcs[i]).attr('src',Qs_src[1]);
                                break;
                            case defQs[2]:
                                $(srcs[i]).attr('src',Qs_src[2]);
                                break;
                            case defQs[3]:
                                $(srcs[i]).attr('src',Qs_src[3]);
                                break;
                        }
                    }
                }
            }
            // for(let i = 0; i < srcs.length; i++){
            // 	let targetQ = $(srcs[i]).attr("label");
            // 	console.log(targetQ);
            // 	if(availableQs.includes(targetQ))
            // 		switch (targetQ){
            // 			case defQs[0]:
            // 				$(srcs[i]).attr('src',Qs_src[0]);
            // 				break;
            // 			case defQs[1]:
            // 				$(srcs[i]).attr('src',Qs_src[1]);
            // 				break;
            // 			case defQs[2]:
            // 				$(srcs[i]).attr('src',Qs_src[2]);
            // 				break;
            // 			case defQs[3]:
            // 				$(srcs[i]).attr('src',Qs_src[3]);
            // 				break;
            // 		}
            // 	else
            // 		$(srcs[i]).attr('src',Qs_src[0]);
            // }
            // $(".mejs__qualities-selector-list").find("li:last-of-type").click();
            $([document.documentElement, document.body]).animate({
                scrollTop: $(".video-player:eq(0)").offset().top
            }, 0);
        }catch (err){
            console.log(err);
        }
    });

    if('{{EMPTY_LIST}}' == true)
        $('.video-player-page').css('display', 'none');
    <?php if(!empty($_GET['cat'])) { ?>
    $(document).ready(function(){
        if($( window ).width() < 1000)
            $([document.documentElement, document.body]).animate({
                scrollTop: ($("#nav_main_header").offset().top)
            }, 0);
        else
            $([document.documentElement, document.body]).animate({
                scrollTop: ($(".videos-latest-list:eq(0)").offset().top + $(".videos-latest-list:eq(0)").height())
            }, 0);
    });
    <?php } ?>
    function closeCHCats(elmnt) {
        $(elmnt).parent().css("right", "-" + $(elmnt).parent().css("width"));
    }
    function openCHCats(elmnt) {
        $(elmnt).parent().find(".channel-cats").css("right", "0");
    }
</script>
