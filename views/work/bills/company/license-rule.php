<?php

use yii\helpers\ArrayHelper,
    app\components\MyHtmlHelper,
    app\models\politics\LicenseRule,
    app\models\economics\LicenseProto;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[protoId]')->dropDownList(ArrayHelper::map(LicenseProto::getList(), 'id', 'name'))->label(Yii::t('app', 'License type'))?>
<?=$form->field($model, 'dataArray[whichCompaniesAllowed]')->dropDownList(LicenseRule::getWhichAllowedNamesList())->label(Yii::t('app', 'Which companies allowed'))?>
<?=$form->field($model, 'dataArray[isNeedConfirmation]')->dropDownList([
    1 => Yii::t('yii', 'Yes'),
    0 => Yii::t('yii', 'No'),
])->label(Yii::t('app', 'Is need goverment confirmation'))?>
<?=$form->field($model, 'dataArray[priceForResidents]')->textInput(['type' => 'number', 'min' => 0])->label(Yii::t('app', 'Price for residents').' '.MyHtmlHelper::icon('money'))?>
<?=$form->field($model, 'dataArray[priceForNonresidents]')->textInput(['type' => 'number', 'min' => 0])->label(Yii::t('app', 'Price for nonresidents').' '.MyHtmlHelper::icon('money'))?>
