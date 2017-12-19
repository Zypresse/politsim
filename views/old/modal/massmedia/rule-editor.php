<?php

use yii\helpers\Html,
    app\models\User,
    app\models\massmedia\Massmedia,
    app\models\massmedia\MassmediaEditor,
    app\components\MyHtmlHelper;

/* @var $this yii\web\View */
/* @var $massmedia Massmedia */
/* @var $user User */
/* @var $rule MassmediaEditor */

?>
<h5>СМИ: <?=$massmedia->getHtmlName()?></h5>
<h5>Человек по имени <?=$user->getHtmlName()?></h5>
<form class="form-horizontal" id="add-editor-form">
    <?= Html::input('hidden', 'massmediaId', $massmedia->id) ?>
    <?= Html::input('hidden', 'userId', $user->id) ?>
    <div class="form-group" >
        <label for="editor-custom-name" class="col-sm-4 control-label">Название должности:</label>
        <div class="col-sm-8">
            <?=Html::input('text', 'MassmediaEditor[customName]', $rule->customName, ['class'=>'form-control', 'id'=>'editor-custom-name'])?>
            <span id="holding-help-block" class="help-block">Необязательно</span>
        </div>
    </div>
    <div class="form-group" >
        <label class="col-sm-4 control-label">Настройки:</label>
        <div class="col-sm-8">
            <label for="editor-hide"><?=Html::checkbox('MassmediaEditor[hide]', $rule->hide, ['id'=>'editor-hide'])?> Скрыть в списке редакторов</label>
        </div>
    </div>
    <div class="form-group" >
        <label class="col-sm-4 control-label">Права доступа:</label>
        <div class="col-sm-8">
            <label for="editor-rules1"><?=Html::checkbox('rules[]', $rule->isHavePermission(MassmediaEditor::RULE_POST), ['id'=>'editor-rules1', 'value'=> MassmediaEditor::RULE_POST])?> Публикация контента</label><br>
            <label for="editor-rules2"><?=Html::checkbox('rules[]', $rule->isHavePermission(MassmediaEditor::RULE_DELETE_POSTS), ['id'=>'editor-rules2', 'value'=> MassmediaEditor::RULE_DELETE_POSTS])?> Удаление контента</label><br>
            <label for="editor-rules3"><?=Html::checkbox('rules[]', $rule->isHavePermission(MassmediaEditor::RULE_DELETE_COMMENTS), ['id'=>'editor-rules3', 'value'=> MassmediaEditor::RULE_DELETE_COMMENTS])?> Удаление комментариев</label><br>
            <label for="editor-rules4"><?=Html::checkbox('rules[]', $rule->isHavePermission(MassmediaEditor::RULE_SET_EDITORS), ['id'=>'editor-rules4', 'value'=> MassmediaEditor::RULE_SET_EDITORS])?> Назначение редакторов</label>
        </div>
    </div>
</form>
<script>
    $(function(){
       $('#add-editor-form').submit(function(){
           json_request('save-editor-rule',$(this).serializeObject());
           return false;
       });
    });
</script>