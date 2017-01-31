<?php

use app\models\politics\constitution\articles\statesonly\Parties;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[value]')->dropDownList(Parties::getList())->label(Yii::t('app', 'New parties politic'))?>
<?=$form->field($model, 'dataArray[value2]')->hiddenInput()->label(Yii::t('app', 'Ruling party'))?>
<?=$form->field($model, 'dataArray[value3]')->textInput()->label(Yii::t('app', 'Party registration cost'))?>
