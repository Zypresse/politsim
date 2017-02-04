<?php

use yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[agencyId]')->dropDownList(ArrayHelper::map($post->state->agencies, 'id', 'name'))->label(Yii::t('app', 'Agency'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Agency name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Agency short name'))?>
