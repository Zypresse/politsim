<?php

use yii\widgets\ActiveForm,
    yii\helpers\Html,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\DestignationType;

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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::CREATE_POST])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[agencyId]')->dropDownList(ArrayHelper::map($post->state->agencies, 'id', 'name'))->label(Yii::t('app', 'Agency'))?>
<?=$form->field($model, 'dataArray[name]')->textInput()->label(Yii::t('app', 'Agency post name'))?>
<?=$form->field($model, 'dataArray[nameShort]')->textInput()->label(Yii::t('app', 'Agency post short name'))?>

<?=$form->field($model, 'dataArray[destignationValue]')->dropDownList(DestignationType::getList())->label(Yii::t('app', 'Destignation type'))?>
<?=$form->field($model, 'dataArray[destignationValue2]')->dropDownList([])->label(Yii::t('app', 'Destignator'))?>
<?=$form->field($model, 'dataArray[destignationValue3]')->checkboxList([
    DestignationType::SECOND_TOUR => Yii::t('app', 'Allows second tour'),
    DestignationType::NONE_OF_THE_ABOVE => Yii::t('app', 'Add variant «None of the above»'),
])->label(Yii::t('app', 'Elections rules'))?>

<?=$form->field($model, 'dataArray[toValue]')->textInput()->label(Yii::t('app', 'Terms of office (days)'))?>

<?=$form->field($model, 'dataArray[teValue]')->textInput()->label(Yii::t('app', 'Registration for elections (days)'))?>
<?=$form->field($model, 'dataArray[teValue2]')->textInput()->label(Yii::t('app', 'Pause between registration and voting (days)'))?>
<?=$form->field($model, 'dataArray[teValue3]')->textInput()->label(Yii::t('app', 'Voting (days)'))?>

<?php $form->end() ?>

<?=Html::dropDownList('destignator-posts', null, ArrayHelper::map($post->state->posts, 'id', 'name'), ['id' => 'destignator-posts', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-agencies', null, ArrayHelper::map($post->state->agencies, 'id', 'name'), ['id' => 'destignator-agencies', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-districts', null, ArrayHelper::map($post->state->districts, 'id', 'name'), ['id' => 'destignator-districts', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-regions', null, ArrayHelper::map($post->state->regions, 'id', 'name'), ['id' => 'destignator-regions', 'class' => 'hide'])?>
<?=Html::dropDownList('destignator-cities', null, ArrayHelper::map($post->state->cities, 'id', 'name'), ['id' => 'destignator-cities', 'class' => 'hide'])?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-agencyid',
        'name': 'Bill[dataArray][agencyId]',
        'container': '.field-bill-dataarray-agencyid',
        'input': '#bill-dataarray-agencyid',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
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
        'id': 'bill-dataarray-destignationvalue',
        'name': 'Bill[dataArray][destignationValue]',
        'container': '.field-bill-dataarray-destignationvalue',
        'input': '#bill-dataarray-destignationvalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-destignationvalue2',
        'name': 'Bill[dataArray][destignationValue2]',
        'container': '.field-bill-dataarray-destignationvalue2',
        'input': '#bill-dataarray-destignationvalue2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-destignationvalue3',
        'name': 'Bill[dataArray][destignationValue3]',
        'container': '.field-bill-dataarray-destignationvalue3',
        'input': '#bill-dataarray-destignationvalue3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tovalue',
        'name': 'Bill[dataArray][toValue]',
        'container': '.field-bill-dataarray-tovalue',
        'input': '#bill-dataarray-tovalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue',
        'name': 'Bill[dataArray][teValue]',
        'container': '.field-bill-dataarray-tevalue',
        'input': '#bill-dataarray-tevalue',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue2',
        'name': 'Bill[dataArray][teValue2]',
        'container': '.field-bill-dataarray-tevalue2',
        'input': '#bill-dataarray-tevalue2',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-tevalue3',
        'name': 'Bill[dataArray][teValue3]',
        'container': '.field-bill-dataarray-tevalue3',
        'input': '#bill-dataarray-tevalue3',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
    function loadDestignatorList(value) {
        switch (value) {
            case <?= DestignationType::BY_OTHER_POST ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-posts').html());
                break;
            case <?= DestignationType::BY_AGENCY_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-agencies').html());
                break;
            case <?= DestignationType::BY_DISTRICT_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-districts').html());
                break;
            case <?= DestignationType::BY_REGION_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-regions').html());
                break;
            case <?= DestignationType::BY_CITY_ELECTION ?>:
                $('#bill-dataarray-destignationvalue2').html($('#destignator-cities').html());
                break;
            default:
                $('#bill-dataarray-destignationvalue2').empty();
                break;
        }
    }
    
    function onFormChange() {
    
        var value = parseInt($('#bill-dataarray-destignationvalue').val());
    
        loadDestignatorList(value);
                
        switch (value) {
            case <?= DestignationType::BY_PRECURSOR ?>:
                $('.field-bill-dataarray-destignationvalue2').hide();
                $('.field-bill-dataarray-destignationvalue3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;  
            case <?= DestignationType::BY_OTHER_POST ?>:
                $('.field-bill-dataarray-destignationvalue2').show();
                $('.field-bill-dataarray-destignationvalue3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;
            case <?= DestignationType::BY_STATE_ELECTION ?>:
                $('.field-bill-dataarray-destignationvalue2').hide();
                $('.field-bill-dataarray-destignationvalue3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
            default:
                $('.field-bill-dataarray-destignationvalue2').show();
                $('.field-bill-dataarray-destignationvalue3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
        }
    }
    
    $('#bill-dataarray-destignationvalue').change(onFormChange);
    $(function(){
        onFormChange();
    });
    
</script>
