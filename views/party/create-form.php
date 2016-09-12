<?php

use yii\helpers\Html,
    yii\helpers\ArrayHelper,
//    yii\bootstrap\ActiveForm,
    yii\widgets\ActiveForm,
    app\models\Ideology,
    app\models\Party;

/* @var $this \yii\web\View */
/* @var $model \app\models\Party */
/* @var $user \app\models\User */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'create-party-form',
    ],
    'action' => '/party/create-form', 
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
]) ?>

<?=$form->field($model, 'stateId', [
    'labelOptions' => ['class' => 'hide']
])->hiddenInput()?>

<?=$form->field($model, 'name')->textInput()?>

<?=$form->field($model, 'nameShort')->textInput()?>

<?=$form->field($model, 'ideologyId')->dropDownList(ArrayHelper::map(Ideology::findAll(), 'id', 'name'))?>

<?=$form->field($model, 'joiningRules')->dropDownList([
    Party::JOINING_RULES_PRIVATE => Yii::t('app', 'Private'),
    Party::JOINING_RULES_CLOSED => Yii::t('app', 'Closed'),
    Party::JOINING_RULES_OPEN => Yii::t('app', 'Open')
])?>

<?=$form->field($model, 'listCreationRules')->dropDownList([
    Party::LIST_CREATION_RULES_LEADER => Yii::t('app', 'By leader'),
    Party::LIST_CREATION_RULES_PRIMARIES => Yii::t('app', 'By primaries')
])?>

<?=$form->field($model, 'flag')->textInput()?>

<?=$form->field($model, 'anthem')->textInput()?>

<?php $form->end() ?>