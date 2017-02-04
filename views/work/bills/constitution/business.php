<?php

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[value]')->dropDownList([
    0 => Yii::t('yii', 'No'),
    1 => Yii::t('yii', 'Yes'),
])->label(Yii::t('app', 'Allow local buisness'))?>
<?=$form->field($model, 'dataArray[value2]')->dropDownList([
    0 => Yii::t('yii', 'No'),
    1 => Yii::t('yii', 'Yes'),
])->label(Yii::t('app', 'Allow foreign buisness'))?>
