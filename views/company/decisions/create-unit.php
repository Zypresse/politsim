<?php

use yii\helpers\ArrayHelper,
    yii\helpers\Html,
    kartik\range\RangeInput,
    app\models\economics\units\UnitProto,
    app\models\politics\State;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $form yii\widgets\ActiveForm */

?>
<?= $form->field($model, 'dataArray[protoId]')->dropDownList(UnitProto::getList())->label(Yii::t('app', 'Firm type')) ?>
<?= $form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Firm name')) ?>
<?= $form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Firm short name')) ?>
<?= $form->field($model, 'dataArray[size]')->widget(RangeInput::classname(), [
    'options' => ['style' => 'width: 60px; text-align: center;'],
    'html5Options' => ['min'=>1, 'max'=>1024, 'step'=>1],
    'addon' => ['append'=>['content'=>'<i class="fa fa-balance-scale"></i>']]
])->label(Yii::t('app', 'Firm size')) ?>
<div class="help-block" id="future-building-info"></div>
<hr>
<div class="form-group">
    <label class="control-label" ><?=Yii::t('app', 'Location')?></label>
    <?= Html::dropDownList('selectedStateId', $company->stateId, ArrayHelper::map(State::find()->where(['dateDeleted' => null])->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'), ['id' => 'selected-state-id', 'class' => 'form-control']) ?>
    <?= Html::dropDownList('selectedRegionId', null, ArrayHelper::map($company->state->regions, 'id', 'name'), ['id' => 'selected-region-id', 'class' => 'form-control']) ?>
</div>
<div id="build-map"></div>
<?= $form->field($model, 'dataArray[tileId]', ['labelOptions' => ['class' => 'hide']])->hiddenInput() ?>
