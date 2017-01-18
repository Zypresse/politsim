<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::CHANGE_CAPITAL_REGION])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[regionId]')->dropDownList(ArrayHelper::map($post->state->regions, 'id', 'name'))->label(Yii::t('app', 'Region'))?>
<?=$form->field($model, 'dataArray[cityId]')->dropDownList(ArrayHelper::map($post->state->cities, 'id', 'name'))->label(Yii::t('app', 'City'))?>

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
        'id': 'bill-dataarray-cityid',
        'name': 'Bill[dataArray][cityId]',
        'container': '.field-bill-dataarray-cityid',
        'input': '#bill-dataarray-cityid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function loadCities() {
        $('#bill-dataarray-cityid').empty();
        get_json('region/cities', {id: $('#bill-dataarray-regionid').val()}, function(data){
            for (var i = 0, l = data.result.length; i < l; i++) {
                $('#bill-dataarray-cityid').append('<option value="'+data.result[i].id+'">'+data.result[i].name+'</option>');
            }
        });
    }
    
    $('#bill-dataarray-regionid').change(loadCities);
    $(function(){
        loadCities();
    });
    
</script>
