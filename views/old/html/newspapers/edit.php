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
                <div class="box-body">    
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
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <span class="box-title">
                        <i class="fa fa-user"></i> Редакция
                    </span>
                    <?php if ($rules->isHavePermission(MassmediaEditor::RULE_SET_EDITORS)): ?>
                    <div class="box-tools pull-right">
                        <button id="add-editor-button" class="btn btn-xs btn-success">
                            <i class="fa fa-plus"></i> Назначить редактора
                        </button>
                    </div>
                    <?php endif ?>
                </div>
                <div class="box-body" >
                    <table class="table table-normal">
                        <thead>
                            <tr>
                                <th>Должность</th>
                                <th>Имя</th>
                                <th><i class="fa fa-newspaper-o" title="Посты"></i></th>
                                <th><i class="fa fa-star" title="Рейтинг"></i></th>
                                <th style="min-width:70px"></th>
                            </tr>
                        </thead>
                    <?php foreach ($newspaper->editors as $editor): ?>
                        <tr>
                            <td><?=$editor->customName ? $editor->customName : ($editor->userId === $newspaper->directorId ? 'Главный редактор' : 'Редактор')?></td>
                            <td><?=$editor->user->getHtmlName()?></td>
                            <td class="text-center"><?=$editor->posts?></td>
                            <td class="text-center"><?=$editor->rating?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <?php if ($editor->userId !== $newspaper->directorId): ?>
                                    <button class="btn btn-danger btn-xs fire-editor-button" data-user-id="<?=$editor->userId?>" title="Уволить" ><i class="fa fa-trash"></i></button>
                                    <?php endif ?>
                                    <button class="btn btn-info btn-xs rule-edit-button" data-user-id="<?=$editor->userId?>" title="Изменить" ><i class="fa fa-gears"></i></button>
                                </div>
                            </td>
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
                <a href="#" onclick="load_page('newspaper-new-post',{id:<?=$newspaper->id?>})" class="btn btn-success">Написать новый пост</a>
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
<div style="display:none" class="modal fade" id="rule-editor-modal" tabindex="-1" role="dialog" aria-labelledby="rule-editor-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 id="rule-editor-modal-label">Настройка прав редактора</h3>
            </div>
            <div id="rule-editor-modal-body" class="modal-body">

            </div>
            <div class="modal-footer">
                <button id="rule-editor-submit" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Сохранить</button>
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
       
       $('.rule-edit-button').click(function(){
           load_modal('rule-editor', {'massmediaId':<?=$newspaper->id?>, 'userId':$(this).data('userId')}, 'rule-editor-modal', 'rule-editor-modal-body');
       });
       
       $('#rule-editor-submit').click(function(){
           $('#add-editor-form').submit();
       });
       
       $('.fire-editor-button').click(function(){
           json_request('fire-editor', {'massmediaId':<?=$newspaper->id?>, 'userId':$(this).data('userId')});
       });
    });
</script>