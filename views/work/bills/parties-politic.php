<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\constitution\articles\statesonly\Parties;

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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::PARTIES_POLITIC])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[value]')->dropDownList(Parties::getList())->label(Yii::t('app', 'New parties politic'))?>
<?=$form->field($model, 'dataArray[value2]')->hiddenInput()->label(Yii::t('app', 'Ruling party'))?>
<?=$form->field($model, 'dataArray[value3]')->textInput()->label(Yii::t('app', 'Party registration cost'))?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value',
        'name': 'Bill[dataArray][value]',
        'container': '.field-bill-dataarray-value',
        'input': '#bill-dataarray-value',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value2',
        'name': 'Bill[dataArray][value2]',
        'container': '.field-bill-dataarray-value2',
        'input': '#bill-dataarray-value2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value3',
        'name': 'Bill[dataArray][value3]',
        'container': '.field-bill-dataarray-value3',
        'input': '#bill-dataarray-value3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function onFormChange() {
        var type = parseInt($('#bill-dataarray-value').val());
        switch (type) {
            case <?=Parties::ALLOWED?>:
            case <?=Parties::NEED_CONFIRM?>:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').show();
                break;
            case <?=Parties::ONLY_RULING?>:
                $('.field-bill-dataarray-value2').show();
                $('.field-bill-dataarray-value3').hide();
                break;
            default:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').hide();
                break;
                
        }
    }
    
    $('#new-bill-form .form-control').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
