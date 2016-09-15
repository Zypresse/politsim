<?php

use yii\helpers\Html,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
$this->title = 'Political Simulator';

?>
<header class="main-header">

    <a class="logo" href="#"><?=MyHtmlHelper::icon('lg-icons/globe','width:25px')?> Political Simulator</a>
    <nav class="navbar navbar-static-top show_on_load" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?=Yii::t('app','Toggle navigation')?></span>
        </a>

        <div class="navbar-custom-menu" id="navbar" >

            <ul class="nav navbar-nav">
                <li>
                    <a href="#" onclick="if (fullScreenApi.isFullScreen()) {
                                    fullScreenApi.cancelFullScreen();
                                } else {
                                    fullScreenApi.requestFullScreen(document.documentElement);
                                }"><i class="fa fa-arrows-alt" title="<?=Yii::t('app','Fullscreen')?>"></i></a>
                </li>
                <li>
                    <a href="#" onclick="reload_page()"><i class="fa fa-refresh" title="<?=Yii::t('app','Reload page')?>"></i></a>
                </li>
                <li class="dropdown notifications-menu">
                    <a aria-expanded="true" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span id="new_notifications_count" class="label label-info hide autoupdated-notifications">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?=Yii::t('app', 'You have <span class="autoupdated-notifications">0</span> new notifications')?></li>
                        <li>
                            <ul id="new_notifications_list" class="menu"></ul>
                        </li>
                        <li class="footer"><a href="#!notifications"><?=Yii::t('app', 'View all')?></a></li>
                    </ul>
                </li>
                <li class="dropdown user-menu" >
                    <a href="#" class="dropdown-toggle dropdown-avatar" data-toggle="dropdown">
                        <span>
                            <img class="menu-avatar profile-avatar user-image" src="<?=Yii::$app->user->identity->avatar?>" alt="" /> <span><span class="profile-name" ><?=Yii::$app->user->identity->name?></span> <i class="icon-caret-down"></i></span>
                            <!--<span class="badge badge-dark-red">5</span>-->
                        </span>
                    </a>
                    <ul class="dropdown-menu">

                        <li class="user-header">
                            <?=Html::img(Yii::$app->user->identity->avatarBig, ['class' => 'img-circle'])?>
                            <p>
                                <span class="profile-name" ><?=Html::encode(Yii::$app->user->identity->name)?></span>
                            </p>
                            <p>
                                <span class="star"><span class="autoupdated-fame"><?= Yii::$app->user->identity->fame ?></span> <?= MyHtmlHelper::icon('star') ?></span>
                                <span class="heart"><span class="autoupdated-trust"><?= Yii::$app->user->identity->trust ?></span> <?= MyHtmlHelper::icon('heart') ?></span>
                                <span class="chart_pie"><span class="autoupdated-success"><?= Yii::$app->user->identity->success ?></span> <?= MyHtmlHelper::icon('chart_pie') ?></span>
                            </p>
                        </li>


                        <li class="user-footer">
                            <div class="text-center">
                                <div class="btn-group">
                                    <?=Html::a('<i class="fa fa-user"></i> Профиль', '#!profile', [
                                        'class' => 'btn btn-primary btn-xs'
                                    ])?>
                                    <?=MyHtmlHelper::a('<i class="fa fa-cog"></i> Настройки', "load_modal('account-settings',{},'settings_modal','settings_modal_body')", [
                                        'class' => 'btn btn-info btn-xs'
                                    ])?>
                                    <?=Html::a('<i class="fa fa-sign-out"></i> Выход', ["site/logout"], [
                                        'class' => 'btn btn-warning btn-xs',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to sign out?',
                                            'method' => 'post',
                                        ]
                                    ])?>
                                </div>
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
                <img src="<?=Yii::$app->user->identity->avatar?>" class="img-circle" alt="">
            </div>
            <div class="pull-left info">
                <p><?=Yii::$app->user->identity->name?></p>
                <strong id="head_money"></strong> <?//=MyHtmlHelper::icon('money')?>
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
        <li class="treeview profile_page capital_page dealings_page notifications_page">
            <a href="#" >
                <?=MyHtmlHelper::icon('lg-icons/profile', '')?>
                <span>
                    Профиль                     
                </span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!profile">
                        <i class="fa fa-user"></i> Мой профиль
                    </a>
                </li>
                <li>
                    <a href="#!dealings">
                        <i class="fa fa-briefcase"></i> Мои сделки
                    </a>
                </li>
                <li>
                    <a href="#!notifications">
                        <i class="fa fa-comments"></i> Мои уведомления
                    </a>
                </li>
            </ul>
        </li>
        <li class="work_page">
            <a href="#!work">
                <?= MyHtmlHelper::icon('lg-icons/work', '') ?>
                <span>Моя работа</span>
            </a>
        </li>
        <li class="membership_page party_page party-members_page party-program_page">
            <a href="#!membership">
                <?= MyHtmlHelper::icon('lg-icons/party', '') ?>
                <span>Мои партии</span>
            </a>
        </li>
        <li class="treeview state_page state-constitution_page elections_page agency_page">
            <a href="#" >
                <?= MyHtmlHelper::icon('lg-icons/goverment', '') ?>
                <span>Государство</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!citizenship">
                        <i class="fa fa-flag"></i> Гражданство
                    </a>
                </li>
                <li>
                    <a href="#!state/elections">
                        <i class="fa fa-bullhorn"></i> Выборы
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview holding-info_page my-buisness_page market_page market-factories_page market-forex_page market-stocks_page market-resources_page factory-info_page">
            <a href="#" >
                <?= MyHtmlHelper::icon('lg-icons/business', '') ?>
                <span>Бизнес</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!business">
                        <i class="fa fa-building"></i> Мой бизнес
                    </a>
                </li>
                <li>
                    <a href="#!market">
                        <i class="fa fa-th-large"></i> Рынок
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview map_page map-politic_page map-resources_page map-population_page">
            <a href="#" >
                <?= MyHtmlHelper::icon('lg-icons/globe', '') ?>
                <span>Карта</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!map">
                        <i class="fa fa-flag"></i> Тестовая карта
                    </a>
                </li>
                <li>
                    <a href="#!map/political">
                        <i class="fa fa-flag"></i> Политическая карта
                    </a>
                </li>
                <li>
                    <a href="#!map/historical">
                        <i class="fa fa-legal"></i> Карта исторических территорий
                    </a>
                </li>
                <li>
                    <a href="#!map/resources">
                        <i class="fa fa-money"></i> Карта ресурсов
                    </a>
                </li>
                <li>
                    <a href="#!map/demography">
                        <i class="fa fa-group"></i> Демографическая карта
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview twitter_page newspapers_page newspaper_page newspaper-new-post_page newspaper-feed_page radio_page tv_page">
            <a href="#" >
                <?= MyHtmlHelper::icon('lg-icons/news', '') ?>
                <span>СМИ</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!twitter">
                        <i class="fa fa-twitter"></i> Соц. сети
                    </a>
                </li>
                <li>
                    <a href="#!newspapers">
                        <i class="fa fa-newspaper-o"></i> Пресса
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview chart-states_page chart-parties_page chart-peoples_page">
            <a href="#" >
                <?= MyHtmlHelper::icon('lg-icons/rating', '') ?>
                <span>Рейтинг</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!chart/users">
                        <i class="fa fa-list"></i> Рейтинг людей
                    </a>
                </li>
                <li>
                    <a href="#!chart/states">
                        <i class="fa fa-th-list"></i> Рейтинг государств
                    </a>
                </li>
                <li>
                    <a href="#!chart/parties">
                        <i class="fa fa-list-ul"></i> Рейтинг партий
                    </a>
                </li>
                <li>
                    <a href="#!chart/companies">
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
    <!-- politsim resp -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-7725940874180553"
         data-ad-slot="9864631062"
         data-ad-format="auto">
    </ins>
</div>
<script type="text/javascript">

var datatable_language = {
    paginate: {
        first:    '«',
        previous: '‹',
        next:     '›',
        last:     '»'
    },
    aria: {
        paginate: {
            first:    '<?=Yii::t('app','First')?>',
            previous: '<?=Yii::t('app','Previous')?>',
            next:     '<?=Yii::t('app','Next')?>',
            last:     '<?=Yii::t('app','Last')?>'
        },
        sortAscending: '<?=Yii::t('app',' - click/return to sort ascending')?>',
        sortDescending: '<?=Yii::t('app',' - click/return to sort descending')?>'
    },
    decimal: ',',
    thousands: '.',
    emptyTable: '<?=Yii::t('app','No data available in table')?>',
    info: '<?=Yii::t('app','Showing _START_ to _END_ of _TOTAL_ items')?>',
    infoEmpty: '<?=Yii::t('app','No entries to show')?>',
    infoFiltered: '<?=Yii::t('app','(filtered from _MAX_ total items)')?>',
    lengthMenu: '<?=Yii::t('app','Show <select><option value="10">10</option><option value="20">20</option><option value="30">30</option><option value="40">40</option><option value="50">50</option><option value="-1">All</option></select> items')?>',
    processing: '<?=Yii::t('app','Processing...')?>',
    search: '<?=Yii::t('app','Search:')?>',
    searchPlaceholder: '<?=Yii::t('app','Type anything')?>',
    zeroRecords: '<?=Yii::t('app','No matching states found')?>'
};

</script>
