<?php

use yii\helpers\Url,
    yii\bootstrap\ActiveForm,
    app\components\MyHtmlHelper,
    app\components\LinkCreator,
    app\models\economics\Company,
    app\models\politics\constitution\articles\statesonly\Business;

/* @var $this \yii\web\View */
/* @var $model Company */
/* @var $user \app\models\User */
/* @var $state \app\models\politics\State */
/* @var $article Business */

$form = new ActiveForm();

?>

<?php $form->begin([
    'options' => [
        'id' => 'create-company-form',
    ],
    'action' => Url::to(['business/create-company-form']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['business/create-company-form'])
]) ?>

<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'name')->textInput()?>
<?=$form->field($model, 'nameShort')->textInput()?>
<?=$form->field($model, 'flag')->textInput()?>

<?=$form->field($model, 'sharesIssued')->textInput()?>
<?=$form->field($model, 'sharesPrice')->textInput()->label(Yii::t('app', 'Shares Price').' '.MyHtmlHelper::icon('money', 'vertical-align: bottom;'))?>

<div class="help-block">
    <p><?=Yii::t('app', 'Company will be created as private company of state {0}', [LinkCreator::stateLink($state)])?></p>
    <p><?=Yii::t('app', 'Shareholders:')?> <?=LinkCreator::userLink($user)?> (100%)</p>
    <p>
        <?=Yii::t('app', 'This company gets <span id="company-registration-cost">0</span> {0}', [
            MyHtmlHelper::icon('money', 'vertical-align: bottom;'),
        ])?>
    </p>
</div>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#create-company-form');
    
    $form.yiiActiveForm('add', {
        'id': 'company-name',
        'name': 'Company[name]',
        'container': '.field-company-name',
        'input': '#company-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'company-nameshort',
        'name': 'Company[nameShort]',
        'container': '.field-company-nameshort',
        'input': '#company-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'company-flag',
        'name': 'Company[flag]',
        'container': '.field-company-flag',
        'input': '#company-flag',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'company-sharesissued',
        'name': 'Company[sharesIssued]',
        'container': '.field-company-sharesissued',
        'input': '#company-sharesissued',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'company-sharesprice',
        'name': 'Company[sharesPrice]',
        'container': '.field-company-sharesprice',
        'input': '#company-sharesprice',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('business/create-company', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function recalcSumPrice(){
        var count = parseInt($('#company-sharesissued').val()),
            price = parseFloat($('#company-sharesprice').val());
            
        $('#company-registration-cost').text(number_format(count*price, 2, '.', ' '));
    }
    $('#company-sharesissued').change(recalcSumPrice);
    $('#company-sharesprice').change(recalcSumPrice);
    
</script>
