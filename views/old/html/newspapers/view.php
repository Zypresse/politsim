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
                        <?php if ($newspaper->state): ?>
                        <li><?=$newspaper->state->getHtmlName()?></li>
                        <?php endif ?>
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
                    </ul>
                </div>
                <div class="box-footer">
                    <p class="text-info text-center">
                        Охват: <?=MyHtmlHelper::formateNumberword($newspaper->coverage, 'h')?> <i class="fa fa-user"></i>
                    </p>
                </div>
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
                    <?php foreach ($newspaper->publicEditors as $editor): ?>
                        <tr>
                            <td><?=$editor->customName ? $editor->customName : ($editor->userId === $newspaper->directorId ? 'Главный редактор' : 'Редактор')?></td>
                            <td><?=$editor->user->getHtmlName()?></td>
                        </tr>
                    <?php endforeach ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="btn-group">
                <a href="#" onclick="load_page('newspaper-feed',{id:<?=$newspaper->id?>})" class="btn btn-info">Посмотреть статьи</a>
            </div>
        </div>
    </div>
</section>