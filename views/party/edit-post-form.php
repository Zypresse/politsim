<?php

use yii\helpers\Html,
    yii\helpers\Url,
    yii\bootstrap\ActiveForm,
    app\models\PartyPost;

/* @var $this \yii\web\View */
/* @var $model \app\models\PartyPost */
/* @var $user \app\models\User */

$form = new ActiveForm();

$powers = [];
if ($model->powers) {
    if ($model->powers & PartyPost::POWER_CHANGE_FIELDS) {
        $powers[] = PartyPost::POWER_CHANGE_FIELDS;
    }
    if ($model->powers & PartyPost::POWER_EDIT_POSTS) {
        $powers[] = PartyPost::POWER_EDIT_POSTS;
    }
    if ($model->powers & PartyPost::POWER_APPROVE_REQUESTS) {
        $powers[] = PartyPost::POWER_APPROVE_REQUESTS;
    }
}
$model->powers = $powers;

?>

<?php $form->begin([
    'options' => [
        'id' => 'edit-party-post-form',
    ],
    'action' => Url::to(['party/edit-post-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['party/edit-post-form'])
]) ?>

<?=$form->field($model, 'id', [
    'labelOptions' => ['class' => 'hide']
])->hiddenInput()?>

<?=$form->field($model, 'partyId', [
    'labelOptions' => ['class' => 'hide']
])->hiddenInput()?>

<?=$form->field($model, 'name')->textInput()?>

<?=$form->field($model, 'nameShort')->textInput()?>

<?php if (!$model->isPartyLeader()): ?>
<?=$form->field($model, 'powers')->checkboxList([
    PartyPost::POWER_CHANGE_FIELDS => Yii::t('app', 'Can change party name, flag, ideology & etc.'),
    PartyPost::POWER_EDIT_POSTS => Yii::t('app', 'Can edit party posts, drop and set users to posts'),
    PartyPost::POWER_APPROVE_REQUESTS => Yii::t('app', 'Can approve party membership requests'),
])?>
<?php endif ?>

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
        
    $form = $('#edit-party-post-form');
    
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
            json_request('party/edit-post',$form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
</script>
