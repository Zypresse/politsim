<?php

use app\helpers\Icon;
use dmstr\widgets\Menu;

/* @var $this \yii\web\View */

?>
<aside class="main-sidebar">
    <section class="sidebar" style="height:auto">
        <?php if (Yii::$app->user->identity->currentUser): ?>
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::$app->user->identity->currentUser->avatar ?>" class="img-circle" alt="">
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->currentUser->name ?></p>
                <p>
                    <span class="star"><span class="autoupdated-fame"><?= Yii::$app->user->identity->currentUser->fame ?></span> <?= Icon::draw(Icon::STAR) ?></span>
                    <span class="heart"><span class="autoupdated-trust"><?= Yii::$app->user->identity->currentUser->trust ?></span> <?= Icon::draw(Icon::HEART) ?></span>
                    <span class="chart_pie"><span class="autoupdated-success"><?= Yii::$app->user->identity->currentUser->success ?></span> <?= Icon::draw(Icon::CHARTPIE) ?></span>
                </p>
                <strong id="head_money"></strong>
            </div>
        </div>
        <?php endif ?>
        <form action="#" method="get" class="sidebar-form" style="display:none">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search...">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
        <?= Menu::widget([
            'options' => ['id' => 'topmenu', 'class' => 'sidebar-menu', 'data-widget' => 'tree'],
            'items' => [
                Yii::$app->user->identity->currentUser ? 
                [
                    'label' => 'Профиль',
                    'template' => '<a href="{url}">'.Icon::draw(Icon::PROFILE).' {label} <i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Мой профиль', 'icon' => 'user', 'url' => ['/user/profile']],
                    ],
                ] : [],
                
                Yii::$app->user->identity->currentUser ? [
                    'label' => 'Государство',
                    'template' => '<a href="{url}">'.Icon::draw(Icon::GOVERNMENT).' {label} <i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Гражданство', 'icon' => 'flag', 'url' => ['/citizenship']],
                    ],
                ] : [],
                
                Yii::$app->user->identity->currentUser ? [
                    'label' => 'Организации',
                    'template' => '<a href="{url}">'.Icon::draw(Icon::PARTY).' {label} <i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Членство', 'icon' => 'users', 'url' => ['/organization']],
                    ],
                ] : [],
                
                [
                    'label' => 'Карта',
                    'template' => '<a href="{url}">'.Icon::draw(Icon::GLOBE).' {label} <i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Политическая', 'icon' => 'flag', 'url' => ['/map/political']],
                    ],
                ],
                
                [
                    'label' => 'Рейтинг',
                    'template' => '<a href="{url}">'.Icon::draw(Icon::RATING).' {label} <i class="fa fa-angle-left pull-right"></i></a>',
                    'items' => [
                        ['label' => 'Государства', 'icon' => 'flag', 'url' => ['/rating/states']],
                        ['label' => 'Организации', 'icon' => 'flag', 'url' => ['/rating/organizations']],
                    ],
                ],
                
                YII_ENV_DEV && Yii::$app->user->identity->role === 100 ? [ 'label' => 'Development', 'icon' => 'terminal', 'items' => [
                    ['label' => 'Gii', 'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug', 'icon' => 'dashboard', 'url' => ['/debug']],
                ]] : [],
            ],
        ]) ?>
    </section>
</aside>
