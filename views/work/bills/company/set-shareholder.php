<?php

use yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[companyId]')->dropDownList(ArrayHelper::map($post->state->companiesGovermentAndHalfGoverment, 'id', 'name'))->label(Yii::t('app', 'Company'))?>
<?=$form->field($model, 'dataArray[shareholderUtr]')->dropDownList(ArrayHelper::map($post->state->taxpayersGoverment, 'utrForced', 'name'))->label(Yii::t('app', 'Shareholder'))?>
