<?php

use yii\helpers\Html;
use app\helpers\Icon;

/* @var $this \yii\web\View */

?>
<header class="main-header">
    <a class="logo" href="/">
        <svg xmlns="http://www.w3.org/2000/svg" width="32px" height="32px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" style="vertical-align: middle;" >
            <g>
                <circle cx="50" cy="50" r="45" fill="none" stroke="none" />
                <circle cx="50" cy="50" r="35" fill="none" stroke="#eee" stroke-width="5"/>
                <ellipse cx="50" cy="50" fill="none" ry="35" stroke="#eee" stroke-width="3" rx="18"></ellipse>
                <ellipse cx="50" cy="50" fill="none" ry="35" stroke="#eee" stroke-width="3" rx="0.1"></ellipse>
                <ellipse cx="50" cy="30" ry="0.1" fill="none" rx="28" stroke="#eee" stroke-width="3"/>
                <ellipse cx="50" cy="50" ry="0.1" fill="none" rx="34" stroke="#eee" stroke-width="3"/>
                <ellipse cx="50" cy="70" ry="0.1" fill="none" rx="28" stroke="#eee" stroke-width="3"/>
            </g>
        </svg>
        Political Simulator
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only"><?= Yii::t('app', 'Toggle navigation') ?></span>
        </a>

        <div class="navbar-custom-menu" id="navbar" >

            <ul class="nav navbar-nav">
                <li>
                    <a href="#" onclick="if (fullScreenApi.isFullScreen()) {fullScreenApi.cancelFullScreen();} else {fullScreenApi.requestFullScreen(document.documentElement);} return false"><i class="fa fa-arrows-alt" title="<?= Yii::t('app', 'Fullscreen') ?>"></i></a>
                </li>
                <li class="dropdown notifications-menu">
                    <a aria-expanded="true" href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell"></i>
                        <span id="new_notifications_count" class="label label-info hide autoupdated-notifications">0</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header"><?= Yii::t('app', 'You have <span class="autoupdated-notifications">0</span> new notifications') ?></li>
                        <li>
                            <ul id="new_notifications_list" class="menu"></ul>
                        </li>
                        <li class="footer"><a href="/notifications"><?= Yii::t('app', 'View all') ?></a></li>
                    </ul>
                </li>
                <li class="dropdown user-menu" >
                    <a href="#" class="dropdown-toggle dropdown-avatar" data-toggle="dropdown">
                        <span>
                            <?= Html::img(Yii::$app->user->identity->currentUser ? Yii::$app->user->identity->currentUser->avatar : '/img/profile.png', ['class' => "menu-avatar profile-avatar user-image"]) ?>
                            <span><span class="profile-name" ><?= Yii::$app->user->identity->currentUser ? Html::encode(Yii::$app->user->identity->currentUser->name) : 'Нет персонажа' ?></span> <i class="icon-caret-down"></i></span>
                            <!--<span class="badge badge-dark-red">5</span>-->
                        </span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <?= Html::img(Yii::$app->user->identity->currentUser ? Yii::$app->user->identity->currentUser->avatar : '/img/profile.png', ['class' => 'img-circle']) ?>
                            <p>
                                <span class="profile-name" ><?= Yii::$app->user->identity->currentUser ? Html::encode(Yii::$app->user->identity->currentUser->name) : Html::encode('Персонаж не выбран') ?></span>
                            </p>
                            <?php if (Yii::$app->user->identity->currentUser): ?>
                            <p>
                                <span class="star"><span class="autoupdated-fame"><?= Yii::$app->user->identity->currentUser->fame ?></span> <?= Icon::draw(Icon::STAR) ?></span>
                                <span class="heart"><span class="autoupdated-trust"><?= Yii::$app->user->identity->currentUser->fame ?></span> <?= Icon::draw(Icon::HEART) ?></span>
                                <span class="chart_pie"><span class="autoupdated-success"><?= Yii::$app->user->identity->currentUser->fame ?></span> <?= Icon::draw(Icon::CHARTPIE) ?></span>
                            </p>
                            <?php endif ?>
                        </li>
                        <li class="user-footer">
                            <div class="text-center">
                                <div class="btn-group">
                                    <?= Html::a('<i class="fa fa-cog"></i> Настройки акканута', ["/account/profile"], ['class' => 'btn btn-info btn-xs']) ?>
                                    <?= Html::a('<i class="fa fa-sign-out"></i> Выход', ["auth/logout"], [
                                        'class' => 'btn btn-warning btn-xs',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to sign out?',
                                            'method' => 'post',
                                        ]
                                    ]) ?>
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
