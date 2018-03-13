<?php

use yii\helpers\Url,
    yii\bootstrap\ActiveForm,
    app\models\politics\PartyPost;

/* @var $this \yii\web\View */
/* @var $model PartyPost */
/* @var $party \app\models\politics\Party */
/* @var $user \app\models\User */

$form = new ActiveForm();

?>

<?php $form->begin([
    'options' => [
        'id' => 'create-party-post-form',
    ],
    'action' => Url::to(['party/create-post-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['party/create-post-form'])
]) ?>

<?=$form->field($model, 'partyId', [
    'labelOptions' => ['class' => 'hide']
])->hiddenInput()?>

<?=$form->field($model, 'name')->textInput()?>

<?=$form->field($model, 'nameShort')->textInput()?>

<?=$form->field($model, 'powers')->checkboxList([
    PartyPost::POWER_CHANGE_FIELDS => Yii::t('app', 'Can change party name, flag, ideology & etc.'),
    PartyPost::POWER_EDIT_POSTS => Yii::t('app', 'Can edit party posts, drop and set users to posts'),
    PartyPost::POWER_APPROVE_REQUESTS => Yii::t('app', 'Can approve party membership requests'),
])?>

<?=$form->field($model, 'appointmentType')->dropDownList([
    PartyPost::APPOINTMENT_TYPE_LEADER => Yii::t('app', 'By leader'),
    PartyPost::APPOINTMENT_TYPE_INHERITANCE => Yii::t('app', 'By inheritance'),
    PartyPost::APPOINTMENT_TYPE_PRIMARIES => Yii::t('app', 'By primaries'),
])?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#create-party-post-form');
    
    $form.yiiActiveForm('add', {
        'id': 'partypost-name',
        'name': 'PartyPost[name]',
        'container': '.field-partypost-name',
        'input': '#partypost-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'partypost-nameshort',
        'name': 'PartyPost[nameShort]',
        'container': '.field-partypost-nameshort',
        'input': '#partypost-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('party/create-post',$form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
</script>
