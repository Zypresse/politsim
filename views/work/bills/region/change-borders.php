<?php

use yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[region1Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'First region'))?>
<?=$form->field($model, 'dataArray[region2Id]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Second region'))?>
<div id="same-regions-alert" class="alert alert-warning">
    <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>-->
    <h4><i class="icon fa fa-warning"></i> <?=Yii::t('app', 'Alert!')?></h4>
    <?=Yii::t('app', 'Please select two different regions')?>
</div>
<div class="form-group">
    <label><?=Yii::t('app', 'Select instrument:')?></label>
    <div class="btn-group">
        <?=Html::button(Yii::t('app', 'Paint first region by click'), ['class' => 'btn btn-default btn-xs instrument btn-danger active', 'data-instrument' => 'paint-click'])?>
        <?=Html::button(Yii::t('app', 'Paint first region by mouseover'), ['class' => 'btn btn-default btn-xs instrument btn-danger', 'data-instrument' => 'paint-over'])?>
        <?=Html::button(Yii::t('app', 'Paint second region by click'), ['class' => 'btn btn-default btn-xs instrument btn-primary', 'data-instrument' => 'clear-click'])?>
        <?=Html::button(Yii::t('app', 'Paint second region by mouseover'), ['class' => 'btn btn-default btn-xs instrument btn-primary', 'data-instrument' => 'clear-over'])?>
    </div>
</div>
<div id="map"></div>
<?=$form->field($model, 'dataArray[tiles1]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'dataArray[tiles2]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
