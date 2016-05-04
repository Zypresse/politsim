<?php

use app\models\User,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaEditor,
    app\components\MyHtmlHelper,
    yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $newspaper Massmedia */
/* @var $user User */
/* @var $rules MassmediaEditor */

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
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <span class="box-title">
                            <i class="fa fa-user"></i> Редакция
                        </span>
                        <?php if ($rules->isHavePermission(MassmediaEditor::RULE_SET_EDITORS)): ?>
                        <div class="box-tools pull-right">
                            <button id="add-editor-button" class="btn btn-xs btn-success">Добавить</button>
                        </div>
                        <?php endif ?>
                    </div>
                    <div class="box-content">
                        <table class="table table-normal">
                            <thead>
                                <tr>
                                    <th>Должность</th>
                                    <th>Имя</th>
                                    <th><i class="fa fa-newspaper-o" title="Посты"></i></th>
                                    <th><i class="fa fa-star" title="Рейтинг"></i></th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                        <?php foreach ($newspaper->editors as $editor): ?>
                            <tr>
                                <td><?=$editor->customName ? $editor->customName : ($editor->userId === $newspaper->directorId ? 'Главный редактор' : 'Редактор')?></td>
                                <td><?=$editor->user->getHtmlName()?></td>
                                <td><?=$editor->posts?></td>
                                <td><?=$editor->rating?></td>
                                <td>
                                    <div class="btn-group">
                                        <?php if ($editor->userId !== $newspaper->directorId): ?>
                                        <button class="btn btn-danger btn-xs" ><i class="fa fa-trash"></i> Уволить</button>
                                        <?php endif ?>
                                        <button class="btn btn-info btn-xs" ><i class="fa fa-cog"></i> Изменить</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<div style="display:none" class="modal fade" id="add-editor-modal" tabindex="-1" role="dialog" aria-labelledby="add-editor-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="add-editor-modal-label">Добавление нового редактора</h3>
            </div>
            <div id="add-editor-modal-body" class="modal-body">

            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" data-dismiss="modal" aria-hidden="true">Закрыть</button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function(){
       $('#add-editor-button').click(function(){
           load_modal('add-editor', {'massmediaId':<?=$newspaper->id?>}, 'add-editor-modal', 'add-editor-modal-body');
       });
    });
</script>