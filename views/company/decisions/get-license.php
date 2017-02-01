<?php

use yii\helpers\ArrayHelper,
    app\components\MyHtmlHelper,
    app\models\economics\LicenseProto,
    app\models\politics\State;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[stateId]')->dropDownList(ArrayHelper::map(State::find()->where(['dateDeleted' => null])->orderBy(['name' => SORT_ASC])->all(), 'id', 'name'))->label(Yii::t('app', 'State'))?>
<?=$form->field($model, 'dataArray[protoId]')->dropDownList(ArrayHelper::map(LicenseProto::findAll(), 'id', 'name'))->label(Yii::t('app', 'License type'))?>
<br>
<div class="callout callout-info">
    <h4><i class="fa fa-exclamation-circle"></i> <?=Yii::t('app', 'Be careful!')?></h4>
    <p><?=Yii::t('app', 'This license costs <span id="license-cost"></span> {0}', [
        MyHtmlHelper::icon('money', ''),
    ])?></p>
    <p id="license-need-confirmation-alert">
        <?=Yii::t('app', 'License need confirmation by the state goverment')?>
    </p>
</div>
