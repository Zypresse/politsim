<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */

?>
<aside class="main-sidebar">
    <section class="sidebar" style="height:auto">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= 'TODO: avatar' ?>" class="img-circle" alt="">
            </div>
            <div class="pull-left info">
                <p><?= 'TODO: name' ?></p>
                <p>
                    <span class="star"><span class="autoupdated-fame"><?= 'fame' ?></span> <?= 'star' ?></span>
                    <span class="heart"><span class="autoupdated-trust"><?= 'trust' ?></span> <?= 'heart' ?></span>
                    <span class="chart_pie"><span class="autoupdated-success"><?= 'success' ?></span> <?= 'chart_pie' ?></span>
                </p>
                <strong id="head_money"></strong>
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
                <?='lg-icons/profile'?>
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
                <?= 'lg-icons/work' ?>
                <span>Моя работа</span>
            </a>
        </li>
        <li class="membership_page party_page party-members_page party-program_page">
            <a href="#!membership">
                <?= 'lg-icons/party' ?>
                <span>Мои партии</span>
            </a>
        </li>
        <li class="treeview state_page state-constitution_page elections_page agency_page region_page region-constitution_page city_page">
            <a href="#" >
                <?= 'lg-icons/goverment' ?>
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
                    <a href="#!elections">
                        <i class="fa fa-bullhorn"></i> Выборы
                    </a>
                </li>
            </ul>
        </li>
        <li class="treeview holding-info_page my-buisness_page market_page market-factories_page market-forex_page market-stocks_page market-resources_page factory-info_page">
            <a href="#" >
                <?= 'lg-icons/business' ?>
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
                <?= 'lg-icons/globe' ?>
                <span>Карта</span>
                <i class="fa fa-angle-left pull-right"></i>
            </a>
            <ul class="treeview-menu">
                <li>
                    <a href="#!map">
                        <i class="fa fa-flag"></i> Политическая карта
                    </a>
                </li>
                <li>
                    <a href="#!map/historical">
                        <i class="fa fa-legal"></i> Карта претензий
                    </a>
                </li>
                <li>
                    <a href="#!map/economical">
                        <i class="fa fa-briefcase"></i> Экономическая карта
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
                <?= 'lg-icons/news' ?>
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
                <?= 'lg-icons/rating' ?>
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
