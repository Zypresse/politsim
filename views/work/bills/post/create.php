<?php

use yii\helpers\Html,
    yii\helpers\ArrayHelper,
    app\models\politics\constitution\articles\postsonly\DestignationType;

/* @var $this yii\base\View */
/* @var $post app\models\politics\AgencyPost */
/* @var $model app\models\politics\bills\Bill */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[agencyId]')->dropDownList(ArrayHelper::map($post->state->agencies, 'id', 'name'))->label(Yii::t('app', 'Agency'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Agency post name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Agency post short name'))?>

<?=$form->field($model, 'dataArray[destignationValue]')->dropDownList(DestignationType::getList())->label(Yii::t('app', 'Destignation type'))?>
<?=$form->field($model, 'dataArray[destignationValue2]')->dropDownList([])->label(Yii::t('app', 'Destignator'))?>
<?=$form->field($model, 'dataArray[destignationValue3]')->checkboxList([
    DestignationType::SECOND_TOUR => Yii::t('app', 'Allows second tour'),
    DestignationType::NONE_OF_THE_ABOVE => Yii::t('app', 'Add variant «None of the above»'),
])->label(Yii::t('app', 'Elections rules'))?>

<?=$form->field($model, 'dataArray[toValue]')->textInput()->label(Yii::t('app', 'Terms of office (days)'))?>

<?=$form->field($model, 'dataArray[teValue]')->textInput()->label(Yii::t('app', 'Registration for elections (days)'))?>
<?=$form->field($model, 'dataArray[teValue2]')->textInput()->label(Yii::t('app', 'Pause between registration and voting (days)'))?>
<?=$form->field($model, 'dataArray[teValue3]')->textInput()->label(Yii::t('app', 'Voting (days)'))?>

<?=Html::dropDownList('destignator-posts', null, ArrayHelper::map($post->state->posts, 'id', 'name'), ['id' => 'destignator-posts', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-agencies', null, ArrayHelper::map($post->state->agencies, 'id', 'name'), ['id' => 'destignator-agencies', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-districts', null, ArrayHelper::map($post->state->districts, 'id', 'name'), ['id' => 'destignator-districts', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-regions', null, ArrayHelper::map($post->state->regions, 'id', 'name'), ['id' => 'destignator-regions', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-cities', null, ArrayHelper::map($post->state->cities, 'id', 'name'), ['id' => 'destignator-cities', 'class' => 'hide'])?>
