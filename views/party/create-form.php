<?php

use yii\helpers\Url,
    yii\helpers\ArrayHelper,
    yii\bootstrap\ActiveForm,
    app\components\MyHtmlHelper,
    app\models\Ideology,
    app\models\politics\Party,
    app\models\politics\constitution\articles\statesonly\Parties;

/* @var $this \yii\web\View */
/* @var $model Party */
/* @var $user \app\models\User */
/* @var $article Parties */

$form = new ActiveForm();

?>

<?php $form->begin([
    'options' => [
        'id' => 'create-party-form',
    ],
    'action' => Url::to(['party/create-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['party/create-form'])
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

<div class="help-block">
    <?php if ($article->value == Parties::NEED_CONFIRM): ?>
    <p><?=Yii::t('app', 'Registration needs goverment confirmation')?></p>
    <?php endif ?>
    <?php if ($article->value3): ?>
    <p><?=Yii::t('app', 'Registration cost:')?> <?=MyHtmlHelper::moneyFormat($article->value3)?></p>
    <?php endif ?>
</div>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#create-party-form');
    
    $form.yiiActiveForm('add', {
        'id': 'party-name',
        'name': 'Party[name]',
        'container': '.field-party-name',
        'input': '#party-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'party-nameshort',
        'name': 'Party[nameShort]',
        'container': '.field-party-nameshort',
        'input': '#party-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'party-flag',
        'name': 'Party[flag]',
        'container': '.field-party-flag',
        'input': '#party-flag',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'party-anthem',
        'name': 'Party[anthem]',
        'container': '.field-party-anthem',
        'input': '#party-anthem',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('party/create', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
</script>
