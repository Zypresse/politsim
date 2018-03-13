<?php

use yii\helpers\ArrayHelper,
    app\models\politics\AgencyTemplate;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[agencyTemplateId]')->dropDownList(ArrayHelper::map(AgencyTemplate::findAll(), 'id', 'name'))->label(Yii::t('app', 'Agency template'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Agency name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Agency short name'))?>
