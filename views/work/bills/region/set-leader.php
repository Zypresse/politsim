<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\models\politics\constitution\ConstitutionArticleType,
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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::SET_REGION_LEADER])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[regionId]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Region'))?>
<?=$form->field($model, 'dataArray[value]')->dropDownList(ArrayHelper::map($post->state->posts, 'id', 'name'))->label(Yii::t('app', 'Region leader'))?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-regionid',
        'name': 'Bill[dataArray][regionId]',
        'container': '.field-bill-dataarray-regionid',
        'input': '#bill-dataarray-regionid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-value',
        'name': 'Bill[dataArray][value]',
        'container': '.field-bill-dataarray-value',
        'input': '#bill-dataarray-value',
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
        var regionId = parseInt($('#bill-dataarray-regionid').val());
        get_json('region/constitution-value', {
            regionId: regionId,
            type: <?= ConstitutionArticleType::LEADER_POST ?>
        }, function(data) {
            $('#bill-dataarray-value').val(data.result.value).attr('selected', 'selected');
        });
    }
    
    $('#bill-dataarray-regionid').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
