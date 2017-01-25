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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::POST_DESTIGNATION])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[postId]')->dropDownList(ArrayHelper::map($post->state->posts, 'id', 'name'))->label(Yii::t('app', 'Agency post'))?>

<?=$form->field($model, 'dataArray[value]')->dropDownList(DestignationType::getList())->label(Yii::t('app', 'Destignation type'))?>
<?=$form->field($model, 'dataArray[value2]')->dropDownList([])->label(Yii::t('app', 'Destignator'))?>
<?=$form->field($model, 'dataArray[value3]')->checkboxList([
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
        'id': 'bill-dataarray-postid',
        'name': 'Bill[dataArray][postId]',
        'container': '.field-bill-dataarray-postid',
        'input': '#bill-dataarray-postid',
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
                $('#bill-dataarray-value2').html($('#destignator-posts').html());
                break;
            case <?= DestignationType::BY_AGENCY_ELECTION ?>:
                $('#bill-dataarray-value2').html($('#destignator-agencies').html());
                break;
            case <?= DestignationType::BY_DISTRICT_ELECTION ?>:
                $('#bill-dataarray-value2').html($('#destignator-districts').html());
                break;
            case <?= DestignationType::BY_REGION_ELECTION ?>:
                $('#bill-dataarray-value2').html($('#destignator-regions').html());
                break;
            case <?= DestignationType::BY_CITY_ELECTION ?>:
                $('#bill-dataarray-value2').html($('#destignator-cities').html());
                break;
            default:
                $('#bill-dataarray-value2').empty();
                break;
        }
    }
    
    function onPostChange() {
        
        var postId = parseInt($('#bill-dataarray-postid').val());
        get_json('post/constitution', {
            postId: postId,
            types: '<?= ConstitutionArticleType::DESTIGNATION_TYPE ?>,<?= ConstitutionArticleType::TERMS_OF_ELECTION ?>,<?= ConstitutionArticleType::TERMS_OF_OFFICE ?>'
        }, function(data) {
            
            var value,value2,value3,toValue,teValue,teValue2,teValue3;
            for (var i = 0; i < data.result.length; i++) {
                var type = parseInt(data.result[i].type);
                switch (type) {
                    case <?= ConstitutionArticleType::DESTIGNATION_TYPE ?>:
                        value = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        value2 = data.result[i].value2 ? parseInt(data.result[i].value2) : 0;
                        value3 = data.result[i].value3 ? parseInt(data.result[i].value3) : 0;
                        break;
                    case <?= ConstitutionArticleType::TERMS_OF_ELECTION ?>:
                        teValue = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        teValue2 = data.result[i].value2 ? parseInt(data.result[i].value2) : 0;
                        teValue3 = data.result[i].value3 ? parseInt(data.result[i].value3) : 0;
                        break;
                    case <?= ConstitutionArticleType::TERMS_OF_OFFICE ?>:
                        toValue = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        break;
                }
            }
        
            loadDestignatorList(value);
            
            $('#bill-dataarray-value').val(value).attr("selected", "selected");
            $('#bill-dataarray-value2').val(value2).attr("selected", "selected");
            
            $('#bill-dataarray-tovalue').val(toValue);
            $('#bill-dataarray-tevalue').val(teValue);
            $('#bill-dataarray-tevalue2').val(teValue2);
            $('#bill-dataarray-tevalue3').val(teValue3);
            
            $('#bill-dataarray-value3 input').prop('checked', false);
            if (value3 & <?= DestignationType::SECOND_TOUR ?>) {
                $('#bill-dataarray-value3 input[value=<?= DestignationType::SECOND_TOUR ?>]').prop('checked', true);;
            }
            if (value3 & <?= DestignationType::NONE_OF_THE_ABOVE ?>) {
                $('#bill-dataarray-value3 input[value=<?= DestignationType::NONE_OF_THE_ABOVE ?>]').prop('checked', true);;
            }
            
            onFormChange();
        });
    }
    
    function onFormChange() {
    
        var value = parseInt($('#bill-dataarray-value').val()),
            value2 = parseInt($('#bill-dataarray-value2').val());
    
        loadDestignatorList(value);
        $('#bill-dataarray-value').val(value).attr("selected", "selected");
        $('#bill-dataarray-value2').val(value2).attr("selected", "selected");
        
        switch (value) {
            case <?= DestignationType::BY_PRECURSOR ?>:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;  
            case <?= DestignationType::BY_OTHER_POST ?>:
                $('.field-bill-dataarray-value2').show();
                $('.field-bill-dataarray-value3').hide();
                
                $('.field-bill-dataarray-tovalue').hide();
                $('.field-bill-dataarray-tevalue').hide();
                $('.field-bill-dataarray-tevalue2').hide();
                $('.field-bill-dataarray-tevalue3').hide();
                break;
            case <?= DestignationType::BY_STATE_ELECTION ?>:
                $('.field-bill-dataarray-value2').hide();
                $('.field-bill-dataarray-value3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
            default:
                $('.field-bill-dataarray-value2').show();
                $('.field-bill-dataarray-value3').show();
                
                $('.field-bill-dataarray-tovalue').show();
                $('.field-bill-dataarray-tevalue').show();
                $('.field-bill-dataarray-tevalue2').show();
                $('.field-bill-dataarray-tevalue3').show();
                break;
        }
    }
    
    $('#bill-dataarray-value').change(onFormChange);
    $('#bill-dataarray-postid').change(onPostChange);
    $(function(){
        onPostChange();
    });
    
</script>