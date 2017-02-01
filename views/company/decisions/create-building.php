<?php

use yii\helpers\ArrayHelper,
    yii\helpers\Html,
    app\components\MyHtmlHelper,
    app\models\economics\units\BuildingProto,
    app\models\politics\State;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $form yii\widgets\ActiveForm */

?>
<?= $form->field($model, 'dataArray[protoId]')->dropDownList(BuildingProto::getList())->label(Yii::t('app', 'Building type')) ?>
<div class="form-group">
    <label class="control-label" ><?=Yii::t('app', 'Location')?></label>
    <?= Html::dropDownList('selectedStateId', $company->stateId, ArrayHelper::map(State::find()->where(['dateDeleted' => null])->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'), ['id' => 'selected-state-id', 'class' => 'form-control']) ?>
    <?= Html::dropDownList('selectedRegionId', null, ArrayHelper::map($company->state->regions, 'id', 'name'), ['id' => 'selected-region-id', 'class' => 'form-control']) ?>
</div>
<div id="build-map"></div>
<?= $form->field($model, 'dataArray[tileId]', ['labelOptions' => ['class' => 'hide']])->hiddenInput() ?>
