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
                    <p>Так же рекомендуется к прочтению: <a href="http://blog.politsim.net">официальный блог разработки</a>, <a href="http://wiki.politsim.net">вики по игре</a>.</p>
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
    <div class="navbar navbar-fixed-top show_on_load" id="navbar" >
        <div class="navbar-inner">
            <div class="container" style="position:relative">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <a class="brand" href="#" onclick="load_page('profile', {'uid':<?= Yii::$app->user->identity->uid ?>});">
                    <img style="vertical-align: top;" src="<?= Yii::$app->user->identity->photo ?>" alt=''>
                </a>
                <a class="brand brand2" href="#" onclick="load_page('profile', {'uid':<?= Yii::$app->user->identity->uid ?>});">
    <?= Yii::$app->user->identity->name ?>
                </a>
                <div class="sub_brand">
                    <span class="star"><span id="head_star"><?= Yii::$app->user->identity->star ?></span> <?= MyHtmlHelper::icon('star') ?></span> 
                    <span class="heart"><span id="head_heart"><?= Yii::$app->user->identity->heart ?></span> <?= MyHtmlHelper::icon('heart') ?></span>
                    <span class="chart_pie"><span id="head_chart_pie"><?= Yii::$app->user->identity->chart_pie ?></span> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                </div>
                <div class="nav-collapse rightmenu">
                    <ul class="nav pull-right right-info">
                        <li>
                            <span id="head_money"><?= number_format(Yii::$app->user->identity->money, 0, '', ' ') ?></span> <?= MyHtmlHelper::icon('money') ?>
                            <br>
                            <span id="current_date" style="display:none"></span>
                            <!--<br>-->
                            <a href="#" onclick="if (fullScreenApi.isFullScreen()) {
                                            fullScreenApi.cancelFullScreen();
                                        } else {
                                            fullScreenApi.requestFullScreen(document.documentElement);
                                        }"><i class="icon-fullscreen icon-white" title="На весь экран"></i></a>
                            <a href="#" onclick="reload_page()"><i class="icon-refresh icon-white" title="Обновить"></i></a>
                            <a href="#" style="display: none" id="account_settings_button" onclick="load_modal('account-settings',{},'settings_modal','settings_modal_body')"><i class="icon-cog icon-white" title="Аккаунты"></i></a>                            
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="navbar-inner" style="text-align:center;height: 85px;">
            <ul class="nav" id="topmenu" style="float: none;display:inline-block">
                <li class="dropdown profile_page capital_page dealings_page ">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/profile')//(intval(Yii::$app->user->identity->sex)===1) ? MyHtmlHelper::icon('lg-icons/profile-female') : MyHtmlHelper::icon('lg-icons/profile-male')  ?><br>Профиль<span id="profile_badge" style="display:none"> <span class="badge badge-success" id="profile_badge_value">2</span></span></a>
                    <ul class="dropdown-menu">
                        <li><a onclick="load_page('profile', {'uid':<?= Yii::$app->user->identity->uid ?>})" href="#">Мой профиль</a></li>
                        <!--<li><a onclick="load_page('capital',{'uid':<?= Yii::$app->user->identity->uid ?>})" href="#">Мой капитал</a></li>-->
                        <li><a href="#" onclick="load_page('dealings')">Мои сделки<span id="new_dealings_count"> <span id="new_dealings_count_value" class="badge badge-success">2</span></span></a></li>
                        <li><a href="#" onclick="load_page('notifications')">Мои уведомления</a></li>

                        <!--
                        <li class="nav-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>-->
                    </ul>
                </li>
                <!--<li class="active"><a href="#">Active</a></li>-->
                <li class="work_page"><a href="#" onclick="load_page('work')"><?= MyHtmlHelper::icon('lg-icons/work') ?><br>Работа</a></li>

                <li class="party-info_page"><a href="#" onclick="load_page('party-info')" ><?= MyHtmlHelper::icon('lg-icons/party') ?><br>Партия</a></li>

                <li class="dropdown map-politic_page map-resurses_page map-population_page">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/globe') ?><br>Карта</a>
                    <ul class="dropdown-menu">
                        <li><a href="#" onclick="load_page('map-politic')">Политическая карта</a></li>
                        <li><a href="#" onclick="load_page('map-cores')">Карта корневых провинций</a></li>
                        <li class="divider"></li>
                        <li><a href="#" onclick="load_page('map-resurses')">Экономическая карта</a></li>
                        <li><a href="#" onclick="load_page('map-population')">Демографическая карта</a></li>
                    </ul>
                </li>
                <!--<li class="elections_page"><a onclick="load_page('elections')" href="#"><?= MyHtmlHelper::icon('check_box_list') ?><br>Выборы</a></li>-->
                <!--<li><a href="#" onclick="load_page('chart')"><?= MyHtmlHelper::icon('chart_bar') ?><br>Рейтинг</a></li>-->
                <li class="dropdown state-info_page elections_page org-info_page">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/goverment') ?><br>Государство</a>
                    <ul class="dropdown-menu">
                        <li><a onclick="load_page('state-info')" href="#">О государстве</a></li>
                        <li class="divider"></li>
                        <li><a onclick="load_page('elections')" href="#">Выборы</a></li>
                        <!--
                        <li class="nav-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>-->
                    </ul>
                </li>
                <li class="dropdown twitter_page newspapers_page radio_page tv_page">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/news') ?><br>СМИ</a>
                    <ul class="dropdown-menu">
                        <li><a onclick="load_page('twitter')" href="#">Соц. сети</a></li>
                        <!--<li><a onclick="load_page('newspapers')" href="#">Пресса</a></li>
                        <li><a onclick="load_page('radio')" href="#">Радио</a></li>
                        <li><a onclick="load_page('tv')" href="#">Телевиденье</a></li>-->
                    </ul>
                </li>
                <li class="dropdown holding-info_page my-buisness_page market_page market-factories_page market-forex_page market-stocks_page market-resurses_page holding-control_page">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/business') ?><br>Бизнес</a>
                    <ul class="dropdown-menu">
                        <li><a onclick="load_page('my-buisness')" href="#">Мой бизнес</a></li>
                        <li><a onclick="load_page('market')" href="#">Рынок</a></li>
                    </ul>
                </li>
                <li class="dropdown chart-states_page chart-parties_page chart-peoples_page">
                    <a href="#" class="aaadropdown-toggle" data-toggle="dropdown"><?= MyHtmlHelper::icon('lg-icons/rating') ?><br>Рейтинг</a>
                    <ul class="dropdown-menu">
                        <li><a onclick="load_page('chart-states')" href="#">Рейтинг государств</a></li>
                        <li><a onclick="load_page('chart-parties')" href="#">Рейтинг партий</a></li>
                        <li><a onclick="load_page('chart-peoples')" href="#">Рейтинг людей</a></li>
                        <li><a onclick="load_page('chart-holdings')" href="#">Рейтинг компаний</a></li>
                        <!--<li class="divider"></li>
                        <li class="nav-header">Nav header</li>
                        <li><a href="#">Separated link</a></li>
                        <li><a href="#">One more separated link</a></li>-->
                    </ul>
                </li>

            </ul>
        </div>
    </div>


    <div id="bigcontainer" class="container">
        <div style="display:none" class="alert alert-block alert-error" id="error_block">
            <!--<a class="close" data-dismiss="alert" href="#">&times;</a>-->
            <h4>Ошибка!</h4>
            <p id="error_text">Неизвестная ошибка</p><br>
            <p><small>Если вы не знаете причину этой ошибки и она повторяется, пожалуйста, сообщите о ней <a href="//vk.com/topic-56461826_28488767" target="_blank">здесь</a>.</small></p>
        </div>
        <div id="row1"  class="row">

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
            <button class="btn" data-dismiss="modal" aria-hidden="true">Закрыть</button>
        </div>
    </div>
            <?php endif ?>