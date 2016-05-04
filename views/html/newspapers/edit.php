<?php

use app\models\User,
    app\models\massmedia\Massmedia,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $newspaper Massmedia */
/* @var $user User */

?>
<section class="content">
    <div class="row">
        <div class="col-md-7">
            <h2>
                <i class="fa fa-newspaper-o"></i> <?=$newspaper->name?>
                <small>
                    <span class="label-success" style='border-radius: 5px' title="Рейтинг" >
                        &nbsp;<?=number_format($newspaper->rating, 0, '', ' ')?> <i class="fa fa-star"></i>&nbsp;
                    </span>
                </small>
            </h2>
            <p>Зарегистрирована в государстве: <?=$newspaper->state->getHtmlName()?></p>
            <p>Главный редактор: <?=$newspaper->director->getHtmlName()?></p>
            <p>Владелец: <?=$newspaper->holding->getHtmlName()?></p>
            <p>Основана: <i class="fa fa-calendar"></i> <?=date('d-m-Y', $newspaper->created)?></p>
        </div>
        <div class="col-md-5">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">
                        <i class="fa fa-group"></i> Аудитория
                    </span>
                </div>
                <div class="box-content">    
                    <ul>
                        <?php if ($newspaper->region): ?>
                        <li><?=$newspaper->region->getHtmlName()?></li>
                        <?php endif ?>
                        <?php if ($newspaper->popClass): ?>
                        <li><?=$newspaper->popClass->name?></li>
                        <?php endif ?>
                        <?php if ($newspaper->popNation): ?>
                        <li><?=$newspaper->popNation->name?></li>
                        <?php endif ?>
                        <?php if ($newspaper->religion): ?>
                        <li><?=$newspaper->religion->name?></li>
                        <?php endif ?>
                        <?php if ($newspaper->ideology): ?>
                        <li><?=$newspaper->ideology->name?></li>
                        <?php endif ?>
                    </table>
                </div
                <div class="box-footer">
                    <p class="text-info text-center">
                        Охват: <?=MyHtmlHelper::formateNumberword($newspaper->coverage, 'h')?> <i class="fa fa-user"></i>
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <div class="box-header">
                        <span class="box-title">
                            <i class="fa fa-user"></i> Редакция
                        </span>
                    </div>
                    <div class="box-content">
                        <table class="table table-normal">
                            <thead>
                                <tr>
                                    <th>Должность</th>
                                    <th>Имя</th>
                                    <th><i class="fa fa-newspaper-o" title="Посты"></i></th>
                                    <th><i class="fa fa-star" title="Рейтинг"></i></th>
                                </tr>
                            </thead>
                        <?php foreach ($newspaper->editors as $editor): ?>
                            <tr>
                                <td><?=$editor->customName ? $editor->customName : ($editor->userId === $newspaper->directorId ? 'Главный редактор' : 'Редактор')?></td>
                                <td><?=$editor->user->getHtmlName()?></td>
                                <td><?=$editor->posts?></td>
                                <td><?=$editor->rating?></td>
                            </tr>
                        <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>