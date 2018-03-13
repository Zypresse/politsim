<?php

use yii\helpers\ArrayHelper;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[regionId]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Region'))?>
<?=$form->field($model, 'dataArray[value]')->dropDownList(ArrayHelper::map($post->state->posts, 'id', 'name'))->label(Yii::t('app', 'Region leader'))?>
