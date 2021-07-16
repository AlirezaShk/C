<!--video play area-->
<div class="top-video video-player-page">
    <div class="row">
        <div id="background" class="hidden"></div>
        <div class="col-md-12 player-video">
            <div class="video-player pt_video_player {{USR_AD_TRANS}}">
                <video id="my-video"   class="video-js channel-videos" data-setup='' autoplay controls  style="width:100%; height:610px;position: relative;" poster="{{HEADPIC}}"  >
                <!--<video id="my-video<?php if ($pt->config->player_type == 'fluidPlayer') { ?>_<?php echo $pt->get_video->id; ?><?php } ?>" <?php if ($pt->config->player_type == 'videojs') { ?>   class="video-js" data-setup=''  <?php } ?> controls <?php if (empty($pt->ad_image)) { ?>  <?php } ?> style="width:100%; height:100%;position: relative;" poster="{{HEADPIC}}"  >-->
<!--                <source src="{{HEADVIDEO}}" type="video/mp4" data-quality="360p" label='360p' res='360p'>-->

                <?php if (!empty($pt->video_4096)) { ?>
                    <source src="{{VIDEO_LOCATION_4096}}" type="{{VIDEO_TYPE}}" data-quality="4K" title='4K' label='4K' res='4096'>
                    <?php } ?>
                    <?php if (!empty($pt->video_2048)) { ?>
                    <source src="{{VIDEO_LOCATION_2048}}" type="video/mp4" data-quality="2K" title='2K' label='2K' res='2048'>
                    <?php } ?>
                    <?php if (!empty($pt->video_1080)) { ?>
                    <source src="{{VIDEO_LOCATION_1080}}" type="video/mp4" data-quality="1080p" title='1080p' label='1080p' res='1080'>
                    <?php } ?>
                    <?php if (!empty($pt->video_720)) { ?>
                    <source src="{{VIDEO_LOCATION_720}}" type="video/mp4" data-quality="720p" title='720p' label='720p' res='720'>
                    <?php } ?>
                    <?php if (!empty($pt->video_480)) { ?>
                    <source src="{{VIDEO_LOCATION_480}}" type="video/mp4" data-quality="480p" title='480p' label='480p' res='480'>
                    <?php } ?>
                    <?php if (!empty($pt->video_360)) { ?>
                    <source src="{{VIDEO_LOCATION_360}}" type="video/mp4" data-quality="360p" title='360p' label='360p' res='360'>
                    <?php } ?>
                    <?php if (!empty($pt->video_240)) { ?>
                    <source src="{{VIDEO_LOCATION_240}}" type="video/mp4" data-quality="240p" title='240p' label='240p' res='240'>
                    <?php } ?>
                    <?php if (!empty($pt->get_video->youtube) || (!empty($pt->get_video->video_location) && empty($pt->video_360))) { ?>
                    <source src="{{VIDEO_LOCATION}}" type="video/mp4" data-quality="360p" title='360p' label='360p' res='360'>
                    <?php } ?>
                </video>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="channelPlayerTools">
                <div class="video-title pt_video_info channel_video_info">
                    <input type="hidden" value="{{ID}}" id="video-id">
                    <div class="video-big-title">
                        <h1 itemprop="title">{{TITLE}}
                            <?php if ($pt->user->admin == 1) { ?><br><span>id: {{ID}}</span><?php } ?>
                            <?php if ($pt->get_video->privacy == 1) { ?> <span class="private-text">{{LANG private}}</span><?php } ?>
                            <?php if ($pt->get_video->privacy == 2) { ?> <span class="private-text">{{LANG unlisted}}</span><?php } ?>
                            <?php if ($pt->get_video->sell_video > 0) { ?>
                            <?php if ($pt->video_approved == true) { ?>
                            <span><i class="fa fa-check-circle fa-fw verified" title="{{LANG video_verified}}"></i></span>
                            <?php } ?>
                            <?php } ?>
                        </h1>
                    </div>
                    <div class="video-likes pull-right">
                        <div class="like-btn {{LIKE_ACTIVE_CLASS}}" id="likes-bar" onclick="Wo_LikeSystem('{{ID}}', 'like', this, 'is_ajax')" data-likes="{{RAEL_LIKES}}" {{ISLIKED}}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-up">
                                <path d="M14 9V5a3 3 0 0 0-3-3l-4 9v11h11.28a2 2 0 0 0 2-1.7l1.38-9a2 2 0 0 0-2-2.3zM7 22H4a2 2 0 0 1-2-2v-7a2 2 0 0 1 2-2h3"></path>
                            </svg>
                            <span class="likes" id="likes">{{LIKES}}</span>
                        </div>
                        <div class="video-info-element pull-right">
                            <div class="views-bar" style="width: {{DISLIKES_P}}%"></div>
                            <div class="views-bar blue" style="width: {{LIKES_P}}%"></div>
                            <div class="clear"></div>
                        </div>
                        <div class="like-btn text-right {{DIS_ACTIVE_CLASS}}" id="dislikes-bar" onclick="Wo_LikeSystem('{{ID}}', 'dislike', this, 'is_ajax')" data-likes="{{RAEL_DISLIKES}}" {{ISDISLIKED}}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-thumbs-down">
                                <path d="M10 15v4a3 3 0 0 0 3 3l4-9V2H5.72a2 2 0 0 0-2 1.7l-1.38 9a2 2 0 0 0 2 2.3zm7-13h2.67A2.31 2.31 0 0 1 22 4v7a2.31 2.31 0 0 1-2.33 2H17"></path>
                            </svg>
                            <span class="likes" id="dislikes">{{DISLIKES}}</span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <?php if (IS_LOGGED == true) { ?>
                    <div class="video-options" style="padding-bottom: 0;border-bottom: none !important;">
                        <button class="btn-share" id="share-video" onclick="$('.share-video').toggleClass('hidden')">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2">
                                <circle cx="18" cy="5" r="3"></circle>
                                <circle cx="6" cy="12" r="3"></circle>
                                <circle cx="18" cy="19" r="3"></circle>
                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                            </svg>
                            <!--{{LANG share}}-->
                        </button>

                        <button class="btn-share" onclick="PT_ShareVideo('{{ID}}', '{{ME id}}',this); $('#share-user-select2').select2({'width':'300px', 'dropdownCssClass':'shareVideoToUsersSwal'});" id="share_to_users" data-rep="1">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                <!-- Generator: Sketch 58 (84663) - https://sketch.com -->
                                <title>TELGERAM</title>
                                <desc>Created with Sketch.</desc>
                                <g id="TELGERAM" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="telegram" transform="translate(1.000000, 2.000000)" fill="currentColor" fill-rule="nonzero">
                                        <path d="M0.379677263,9.22616368 L5.75974808,11.8722315 C5.96783738,11.973551 6.21351109,11.9663139 6.41243345,11.8514248 L11.2012373,9.10403747 L8.30265428,11.6388347 C8.15506672,11.7681981 8.07073097,11.9527443 8.07073097,12.1472416 L8.07073097,18.3213999 C8.07073097,18.9727396 8.91317177,19.2468451 9.30826643,18.7293919 L11.6339164,15.678046 L17.3852479,18.9103196 C17.7885927,19.1391931 18.3056076,18.9094149 18.3991102,18.4543817 L21.9861297,0.813929606 C22.0933828,0.283811404 21.5589508,-0.148605833 21.0621032,0.0486053755 L0.436512225,7.98680884 C-0.117170307,8.20030354 -0.152921331,8.96472313 0.379677263,9.22616368 Z M20.391084,1.76380011 L17.238577,17.2665913 L11.7888375,14.2034851 C11.491829,14.036127 11.109568,14.1103073 10.8987287,14.3853174 L9.44577037,16.2913909 L9.44577037,12.4521048 L17.2844116,5.59856301 C17.9050127,5.0566845 17.1918256,4.09324443 16.483222,4.50395034 L6.04208951,10.494467 L2.38265132,8.69514084 L20.391084,1.76380011 Z" id="Shape"/>
                                    </g>
                                </g>
                            </svg>
                            <!--{{LANG download}}-->
                        </button>
                    </div>
                    <?php } ?>
                    <div class="share-video hidden">
                        <!--<div class="row share-input">-->
                        <!--<div class="col-md-4">-->
                        <!--<input type="text" value="{{CONFIG site_url}}/v/<?php echo $pt->get_video->short_id; ?>" class="form-control input-md" readonly onClick="this.select();">-->
                        <!--</div>-->
                        <!--</div>-->
                        <a href="#" onclick="OpenShareWindow('https://www.facebook.com/sharer/sharer.php?u={{ENCODED_URL}}')">
                            <i class="fa fa-facebook"></i>
                        </a>
                        <a href="#" class="" onclick="OpenShareWindow('https://twitter.com/home?status={{ENCODED_URL}}')">
                            <i class="fa fa-twitter"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('https://plus.google.com/share?url={{ENCODED_URL}}')">
                            <i class="fa fa-google"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('https://www.linkedin.com/shareArticle?mini=true&url={{ENCODED_URL}}&title={{TITLE}}')">
                            <i class="fa fa-linkedin"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('https://pinterest.com/pin/create/button/?url={{ENCODED_URL}}&media={{THUMBNAIL}}')">
                            <i class="fa fa-pinterest"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('http://www.tumblr.com/share/link?url={{ENCODED_URL}}')">
                            <i class="fa fa-tumblr"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('https://t.me/share/url?url={{ENCODED_URL}}')">
                            <i class="fa fa-telegram" style="background-color: #3888de;color: #fff;"></i>
                        </a>
                        <a href="#" onclick="OpenShareWindow('whatsapp://send?text={{ENCODED_URL}}')">
                            <i class="fa fa-whatsapp" style="background-color: #58da58;color: #fff;"></i>
                        </a>

                    </div>
                    <div class="watch-video-description">
                        <div class="video-views">
                            <span id="video-views-count">{{VIEWS}}</span> {{LANG views}}
                            <?php if ($pt->get_video->sell_video > 0) { ?>
                            <span><?php echo $pt->purchased; ?> {{LANG purchased}}</span>
                            <?php } ?>
                        </div>
                        <p dir="auto" itemprop="description">{{DESC}}</p>
                        <p dir="auto" itemprop="tags">{{TAGS}}</p>
                    </div>
                    <div class="share-placement hidden" style="display: none;margin: 30px 10px">
                        <div class="alert alert-success" id="successMessage" style="display: none">با موفقیت اشتراک گذاری شد</div>
                        <form class="" id="shareForm">
                            <div class="form-group">
                                <label class="col-xs-3">انتخاب کاربر</label>
                                <button class="btn-info col-xs-3" style="float: left">ارسال</button>
                                <select class="form-control col-xs-6 js-example-basic-single" id="selected_user">
                                    {{USERS_LIST}}
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">




    <?php if (!empty($pt->get_video->demo) && PT_GetMedia($pt->get_video->demo) == $pt->get_video->video_location) { ?>
        $("video").bind("ended", function() {
            var link_ = "'"+site_url+"/login'";
            $('.video-player').prepend('<div class="video-processing pay_to_content"><div class="pay_to_content_background"></div><div style="background: rgba(0,0,0,0.8) !important;"><h5><p><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></p>{{LANG pay_to_see_video}}<br><br><?php if (!IS_LOGGED) { ?><button class="btn btn-main" onselectstart="return false" onclick="location.href = '+link_+';">{{LANG pay}} {{CURRENCY}}<?php echo $pt->get_video->sell_video; ?> </button><?php } else{ ?><button class="btn btn-main p_t_show_btn_" onselectstart="return false" onclick="pay_to_see(<?php echo $pt->get_video->id; ?>,<?php echo $pt->get_video->sell_video; ?>)">{{LANG pay}} {{CURRENCY}}<?php echo $pt->get_video->sell_video; ?> </button><?php } ?></h5></div></div>');
        });
    <?php } ?>
    var sources = [];
    for (var i = 0 ; i < $('video').find('source').length; i++) {
        sources[i] = parseFloat($($('video').find('source')[i]).attr('res'));
    }

    // var imageAddr = "http://www.kenrockwell.com/contax/images/g2/examples/31120037-5mb.jpg";
    // var downloadSize = 4995374;
    var imageAddr = site_url+"/upload/photos/speed.jpg";
    var downloadSize = 1082828;
    function getQuality() {
        MeasureConnectionSpeed();


        function MeasureConnectionSpeed() {
            if (getCookie('internet_speed') > 0) {
                showResults(getCookie('internet_speed'));
            }
            else{
                var startTime, endTime;
                var download = new Image();
                download.onload = function () {
                    endTime = (new Date()).getTime();
                    showResults();
                }

                download.onerror = function (err, msg) {
                    ShowProgressMessage(0);
                }

                startTime = (new Date()).getTime();
                var cacheBuster = "?nnn=" + startTime;
                download.src = imageAddr + cacheBuster;
            }
            //console.log($.cookie("internet_speed"));


            function showResults(speed = 0) {
                if (speed == 0) {
                    var duration = (endTime - startTime) / 1000;
                    var bitsLoaded = downloadSize * 8;
                    var speedBps = (bitsLoaded / duration).toFixed(2);
                    var speedKbps = (speedBps / 1024).toFixed(2);
                    var speedMbps = (speedKbps / 1024).toFixed(2);
                    setCookie("internet_speed", speedKbps,1);

                }
                else{
                    speedKbps = speed;
                    if (speed < 240) {
                        speedKbps = 250;
                    }
                }
                for (var i = 0 ; i < sources.length; i++) {
                    if (sources[i] < parseFloat(speedKbps)) {
                        is_clicked = true;
                        video_source = sources[i];
                    <?php if ($pt->config->player_type == 'fluidPlayer' && empty($pt->get_video->youtube)) { ?>
                            $('#source_my-video_{{ID}}_'+video_source+'p').click();
                            $('.source_button_icon').removeClass('source_selected');
                            $('#source_my-video_{{ID}}_auto').find('span').addClass('source_selected');
                        <?php } elseif ($pt->config->player_type == 'videojs' && empty($pt->get_video->youtube)) { ?>
                            $( ".vjs-menu-item-text:contains('"+video_source+"p')" ).click();
                            $('.vjs-resolution-button-label').text('auto');
                        <?php }else{ ?>
                            $('#'+$('.mejs__container').attr('id')+'-qualities-'+video_source+'p').click();
                            $('.mejs__qualities-button').find('button').text('auto');
                            $('.mejs__qualities-selector-label').removeClass('mejs__qualities-selected');
                            $('#quality__auto').addClass('mejs__qualities-selected');
                        <?php } ?>
                        break;
                    }
                }
            }
        }
    }
    function setAuto(self) {
    <?php
    if ($pt->config->player_type == 'fluidPlayer' && empty($pt->get_video->youtube)) { ?>
            $('.source_button_icon').removeClass('source_selected');
            $('#source_my-video_{{ID}}_auto').find('span').addClass('source_selected');
        <?php }
        elseif ($pt->config->player_type == 'videojs' && empty($pt->get_video->youtube)) { ?>
            $('.vjs-resolution-button-label').text('auto');
        <?php }else{ ?>
            $('.mejs__qualities-button').find('button').text('auto');
            $('.mejs__qualities-selector-label').removeClass('mejs__qualities-selected');
            $('#quality__auto').addClass('mejs__qualities-selected');
        <?php } ?>
        getQuality();
        setTimeout(function (argument) {
            setCookie('auto', 'auto', 1);
        },1000);

    }
    $(document).ready(function(){
        document.querySelector('video').addEventListener("loadeddata", function(){
            setCookie('auto', '', 1);
        });
    });

    <?php if (!empty($_COOKIE['auto']) && $_COOKIE['auto'] == 'auto') { ?>
        setTimeout(function (argument) {
            setAuto('');
        },2000);
    <?php } ?>
</script>

<?php if ($pt->config->player_type == 'videojs' && empty($pt->get_video->youtube)) { ?>
<script src="{{CONFIG theme_url}}/player/js/video_js/video.js"></script>
<script src="{{CONFIG theme_url}}/player/js/video_js/videojs-resolution-switcher.js"></script>
<script src="{{CONFIG theme_url}}/player/js/video_js/videojs-contrib-ads.min.js"></script>
<script src="{{CONFIG theme_url}}/player/js/video_js/videojs.ima.js"></script>
<?php } ?>
<?php if (empty($pt->get_video->facebook) && empty($pt->get_video->vimeo) && empty($pt->get_video->daily)) { ?>
<script type="text/javascript">
    function go_to_duration(duration) {
        window.scrollTo(0, 0);
        var vid = document.querySelector("video");
        vid.currentTime = duration;
        vid.play();
    }
</script>
<?php } ?>
<?php if (empty($pt->get_video->facebook) && empty($pt->get_video->vimeo) && empty($pt->get_video->daily) && ($pt->get_video->sell_video == 0 || $pt->get_video->is_owner || $pt->video_approved == false  || !empty($pt->get_video->demo) || ($pt->get_video->sell_video > 0 && $pt->is_paid > 0))) { ?>
<script type="text/javascript">


    <?php if ($pt->config->player_type == 'fluidPlayer' && empty($pt->get_video->youtube)) { ?>

        if ('{{VAST_URL}}' != '') {
            myFluidPlayer = fluidPlayer(
                'my-video_<?php echo $pt->get_video->id; ?>',
                {
                    vastOptions: {
                        adList: [ {roll : "preRoll",
                            vastTag : "{{VAST_URL}}",
                            vastTimeout: {{AD_SKIP_NUM}} * 1000 }],
            vastAdvanced: {
                vastLoadedCallback:       (function() {  }),
                    noVastVideoCallback:      (function() {  }),
                    vastVideoSkippedCallback: (function() {
                    setTimeout(function () {
                        if ($('#autoplay').is(":checked")) {
                            var media = document.querySelector("video");
                            media.addEventListener('ended', function (e) {
                                if ($('#autoplay').is(":checked")) {
                                    var url = $('#next-video').find('.video-title').find('a').attr('href');
                                    if (url) {
                                        window.location.href = url;
                                    }
                                }
                            }, false);

                        }
                    },2000);
                }),
                    vastVideoEndedCallback:   (function() {
                    setTimeout(function () {
                        if ($('#autoplay').is(":checked")) {
                            var media = document.querySelector("video");
                            media.addEventListener('ended', function (e) {
                                if ($('#autoplay').is(":checked")) {
                                    var url = $('#next-video').find('.video-title').find('a').attr('href');
                                    if (url) {
                                        window.location.href = url;
                                    }
                                }
                            }, false);

                        }
                    },2000);

                })
            }
        },
            layoutControls: {
                playbackRateEnabled:    true,
                    fillToContainer:true,
                    autoPlay: true
            }
        }
        );
            myFluidPlayer.on('play', function(){
                // if (pt_elexists('.ads-overlay-info')) {
                //   $('.ads-overlay-info').remove();
                // }

                $('.ads-test').remove();

                if ($('body').attr('resized') == 'true') {
                    PT_Resize(true);
                }
                $('.mejs__container').css('height', ($('.mejs__container').width() / 1.77176216) + 'px');
                $('video, iframe').css('height', '100%');
            });
        }else{
        <?php if (!empty($pt->ad_image)) { ?>
                myFluidPlayer = fluidPlayer('my-video_<?php echo $pt->get_video->id; ?>',{
                    layoutControls: {
                        playbackRateEnabled:    true,
                        fillToContainer:true,
                        autoPlay: false,
                        playButtonShowing: true,
                        playPauseAnimation: false

                    }
                });
                if ($('#autoplay').is(":checked")) {
                    var media = document.querySelector("video");
                    media.addEventListener('ended', function (e) {
                        if ($('#autoplay').is(":checked")) {
                            var url = $('#next-video').find('.video-title').find('a').attr('href');
                            if (url) {
                                window.location.href = url;
                            }
                        }
                    }, false);

                }
            <?php }else{ ?>
                myFluidPlayer = fluidPlayer('my-video_<?php echo $pt->get_video->id; ?>',{
                    layoutControls: {
                        playbackRateEnabled:    true,
                        fillToContainer:true,
                        autoPlay: true,
                        playButtonShowing: true,
                        playPauseAnimation: false

                    }
                });
                if ($('#autoplay').is(":checked")) {
                    var media = document.querySelector("video");
                    media.addEventListener('ended', function (e) {
                        if ($('#autoplay').is(":checked")) {
                            var url = $('#next-video').find('.video-title').find('a').attr('href');
                            if (url) {
                                window.location.href = url;
                            }
                        }
                    }, false);

                }
            <?php } ?>

        }
        if (sources.length > 1) {
            setTimeout(function () {
                $('#my-video_{{ID}}_fluid_control_video_source_list').append('<div id="source_my-video_{{ID}}_auto" class="fluid_video_source_list_item" onclick="setAuto(this)"><span class="source_button_icon"></span>auto</div>');
            },1000);
        }



    <?php }
    elseif ($pt->config->player_type == 'videojs' && empty($pt->get_video->youtube)) { ?>



        if ({{AD_MEDIA}} != '') {
            var ad_started = false;
            //var player = videojs("my-video", { "controls": true, "autoplay": 'play', "preload": "auto" });
            var player = videojs('my-video', {
                controls: true,
                "autoplay": 'play',
                plugins: {
                    videoJsResolutionSwitcher: {
                        default: 'low', // Default resolution [{Number}, 'low', 'high'],
                        dynamicLabel: true
                    }
                }
            }, function(){
                var player = this;
                window.player = player
                player.updateSrc([
                <?php if (!empty($pt->video_4096)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_4096}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '4K',
                        res: 4096
                    },
                <?php } ?>
            <?php if (!empty($pt->video_2048)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_2048}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '2K',
                        res: 2048
                    },
                <?php } ?>
            <?php if (!empty($pt->video_1080)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_1080}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '1080p',
                        res: 1080
                    },
                <?php } ?>
            <?php if (!empty($pt->video_720)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_720}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '720p',
                        res: 720
                    },
                <?php } ?>
            <?php if (!empty($pt->video_480)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_480}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '480p',
                        res: 480
                    },
                <?php } ?>
            <?php if (!empty($pt->video_360)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_360}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '360p',
                        res: 360
                    },
                <?php } ?>
            <?php if (!empty($pt->video_240)) { ?>
                    {
                        src: '{{VIDEO_LOCATION_240}}',
                            type: '{{VIDEO_TYPE}}',
                        label: '240p',
                        res: 240
                    },
                <?php } ?>
            <?php if (!empty($pt->get_video->youtube) || (!empty($pt->get_video->video_location) && empty($pt->video_360))) { ?>
                    {
                        src: '{{VIDEO_LOCATION}}',
                            type: '{{VIDEO_TYPE}}',
                        label: <?php echo(!empty($pt->video_quality) ? '"'.$pt->video_quality.'"' : '"360p"') ?>,
                        res: <?php echo(!empty($pt->video_res) ? $pt->video_res : 360) ?>
                    },
                <?php } ?>
            ])

            });


            player.ads(); // initialize videojs-contrib-ads

            // request ads whenever there's new video content
            player.on('contentchanged', function() {
                // in a real plugin, you might fetch your ad inventory here
                player.trigger('adsready');
            });
            player.on('readyforpreroll', function() {
                if (ad_started == false) {
                    player.ads.startLinearAdMode();
                    // play your linear ad content
                    // in this example, we use a static mp4
                    player.src({{AD_MEDIA}});
                    ad_started = true;
                    $('.vjs-resolution-button').hide();
                }



                // send event when ad is playing to remove loading spinner
                player.one('adplaying', function() {


                    if (pt_elexists('.ads-overlay-info')) {
                        $('.ads-overlay-info').remove();
                    }

                    $('.ads-test').remove();

                    if ($('body').attr('resized') == 'true') {
                        PT_Resize(true);
                    }
                    $('.mejs__container').css('height', ($('.mejs__container').width() / 1.77176216) + 'px');
                    $('video, iframe').css('height', '100%');


                    player.trigger('ads-ad-started');

                    num = {{AD_SKIP_NUM}};
                    $('.video-player').append('<span class="video_js_skip_ad">Skip in '+num+'</span>');
                    setInterval(function () {
                        num = num -1;

                        if (num == 0 || num < 0) {
                            $('.video_js_skip_ad').html('<a href="javascript:void(0)" onclick="end_video_js_ad()">Skip Ad</a>');

                            //$('.video-player').attr('onclick', '');
                        }
                        if (num > 0) {
                            $('.video-player').attr('onclick', 'window.open("'+{{AD_LINK}}+'");');
                            $('.video_js_skip_ad').text('Skip in '+(num));
                        }
                    },1000);
                });

                // resume content when all your linear ads have finished
                player.one('adended', function() {
                    player.ads.endLinearAdMode();
                    $('.video_js_skip_ad').remove();
                    $('.video-player').attr('onclick', '');
                    setTimeout(function () {
                        if ($('#autoplay').is(":checked")) {
                            var media = document.querySelector("video");
                            media.addEventListener('ended', function (e) {
                                if ($('#autoplay').is(":checked")) {
                                    var url = $('#next-video').find('.video-title').find('a').attr('href');
                                    if (url) {
                                        window.location.href = url;
                                    }
                                }
                            }, false);

                        }

                    },2000);
                    $('.vjs-resolution-button').show();
                });
            });

            player.trigger('adsready');
            function end_video_js_ad() {
                player.ads.endLinearAdMode();
                $('.video_js_skip_ad').remove();
                $('.video-player').attr('onclick', '');
                setTimeout(function () {
                    if ($('#autoplay').is(":checked")) {
                        var media = document.querySelector("video");
                        media.addEventListener('ended', function (e) {
                            if ($('#autoplay').is(":checked")) {
                                var url = $('#next-video').find('.video-title').find('a').attr('href');
                                if (url) {
                                    window.location.href = url;
                                }
                            }
                        }, false);

                    }

                },2000);
                $('.vjs-resolution-button').show();

            }
        }else{
        <?php if (!empty($pt->ad_image)) { ?>
                //videojs("my-video", { "controls": true, "autoplay": false, "preload": "auto" });
                videojs('my-video', {
                    controls: true,
                    plugins: {
                        videoJsResolutionSwitcher: {
                            default: 'low', // Default resolution [{Number}, 'low', 'high'],
                            dynamicLabel: true
                        }
                    }
                }, function(){
                    var player = this;
                    window.player = player
                    player.updateSrc([
                    <?php if (!empty($pt->video_4096)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_4096}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '4K',
                            res: 4096
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_2048)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_2048}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '2K',
                            res: 2048
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_1080)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_1080}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '1080p',
                            res: 1080
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_720)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_720}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '720p',
                            res: 720
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_480)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_480}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '480p',
                            res: 480
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_360)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_360}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '360p',
                            res: 360
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_240)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_240}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '240p',
                            res: 240
                        },
                    <?php } ?>
                <?php if (!empty($pt->get_video->youtube) || (!empty($pt->get_video->video_location) && empty($pt->video_360))) { ?>
                        {
                            src: '{{VIDEO_LOCATION}}',
                                type: '{{VIDEO_TYPE}}',
                            label: <?php echo(!empty($pt->video_quality) ? '"'.$pt->video_quality.'"' : '"360p"') ?>,
                            res: <?php echo(!empty($pt->video_res) ? $pt->video_res : 360) ?>
                        },
                    <?php } ?>
                ])

                });
                if ($('#autoplay').is(":checked")) {
                    var media = document.querySelector("video");
                    media.addEventListener('ended', function (e) {
                        if ($('#autoplay').is(":checked")) {
                            var url = $('#next-video').find('.video-title').find('a').attr('href');
                            if (url) {
                                window.location.href = url;
                            }
                        }
                    }, false);

                }
            <?php }else{ ?>




                // videojs("my-video", { "controls": true, "autoplay": 'play', "preload": "auto" });
                videojs('my-video', {
                    controls: true,
                    plugins: {
                        videoJsResolutionSwitcher: {
                            default: 'low', // Default resolution [{Number}, 'low', 'high'],
                            dynamicLabel: true
                        }
                    }
                }, function(){
                    var player = this;
                    window.player = player;
                    player.updateSrc([
                    <?php if (!empty($pt->video_4096)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_4096}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '4K',
                            res: 4096
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_2048)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_2048}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '2K',
                            res: 2048
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_1080)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_1080}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '1080p',
                            res: 1080
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_720)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_720}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '720p',
                            res: 720
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_480)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_480}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '480p',
                            res: 480
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_360)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_360}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '360p',
                            res: 360
                        },
                    <?php } ?>
                <?php if (!empty($pt->video_240)) { ?>
                        {
                            src: '{{VIDEO_LOCATION_240}}',
                                type: '{{VIDEO_TYPE}}',
                            label: '240p',
                            res: 240
                        },
                    <?php } ?>
                <?php if (!empty($pt->get_video->youtube) || (!empty($pt->get_video->video_location) && empty($pt->video_360))) { ?>
                        {
                            src: '{{VIDEO_LOCATION}}',
                                type: '{{VIDEO_TYPE}}',
                            label: <?php echo(!empty($pt->video_quality) ? '"'.$pt->video_quality.'"' : '"360p"') ?>,
                            res: <?php echo(!empty($pt->video_res) ? $pt->video_res : 360) ?>
                        },
                    <?php } ?>
                ]);

                    if ('{{VAST_URL}}' != '') {
                        var options = {
                            id: 'my-video',
                            adTagUrl: "{{VAST_URL}}",
                            vastLoadTimeout:{{AD_SKIP_NUM}} * 1000
                    };

                        player.ima(options);

                        // Remove controls from the player on iPad to stop native controls from stealing
                        // our click
                        var contentPlayer =  document.getElementById('my-video_html5_api');
                        if ((navigator.userAgent.match(/iPad/i) ||
                            navigator.userAgent.match(/Android/i)) &&
                            contentPlayer.hasAttribute('controls')) {
                            contentPlayer.removeAttribute('controls');
                        }

                        // Initialize the ad container when the video player is clicked, but only the
                        // first time it's clicked.
                        var initAdDisplayContainer = function() {
                            player.ima.initializeAdDisplayContainer();
                            wrapperDiv.removeEventListener(startEvent, initAdDisplayContainer);
                        }

                        var startEvent = 'click';
                        if (navigator.userAgent.match(/iPhone/i) ||
                            navigator.userAgent.match(/iPad/i) ||
                            navigator.userAgent.match(/Android/i)) {
                            startEvent = 'touchend';
                        }

                        var wrapperDiv = document.getElementById('my-video');
                        wrapperDiv.addEventListener(startEvent, initAdDisplayContainer);




                    }



                });




                if ($('#autoplay').is(":checked")) {
                    var media = document.querySelector("video");
                    media.addEventListener('ended', function (e) {
                        if ($('#autoplay').is(":checked")) {
                            var url = $('#next-video').find('.video-title').find('a').attr('href');
                            if (url) {
                                window.location.href = url;
                            }
                        }
                    }, false);

                }
            <?php } ?>

        }

        if (sources.length > 1) {
            setTimeout(function () {
                $('.vjs-menu-content').append('<li class="vjs-menu-item" tabindex="-1" role="menuitem" aria-disabled="false" onclick="setAuto(this)"><span class="vjs-menu-item-text">auto</span><span class="vjs-control-text" aria-live="polite"></span></li>');
            },1000);
        }

    <?php }
    else{ ?>
        $('video').mediaelementplayer({
            pluginPath: 'https://cdnjs.com/libraries/mediaelement-plugins/',
            shimScriptAccess: 'always',
            autoplay: true,
            features: ['playpause', 'current', 'progress', 'duration', 'speed', 'skipback', 'jumpforward', 'tracks', 'markers', 'volume', 'chromecast', 'contextmenu', 'flash' <?php echo ($pt->config->ffmpeg_system == 'on' && empty($pt->get_video->youtube)) ? ", 'quality'" : ''?> {{ADS}} {{VAT}}, 'fullscreen'],
        vastAdTagUrl: '{{VAST_URL}}',
            vastAdsType: '{{VAST_TYPE}}',
            jumpForwardInterval: 20,
            adsPrerollMediaUrl: [{{AD_MEDIA}}],
        adsPrerollAdUrl: [{{AD_LINK}}],
        adsPrerollAdEnableSkip: {{AD_SKIP}},
        adsPrerollAdSkipSeconds: {{AD_SKIP_NUM}},
        success: function (media) {
            media.addEventListener('ended', function (e) {
                if ($('#autoplay').is(":checked")) {
                    var url = $('#next-video').find('.video-title').find('a').attr('href');
                    if (url) {
                        window.location.href = url;
                    }
                }
                else{
//manual ads added by me

                    if( {{AD_LINK}} != ''){
                        num = {{AD_SKIP_NUM}};

//                $('.video-player').append('<span class="video_js_skip_ad">Skip in '+num+'</span>');
                        setInterval(function () {
                            num = num -1;

                            if (num == 0 || num < 0) {
                                $('.video_js_skip_ad').html('<a href="javascript:void(0)" onclick="end_video_js_ad()">Skip Ad</a>');

                                //$('.video-player').attr('onclick', '');
                            }
                            if (num > 0) {
                                $('.video-player').attr('onclick', 'window.open("'+{{AD_LINK}}+'");');
                                $('.video_js_skip_ad').text('Skip in '+(num));
                            }
                        },1000);
                        $(".mejs__inner").prepend('<a href="{{AD_P_LINK}}" class="ad-link end-ads" target="_blank"><div class="ad-image"><div class="ads-test"><i class="fa fa-bullhorn"></i> Advertisement will close in <span class="timer">(<span>'+num+'</span>)</span></div><img src="{{AD_IMAGE}}" alt="Ads"></div></a>');
                    }
//                console.log({{END_ADD_LINK}});
                }
            }, false);

            $(".end_ads").click(function(){
                $(this).remove();
            });

            media.addEventListener('playing', function (e) {
                if (pt_elexists('.ads-overlay-info')) {
                    $('.ads-overlay-info').remove();
                }

                $('.ads-test').remove();

                if ($('body').attr('resized') == 'true') {
                    PT_Resize(true);
                }
                $('.mejs__container').css('height', ($('.mejs__container').width() / 1.77176216) + 'px');
                $('video, iframe').css('height', '100%');
            });
        <?php if (!empty($pt->get_video->youtube)) { ?>
                $(document).on('click', '.mejs__container', function(event) {

                    $('.mejs__layer').css('display', 'flex');
                    $('.mejs__overlay-play').css('display', 'none');
                    media.play();
                });
            <?php } ?>
        },
    });



        if (sources.length > 1) {
            setTimeout(function () {
                $('.mejs__qualities-selector-list').append('<li class="mejs__qualities-selector-list-item" onclick="setAuto(this)"><input class="mejs__qualities-selector-input" type="radio" name="mep_0_qualities" value="auto" id="mep_0-qualities-auto"><label for="mep_0-qualities-auto" class="mejs__qualities-selector-label" id="quality__auto">auto</label></li>');
            },1000);
        }



    <?php } ?>
</script>
<?php } ?>

<?php if (!empty($pt->get_video->facebook)) { ?>
<div id="fb-root"></div>

<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = 'https://connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v3.1&appId={{CONFIG facebook_app_ID}}&autoLogAppEvents=1';
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php } ?>
<script>

    <?php if (!empty($pt->get_video->vimeo)) {?>
        var $window = $(window);
        var $videoWrap = $('.pt_vdo_plyr');
        var $video = $('.embed-responsive');
        var videoHeight = $videoWrap.outerHeight();

        $window.on('scroll',  function() {
            var windowScrollTop = $window.scrollTop();
            var videoBottom = videoHeight + $videoWrap.offset().top;

            if (windowScrollTop > videoBottom) {
                $videoWrap.height(videoHeight);
                $video.addClass('stuck');
            } else {
                $videoWrap.height('auto');
                $video.removeClass('stuck');
            }
        });
    <?php } else if (!empty($pt->get_video->daily)) { ?>
        var $window = $(window);
        var $videoWrap = $('.pt_vdo_plyr');
        var $video = $('.embed-responsive');
        var videoHeight = $videoWrap.outerHeight();

        $window.on('scroll',  function() {
            var windowScrollTop = $window.scrollTop();
            var videoBottom = videoHeight + $videoWrap.offset().top;

            if (windowScrollTop > videoBottom) {
                $videoWrap.height(videoHeight);
                $video.addClass('stuck');
            } else {
                $videoWrap.height('auto');
                $video.removeClass('stuck');
            }
        });
    <?php } else { ?>
    <?php } ?>

    <?php if ($pt->converted !== true): ?>
    setInterval(function () {
        $.get('{{LINK aj/check-video-status}}', {id: $('#video-id').val()}, function(data) {
            if (data.status == 200) {
                location.reload();
            }
        });
    }, 5000);
    <?php endif; ?>
    jQuery(window).ready(function($) {
        var width = $('.video-player').width().toString();
        var width = width.substring(0, width.lastIndexOf("."))
        $('.fb-video').attr('data-width', width);
        //$( 'iframe' ).attr( 'src', function ( i, val ) { return val; });
        $("#load-related-videos").click(function(event) {
            let id = 0;
            if ($("div[data-sidebar-video]").length > 0) {
                id = $("div[data-sidebar-video]").last().attr('data-sidebar-video');
            }

            $("#load-related-videos").find('i.spin').removeClass('hidden');

            $.ajax({
                url: '{{LINK aj/load-related-videos}}',
                type: 'GET',
                dataType: 'json',
                data: {id: id,video_id:'{{ID}}'},
            })
                .done(function(data) {
                    if (data.status == 200) {
                        $(".related-videos").append(data.html);
                    }
                    else{
                        $("#load-related-videos").find('span').text('{{LANG no_more_videos}}');
                    }
                    $("#load-related-videos").find('i.spin').addClass('hidden');

                });
        });
    });

    $('.ad-link').on('click', function(event) {
        $('.ad-link').remove();
        $('video')[0].play();
    });

    <?php if (!empty($pt->ad_image)) { ?>
        var counter = {{AD_SKIP_NUM}};
        var interval = setInterval(function() {
            counter--;
            $('.timer span').text(counter);
            if (counter == 0) {
                $('.ad-link').remove();
                if(typeof(myFluidPlayer) != 'undefined'){
                    myFluidPlayer.play();
                }
                else{
                    $('video')[0].play();
                }

                clearInterval(interval);
            }
        }, 1000);
    <?php } ?>
    $('.autoplay-video').on('change', function(event) {
        event.preventDefault();
        checked = 1;
        if($(this).is(":checked")) {
            checked = 2;
        }
        $.post('{{LINK aj/set-cookies}}', {name: 'autoplay', value: checked});
    });
    $('.ads-test').on('click', function(event) {
        $(this).remove();
    });


    $(function () {
        $('.rad-transaction').click(function(event) {
            $(this).off("click").removeClass('rad-transaction');
            $.get('{{LINK aj/ads/rad-transaction}}', function(data){ /* pass */ });
        });

        if ($('[data-litsitem-id]').length > 4) {
            var listItemtopPos = $("div[data-litsitem-id=<?php echo $pt->get_id; ?>]").offset();
            $('.play-list-cont').scrollTop((listItemtopPos.top - 170));
        }


        $('#share-video').on('click', function(event) {
            event.preventDefault();
            if ($('.share-video').hasClass('hidden')) {
                $('.share-video').css('display', 'none');
                $('.share-video').toggleClass('hidden');
            }else{
                $('.share-video').toggleClass('hidden');
                $('.share-video').css('display', 'block');
            }
        });

        $('#embed-video').on('click', function(event) {
            event.preventDefault();
            $('.embed-placement').toggleClass('hidden');
            if (!$('.share-video').hasClass('hidden')) {
                $('.share-video').toggleClass('hidden');
            }
            if (!$('.download-placement').hasClass('hidden')) {
                $('.download-placement').toggleClass('hidden');
            }
        });
        $('#share_to_users').on('click', function(event) {
            console.log('mana');
            event.preventDefault();
            $('.share-placement').toggle('show');
            if (!$('.embed-placement').hasClass('hidden')) {
                $('.embed-placement').toggleClass('hidden');
            }
            if (!$('.download-placement').hasClass('hidden')) {
                $('.download-placement').toggleClass('hidden');
            }
        });
        $('#download-video').on('click', function(event) {
            event.preventDefault();
            $('.download-placement').toggleClass('hidden');
            if (!$('.embed-placement').hasClass('hidden')) {
                $('.embed-placement').toggleClass('hidden');
            }
            if (!$('.share-video').hasClass('hidden')) {
                $('.share-video').toggleClass('hidden');
            }
        });

        $('#save-button').on('click', function(event) {
            event.preventDefault();
            var logged = $('#main-container').attr('data-logged');
            if (!logged) {
                window.location.href = "{{LINK register?to=<?php echo $pt->actual_link;?>}}";
                return false;
            }
            var video_id = $('#video-id').val();
            if ($(this).attr('saved')) {
                $(this).html('<i class="fa fa-floppy-o fa-fw"></i> {{LANG save}}');
                $(this).removeAttr('saved');
            } else {
                $(this).html('<i class="fa fa-check fa-fw"></i> {{LANG saved}}');
                $(this).attr('saved', 'true');
            }
            $.post('{{LINK aj/save-video}}', {video_id: video_id});
        });
        $('.desc').on('click', function(event) {
            event.preventDefault();
            if ($(this).hasClass('expended')) {
                $('.watch-video-description').css({
                    'max-height': '100px',
                    'height': '100px',
                    'overflow': 'hidden'
                });
                $(this).removeClass('expended');
                $(this).text("{{LANG show_more}}");
            } else {
                $('.watch-video-description').css({
                    'max-height': '4000px',
                    'height': 'auto',
                    'overflow': 'auto'
                });
                $(this).addClass('expended');
                $(this).text("{{LANG show_less}}");
            }
        });

    <?php if (!empty($pt->get_video->youtube)) { ?>
            setTimeout(function () {
                $('iframe').css({ width: '100%', height: '100%'});
            }, 2000);
        <?php } ?>

        $('.expend-player').on('click', function(event) {
            event.preventDefault();
            var resize = 0;
            if ($('.player-video').hasClass('col-md-12')) {
                resize = 0;
            } else {
                resize = 1;
            }
            $.post('{{LINK aj/set-cookies}}', {name: 'resize', value:resize});
            PT_Resize();
        });

        // $('video').mediaelementplayer({
        //   pluginPath: 'https://cdnjs.com/libraries/mediaelement-plugins/',
        //   shimScriptAccess: 'always',
        //   autoplay: true,
        //   features: ['playpause', 'current', 'progress', 'duration', 'speed', 'skipback', 'jumpforward', 'tracks', 'markers', 'volume', 'chromecast', 'contextmenu', 'flash' <?php echo ($pt->config->ffmpeg_system == 'on' && empty($pt->get_video->youtube)) ? ", 'quality'" : ''?> {{ADS}} {{VAT}}, 'fullscreen'],
        //   vastAdTagUrl: '{{VAST_URL}}',
        //   vastAdsType: '{{VAST_TYPE}}',
        //   jumpForwardInterval: 20,
        //   adsPrerollMediaUrl: [{{AD_MEDIA}}],
        //   adsPrerollAdUrl: [{{AD_LINK}}],
        //   adsPrerollAdEnableSkip: {{AD_SKIP}},
        //   adsPrerollAdSkipSeconds: {{AD_SKIP_NUM}},
        //   success: function (media) {
        //       media.addEventListener('ended', function (e) {

        //         if ($('#autoplay').is(":checked")) {
        //            var url = $('#next-video').find('.video-title').find('a').attr('href');
        //            if (url) {
        //               window.location.href = url;
        //            }
        //         }
        //         else{
        //           /* pass */
        //         }
        //       }, false);

        //       media.addEventListener('playing', function (e) {
        //         if (pt_elexists('.ads-overlay-info')) {
        //           $('.ads-overlay-info').remove();
        //         }

        //         $('.ads-test').remove();

        //         if ($('body').attr('resized') == 'true') {
        //             PT_Resize(true);
        //         }
        //         $('.mejs__container').css('height', ($('.mejs__container').width() / 1.77176216) + 'px');
        //         $('video, iframe').css('height', '100%');
        //       });
        //   },
        // });

        // $('.expend-player').on('click', function(event) {
        //  event.preventDefault();
        //  var resize = 0;
        //  if ($('.player-video').hasClass('col-md-12')) {
        //    resize = 0;
        //  } else {
        //    resize = 1;
        //  }
        //  $.post('{{LINK aj/set-cookies}}', {name: 'resize', value:resize});
        //  PT_Resize();
        // });
    <?php
        if (!empty($_SESSION['resize']) && $pt->config->resize_video == 'on' && (empty($pt->get_video->daily) && empty($pt->get_video->vimeo))) {
                ?>
            PT_Resize(true);
            if ($('.player-video').hasClass('col-md-12')) {
                $('#background').css('height', '89%');
            }
        <?php
        }
            ?>
        $(window).resize(function(event) {
            if ($('body').attr('resized') == 'true') {
                PT_Resize(true);
            }
        });

    });


    if (document.addEventListener)
    {
        document.addEventListener('webkitfullscreenchange', exitHandler, false);
        document.addEventListener('mozfullscreenchange', exitHandler, false);
        document.addEventListener('fullscreenchange', exitHandler, false);
        document.addEventListener('MSFullscreenChange', exitHandler, false);
    }

    function exitHandler()
    {
        if (document.webkitIsFullScreen || document.mozFullScreen || document.msFullscreenElement !== null)
        {
            setTimeout(function () {
                PT_Resize(false);
            }, 100);
        }
    }
    function PT_Resize(type) {

        if ($('.player-video').hasClass('col-md-12') && type != true) {
            $('.mejs__layer').css('display', 'none');
            $('.player-video').addClass('col-md-8');
            $('.player-video').removeClass('col-md-12');
            $('.player-video').css('margin-bottom', '0');
            $('.player-video').css('margin-top', '0');
            $('.mejs__container, video, iframe').css('width', '100%');
            $('.mejs__container').css('height', ($('.mejs__container').width() / 1.77176216) + 'px');
            $('video, iframe').css('height', '100%');
            $('.second-header-layout').removeClass('hidden');
            $('.header-layout').css('background', '#fff');
            $('.header-layout').css('border-bottom', '1px solid #f1f1f1');
            $('#search-bar').css('border', '1px solid #f5f5f5');
            $('#search-bar').css('color', '#444');
            $('.hide-resize').removeClass('hidden');
            $('.logo-img').find('img').attr('src', '{{CONFIG theme_url}}/img/logo.png');
            $('.top-header a').css('color', '#444');
            $('#background').addClass('hidden');
            $('body').attr('resized', 'false');
            $('body').css('padding-top', '0px');
        } else {
            var pixels = ($(window).height() / 100) * 88;
            $('.player-video').removeClass('col-md-8');
            $('.player-video').addClass('col-md-12');
            $('.second-header-layout').addClass('hidden');
            $('.player-video').css('margin-bottom', '10px');
            $('.player-video').css('margin-top', '0px');
            $('body').css('padding-top', '57px');
            $('.mejs__container, video, iframe').css('width', '100%');
            $('.mejs__container').css('height', pixels + 'px');
            $('video, iframe').css('height', '100%');
            $('.header-layout').css('background', 'rgb(32,32,32)');
            $('.header-layout').css('border-bottom', 'none');
            $('#search-bar').css('border', '1px solid #555');
            $('#search-bar').css('color', '#fff');
            $('.hide-resize').addClass('hidden');
            $('.logo-img').find('img').attr('src', '{{CONFIG theme_url}}/img/logo-light.png');
            $('.top-header a').css('color', '#fff');
            $('#background').removeClass('hidden');
            $('#background').css('height', '89.4%');
            $('body').attr('resized', 'true');
        }
    }
    $('.player-video').hover(function() {
        $('.icons').removeClass('hidden');
    });
    $('.player-video').mouseleave(function() {
        $('.icons').addClass('hidden');
    });

</script>

<style>
    /*.mejs__fullscreen .mejs__container {
      max-height: 100% !important;
    }
    .mejs__container {
      max-height: 555px !important;
    }
    .mejs__fullscreen video {
       max-height: 100% !important;
    }
    .vjs-fullscreen video {
       max-height: 100% !important;
    }
    .fluid_video_wrapper.fluid_player_layout_default:-webkit-full-screen video{max-height: 100% !important;}
    video {
      max-height: 555px !important;
    }*/
    .mejs__offscreen {
        clip: initial !important;
        clip-path: inherit !important;
        -webkit-clip-path: inherit !important;
        opacity: 0;
    }
</style>

<?php
if (empty($_SESSION['finger'])) {
?>
<script>
    var fingerprintReport = function () {
        Fingerprint2.get(function(components) {
            var murmur = Fingerprint2.x64hash128(components.map(function (pair) { return pair.value }).join(), 31)
            $.post('{{LINK aj/views}}?hash=' + $('.main_session').val()+'&type_=set', {finger: murmur}, function(data, textStatus, xhr) {

            <?php
                // if ($pt->continent_hide == false) {
                //     if (($pt->video_approved == true  && (($pt->get_video->sell_video > 0 && $pt->is_paid > 0) || $pt->get_video->sell_video == 0) && ($pt->get_video->age == false) && $pt->video_type == 'public') || $pt->get_video->is_owner || PT_IsAdmin()) {
                //         if ($pt->converted == true) {
                                ?>

                            $.post('{{LINK aj/views}}?hash=' + $('.main_session').val()+'&type_=add', {video_id:{{ID}}}, function(data, textStatus, xhr) {
                                if (data.status == 200) {
                                    $('#video-views-count').html(data.count);
                                }
                            });
                        <?php
                        // }
                // }
                // }
                ?>


            });
        })
    }
    fingerprintReport();
</script>
<?php } else { ?>
<?php
if ($pt->continent_hide == false) {
if (($pt->video_approved == true  && (($pt->get_video->sell_video > 0 && $pt->is_paid > 0) || $pt->get_video->sell_video == 0) && ($pt->get_video->age == false) && $pt->video_type == 'public') || $pt->get_video->is_owner || PT_IsAdmin()) {
if ($pt->converted == true) {
?>
<script>
    $.post('{{LINK aj/views}}?hash=' + $('.main_session').val()+'&type_=add', {video_id:{{ID}}}, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('#video-views-count').html(data.count);
        }
    });
</script>
<?php } } } ?>
<?php } ?>
<!--video play area-->

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    // console.log("<?php global $pt; echo intval($pt->continent_hide) . " && { " . intval($pt->video_approved) . " && (( " . intval($pt->get_video->sell_video) . " && " . intval($pt->is_paid) . " ) || " . intval($pt->get_video->sell_video) . ") && (" . intval($pt->get_video->age) . ") && (" . $pt->video_type . ")) || " . intval($pt->get_video->is_owner) . " || " . intval(PT_IsAdmin()) . " }"; ?>");

    $(document).ready(function() {
        $('.js-example-basic-single').select2({'width':'300px'});
        $("#shareForm").submit(function(e){
            e.preventDefault();
            $("#successMessage").css('display', 'none');
            let to = $("#selected_user").val();
            $.ajax({
                url: '{{LINK aj/share-media}}',
                type: 'POST',
                dataType: 'json',
                data: {media_id: '{{ID}}',from_user_id:'{{ME id}}', to_user_id:to, type:0},
            })
                .done(function(data) {
                    if (typeof data.errors === 'undefined') {
                        $("#successMessage").toggle('show');
                        console.log('ok');
                    }
                    else{
                        console.log('nok');
                    }
                });
        })
    });
</script>
<style>
    /*.profile-container .card-container {*/
    /*    width:50%;*/
    /*}*/
</style>
