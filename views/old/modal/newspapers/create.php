<?php

/* @var $user app\models\User */
/* @var $currentState app\models\State */
/* @var $holdings app\models\Holding[] */
/* @var $popClasses app\models\PopClass[] */
/* @var $popNations app\models\PopNation[] */
/* @var $religions app\models\Religion[] */
/* @var $ideologies app\models\Ideology[] */
/* @var $regions app\models\Region[] */

use yii\helpers\Html,
    yii\helpers\ArrayHelper;

?>
<h5>СМИ будет зарегистрировано в государстве <?=$currentState->getHtmlName()?></h5>
<form class="form-horizontal" id="create-newspaper-form">
    <input type="hidden" name="protoId" value="1">
    <div class="form-group" id="holding-form-group" >
        <label for="holding-select" class="col-sm-4 control-label">Компания-владелец:</label>
        <div class="col-sm-8">
            <?=Html::dropDownList('holdingId', null,  ArrayHelper::map($holdings, 'id', 'name'),['id'=>'holding-select','class'=>'form-control'])?>
            <span id="holding-help-block" class="help-block">Компания не имеет лицензии на регистрацию СМИ</span>
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-4 control-label">Главный редактор</label>
        <div class="col-sm-8"><?=$user->getHtmlName()?></div>
    </div>
    <div class="form-group" id="name-form-group">
        <label for="newspaper-name" class="col-sm-4 control-label">Название</label>
        <div class="col-sm-8">
            <?=Html::input('text', 'name', '', ['placeholder'=>'Вестник Политсима','class'=>'form-control','id'=>'newspaper-name'])?>
            <span id="name-help-block" class="help-block">Название уже занято</span>
        </div>
    </div>
    <h5>Ориентация на аудиторию:</h5>
    <?php if ($user->party): ?>
    <div class="form-group" >
        <label class="col-sm-4 control-label">Партийность:</label>
        <div class="col-sm-8">
            <label for="party-id"><input type="checkbox" name="partyId" id="party-id" value="<?=$user->party_id?>"> Партия <?=$user->party->getHtmlName()?></label>
        </div>
    </div>
    <?php endif ?>
    <div class="form-group" >
        <label for="region-id" class="col-sm-4 control-label">Регион:</label>
        <div class="col-sm-8">
            <select id="region-id" class="form-control" name="regionId" >
                <option value="0" selected="selected" >Не указано</option>
            <?php foreach ($regions as $i => $region): ?>
                <?php if ($i == 0 || $regions[$i - 1]->state_id != $region->state_id): ?>
                    <?= ($i) ? '</optgroup>' : '' ?><optgroup label="<?= ($region->state) ? $region->state->name : 'Ничейные регионы' ?>">
                <?php endif ?>
                <option value="<?= $region->id ?>"><?= $region->name ?></option>
            <?php endforeach ?>
            </select>
        </div>
    </div>
    <div class="form-group" >
        <label for="pop-class-id" class="col-sm-4 control-label">Класс:</label>
        <div class="col-sm-8">
            <?php 
                $ar = ArrayHelper::map($popClasses, 'id', 'name');
                $ar[0] = 'Не указано';
            ?>
            <?=Html::dropDownList('popClassId', 0,  $ar,['id'=>'pop-class-id','class'=>'form-control'])?>
        </div>
    </div>
    <div class="form-group" >
        <label for="pop-nation-id" class="col-sm-4 control-label">Национальность:</label>
        <div class="col-sm-8">
            <?php 
                $ar = ArrayHelper::map($popNations, 'id', 'name');
                $ar[0] = 'Не указано';
            ?>
            <?=Html::dropDownList('popNationId', 0,  $ar,['id'=>'pop-nation-id','class'=>'form-control'])?>
        </div>
    </div>
    <div class="form-group" >
        <label for="religion-id" class="col-sm-4 control-label">Религия:</label>
        <div class="col-sm-8">
            <?php 
                $ar = ArrayHelper::map($religions, 'id', 'name');
                $ar[0] = 'Не указано';
            ?>
            <?=Html::dropDownList('religionId', 0,  $ar,['id'=>'religion-id','class'=>'form-control'])?>
        </div>
    </div>
    <div class="form-group" >
        <label for="ideology-id" class="col-sm-4 control-label">Идеология:</label>
        <div class="col-sm-8">
            <?php 
                $ar = ArrayHelper::map($ideologies, 'id', 'name');
                $ar[0] = 'Не указано';
            ?>
            <?=Html::dropDownList('ideologyId', 0,  $ar,['id'=>'ideology-id','class'=>'form-control'])?>
        </div>
    </div>
</form>
<script>
    
    function validateNameInput() {
        $('#name-form-group').removeClass('has-error');
        $('#name-form-group').removeClass('has-success');
        
        if ($('#newspaper-name').val()) {
            get_json('is-massmedia-name-registered',{'name':$('#newspaper-name').val()},function(data){
                if (data.result) {
                    $('#name-form-group').addClass('has-success');
                    $('#name-help-block').hide();
                } else {
                    $('#name-form-group').addClass('has-error');
                    $('#name-help-block').show();
                    addError();
                }
            });
        } else {
            $('#name-form-group').addClass('has-error');
            $('#name-help-block').hide();
            addError();
        }
    }
    
    function validateHoldingSelect() {
        $('#holding-form-group').removeClass('has-error');
        $('#holding-form-group').removeClass('has-success');
        get_json('is-holding-have-license',{'holding_id':$('#holding-select').val(),'state_id': <?=$currentState->id?>,'license_proto_code':'newspapers'},function(data){
            if (data.result) {
                $('#holding-form-group').addClass('has-success');
                $('#holding-help-block').hide();
            } else {
                $('#holding-form-group').addClass('has-error');
                $('#holding-help-block').show();
                addError();
            }
        });
    }
    
    function addError() {        
        $('#create-newspaper-form').data('validateErrors',parseInt($('#create-newspaper-form').data('validateErrors'))+1);
    }
    
    function validate() {
        $('#create-newspaper-form').data('validateErrors',0);
        validateHoldingSelect();
        validateNameInput();
        if ($('#create-newspaper-form').data('validateErrors')) {
            $('#create-newspaper-send-button').attr('disabled','disabled');            
        } else {
            $('#create-newspaper-send-button').removeAttr('disabled');
        }            
    }
    
    $(function(){
        $('#holding-select').change(validate);
        $('#newspaper-name').change(validate);
        validate();        
    });
</script>