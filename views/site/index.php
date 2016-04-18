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
                    <p>Так же рекомендуется к прочтению: <a href="http://blog.politsim.net">официальный блог разработки</a>, <a href="http://wiki-politsim.lazzyteam.pw">вики по игре</a>.</p>
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
    <header class="main-header">

        <a class="logo" href="#"><?=MyHtmlHelper::icon('lg-icons/globe','width:25px')?> Political Simulator</a>
        <nav class="navbar navbar-static-top show_on_load" role="navigation">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu" id="navbar" >

                <ul class="nav navbar-nav">
                    <li>
                        <a href="#" onclick="if (fullScreenApi.isFullScreen()) {
                                        fullScreenApi.cancelFullScreen();
                                    } else {
                                        fullScreenApi.requestFullScreen(document.documentElement);
                                    }"><i class="fa fa-arrows-alt" title="На весь экран"></i></a>
                    </li>
                    <li>
                        <a href="#" onclick="reload_page()"><i class="fa fa-refresh" title="Обновить"></i></a>
                    </li>
                    <li class="dropdown user-menu" >
                        <a href="#" class="dropdown-toggle dropdown-avatar" data-toggle="dropdown">
                            <span>
                                <img class="menu-avatar profile-avatar user-image" src="<?=Yii::$app->user->identity->photo?>" alt="" /> <span><span class="profile-name" ><?=Yii::$app->user->identity->name?></span> <i class="icon-caret-down"></i></span>
                                <!--<span class="badge badge-dark-red">5</span>-->
                            </span>
                        </a>
                        <ul class="dropdown-menu">

                            <li class="user-header">
                                <img class="img-circle" src="<?=Yii::$app->user->identity->photo_big?>" alt="" />
                                <p>
                                    <span class="profile-name" ><?=Yii::$app->user->identity->name?></span>
                                </p>
                                <p>
                                    <small>
                                        <span class="profile-star star"><?= Yii::$app->user->identity->star ?> <?= MyHtmlHelper::icon('star') ?></span>
                                        <span class="profile-heart heart"><?= Yii::$app->user->identity->heart ?> <?= MyHtmlHelper::icon('heart') ?></span>
                                        <span class="profile-chart_pie chart_pie"><?= Yii::$app->user->identity->chart_pie ?> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                                    </small>
                                </p>
                            </li>


                            <li class="user-footer">
                                <div class="col-xs-6 text-center">
                                    <?=MyHtmlHelper::a('<i class="fa fa-user"></i> Профиль', 'load_page("profile",{"id":'.Yii::$app->user->identity->id.'})', ['class' => 'btn btn-default btn-flat'])?>
                                </div>
                                <div class="col-xs-6 text-center">
                                    <?=MyHtmlHelper::a('<i class="fa fa-cog"></i> Настройки', "load_modal('account-settings',{},'settings_modal','settings_modal_body')", ['class' => 'btn btn-default btn-flat'])?>
                                </div>
                            </li>
                            <!-- <li class="divider"></li> -->                 
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar show_on_load">
        <section class="sidebar" style="height:auto">
            <div class="user-panel">
                <div class="pull-left image">
                    <img src="<?=Yii::$app->user->identity->photo?>" class="img-circle" alt="">
                </div>
                <div class="pull-left info">
                    <p><?=Yii::$app->user->identity->name?></p>
                    <strong id="head_money"></strong> <?=MyHtmlHelper::icon('money')?>
                </div>
            </div>
            <form action="#" method="get" class="sidebar-form" style="display:none">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                    </span>
                </div>
            </form>
            <ul class="sidebar-menu" id="topmenu" >
            <li class="treeview profile_page capital_page dealings_page ">
                <a href="#" >
                    <?=MyHtmlHelper::icon('lg-icons/profile')?>
                    <span>
                        Профиль
                        <span id="profile_badge" class="badge badge-green"></span>                        
                    </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a onclick="load_page('profile', {'uid':<?= Yii::$app->user->identity->uid ?>})" href="#">
                            <i class="fa fa-user"></i> Мой профиль
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('dealings')">
                            <i class="fa fa-briefcase"></i> Мои сделки
                            <span id="new_dealings_count" class="badge badge-green"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('notifications')">
                            <i class="fa fa-comments"></i> Мои уведомления
                        </a>
                    </li>
                </ul>
            </li>
            <li class="work_page">
                <a href="#" onclick="load_page('work')">
                    <?= MyHtmlHelper::icon('lg-icons/work') ?>
                    <span>Работа</span>
                </a>
            </li>
            <li class="party-info_page">
                <a href="#" onclick="load_page('party-info')" >
                    <?= MyHtmlHelper::icon('lg-icons/party') ?>
                    <span>Партия</span>
                </a>
            </li>
            <li class="treeview map-politic_page map-resources_page map-population_page">
                <a href="#" >
                    <?= MyHtmlHelper::icon('lg-icons/globe') ?>
                    <span>Карта</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="#" onclick="load_page('map-politic')">
                            <i class="fa fa-flag"></i> Политическая карта
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-cores')">
                            <i class="fa fa-legal"></i> Карта претензий
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-resources')">
                            <i class="fa fa-money"></i> Экономическая карта
                        </a>
                    </li>
                    <li>
                        <a href="#" onclick="load_page('map-population')">
                            <i class="fa fa-group"></i> Демографическая карта
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview state-info_page elections_page org-info_page">
                <a href="#" >
                    <?= MyHtmlHelper::icon('lg-icons/goverment') ?>
                    <span>Государство</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a onclick="load_page('state-info')" href="#">
                            <i class="fa fa-flag"></i> О государстве
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('elections')" href="#">
                            <i class="fa fa-bullhorn"></i> Выборы
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview twitter_page newspapers_page radio_page tv_page">
                <a href="#" >
                    <?= MyHtmlHelper::icon('lg-icons/news') ?>
                    <span>СМИ</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a onclick="load_page('twitter')" href="#">
                            <i class="fa fa-twitter"></i> Соц. сети
                        </a>
                    </li>
                    <!--<li><a onclick="load_page('newspapers')" href="#">Пресса</a></li>
                    <li><a onclick="load_page('radio')" href="#">Радио</a></li>
                    <li><a onclick="load_page('tv')" href="#">Телевиденье</a></li>-->
                </ul>
            </li>
            <li class="treeview holding-info_page my-buisness_page market_page market-factories_page market-forex_page market-stocks_page market-resources_page factory-info_page">
                <a href="#" >
                    <?= MyHtmlHelper::icon('lg-icons/business') ?>
                    <span>Бизнес</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a onclick="load_page('my-buisness')" href="#">
                            <i class="fa fa-building"></i> Мой бизнес
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('market')" href="#">
                            <i class="fa fa-th-large"></i> Рынок
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview chart-states_page chart-parties_page chart-peoples_page">
                <a href="#" >
                    <?= MyHtmlHelper::icon('lg-icons/rating') ?>
                    <span>Рейтинг</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a onclick="load_page('chart-states')" href="#">
                            <i class="fa fa-th-list"></i> Рейтинг государств
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-parties')" href="#">
                            <i class="fa fa-list-ul"></i> Рейтинг партий
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-peoples')" href="#">
                            <i class="fa fa-list"></i> Рейтинг людей
                        </a>
                    </li>
                    <li>
                        <a onclick="load_page('chart-holdings')" href="#">
                            <i class="fa fa-tasks"></i> Рейтинг компаний
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
        </section>
    </aside>
    <div id="bigcontainer" class="content-wrapper">
        <div style="display:none" class="alert alert-block alert-error" id="error_block">
            <!--<a class="close" data-dismiss="alert" href="#">&times;</a>-->
            <h4>Ошибка!</h4>
            <p id="error_text">Неизвестная ошибка</p><br>
            <p><small>Если вы не знаете причину этой ошибки и она повторяется, пожалуйста, сообщите о ней <a href="//vk.com/topic-56461826_28488767" target="_blank">здесь</a>.</small></p>
        </div>
        <div id="page_content" >

        </div>
        <hr class='show_on_load'>
        <!-- politsim resp -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-7725940874180553"
             data-ad-slot="9864631062"
             data-ad-format="auto">
        </ins>
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
