<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\components\MyHtmlHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\LicenseRule,
    app\models\economics\LicenseProto;

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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::LICENSE_RULE])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[protoId]')->dropDownList(ArrayHelper::map(LicenseProto::getList(), 'id', 'name'))->label(Yii::t('app', 'License type'))?>
<?=$form->field($model, 'dataArray[whichCompaniesAllowed]')->dropDownList(LicenseRule::getWhichAllowedNamesList())->label(Yii::t('app', 'Which companies allowed'))?>
<?=$form->field($model, 'dataArray[isNeedConfirmation]')->dropDownList([
    1 => Yii::t('yii', 'Yes'),
    0 => Yii::t('yii', 'No'),
])->label(Yii::t('app', 'Is need goverment confirmation'))?>
<?=$form->field($model, 'dataArray[priceForResidents]')->textInput(['type' => 'number', 'min' => 0])->label(Yii::t('app', 'Price for residents').' '.MyHtmlHelper::icon('money'))?>
<?=$form->field($model, 'dataArray[priceForNonresidents]')->textInput(['type' => 'number', 'min' => 0])->label(Yii::t('app', 'Price for nonresidents').' '.MyHtmlHelper::icon('money'))?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-protoid',
        'name': 'Bill[dataArray][protoId]',
        'container': '.field-bill-dataarray-protoid',
        'input': '#bill-dataarray-protoid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-whichcompaniesallowed',
        'name': 'Bill[dataArray][whichCompaniesAllowed]',
        'container': '.field-bill-dataarray-whichcompaniesallowed',
        'input': '#bill-dataarray-whichcompaniesallowed',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-isneedconfirmation',
        'name': 'Bill[dataArray][isNeedConfirmation]',
        'container': '.field-bill-dataarray-isneedconfirmation',
        'input': '#bill-dataarray-isneedconfirmation',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-priceforresidents',
        'name': 'Bill[dataArray][priceForResidents]',
        'container': '.field-bill-dataarray-priceforresidents',
        'input': '#bill-dataarray-priceforresidents',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-pricefornonresidents',
        'name': 'Bill[dataArray][priceForNonresidents]',
        'container': '.field-bill-dataarray-pricefornonresidents',
        'input': '#bill-dataarray-pricefornonresidents',
        'error': '.help-block',
        'enableAjaxValidation': true
    });    
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function onProtoChange() {
        var type = parseInt($('#bill-dataarray-protoid').val());
        get_json('state/license-rule-info', {id: <?=$post->stateId?>, protoId: type}, function(data){
            if (data.result) {
                $('#bill-dataarray-whichcompaniesallowed').val(data.result.whichCompaniesAllowed).attr("selected", "selected");
                $('#bill-dataarray-isneedconfirmation').val(data.result.isNeedConfirmation ? 1 : 0).attr("selected", "selected");
                $('#bill-dataarray-priceforresidents').val(data.result.priceForResidents);
                $('#bill-dataarray-pricefornonresidents').val(data.result.priceForNonresidents);
            }
        });
    }
        
    $('#bill-dataarray-protoid').change(onProtoChange);
    $(function(){
        onProtoChange();
    });
        
</script>
