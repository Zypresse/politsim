<?php

use yii\helpers\Html,
    yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[districtId]')->dropDownList(ArrayHelper::map($post->state->districts, 'id', 'name'))->label(Yii::t('app', 'Electoral district'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Electoral district name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Electoral district short name'))?>

<div class="form-group">
    <label><?=Yii::t('app', 'Select instrument:')?></label>
    <div class="btn-group">
        <?=Html::button(Yii::t('app', 'Paint by click'), ['class' => 'btn btn-default btn-xs instrument active', 'data-instrument' => 'paint-click'])?>
        <?=Html::button(Yii::t('app', 'Paint by mouseover'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'paint-over'])?>
        <?=Html::button(Yii::t('app', 'Clear by click'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'clear-click'])?>
        <?=Html::button(Yii::t('app', 'Clear by mouseover'), ['class' => 'btn btn-default btn-xs instrument', 'data-instrument' => 'clear-over'])?>
    </div>
</div>
<div id="map"></div>
<?=$form->field($model, 'dataArray[tiles]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
