<?php

use app\components\MyHtmlHelper;

/* @var $this yii\web\View */
$this->title = 'Political Simulator';
?>
<?php if (Yii::$app->user->isGuest): ?>
    <header class="index-header" >
        <div class="container" >
            <div class="row">
                <div class="col-md-6">
                    <h1>Political Simulator</h1>
                    <p class="subtitle">Мультиплеерный реалистичный симулятор геополитики и бизнеса.</p>
                    <p>Следите за обновлениями в социальных сетях: <a href="https://plus.google.com/110425397057830817568" rel="publisher" target="_blank">Google+</a>, <a href="https://vk.com/politsim" target="_blank">VK</a></p>
                    <!--<p>Так же рекомендуется к прочтению: <a href="http://blog.politsim.net">официальный блог разработки</a>, <a href="http://wiki.politsim.net">вики по игре</a>.</p>-->
                </div>
                <div class="col-md-6">
                    <h3>Вход через соц. сети:</h3>
                    <?=yii\authclient\widgets\AuthChoice::widget([
                        'baseAuthUrl' => ['site/auth']
                    ]);?>
                </div>
            </div>
        </div>
    </header>
<?php else: ?>
    <nav class="navbar navbar-default navbar-inverse navbar-static-top show_on_load" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <a class="navbar-brand" href="#"><?=MyHtmlHelper::icon('lg-icons/globe','width:25px')?> Political Simulator</a>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
                <span class="sr-only">Toggle Side Navigation</span>
                <i class="icon-th-list"></i>
            </button>

            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
                <span class="sr-only">Toggle Top Navigation</span>
                <i class="icon-align-justify"></i>
            </button>

        </div>

        <div class="navbar navbar-collapse navbar-collapse-top" id="navbar" >
            <div class="navbar-right">            
                <form class="navbar-form navbar-left" role="search">
                    <div class="form-group">
                        <input type="text" class="search-query animated" placeholder="Search">
                        <i class="icon-search"></i>
                    </div>
                </form>

                <ul class="nav navbar-nav navbar-left">
                    <li>
                        <a href="#" onclick="if (fullScreenApi.isFullScreen()) {
                                        fullScreenApi.cancelFullScreen();
                                    } else {
                                        fullScreenApi.requestFullScreen(document.documentElement);
                                    }"><i class="icon-fullscreen icon-white" title="На весь экран"></i></a>
                    </li>
                    <li>
                        <a href="#" onclick="reload_page()"><i class="icon-refresh icon-white" title="Обновить"></i></a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle dropdown-avatar" data-toggle="dropdown">
                            <span>
                                <img class="menu-avatar profile-avatar" src="<?=Yii::$app->user->identity->photo?>" alt="" /> <span><span class="profile-name" ><?=Yii::$app->user->identity->name?></span> <i class="icon-caret-down"></i></span>
                                <!--<span class="badge badge-dark-red">5</span>-->
                            </span>
                        </a>
                        <ul class="dropdown-menu">

                            <!-- the first element is the one with the big avatar, add a with-image class to it -->

                            <li class="with-image">
                                <div class="avatar">
                                    <img class="profile-avatar-big" src="<?=Yii::$app->user->identity->photo_big?>" alt="" />
                                </div>
                                <span class="profile-name" ><?=Yii::$app->user->identity->name?></span>
                            </li>

                            <li class="divider"></li>

                            <li><?=MyHtmlHelper::a('<i class="icon-user"></i> <span>Профиль</span>', 'load_page("profile",{"id":'.Yii::$app->user->identity->id.'})')?></li>
                            <li><?=MyHtmlHelper::a('<i class="icon-cog"></i> <span>Настройки</span>', "load_modal('account-settings',{},'settings_modal','settings_modal_body')")?></li>                        
                            <!--                <li><a href="#"><i class="icon-envelope"></i> <span>Messages</span> <span class="label label-dark-red pull-right">5</span></a></li>-->
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="sidebar-background show_on_load">
        <div class="primary-sidebar-background"></div>
    </div>
    <div class="primary-sidebar show_on_load">
        <div class="sparkline-box side">
            <div class="sparkline-row">
                <h4 class="dark-green"><span>Счёт</span>  <strong id="head_money"></strong> <?=MyHtmlHelper::icon('money')?></h4> 
            </div>

            <hr class="divider">
        </div>
        <ul class="nav navbar-collapse collapse navbar-collapse-primary" id="topmenu" style="float: none;display:inline-block">
            <li class="dark-nav profile_page capital_page dealings_page ">
                <span class="glow"></span>
                <a href="#uLKT7nwqBc" class="accordion-toggle " data-toggle="collapse">
                    <?=MyHtmlHelper::icon('lg-icons/profile')?>
                    <span>
                        Профиль
                        <span id="profile_badge" class="badge badge-green"></span>                        
                    </span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="uLKT7nwqBc" class="collapse ">
                    <li>
                        <a onclick="load_page('profile', {'uid':<?= Yii::$app->user->identity->uid ?>})" href="#">
                            <i class="icon-user"></i> Мой профиль
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('dealings')">
                            <i class="icon-briefcase"></i> Мои сделки
                            <span id="new_dealings_count" class="badge badge-green"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('notifications')">
                            <i class="icon-comments"></i> Мои уведомления
                        </a>
                    </li>

                    <!--
                    <li class="nav-header">Nav header</li>
                    <li><a href="#">Separated link</a></li>
                    <li><a href="#">One more separated link</a></li>-->
                </ul>
            </li>
            <li class="dark-nav work_page">
                <span class="glow"></span>
                <a href="#" onclick="load_page('work')">
                    <?= MyHtmlHelper::icon('lg-icons/work') ?>
                    <span>Работа</span>
                </a>
            </li>
            <li class="dark-nav party-info_page">
                <span class="glow"></span>
                <a href="#" onclick="load_page('party-info')" >
                    <?= MyHtmlHelper::icon('lg-icons/party') ?>
                    <span>Партия</span>
                </a>
            </li>
            <li class="dark-nav map-politic_page map-resources_page map-population_page">
                <span class="glow"></span>
                <a href="#AsEWSFDSS" class="accordion-toggle" data-toggle="collapse">
                    <?= MyHtmlHelper::icon('lg-icons/globe') ?>
                    <span>Карта</span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="AsEWSFDSS" class="collapse">
                    <li>
                        <a href="#" onclick="load_page('map-politic')">
                            <i class="icon-flag"></i> Политическая карта
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-cores')">
                            <i class="icon-legal"></i> Карта претензий
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-resources')">
                            <i class="icon-money"></i> Экономическая карта
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-population')">
                            <i class="icon-group"></i> Демографическая карта
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dark-nav state-info_page elections_page org-info_page">
                <span class="glow"></span>
                <a href="#ASSSASDASDASDASDA" class="accordion-toggle" data-toggle="collapse">
                    <?= MyHtmlHelper::icon('lg-icons/goverment') ?>
                    <span>Государство</span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="ASSSASDASDASDASDA" class="collapse">
                    <li>
                        <a onclick="load_page('state-info')" href="#">
                            <i class="icon-flag"></i> О государстве
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('elections')" href="#">
                            <i class="icon-bullhorn"></i> Выборы
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dark-nav twitter_page newspapers_page radio_page tv_page">
                <span class="glow"></span>
                <a href="#SA123213asd" class="accordion-toggle" data-toggle="collapse">
                    <?= MyHtmlHelper::icon('lg-icons/news') ?>
                    <span>СМИ</span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="SA123213asd" class="collapse">
                    <li>
                        <a onclick="load_page('twitter')" href="#">
                            <i class="icon-twitter"></i> Соц. сети
                        </a>
                    </li>
                    <!--<li><a onclick="load_page('newspapers')" href="#">Пресса</a></li>
                    <li><a onclick="load_page('radio')" href="#">Радио</a></li>
                    <li><a onclick="load_page('tv')" href="#">Телевиденье</a></li>-->
                </ul>
            </li>
            <li class="dark-nav holding-info_page my-buisness_page market_page market-factories_page market-forex_page market-stocks_page market-resources_page factory-info_page">
                <span class="glow"></span>
                <a href="#aaasq2sdad" class="accordion-toggle" data-toggle="collapse">
                    <?= MyHtmlHelper::icon('lg-icons/business') ?>
                    <span>Бизнес</span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="aaasq2sdad" class="collapse">
                    <li>
                        <a onclick="load_page('my-buisness')" href="#">
                            <i class="icon-building"></i> Мой бизнес
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('market')" href="#">
                            <i class="icon-th-large"></i> Рынок
                        </a>
                    </li>
                </ul>
            </li>
            <li class="dark-nav chart-states_page chart-parties_page chart-peoples_page">
                <span class="glow"></span>
                <a href="#YUhu234hsa" class="accordion-toggle" data-toggle="collapse">
                    <?= MyHtmlHelper::icon('lg-icons/rating') ?>
                    <span>Рейтинг</span>
                    <i class="icon-caret-down"></i>
                </a>
                <ul id="YUhu234hsa" class="collapse">
                    <li>
                        <a onclick="load_page('chart-states')" href="#">
                            <i class="icon-th-list"></i> Рейтинг государств
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-parties')" href="#">
                            <i class="icon-list-ul"></i> Рейтинг партий
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-peoples')" href="#">
                            <i class="icon-list"></i> Рейтинг людей
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-holdings')" href="#">
                            <i class="icon-tasks"></i> Рейтинг компаний
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
		<!-- politsim resp -->
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="ca-pub-7725940874180553"
		     data-ad-slot="9864631062"
		     data-ad-format="auto"></ins>
<!--        <div class="hidden-sm hidden-xs">
            <div class="sparkline-box side">
                    <div class="sparkline-row">
                    <h4 class="gray"><span>Orders</span> 847</h4>
                    <div class="sparkline big" data-color="gray">15,5,24,12,20,6,4,27,21,22,15,23</div>
                </div>

                <hr class="divider">
                <div class="sparkline-row">
                    <h4 class="dark-green"><span>Счёт</span> 43 330 <i class="icon-money"></i></h4>
                    <div class="sparkline big" data-color="darkGreen">21,20,8,27,27,19,9,10,22,11,16,19</div>
                </div>

                <hr class="divider">
            </div>
        </div>-->
    </div>
    <div id="bigcontainer" class="main-content">
        <div style="display:none" class="alert alert-block alert-error" id="error_block">
            <!--<a class="close" data-dismiss="alert" href="#">&times;</a>-->
            <h4>Ошибка!</h4>
            <p id="error_text">Неизвестная ошибка</p><br>
            <p><small>Если вы не знаете причину этой ошибки и она повторяется, пожалуйста, сообщите о ней <a href="//vk.com/topic-56461826_28488767" target="_blank">здесь</a>.</small></p>
        </div>
        <div id="page_content" >

        </div>
        <hr class='show_on_load'>
    </div>
    <div style="display:none" class="modal" id="settings_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 id="myModalLabel">Настройки авторизации</h3>
        </div>
        <div id="settings_modal_body" class="modal-body">
            
        </div>
        <div class="modal-footer">
            <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        </div>
    </div>
    <div style="display:none;" class="modal fade" id="region_info" tabindex="-1" role="dialog" aria-labelledby="region_info_label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 id="region_info_label">Информация о регионе</h3>
                </div>
                <div id="region_info_body" class="modal-body">
                    <p>Загрузка…</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-red" data-dismiss="modal" aria-hidden="true">Закрыть</button>
                    <!--<button class="btn btn-green">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
<?php endif ?>