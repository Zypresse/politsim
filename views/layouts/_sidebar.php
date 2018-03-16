<?php

use yii\helpers\Html;
use app\helpers\Icon;

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
        <ul class="sidebar-menu" id="topmenu" >
        <?php if (Yii::$app->user->identity->currentUser): ?>
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::PROFILE) ?>
                    <span>
                        Профиль                     
                    </span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <?= Html::a('<i class="fa fa-user"></i> Мой профиль', ["user/profile", "id" => Yii::$app->user->identity->activeUserId]) ?>
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
            <li class="">
                <a href="#!work">
                    <?= Icon::draw(Icon::WORK) ?>
                    <span>Моя работа</span>
                </a>
            </li>
            <li class="">
                <a href="#!membership">
                    <?= Icon::draw(Icon::PARTY) ?>
                    <span>Мои партии</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::GOVERMENT) ?>
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
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::BUSINESS) ?>
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
            <?php endif ?>
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::GLOBE) ?>
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
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::NEWS) ?>
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
            <li class="treeview">
                <a href="#" >
                    <?= Icon::draw(Icon::RATING) ?>
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
