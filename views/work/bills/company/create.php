<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    app\components\MyHtmlHelper,
    app\models\politics\bills\BillProto;

/* @var $this yii\base\View */
/* @var $model app\models\politics\bills\Bill */
/* @var $post app\models\politics\AgencyPost */
/* @var $types array */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'new-bill-form',
    ],
    'action' => Url::to(['work/new-bill']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::COMPANY_CREATE])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Company name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Company short name'))?>
<?=$form->field($model, 'dataArray[flag]')->textInput()->label(Yii::t('app', 'Company flag'))?>
<?=$form->field($model, 'dataArray[sharesPrice]')->textInput(['type' => 'number'])->label(Yii::t('app', 'Shares Price').' '.MyHtmlHelper::icon('money', 'vertical-align: bottom;'))?>
<?=$form->field($model, 'dataArray[sharesIssued]')->textInput(['type' => 'number'])->label(Yii::t('app', 'Shares Issued'))?>

<div class="help-block">
    <?=Yii::t('app', 'This company gets <span id="company-registration-cost">0</span> {0} from budget', [
        MyHtmlHelper::icon('money', 'vertical-align: bottom;'),
    ])?>
</div>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-name',
        'name': 'Bill[dataArray][name]',
        'container': '.field-bill-dataarray-name',
        'input': '#bill-dataarray-name',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-nameshort',
        'name': 'Bill[dataArray][nameShort]',
        'container': '.field-bill-dataarray-nameshort',
        'input': '#bill-dataarray-nameshort',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-flag',
        'name': 'Bill[dataArray][flag]',
        'container': '.field-bill-dataarray-flag',
        'input': '#bill-dataarray-flag',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-sharesissued',
        'name': 'Bill[dataArray][sharesIssued]',
        'container': '.field-bill-dataarray-sharesissued',
        'input': '#bill-dataarray-sharesissued',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-sharesprice',
        'name': 'Bill[dataArray][sharesPrice]',
        'container': '.field-bill-dataarray-sharesprice',
        'input': '#bill-dataarray-sharesprice',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function recalcSumPrice(){
        var count = parseInt($('#bill-dataarray-sharesissued').val()),
            price = parseFloat($('#bill-dataarray-sharesprice').val());
            
        $('#company-registration-cost').text(number_format(count*price, 2, '.', ' '));
    }
    $('#bill-dataarray-sharesissued').change(recalcSumPrice);
    $('#bill-dataarray-sharesprice').change(recalcSumPrice);
    
</script>
