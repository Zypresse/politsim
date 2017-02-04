<?php

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[value]')->textInput()->label(Yii::t('app', 'Voting time (hours)'))?>
<?=$form->field($model, 'dataArray[value2]')->textInput()->label(Yii::t('app', 'Count of active bills by post'))?>
