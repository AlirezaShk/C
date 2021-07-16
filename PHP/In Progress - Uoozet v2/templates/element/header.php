<header class="main_header navbar-fixed-top">
    <div id="top_main_header">
        <div class="container">
            <div class="row">
                <div class="col-4 col-lg-3 logo_field text-right">
                    <a href="/"><img src="<?= $uoozet->config->googleAnalytics ?>/img/LogoForWeb.png" alt="" width="155px"></a>
                </div>
                <div class="col-8 col-lg-3 sign_field" id="responsive_sign">
                    <?php if ((!empty($uoozet->page->name) && $uoozet->page->name != 'watch' && $uoozet->page->name != 'movies') || (!empty($uoozet->page->name) && $uoozet->page->name == 'watch' && !empty($uoozet->get_video) && !$uoozet->get_video->is_movie)) { ?>
                        <!--                    <?php if ($uoozet->config->night_mode == 'both' || $uoozet->config->night_mode == 'night_default'): ?>-->
                            <!--                    <?php if (!IS_LOGGED): ?>-->
                                <div class="toggle-mode top-header">
                                    <label class="switch">
                                        <input type="checkbox" {{MODE}} id="toggle-mode" class="mode-js">
                                        <span class="slider round mr-0">
							<svg fill="#009da0" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-bulb"><path d="M12,6A6,6 0 0,1 18,12C18,14.22 16.79,16.16 15,17.2V19A1,1 0 0,1 14,20H10A1,1 0 0,1 9,19V17.2C7.21,16.16 6,14.22 6,12A6,6 0 0,1 12,6M14,21V22A1,1 0 0,1 13,23H11A1,1 0 0,1 10,22V21H14M20,11H23V13H20V11M1,11H4V13H1V11M13,1V4H11V1H13M4.92,3.5L7.05,5.64L5.63,7.05L3.5,4.93L4.92,3.5M16.95,5.63L19.07,3.5L20.5,4.93L18.37,7.05L16.95,5.63Z" /></svg>
						</span>
                                        <span style="margin-right: 5px;">{{LANG mode}}</span>
                                    </label>

                                    <div class="addVideoHeaderButton" id="addVideoHeaderButton">
                                        <div class="dropdown-menu-wrapper">
                                            <a href="#" class="dropdown-toggle nav-item" data-toggle="dropdown" role="button" aria-expanded="false">
                                                <svg fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-upload "><path d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"></path></svg>
                                            </a>
                                            <ul class="dropdown-menu" role="menu">
                                                <?php if ($uoozet->config->upload_system == 'on') { ?>
                                                    <li data-toggle="tooltip" data-placement="left" title="">
                                                        <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'upload-video', $user->id], ['class' => 'side-nav-item']) ?>?type=video" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload}}</a>
                                                    </li>
                                                    <?php if($uoozet->user->admin === 1){ ?>
                                                        <li data-toggle="tooltip" data-placement="left" title="">
                                                            <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'upload-video', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=cinema" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_movie}}</a>
                                                        </li>
                                                        <li data-toggle="tooltip" data-placement="left" title="">
                                                            <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'upload-video', $user->id], ['class' => 'side-nav-item']) ?>upload-music}}" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_music}}</a>
                                                        </li>
                                                    <?php } ?>
                                                <?php } ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <!--                    <?php endif; ?>-->
                            <!--                    <?php endif; ?>-->
                    <?php } ?>

                    <?php if (IS_LOGGED): ?>
                        <div class="toggle-mode top-header">
                            <?php if ((!empty($page->name) && $page->name != 'watch' && $page->name != 'movies') || (!empty($page->name) && $page->name == 'watch' && !empty($uoozet->get_video) && !$uoozet->get_video->is_movie)) { ?>
                                <?php if ($uoozet->config->night_mode == 'both' || $uoozet->config->night_mode == 'night_default'): ?>
                                    <label class="switch">
                                        <input type="checkbox" {{MODE}} id="toggle-mode" class="mode-js">
                                        <span class="slider round mr-0">
                                    <svg fill="#009da0" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-bulb "><path d="M12,6A6,6 0 0,1 18,12C18,14.22 16.79,16.16 15,17.2V19A1,1 0 0,1 14,20H10A1,1 0 0,1 9,19V17.2C7.21,16.16 6,14.22 6,12A6,6 0 0,1 12,6M14,21V22A1,1 0 0,1 13,23H11A1,1 0 0,1 10,22V21H14M20,11H23V13H20V11M1,11H4V13H1V11M13,1V4H11V1H13M4.92,3.5L7.05,5.64L5.63,7.05L3.5,4.93L4.92,3.5M16.95,5.63L19.07,3.5L20.5,4.93L18.37,7.05L16.95,5.63Z" /></svg>
                                </span>
                                        <span style="margin-right: 5px;">{{LANG mode}}</span>
                                    </label>
                                <?php endif; ?>
                            <?php } ?>
                            <div class="addVideoHeaderButton" id="addVideoHeaderButton">
                                <div class="dropdown-menu-wrapper">
                                    <a href="#" class="dropdown-toggle nav-item" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <svg fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-upload firstSvg"><path d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"></path></svg>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php if ($uoozet->config->upload_system == 'on') { ?>
                                            <li data-toggle="tooltip" data-placement="left" title="">
                                                <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=video" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload}}</a>
                                            </li>
                                            <?php if($uoozet->user->admin === 1){ ?>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=cinema" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_movie}}</a>
                                                </li>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-music}}" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_music}}</a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <nav class="navbar navbar-findcond navbar-fixed-top header-layout" style="direction: ltr">
                            {{SIDE_HEADER}}
                        </nav>
                    <?php endif; ?>
                    <?php if (!IS_LOGGED){ ?>
                        <a href="<?= /*PT_Link(*/'loginWithPhone' ?>" class="signup_link" style="background: #dcd628 !important;">{{LANG login}}</a>
                    <?php } ?>
                    <!--<a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>login}}" class="login_link">{{LANG register}}/{{LANG login}}</a>-->
                </div>
                <div class="col-12 col-lg-6 search_field">
                    <form action="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>search}}" method="GET" role="search">
                        <input type="text" id="search-bar" name="keyword" placeholder="{{LANG search_keyword}}"
                               autocomplete="off" value="{{SEARCH_KEYWORD}}">
                        <?php
                        if (!empty($_GET['is_channel'])) {
                            ?>
                            <input type="hidden" name="is_channel" value="true">
                        <?php } ?>
                        <button class="input_search_btn">
                            <img src="{{CONFIG theme_url}}/img/loupe.svg" alt="">
                        </button>
                        <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>advanced_search}}" type="button" id="advSearchBTN">
                            <p>{{LANG advanced_search_short}}</p>
                        </a>
                        <div class="search-dropdown hidden"></div>
                    </form>
                </div>
                <div class="col-3 sign_field">
                    <!--
                    <?php if (!IS_LOGGED): ?>-->

                    <!--                    <?php endif; ?>-->


                    <?php if (IS_LOGGED): ?>
                        <div class="toggle-mode top-header">
                            <?php if ((!empty($page->name) && $page->name != 'watch' && $page->name != 'movies') || (!empty($page->name) && $page->name == 'watch' && !empty($uoozet->get_video) && !$uoozet->get_video->is_movie)) { ?>
                                <?php if ($uoozet->config->night_mode == 'both' || $uoozet->config->night_mode == 'night_default'): ?>
                                    <label class="switch">
                                        <input type="checkbox" {{MODE}} id="toggle-mode" class="mode-js">
                                        <span class="slider round mr-0">
                                    <svg fill="#009da0" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-bulb "><path d="M12,6A6,6 0 0,1 18,12C18,14.22 16.79,16.16 15,17.2V19A1,1 0 0,1 14,20H10A1,1 0 0,1 9,19V17.2C7.21,16.16 6,14.22 6,12A6,6 0 0,1 12,6M14,21V22A1,1 0 0,1 13,23H11A1,1 0 0,1 10,22V21H14M20,11H23V13H20V11M1,11H4V13H1V11M13,1V4H11V1H13M4.92,3.5L7.05,5.64L5.63,7.05L3.5,4.93L4.92,3.5M16.95,5.63L19.07,3.5L20.5,4.93L18.37,7.05L16.95,5.63Z" /></svg>
                                </span>
                                        <span style="margin-right: 5px;">{{LANG mode}}</span>
                                    </label>
                                <?php endif; ?>
                            <?php } ?>
                            <div class="addVideoHeaderButton" id="addVideoHeaderButton">
                                <div class="dropdown-menu-wrapper">
                                    <a href="#" class="dropdown-toggle nav-item" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <svg fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-upload firstSvg"><path d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"></path></svg>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php if ($uoozet->config->upload_system == 'on') { ?>
                                            <li data-toggle="tooltip" data-placement="left" title="">
                                                <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=video" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload}}</a>
                                            </li>
                                            <?php if($uoozet->user->admin === 1){ ?>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=cinema" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_movie}}</a>
                                                </li>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-music}}" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_music}}</a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <nav class="navbar navbar-findcond navbar-fixed-top header-layout" style="direction: ltr">
                            {{SIDE_HEADER}}
                        </nav>
                    <?php endif; ?>
                    <?php if (!IS_LOGGED){ ?>
                        <div class="toggle-mode top-header">
                            <?php if ((!empty($page->name) && $page->name != 'watch' && $page->name != 'movies') || (!empty($page->name) && $page->name == 'watch' && !empty($uoozet->get_video) && !$uoozet->get_video->is_movie)) { ?>
                                <?php if ($uoozet->config->night_mode == 'both' || $uoozet->config->night_mode == 'night_default'): ?>
                                    <label class="switch">
                                        <input type="checkbox" {{MODE}} id="toggle-mode" class="mode-js">
                                        <span class="slider round mr-0">
                                <svg fill="#009da0" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-bulb "><path d="M12,6A6,6 0 0,1 18,12C18,14.22 16.79,16.16 15,17.2V19A1,1 0 0,1 14,20H10A1,1 0 0,1 9,19V17.2C7.21,16.16 6,14.22 6,12A6,6 0 0,1 12,6M14,21V22A1,1 0 0,1 13,23H11A1,1 0 0,1 10,22V21H14M20,11H23V13H20V11M1,11H4V13H1V11M13,1V4H11V1H13M4.92,3.5L7.05,5.64L5.63,7.05L3.5,4.93L4.92,3.5M16.95,5.63L19.07,3.5L20.5,4.93L18.37,7.05L16.95,5.63Z" /></svg>
                            </span>
                                        <span style="margin-right: 5px;">{{LANG mode}}</span>
                                    </label>
                                <?php endif; ?>
                            <?php } ?>
                            <div class="addVideoHeaderButton" id="addVideoHeaderButton">
                                <div class="dropdown-menu-wrapper">
                                    <a href="#" class="dropdown-toggle nav-item" data-toggle="dropdown" role="button" aria-expanded="false">
                                        <svg fill="currentColor" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="feather feather-upload firstSvg"><path d="M14,13V17H10V13H7L12,8L17,13M19.35,10.03C18.67,6.59 15.64,4 12,4C9.11,4 6.6,5.64 5.35,8.03C2.34,8.36 0,10.9 0,14A6,6 0 0,0 6,20H19A5,5 0 0,0 24,15C24,12.36 21.95,10.22 19.35,10.03Z"></path></svg>
                                    </a>
                                    <ul class="dropdown-menu" role="menu">
                                        <?php if ($uoozet->config->upload_system == 'on') { ?>
                                            <li data-toggle="tooltip" data-placement="left" title="">
                                                <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=video" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload}}</a>
                                            </li>
                                            <?php if(isset($uoozet->user->admin) and $uoozet->user->access >= ACCESS_LEVEL__HIDDEN_SITE_SECTIONS){ ?>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-video}}?type=cinema" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_movie}}</a>
                                                </li>
                                                <li data-toggle="tooltip" data-placement="left" title="">
                                                    <a href="<?= $this->Html->link(__('Edit User'), ['action' => 'edit', $user->id], ['class' => 'side-nav-item']) ?>upload-music}}" class="btn-floating"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload" color="#d84c47"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>{{LANG upload_music}}</a>
                                                </li>
                                            <?php } ?>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <a href="<?= /*PT_Link(*/'loginWithPhone' ?>" class="signup_link" style="background: #dcd628 !important;">{{LANG login}}</a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <div id="nav_main_header">
        <div class="container">
            <div class="row">
                <nav class="navbar navbar-expand-lg navbar-light" style="z-index: 10">
                    <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false"
                            aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                        <div class="navbar-nav">
                            <?php if($uoozet->get_video->navbarAvailable->home){ ?><a class="nav-item nav-link home" href="/">
                                <svg width="25px" height="25px" viewBox="0 0 23 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>home (2)</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-1263.000000, -99.000000)" fill="#444444" fill-rule="nonzero">
                                            <g id="home-(2)" transform="translate(1263.000000, 99.000000)">
                                                <path d="M22.1639738,7.90672489 L11.5658952,0.147030568 C11.3615284,-0.00257641921 11.0838865,-0.00257641921 10.879607,0.147030568 L0.281441048,7.90672489 C0.0225764192,8.09628821 -0.0336681223,8.45978166 0.155895197,8.71864629 C0.345458515,8.97751092 0.708995633,9.03366812 0.967816594,8.84419214 L11.2227074,1.33572052 L21.4775983,8.84414847 C21.5810044,8.91991266 21.701179,8.95637555 21.8203057,8.95637555 C21.9991703,8.95637555 22.1756769,8.87406114 22.289476,8.71860262 C22.479083,8.45978166 22.4228384,8.09628821 22.1639738,7.90672489 Z" id="Path"></path>
                                                <path d="M19.3557205,8.97567686 C19.0349345,8.97567686 18.7748035,9.23576419 18.7748035,9.55659389 L18.7748035,18.8032751 L14.1275546,18.8032751 L14.1275546,13.7558515 C14.1275546,12.1541048 12.8244105,10.851048 11.2227511,10.851048 C9.6210917,10.851048 8.3179476,12.1541921 8.3179476,13.7558515 L8.3179476,18.8033188 L3.67065502,18.8033188 L3.67065502,9.55663755 C3.67065502,9.23580786 3.41052402,8.97572052 3.08973799,8.97572052 C2.76895197,8.97572052 2.50882096,9.23580786 2.50882096,9.55663755 L2.50882096,19.3842795 C2.50882096,19.7051092 2.76895197,19.9651965 3.08973799,19.9651965 L8.89886463,19.9651965 C9.20436681,19.9651965 9.45436681,19.7291703 9.47751092,19.4295197 C9.4789083,19.4159389 9.47978166,19.4010917 9.47978166,19.3842795 L9.47978166,13.7558952 C9.47978166,12.7948035 10.2616594,12.0129258 11.2227511,12.0129258 C12.1838428,12.0129258 12.9657205,12.7948472 12.9657205,13.7558952 L12.9657205,19.3842795 C12.9657205,19.4010044 12.9665939,19.4155895 12.9679913,19.4289956 C12.9908734,19.7288646 13.2409607,19.9651965 13.5466376,19.9651965 L19.3557642,19.9651965 C19.6765939,19.9651965 19.9366812,19.7051092 19.9366812,19.3842795 L19.9366812,9.55663755 C19.9366376,9.23576419 19.6765502,8.97567686 19.3557205,8.97567686 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                صفحه اصلی
                                <span class="sr-only">(current)</span>
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->cinema){ ?><a class="nav-item nav-link cinema" href="/movie-sharing">
                                <svg width="25px" height="25px" viewBox="0 0 97 107" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>video-camera</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-997.000000, -296.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="video-camera" stroke="currentColor" stroke-width="1" transform="translate(997.000000, 296.000000)">
                                                <path d="M95.3815589,39.2849746 C94.6592216,38.8541784 93.7635151,38.833913 93.0229835,39.2321174 L77.5680197,47.5423885 L77.5680197,40.4208809 C77.5680197,39.1021683 76.4976891,38.0331169 75.1780747,38.0331169 L71.2911726,38.0331169 C76.1386118,34.1056014 79.2431562,28.1108075 79.2431562,21.404218 C79.2433653,9.60203275 69.6317209,0 57.8173035,0 C47.7334421,0 39.2554949,6.99615471 36.9883096,16.3844608 C34.0224188,12.8093958 29.5459774,10.5279729 24.5456637,10.5279729 C15.6366982,10.5279729 8.38906665,17.7685658 8.38906665,26.6684133 C8.38906665,31.0973405 10.1846624,35.1142744 13.0859318,38.0337436 L10.9036535,38.0337436 C9.58362089,38.0337436 8.51349938,39.102795 8.51349938,40.4215076 L8.51349938,51.2601468 L2.46272246,51.2601468 C1.14268984,51.2601468 0.0725683334,52.3291982 0.0725683334,53.6479108 L0.0725683334,63.0376793 C0.0725683334,64.3563919 1.14268984,65.4254433 2.46272246,65.4254433 L8.51329025,65.4254433 L8.51329025,76.2640824 C8.51329025,77.582795 9.58341176,78.6518464 10.9034444,78.6518464 L39.0994834,78.6518464 L26.1446766,103.476945 C25.5344334,104.64607 25.9886651,106.088255 27.1591693,106.697888 C27.5121819,106.881739 27.8898718,106.968859 28.2619152,106.968859 C29.1241609,106.968859 29.9569191,106.501084 30.3831273,105.684619 L43.0403413,81.429249 L55.6977644,105.684619 C56.1239726,106.501502 56.95694,106.968859 57.8191856,106.968859 C58.1912291,106.968859 58.5691281,106.881739 58.9219315,106.697888 C60.0924357,106.088255 60.5466675,104.64607 59.9362152,103.476945 L46.9814083,78.6518464 L75.1778656,78.6518464 C76.4976891,78.6518464 77.5680197,77.582795 77.5680197,76.2640824 L77.5680197,69.1423659 L93.0229835,77.4526369 C93.3772508,77.6429644 93.7664429,77.7378148 94.155635,77.7378148 C94.5801702,77.7378148 95.0044963,77.6247883 95.3815589,77.3999887 C96.1038961,76.9689836 96.5457892,76.1905421 96.5457892,75.3502597 L96.5457892,41.3347036 C96.5457892,40.4942123 96.1038961,39.7159797 95.3815589,39.2849746 Z M8.51349938,60.6492885 L4.85287659,60.6492885 L4.85287659,56.0348391 L8.51349938,56.0348391 L8.51349938,60.6492885 Z M57.8173035,4.77531903 C66.9956292,4.77531903 74.4630571,12.2350706 74.4630571,21.4044269 C74.4630571,30.5737832 66.9958383,38.0333258 57.8173035,38.0333258 C48.6389778,38.0333258 41.171759,30.5735743 41.171759,21.4044269 C41.1719681,12.2352795 48.6391869,4.77531903 57.8173035,4.77531903 Z M39.5658448,32.6030378 C40.8423782,34.6721965 42.4616768,36.5084077 44.3438526,38.0333258 L36.0053956,38.0333258 C37.5339314,36.4952456 38.7519083,34.6506776 39.5658448,32.6030378 Z M24.5456637,15.303083 C30.8185373,15.303083 35.9221616,20.4013947 35.9221616,26.6682044 C35.9221616,32.9350141 30.8187465,38.0335347 24.5456637,38.0335347 C18.27279,38.0335347 13.1691658,32.935223 13.1691658,26.6682044 C13.1693749,20.4013947 18.27279,15.303083 24.5456637,15.303083 Z M72.7879206,51.539057 L72.7879206,65.1446527 L72.7879206,73.8754828 L43.0836313,73.8754828 C43.0792396,73.8754828 43.075057,73.8754828 43.0706652,73.8754828 L43.0075078,73.8754828 C43.0047891,73.8754828 43.0020704,73.8754828 42.9993517,73.8754828 L13.2935985,73.8754828 L13.2935985,63.0368436 L13.2935985,53.6470751 L13.2935985,42.8084359 L72.7879206,42.8084359 L72.7879206,51.539057 Z M91.76569,71.3527555 L77.5680197,63.7183456 L77.5680197,52.9655731 L91.76569,45.3311632 L91.76569,71.3527555 Z" id="Shape"></path>
                                                <path d="M24.5456637,19.4152851 C20.5422758,19.4152851 17.2854842,22.6690288 17.2854842,26.6682044 C17.2854842,30.6675889 20.5424849,33.9211237 24.5456637,33.9211237 C28.5490516,33.9211237 31.8058431,30.66738 31.8058431,26.6682044 C31.8060522,22.6690288 28.5490516,19.4152851 24.5456637,19.4152851 Z M24.5456637,29.6231903 C22.9144447,29.6231903 21.5875107,28.2975833 21.5875107,26.6679955 C21.5875107,25.0384077 22.9144447,23.7128007 24.5456637,23.7128007 C26.1768827,23.7128007 27.5038166,25.0384077 27.5038166,26.6679955 C27.5038166,28.2975833 26.1766736,29.6231903 24.5456637,29.6231903 Z" id="Shape"></path>
                                                <path d="M57.8173035,10.988227 C52.0680929,10.988227 47.3908861,15.6609656 47.3908861,21.4044269 C47.3908861,27.1476793 52.0680929,31.8204178 57.8173035,31.8204178 C63.566514,31.8204178 68.2437209,27.1476793 68.2437209,21.4044269 C68.2437209,15.6609656 63.5667231,10.988227 57.8173035,10.988227 Z M57.8173035,27.0450988 C54.7039756,27.0450988 52.1709852,24.5146414 52.1709852,21.4044269 C52.1709852,18.2942123 54.7039756,15.763546 57.8173035,15.763546 C60.9306314,15.763546 63.4636217,18.2940034 63.4636217,21.4044269 C63.4636217,24.5148504 60.9306314,27.0450988 57.8173035,27.0450988 Z" id="Shape"></path>
                                                <path d="M50.1062384,49.7519424 L24.1196646,49.7519424 C22.8655081,49.7519424 21.8491332,50.7675099 21.8491332,52.0202033 L21.8491332,64.6639243 C21.8491332,65.9168267 22.8657172,66.9321852 24.1196646,66.9321852 L50.1062384,66.9321852 C51.3603948,66.9321852 52.3767698,65.9166177 52.3767698,64.6639243 L52.3767698,52.0202033 C52.3767698,50.7677188 51.3601857,49.7519424 50.1062384,49.7519424 Z M47.835707,62.3956635 L26.390196,62.3956635 L26.390196,54.2886731 L47.835707,54.2886731 L47.835707,62.3956635 L47.835707,62.3956635 Z" id="Shape"></path>
                                                <ellipse id="Oval" cx="62.6421566" cy="54.4077583" rx="2.39015413" ry="2.38776398"></ellipse>
                                                <ellipse id="Oval" cx="62.6421566" cy="62.2751158" rx="2.39015413" ry="2.38776398"></ellipse>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                سینما
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->audio_book){ ?><a class="nav-item nav-link audio-book" href="/audio_book">
                                <svg width="25px" height="25px" viewBox="0 0 116 110" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>audio</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-992.000000, -655.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="audio" transform="translate(992.000000, 655.000000)">
                                                <path d="M84.6222222,18.6461111 C80.75,18.4280556 77.7877778,15.1122222 78.0061111,11.24 C78.2069444,7.6725 81.0547222,4.82472222 84.6222222,4.62388889 L84.5555556,4.62388889 C85.7827778,4.62388889 86.7777778,3.62888889 86.7777778,2.40166667 C86.7777778,1.17444444 85.7827778,0.179444444 84.5555556,0.179444444 L11.4,0.179444444 C5.09305556,0.216111111 0,5.33916667 0,11.6461111 L0,102.135 C0.0122222222,106.413333 3.47722222,109.878333 7.75555556,109.890556 L79.0888889,109.890556 C83.3672222,109.878333 86.8322222,106.413333 86.8444444,102.135 L86.8444444,20.8683333 C86.8444444,19.6411111 85.8494444,18.6461111 84.6222222,18.6461111 Z M10.6102778,4.62388889 C10.8733333,4.60916667 11.1369444,4.60916667 11.4,4.62388889 L75.5555556,4.62388889 C74.4741667,5.97638889 73.7141667,7.55694444 73.3333333,9.24611111 L53,9.24611111 C51.7727778,9.24611111 50.7777778,10.2411111 50.7777778,11.4683333 C50.7777778,12.6955556 51.7727778,13.6905556 53,13.6905556 L73.3333333,13.6905556 C73.665,15.4969444 74.4272222,17.1969444 75.5555556,18.6461111 L11.4,18.6461111 C7.52777778,18.8641667 4.21222222,15.9019444 3.99388889,12.03 C3.77583333,8.15777778 6.73805556,4.84194444 10.6102778,4.62388889 Z M82.3333333,102.135 C82.3333333,103.963611 80.8508333,105.446111 79.0222222,105.446111 L79.0222222,105.446111 L7.68888889,105.446111 C5.88638889,105.409722 4.44416667,103.938056 4.44444444,102.135 L4.44444444,20.6905556 C6.45194444,22.2513889 8.92388889,23.0961111 11.4666667,23.0905556 L82.3333333,23.0905556 L82.3333333,102.135 Z" id="Shape"></path>
                                                <path d="M68.0266667,44.6194444 C67.6861111,44.6188889 67.3502778,44.6963889 67.0444444,44.8461111 L48.7333333,53.735 C48.3158333,53.6052778 47.8816667,53.5377778 47.4444444,53.535 L38.2444444,53.535 C32.8888889,53.535 27.1333333,56.4461111 27.1333333,64.6461111 L27.1333333,65.6905556 C27.1777778,73.7794444 32.9111111,76.6905556 38.2444444,76.6905556 L47.4666667,76.6905556 C47.9125,76.6827778 48.3544444,76.6077778 48.7777778,76.4683333 L67.0444444,85.3572222 C68.1466667,85.8972222 69.4777778,85.4416667 70.0177778,84.3394444 C70.1675,84.0336111 70.245,83.6977778 70.2444444,83.3572222 L70.2444444,46.8461111 C70.2469444,45.6188889 69.2538889,44.6219444 68.0266667,44.6194444 Z M47.5333333,72.2016667 L38.2444444,72.2016667 C35.0397222,72.6788889 32.055,70.4677778 31.5777778,67.2630556 C31.4925,66.6902778 31.4925,66.1080556 31.5777778,65.535 L31.6444444,64.5794444 C31.1672222,61.3747222 33.3783333,58.39 36.5830556,57.9127778 C37.1558333,57.8275 37.7380556,57.8275 38.3111111,57.9127778 L47.5333333,57.9127778 L47.5333333,72.2016667 Z M65.8,79.7794444 L51.9111111,73.1127778 L51.9111111,57.135 L65.8,50.4683333 L65.8,79.7794444 Z" id="Shape"></path>
                                                <path d="M94.6166667,45.885 C94.4216667,45.6838889 94.2233333,45.4855556 94.0222222,45.2905556 L94.0222222,45.2683333 C93.305,44.2722222 91.9163889,44.0461111 90.9202778,44.7633333 C89.9241667,45.4802778 89.6980556,46.8691667 90.4152778,47.8652778 C90.5583333,48.0641667 90.7333333,48.2377778 90.9333333,48.3797222 C100.138056,57.2575 100.403056,71.9163889 91.525,81.1213889 C91.3313889,81.3222222 91.1341667,81.5194444 90.9333333,81.7130556 C90.0497222,82.5661111 90.0247222,83.9738889 90.8777778,84.8575 C91.7308333,85.7411111 93.1386111,85.7661111 94.0222222,84.9130556 C104.963611,74.2997222 105.229722,56.8263889 94.6166667,45.885 Z" id="Path"></path>
                                                <path d="M105.57,40.3052778 C105.302778,40.0294444 105.031389,39.7577778 104.755556,39.4908333 C103.871944,38.6377778 102.464167,38.6627778 101.611111,39.5463889 C100.758056,40.43 100.783056,41.8377778 101.666667,42.6908333 C114.068611,54.7066667 114.381389,74.5011111 102.365556,86.9030556 C102.136389,87.1397222 101.903333,87.3727778 101.666667,87.6019444 C100.783056,88.455 100.758056,89.8627778 101.611111,90.7463889 C102.464167,91.63 103.871944,91.655 104.755556,90.8019444 C118.924722,77.0825 119.289444,54.4744444 105.57,40.3052778 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                کتاب صوتی
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->radio_music){ ?><a class="nav-item nav-link radio-music" href="/music-sharing">
                                <svg width="25px" height="25px" viewBox="0 0 123 78" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>voice-recorder</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-624.000000, -672.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="voice-recorder" transform="translate(685.000000, 710.500000) rotate(-90.000000) translate(-685.000000, -710.500000) translate(646.000000, 649.000000)">
                                                <path d="M54.7878788,13.1666667 L13.8787879,13.1666667 C12.7491145,13.1666667 11.8333333,14.0752653 11.8333333,15.1960784 L11.8333333,80.1372549 C11.8333333,81.2580681 12.7491145,82.1666667 13.8787879,82.1666667 L54.7878788,82.1666667 C55.9175521,82.1666667 56.8333333,81.2580681 56.8333333,80.1372549 L56.8333333,15.1960784 C56.8333333,14.0752653 55.9175521,13.1666667 54.7878788,13.1666667 Z M52.7424242,78.1078431 L15.9242424,78.1078431 L15.9242424,17.2254902 L52.7424242,17.2254902 L52.7424242,78.1078431 Z" id="Shape"></path>
                                                <path d="M34.3333333,74.6666667 C39.8534396,74.6599273 44.3267211,70.1006212 44.3333333,64.474359 L44.3333333,31.8589744 C44.3333333,26.2299183 39.8561808,21.6666667 34.3333333,21.6666667 C28.8104858,21.6666667 24.3333333,26.2299183 24.3333333,31.8589744 L24.3333333,42.0512821 C24.3333333,43.1770933 25.2287638,44.0897436 26.3333333,44.0897436 C27.4379028,44.0897436 28.3333333,43.1770933 28.3333333,42.0512821 L28.3333333,39.9598205 C31.8657273,42.7476908 36.8009394,42.7476908 40.3333333,39.9598205 L40.3333333,56.3735128 C36.8009394,53.5856425 31.8657273,53.5856425 28.3333333,56.3735128 L28.3333333,50.2051282 C28.3333333,49.079317 27.4379028,48.1666667 26.3333333,48.1666667 C25.2287638,48.1666667 24.3333333,49.079317 24.3333333,50.2051282 L24.3333333,64.474359 C24.3399456,70.1006212 28.813227,74.6599273 34.3333333,74.6666667 Z M34.3333333,37.974359 C31.0196248,37.974359 28.3333333,35.236408 28.3333333,31.8589744 C28.3333333,28.4815407 31.0196248,25.7435897 34.3333333,25.7435897 C37.6470418,25.7435897 40.3333333,28.4815407 40.3333333,31.8589744 C40.3333333,35.236408 37.6470418,37.974359 34.3333333,37.974359 Z M34.3333333,58.3589744 C37.6470418,58.3589744 40.3333333,61.0969253 40.3333333,64.474359 C40.3333333,67.8517926 37.6470418,70.5897436 34.3333333,70.5897436 C31.0196248,70.5897436 28.3333333,67.8517926 28.3333333,64.474359 C28.3333333,61.0969253 31.0196248,58.3589744 34.3333333,58.3589744 Z" id="Shape"></path>
                                                <path d="M71.254386,16.9333333 L69.2280702,16.9333333 L69.2280702,10.8333333 C69.2213709,5.22122526 64.6892305,0.673389103 59.0964912,0.666666667 L10.4649123,0.666666667 C4.872173,0.673389103 0.340032569,5.22122526 0.333333333,10.8333333 L0.333333333,112.5 C0.340032569,118.112108 4.872173,122.659944 10.4649123,122.666667 L59.0964912,122.666667 C64.6892305,122.659944 69.2213709,118.112108 69.2280702,112.5 L69.2280702,53.5333333 L71.254386,53.5333333 C74.6116959,53.5333333 77.3333333,50.8022703 77.3333333,47.4333333 L77.3333333,23.0333333 C77.3333333,19.6643964 74.6116959,16.9333333 71.254386,16.9333333 L71.254386,16.9333333 Z M16.5438596,118.6 L16.5438596,96.2333333 C16.5438596,92.8643964 19.2654971,90.1333333 22.622807,90.1333333 L46.9385965,90.1333333 C50.2959064,90.1333333 53.0175439,92.8643964 53.0175439,96.2333333 L53.0175439,118.6 L16.5438596,118.6 Z M65.1754386,112.5 C65.1754386,115.868937 62.4538012,118.6 59.0964912,118.6 L57.0701754,118.6 L57.0701754,96.2333333 C57.0634762,90.6212253 52.5313358,86.0733891 46.9385965,86.0666667 L22.622807,86.0666667 C17.0300677,86.0733891 12.4979273,90.6212253 12.4912281,96.2333333 L12.4912281,118.6 L10.4649123,118.6 C7.10760235,118.6 4.38596491,115.868937 4.38596491,112.5 L4.38596491,10.8333333 C4.38596491,7.46439636 7.10760235,4.73333333 10.4649123,4.73333333 L59.0964912,4.73333333 C62.4538012,4.73333333 65.1754386,7.46439636 65.1754386,10.8333333 L65.1754386,112.5 Z M73.2807018,47.4333333 C73.2807018,48.5563123 72.3734893,49.4666667 71.254386,49.4666667 L69.2280702,49.4666667 L69.2280702,45.4 L73.2807018,45.4 L73.2807018,47.4333333 Z M73.2807018,41.3333333 L69.2280702,41.3333333 L69.2280702,37.2666667 L73.2807018,37.2666667 L73.2807018,41.3333333 Z M73.2807018,33.2 L69.2280702,33.2 L69.2280702,29.1333333 L73.2807018,29.1333333 L73.2807018,33.2 Z M73.2807018,25.0666667 L69.2280702,25.0666667 L69.2280702,21 L71.254386,21 C72.3734893,21 73.2807018,21.9103543 73.2807018,23.0333333 L73.2807018,25.0666667 Z" id="Shape"></path>
                                                <circle id="Oval" cx="21.8333333" cy="96.1666667" r="2"></circle>
                                                <circle id="Oval" cx="21.8333333" cy="105.166667" r="2"></circle>
                                                <circle id="Oval" cx="21.8333333" cy="113.166667" r="2"></circle>
                                                <circle id="Oval" cx="30.8333333" cy="96.1666667" r="2"></circle>
                                                <circle id="Oval" cx="30.8333333" cy="105.166667" r="2"></circle>
                                                <circle id="Oval" cx="30.8333333" cy="113.166667" r="2"></circle>
                                                <circle id="Oval" cx="38.8333333" cy="96.1666667" r="2"></circle>
                                                <circle id="Oval" cx="38.8333333" cy="105.166667" r="2"></circle>
                                                <circle id="Oval" cx="38.8333333" cy="113.166667" r="2"></circle>
                                                <circle id="Oval" cx="46.8333333" cy="96.1666667" r="2"></circle>
                                                <circle id="Oval" cx="46.8333333" cy="105.166667" r="2"></circle>
                                                <circle id="Oval" cx="46.8333333" cy="113.166667" r="2"></circle>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <!--                                رادیو یوزیت-->
                                پادکست
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->articles){ ?><a class="nav-item nav-link articles" href="/articles">
                                <svg width="25px" height="25px" viewBox="0 0 110 110" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>writing</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-638.000000, -299.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="writing" transform="translate(638.000000, 299.000000)">
                                                <path d="M6.03166667,6.03166667 C5.86593912,6.20694632 5.73525892,6.41230092 5.64666667,6.63666667 C5.45099768,7.08050243 5.45099768,7.58616424 5.64666667,8.03 C5.73081001,8.25660478 5.86202658,8.46280224 6.03166667,8.635 C6.75461619,9.34402395 7.91205047,9.34402395 8.635,8.635 C9.34402395,7.91205047 9.34402395,6.75461619 8.635,6.03166667 C7.89979703,5.35329958 6.76686963,5.35329958 6.03166667,6.03166667 L6.03166667,6.03166667 Z" id="Path"></path>
                                                <path d="M12.1366667,5.64666667 C11.9100619,5.73081001 11.7038644,5.86202658 11.5316667,6.03166667 C10.8226427,6.75461619 10.8226427,7.91205047 11.5316667,8.635 C12.0561658,9.16384324 12.8484891,9.32279674 13.5363828,9.03718008 C14.2242764,8.75156341 14.6709981,8.07815263 14.6666667,7.33333333 C14.6595526,6.84800076 14.4697026,6.38319547 14.135,6.03166667 C13.6038309,5.51907008 12.8202604,5.36810694 12.1366667,5.64666667 Z" id="Path"></path>
                                                <path d="M17.6366667,5.64666667 C17.4123009,5.73525892 17.2069463,5.86593912 17.0316667,6.03166667 C16.3226427,6.75461619 16.3226427,7.91205047 17.0316667,8.635 C17.2099779,8.79679655 17.414578,8.92699657 17.6366667,9.02 C17.8570742,9.11394179 18.0937584,9.16377002 18.3333333,9.16666667 C18.8210648,9.17161864 19.290263,8.98005501 19.635159,8.63515902 C19.980055,8.29026302 20.1716186,7.82106479 20.1666667,7.33333333 C20.1595526,6.84800076 19.9697026,6.38319547 19.635,6.03166667 C19.0994302,5.52760949 18.3211734,5.3776701 17.6366667,5.64666667 Z" id="Path"></path>
                                                <rect id="Rectangle" x="23.8333333" y="31.1666667" width="25.6666667" height="3.66666667"></rect>
                                                <rect id="Rectangle" x="23.8333333" y="42.1666667" width="25.6666667" height="3.66666667"></rect>
                                                <rect id="Rectangle" x="23.8333333" y="53.1666667" width="34.8333333" height="3.66666667"></rect>
                                                <rect id="Rectangle" x="23.8333333" y="64.1666667" width="23.8333333" height="3.66666667"></rect>
                                                <rect id="Rectangle" x="23.8333333" y="75.1666667" width="14.6666667" height="3.66666667"></rect>
                                                <rect id="Rectangle" x="23.8333333" y="86.1666667" width="11" height="3.66666667"></rect>
                                                <path d="M102.980167,22 C101.117413,21.9946065 99.3300821,22.7354212 98.0173333,24.057 L91.6666667,30.4076667 L91.6666667,5.5 C91.6666667,2.46243388 89.2042328,0 86.1666667,0 L5.5,0 C2.46243388,0 8.14163551e-16,2.46243388 0,5.5 L0,104.5 C8.14163551e-16,107.537566 2.46243388,110 5.5,110 L86.1666667,110 C89.2042328,110 91.6666667,107.537566 91.6666667,104.5 L91.6666667,50.259 L107.943,33.9826667 C109.951748,31.9756926 110.553153,28.9560207 109.466609,26.3325811 C108.380065,23.7091415 105.819711,21.998968 102.980167,22 L102.980167,22 Z M3.66666667,5.5 C3.66666667,4.48747796 4.48747796,3.66666667 5.5,3.66666667 L86.1666667,3.66666667 C87.1791887,3.66666667 88,4.48747796 88,5.5 L88,11 L3.66666667,11 L3.66666667,5.5 Z M88,104.5 C88,105.512522 87.1791887,106.333333 86.1666667,106.333333 L5.5,106.333333 C4.48747796,106.333333 3.66666667,105.512522 3.66666667,104.5 L3.66666667,14.6666667 L88,14.6666667 L88,34.0743333 L77,45.0743333 L77,39.5743333 L57.5923333,20.1666667 L14.6666667,20.1666667 L14.6666667,100.833333 L77,100.833333 L77,64.9256667 L88,53.9256667 L88,104.5 Z M44.231,77.8433333 L39.2681667,92.7318333 L54.1566667,87.769 L59.4256667,82.5 L73.3333333,68.5923333 L73.3333333,97.1666667 L18.3333333,97.1666667 L18.3333333,23.8333333 L55,23.8333333 L55,42.1666667 L73.3333333,42.1666667 L73.3333333,48.741 L49.5,72.5743333 L44.231,77.8433333 Z M49.5,77.759 L54.241,82.5 L52.1766667,84.5643333 L45.0651667,86.9348333 L47.4356667,79.8233333 L49.5,77.759 Z M58.6666667,26.4256667 L70.741,38.5 L58.6666667,38.5 L58.6666667,26.4256667 Z M56.8333333,79.9076667 L52.0923333,75.1666667 L95.3333333,31.9256667 L100.074333,36.6666667 L56.8333333,79.9076667 Z M105.350667,31.3903333 L102.666667,34.0743333 L97.9256667,29.3333333 L100.609667,26.6493333 C101.45025,25.7718433 102.699784,25.4177798 103.875742,25.7238702 C105.0517,26.0299606 105.970039,26.9483005 106.27613,28.1242584 C106.58222,29.3002164 106.228157,30.5497505 105.350667,31.3903333 L105.350667,31.3903333 Z" id="Shape"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                مقالات
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->tv){ ?><a class="nav-item nav-link tv" href="/tv">
                                <svg width="25px" height="25px" viewBox="0 0 111 111" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>television</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-261.000000, -643.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="television" transform="translate(261.000000, 643.000000)">
                                                <path d="M103.599711,18.0846172 L68.9983516,18.0846172 C68.7298125,16.3620937 68.1438828,14.7445 67.3067578,13.2884922 L96.1916172,4.23736719 C97.3342812,3.87950781 97.9702187,2.66284375 97.6123594,1.52046875 C97.2542109,0.377804687 96.038125,-0.259 94.8954609,0.100015625 L64.3343281,9.67636719 C61.8718047,7.53441406 58.6594531,6.23305469 55.1470547,6.23305469 C51.6346562,6.23305469 48.4225937,7.53441406 45.9600703,9.67636719 L15.3986484,0.100015625 C14.2556953,-0.258132813 13.0396094,0.37809375 12.68175,1.52046875 C12.3236016,2.66313281 12.9595391,3.87950781 14.1022031,4.23736719 L42.9870625,13.2884922 C42.1499375,14.7445 41.5640078,16.3620937 41.2954687,18.0846172 L6.69584375,18.0846172 C3.09846094,18.0846172 0.171703125,21.011375 0.171703125,24.6084687 L0.171703125,97.9901641 C0.171703125,101.587547 3.09846094,104.514305 6.69584375,104.514305 L7.74138281,104.514305 L7.74138281,108.705711 C7.74138281,109.903008 8.71205469,110.87368 9.90935156,110.87368 C11.1066484,110.87368 12.0773203,109.903008 12.0773203,108.705711 L12.0773203,104.514305 L98.2179453,104.514305 L98.2179453,108.705711 C98.2179453,109.903008 99.1883281,110.87368 100.385914,110.87368 C101.5835,110.87368 102.553883,109.903008 102.553883,108.705711 L102.553883,104.514305 L103.599422,104.514305 C107.196516,104.514305 110.123273,101.587547 110.123273,97.9901641 L110.123273,24.6084687 C110.123852,21.011375 107.197094,18.0846172 103.599711,18.0846172 Z M55.1470547,10.5689922 C59.741125,10.5689922 63.5954844,13.7856797 64.5832109,18.0846172 L45.7111875,18.0846172 C46.698625,13.7859687 50.5526953,10.5689922 55.1470547,10.5689922 Z M105.787914,97.9901641 C105.787914,99.1967109 104.806258,100.178367 103.6,100.178367 L6.69584375,100.178367 C5.48929687,100.178367 4.50764062,99.1967109 4.50764062,97.9901641 L4.50764062,24.6084687 C4.50764062,23.4019219 5.48929687,22.4205547 6.69584375,22.4205547 L103.599711,22.4205547 C104.806258,22.4205547 105.787625,23.4022109 105.787625,24.6084687 L105.787625,97.9901641 L105.787914,97.9901641 Z" id="Shape"></path>
                                                <path d="M100.007531,29.9720234 C99.8407422,28.9981719 99.0368594,28.2601953 98.0523125,28.1775234 C69.5822578,25.7826406 40.7124297,25.7826406 12.2429531,28.1775234 C11.2584063,28.2604844 10.4545234,28.9981719 10.2877344,29.9720234 C6.84442187,50.0881719 6.84442187,70.4876016 10.2877344,90.6034609 C10.4545234,91.5773125 11.2584062,92.315 12.2429531,92.3979609 C26.4775469,93.5955469 40.8124453,94.1941953 55.1476328,94.1941953 C69.4825313,94.1941953 83.8174297,93.5955469 98.0523125,92.3979609 C99.0365703,92.315 99.8407422,91.5773125 100.007531,90.6034609 C103.450844,70.4873125 103.450844,50.0881719 100.007531,29.9720234 Z M96.0092188,88.2158047 C68.8859063,90.4057422 41.4093594,90.4057422 14.286625,88.2158047 C11.2936719,69.675625 11.2936719,50.8998594 14.286625,32.3596797 C41.4096484,30.1697422 68.8859063,30.1697422 96.0092188,32.3596797 C99.0018828,50.8998594 99.0018828,69.675625 96.0092188,88.2158047 Z" id="Shape"></path>
                                                <path d="M92.5271719,48.7616641 C91.3316094,48.8278594 90.4161484,49.8508516 90.4826328,51.0464141 C90.9037969,58.6513594 90.8014688,66.3603672 90.1779609,73.9592422 C90.0799688,75.1524922 90.9679688,76.1994766 92.1612188,76.2971797 C92.2216328,76.3020938 92.2814688,76.3044063 92.3407266,76.3044063 C93.4576641,76.3044063 94.4063672,75.4464688 94.4991563,74.3136328 C95.1388516,66.5176172 95.2440703,58.6085781 94.8116328,50.8062031 C94.7454375,49.6109297 93.7276484,48.6972031 92.5271719,48.7616641 Z" id="Path"></path>
                                                <path d="M92.116125,46.1465156 C92.1857891,46.1465156 92.2563203,46.1430469 92.3271406,46.1363984 C93.5189453,46.0210625 94.3919141,44.9616484 94.2762891,43.7695547 C94.0615156,41.5469531 93.7990469,39.2942891 93.4963984,37.0734219 C93.3345234,35.8868203 92.2375313,35.0566328 91.0555547,35.2182188 C89.8692422,35.3800938 89.0381875,36.47275 89.2000625,37.6590625 C89.4951953,39.8238516 89.7510156,42.0201484 89.960875,44.1869609 C90.0692734,45.3079453 91.0124844,46.1465156 92.116125,46.1465156 Z" id="Path"></path>
                                                <path d="M103.089805,94.1858125 C101.426539,93.0908437 99.2305312,94.7072812 99.812125,96.6171172 C100.076039,97.4834375 100.873562,98.1060781 101.778039,98.15175 C102.722984,98.1994453 103.605203,97.6051328 103.926062,96.7174219 C104.257617,95.7993594 103.906117,94.7231797 103.089805,94.1858125 Z" id="Path"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                تلویزیون اختصاصی یوزیت
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->video_share){ ?><a class="nav-item nav-link video-share" href="/video-sharing">
                                <svg width="25px" height="25px" viewBox="0 0 111 89" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <!-- Generator: Sketch 55 (78076) - https://sketchapp.com -->
                                    <title>share-video</title>
                                    <desc>Created with Sketch.</desc>
                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">
                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-261.000000, -307.000000)" fill="#A6A6A6" fill-rule="nonzero">
                                            <g id="share-video" transform="translate(261.000000, 307.000000)">
                                                <path d="M41.2769393,48.2122897 C40.9119416,48.2122897 40.5460969,48.1214018 40.2158203,47.9387921 C39.5315552,47.5602315 39.107277,46.8464695 39.107277,46.0726714 L39.107277,22.4985127 C39.107277,21.7238807 39.5315552,21.0109526 40.2158203,20.6323918 C40.9000854,20.253831 41.7384797,20.2688401 42.408348,20.6732496 L61.96241,32.460329 C62.6068726,32.8488958 63.0006637,33.5401442 63.0006637,34.2855921 C63.0006637,35.0302059 62.6068726,35.7222881 61.96241,36.1100209 L42.408348,47.8979342 C42.0619812,48.1072267 41.6690369,48.2122897 41.2769393,48.2122897 L41.2769393,48.2122897 Z M43.4466019,26.3133037 L43.4466019,42.2578802 L56.6712113,34.2855921 L43.4466019,26.3133037 Z" id="Shape"></path>
                                                <path d="M106.659828,45.943427 L102.107094,45.943427 L102.107094,6.41635372 C102.107094,2.87756165 99.187111,0 95.5989534,0 L6.50814051,0 C2.91998291,0 0,2.87756165 0,6.41635372 L0,62.1539964 C0,65.6927885 2.91998291,68.5720177 6.50814051,68.5720177 L67.5542449,68.5720177 L67.5542449,84.5199294 C67.5542449,86.8788462 69.5003357,88.8 71.8918762,88.8 L106.659828,88.8 C109.053062,88.8 111,86.8788462 111,84.5199294 L111,50.2234976 C111,47.8620793 109.053062,45.943427 106.659828,45.943427 Z M6.50814051,64.2944487 C5.31406408,64.2944487 4.34017176,63.3338717 4.34017176,62.1539964 L4.34017176,6.41635372 C4.34017176,5.23814593 5.31406408,4.277569 6.50814051,4.277569 L95.5989534,4.277569 C96.7955704,4.277569 97.7686157,5.23814593 97.7686157,6.41635372 L97.7686157,45.943427 L71.8918762,45.943427 C69.5003357,45.943427 67.5542449,47.8620793 67.5542449,50.2234976 L67.5542449,64.2944487 L6.50814051,64.2944487 Z M106.659828,84.5199294 L71.8918762,84.5199294 L71.8918762,50.2234976 L106.659828,50.2234976 L106.659828,84.5199294 Z" id="Shape"></path>
                                                <path d="M30.5226287,59.7850737 L10.9685669,59.7850737 C9.77025599,59.7850737 8.79890453,58.8278321 8.79890453,57.6462892 C8.79890453,56.464746 9.77025599,55.5066707 10.9685669,55.5066707 L30.5226287,55.5066707 C31.7209396,55.5066707 32.6922913,56.464746 32.6922913,57.6462892 C32.6922913,58.8278321 31.7209396,59.7850737 30.5226287,59.7850737 L30.5226287,59.7850737 Z" id="Path"></path>
                                                <path d="M38.130844,59.7850737 C36.9325333,59.7850737 35.9603348,58.8278321 35.9603348,57.6462892 C35.9603348,56.464746 36.9308395,55.5066707 38.1283036,55.5066707 L38.130844,55.5066707 C39.3291549,55.5066707 40.3005066,56.464746 40.3005066,57.6462892 C40.3005066,58.8278321 39.3291549,59.7850737 38.130844,59.7850737 L38.130844,59.7850737 Z" id="Path"></path>
                                                <path d="M97.983719,69.5350962 C96.43396,69.5350962 94.9163818,70.0920974 93.7426301,71.0868614 C93.415741,71.3645281 93.5478516,71.330341 93.2395935,71.225278 C92.9804534,71.1368915 92.7247009,70.9717924 92.4791106,70.8508863 C90.9132614,70.0804238 89.3474121,69.3091271 87.7815629,68.5386643 C87.6104965,68.4544471 87.2700577,68.3627253 87.1396408,68.2226411 C86.9236907,67.9908353 87.0871354,67.5272236 87.0752792,67.1761794 C87.0634232,66.8184646 86.9482499,66.7033954 87.1701278,66.5066106 C87.3555908,66.3415113 87.7019576,66.243119 87.9246826,66.1330529 C89.4812165,65.3634239 91.0385971,64.5929612 92.5959778,63.8224984 C92.7916031,63.7257737 93.0244905,63.5573393 93.2370529,63.5056415 C93.2836304,63.4939679 93.3496858,63.4230921 93.3988037,63.4255934 C93.5410767,63.4322641 93.8569564,63.7966497 93.989067,63.8958758 C95.1848373,64.7955829 96.6787033,65.2600285 98.1818848,65.202494 C100.779213,65.1032677 103.120789,63.4255934 104.027779,61.0283204 C104.958481,58.5676758 104.230179,55.7268029 102.238358,53.986591 C100.245689,52.2455453 97.2875977,51.8803261 94.9248504,53.0977239 C93.7934417,53.67974 92.847496,54.5911209 92.2301331,55.690948 C91.9421997,56.2020882 91.7270964,56.752419 91.5907516,57.3210937 C91.5153809,57.6396185 91.4645691,57.9648137 91.4383163,58.2908429 C91.4256135,58.4567757 91.4188386,58.6243766 91.4188386,58.7911434 C91.4188386,58.9337291 91.5399398,59.4898963 91.4730378,59.5916242 C91.316368,59.8275993 90.5753631,60.0360577 90.3280793,60.1577974 C89.5362626,60.5496995 88.7444458,60.9416017 87.9517822,61.3335036 C87.2200928,61.6953877 86.4884033,62.0572716 85.7558671,62.4191555 C85.5958098,62.4983698 85.3400574,62.6951547 85.1605225,62.7143329 C84.9022294,62.7418494 84.4474641,62.2198693 84.2035676,62.0572716 C81.8204956,60.4671499 78.5710831,60.6472581 76.3777084,62.4716871 C74.3435441,64.1635368 73.5449524,66.9843975 74.4129867,69.4658879 C75.2666244,71.909022 77.6073532,73.6550706 80.2300872,73.789318 C81.7637557,73.8676983 83.2982711,73.4007512 84.5169067,72.4785305 C84.7624968,72.2925857 84.9276351,72.0224234 85.1986312,72.0499399 C85.4137345,72.0707857 85.7236862,72.3075946 85.9150773,72.4026518 C87.4572144,73.1614408 88.9985046,73.9193959 90.5406417,74.6790189 C90.7870789,74.7999248 91.0724716,74.9016526 91.3011246,75.0525766 C91.5822831,75.2385216 91.5179215,75.1067757 91.4874344,75.5545448 C91.4002075,76.870335 91.7431869,78.202802 92.4469299,79.3234751 C93.8434066,81.5439753 96.5389709,82.7355244 99.1515426,82.2669095 C101.726005,81.8049655 103.821991,79.7845853 104.354668,77.2630709 C104.89666,74.7006987 103.76017,72.0107496 101.537155,70.5773887 C100.485352,69.8994816 99.2413102,69.5350962 97.983719,69.5350962 L97.983719,69.5350962 Z M96.8912659,56.9091796 C97.803337,56.5497972 98.7755355,56.5673077 99.4987565,57.3169246 C100.204193,58.0465296 100.289726,59.1963867 99.7028503,60.0177132 C98.9381332,61.0875225 97.3358688,61.2301082 96.3941574,60.3020508 C95.3838501,59.305619 95.6387557,57.5870869 96.8912659,56.9091796 C96.9124374,56.9000076 96.2163163,57.2735653 96.8912659,56.9091796 L96.8912659,56.9091796 Z M80.5705261,69.5184195 C77.7775725,69.5184195 77.7513199,65.2500225 80.5705261,65.2400165 C81.7197189,65.2366811 82.6953048,66.1680738 82.7376481,67.2954176 C82.7816849,68.490302 81.7883147,69.5184195 80.5705261,69.5184195 L80.5705261,69.5184195 Z M100.153381,75.9606219 C100.153381,78.1702825 97.0005111,78.8848783 96.0257722,76.8695012 C95.3821565,75.5403695 96.3221741,73.934405 97.8143464,73.8193358 C99.0575408,73.7234451 100.16185,74.7323843 100.153381,75.9606219 Z" id="Shape"></path>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                اشتراک گذاری ویدیو
                            </a><?php } ?>
                            <?php if($uoozet->get_video->navbarAvailable->video_share and IS_LOGGED){ ?>
                                <!--                            <a class="nav-item nav-link video-share" href="/video-sharing">-->
                                <!--                                <svg width="25px" height="25px" viewBox="0 0 111 89" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">-->
                                <!--                                    &lt;!&ndash; Generator: Sketch 55 (78076) - https://sketchapp.com &ndash;&gt;-->
                                <!--                                    <title>share-video</title>-->
                                <!--                                    <desc>Created with Sketch.</desc>-->
                                <!--                                    <g id="Web" stroke="currentColor" stroke-width="0.5" fill="currentColor" fill-rule="evenodd">-->
                                <!--                                        <g id="Desktop-HD-Copy" stroke="currentColor" stroke-width="1" transform="translate(-261.000000, -307.000000)" fill="#A6A6A6" fill-rule="nonzero">-->
                                <!--                                            <g id="share-video" transform="translate(261.000000, 307.000000)">-->
                                <!--                                                <path d="M41.2769393,48.2122897 C40.9119416,48.2122897 40.5460969,48.1214018 40.2158203,47.9387921 C39.5315552,47.5602315 39.107277,46.8464695 39.107277,46.0726714 L39.107277,22.4985127 C39.107277,21.7238807 39.5315552,21.0109526 40.2158203,20.6323918 C40.9000854,20.253831 41.7384797,20.2688401 42.408348,20.6732496 L61.96241,32.460329 C62.6068726,32.8488958 63.0006637,33.5401442 63.0006637,34.2855921 C63.0006637,35.0302059 62.6068726,35.7222881 61.96241,36.1100209 L42.408348,47.8979342 C42.0619812,48.1072267 41.6690369,48.2122897 41.2769393,48.2122897 L41.2769393,48.2122897 Z M43.4466019,26.3133037 L43.4466019,42.2578802 L56.6712113,34.2855921 L43.4466019,26.3133037 Z" id="Shape"></path>-->
                                <!--                                                <path d="M106.659828,45.943427 L102.107094,45.943427 L102.107094,6.41635372 C102.107094,2.87756165 99.187111,0 95.5989534,0 L6.50814051,0 C2.91998291,0 0,2.87756165 0,6.41635372 L0,62.1539964 C0,65.6927885 2.91998291,68.5720177 6.50814051,68.5720177 L67.5542449,68.5720177 L67.5542449,84.5199294 C67.5542449,86.8788462 69.5003357,88.8 71.8918762,88.8 L106.659828,88.8 C109.053062,88.8 111,86.8788462 111,84.5199294 L111,50.2234976 C111,47.8620793 109.053062,45.943427 106.659828,45.943427 Z M6.50814051,64.2944487 C5.31406408,64.2944487 4.34017176,63.3338717 4.34017176,62.1539964 L4.34017176,6.41635372 C4.34017176,5.23814593 5.31406408,4.277569 6.50814051,4.277569 L95.5989534,4.277569 C96.7955704,4.277569 97.7686157,5.23814593 97.7686157,6.41635372 L97.7686157,45.943427 L71.8918762,45.943427 C69.5003357,45.943427 67.5542449,47.8620793 67.5542449,50.2234976 L67.5542449,64.2944487 L6.50814051,64.2944487 Z M106.659828,84.5199294 L71.8918762,84.5199294 L71.8918762,50.2234976 L106.659828,50.2234976 L106.659828,84.5199294 Z" id="Shape"></path>-->
                                <!--                                                <path d="M30.5226287,59.7850737 L10.9685669,59.7850737 C9.77025599,59.7850737 8.79890453,58.8278321 8.79890453,57.6462892 C8.79890453,56.464746 9.77025599,55.5066707 10.9685669,55.5066707 L30.5226287,55.5066707 C31.7209396,55.5066707 32.6922913,56.464746 32.6922913,57.6462892 C32.6922913,58.8278321 31.7209396,59.7850737 30.5226287,59.7850737 L30.5226287,59.7850737 Z" id="Path"></path>-->
                                <!--                                                <path d="M38.130844,59.7850737 C36.9325333,59.7850737 35.9603348,58.8278321 35.9603348,57.6462892 C35.9603348,56.464746 36.9308395,55.5066707 38.1283036,55.5066707 L38.130844,55.5066707 C39.3291549,55.5066707 40.3005066,56.464746 40.3005066,57.6462892 C40.3005066,58.8278321 39.3291549,59.7850737 38.130844,59.7850737 L38.130844,59.7850737 Z" id="Path"></path>-->
                                <!--                                                <path d="M97.983719,69.5350962 C96.43396,69.5350962 94.9163818,70.0920974 93.7426301,71.0868614 C93.415741,71.3645281 93.5478516,71.330341 93.2395935,71.225278 C92.9804534,71.1368915 92.7247009,70.9717924 92.4791106,70.8508863 C90.9132614,70.0804238 89.3474121,69.3091271 87.7815629,68.5386643 C87.6104965,68.4544471 87.2700577,68.3627253 87.1396408,68.2226411 C86.9236907,67.9908353 87.0871354,67.5272236 87.0752792,67.1761794 C87.0634232,66.8184646 86.9482499,66.7033954 87.1701278,66.5066106 C87.3555908,66.3415113 87.7019576,66.243119 87.9246826,66.1330529 C89.4812165,65.3634239 91.0385971,64.5929612 92.5959778,63.8224984 C92.7916031,63.7257737 93.0244905,63.5573393 93.2370529,63.5056415 C93.2836304,63.4939679 93.3496858,63.4230921 93.3988037,63.4255934 C93.5410767,63.4322641 93.8569564,63.7966497 93.989067,63.8958758 C95.1848373,64.7955829 96.6787033,65.2600285 98.1818848,65.202494 C100.779213,65.1032677 103.120789,63.4255934 104.027779,61.0283204 C104.958481,58.5676758 104.230179,55.7268029 102.238358,53.986591 C100.245689,52.2455453 97.2875977,51.8803261 94.9248504,53.0977239 C93.7934417,53.67974 92.847496,54.5911209 92.2301331,55.690948 C91.9421997,56.2020882 91.7270964,56.752419 91.5907516,57.3210937 C91.5153809,57.6396185 91.4645691,57.9648137 91.4383163,58.2908429 C91.4256135,58.4567757 91.4188386,58.6243766 91.4188386,58.7911434 C91.4188386,58.9337291 91.5399398,59.4898963 91.4730378,59.5916242 C91.316368,59.8275993 90.5753631,60.0360577 90.3280793,60.1577974 C89.5362626,60.5496995 88.7444458,60.9416017 87.9517822,61.3335036 C87.2200928,61.6953877 86.4884033,62.0572716 85.7558671,62.4191555 C85.5958098,62.4983698 85.3400574,62.6951547 85.1605225,62.7143329 C84.9022294,62.7418494 84.4474641,62.2198693 84.2035676,62.0572716 C81.8204956,60.4671499 78.5710831,60.6472581 76.3777084,62.4716871 C74.3435441,64.1635368 73.5449524,66.9843975 74.4129867,69.4658879 C75.2666244,71.909022 77.6073532,73.6550706 80.2300872,73.789318 C81.7637557,73.8676983 83.2982711,73.4007512 84.5169067,72.4785305 C84.7624968,72.2925857 84.9276351,72.0224234 85.1986312,72.0499399 C85.4137345,72.0707857 85.7236862,72.3075946 85.9150773,72.4026518 C87.4572144,73.1614408 88.9985046,73.9193959 90.5406417,74.6790189 C90.7870789,74.7999248 91.0724716,74.9016526 91.3011246,75.0525766 C91.5822831,75.2385216 91.5179215,75.1067757 91.4874344,75.5545448 C91.4002075,76.870335 91.7431869,78.202802 92.4469299,79.3234751 C93.8434066,81.5439753 96.5389709,82.7355244 99.1515426,82.2669095 C101.726005,81.8049655 103.821991,79.7845853 104.354668,77.2630709 C104.89666,74.7006987 103.76017,72.0107496 101.537155,70.5773887 C100.485352,69.8994816 99.2413102,69.5350962 97.983719,69.5350962 L97.983719,69.5350962 Z M96.8912659,56.9091796 C97.803337,56.5497972 98.7755355,56.5673077 99.4987565,57.3169246 C100.204193,58.0465296 100.289726,59.1963867 99.7028503,60.0177132 C98.9381332,61.0875225 97.3358688,61.2301082 96.3941574,60.3020508 C95.3838501,59.305619 95.6387557,57.5870869 96.8912659,56.9091796 C96.9124374,56.9000076 96.2163163,57.2735653 96.8912659,56.9091796 L96.8912659,56.9091796 Z M80.5705261,69.5184195 C77.7775725,69.5184195 77.7513199,65.2500225 80.5705261,65.2400165 C81.7197189,65.2366811 82.6953048,66.1680738 82.7376481,67.2954176 C82.7816849,68.490302 81.7883147,69.5184195 80.5705261,69.5184195 L80.5705261,69.5184195 Z M100.153381,75.9606219 C100.153381,78.1702825 97.0005111,78.8848783 96.0257722,76.8695012 C95.3821565,75.5403695 96.3221741,73.934405 97.8143464,73.8193358 C99.0575408,73.7234451 100.16185,74.7323843 100.153381,75.9606219 Z" id="Shape"></path>-->
                                <!--                                            </g>-->
                                <!--                                        </g>-->
                                <!--                                    </g>-->
                                <!--                                </svg>-->
                                <!--                                -->
                                <!--                            </a>-->
                                <div class="dropdown-menu-wrapper">
                                    <a href="#" class="dropdown-toggle nav-item nav-link" data-toggle="dropdown" role="button" aria-expanded="false">
                                        کتابخانه
                                    </a>
                                    <ul class="dropdown-menu ani-acc-menu1 ani-acc-menu1-new" role="menu">
                                        <li>
                                            <a href="/subscriptions" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                فالوینگ ها
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/saved-videos">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                نشان شده ها
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/history">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                به تازگی تماشا شده
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/liked-videos">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                پسندیده شده
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/shared-media">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                                به اشتراک گذاشته شده
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<script>
    {
        let navbar = $(".navbar-nav");
        let i = 0;
        switch('{{PAGE}}'){
            case 'home':
                i = 0;
                break;
            case 'movie-sharing':
                i = 1;
                break;
            case 'audio_book':
                i = 2;
                break;
            case 'music-sharing':
                i = 3;
                break;
            case 'articles':
                i = 4;
                break;
            case 'tv':
                i = 5;
                break;
            case 'video-sharing':
                i = 6;
                break;
        }
        $(navbar).find("a.nav-item:eq("+i+")").addClass("active");
    }
</script>
