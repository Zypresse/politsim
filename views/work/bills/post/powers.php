<?php

use yii\widgets\ActiveForm,
    yii\helpers\Html,
    yii\helpers\Url,
    yii\helpers\ArrayHelper,
    app\models\politics\bills\BillProto,
    app\models\politics\constitution\ConstitutionArticleType,
    app\models\politics\constitution\articles\postsonly\Powers,
    app\models\politics\constitution\articles\postsonly\powers\Parties,
    app\models\politics\constitution\articles\postsonly\powers\Bills;

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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::POST_POWERS])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[postId]')->dropDownList(ArrayHelper::map($post->state->posts, 'id', 'name'))->label(Yii::t('app', 'Agency post'))?>

<?=$form->field($model, 'dataArray[bills]')->checkboxList(Bills::getList())->label(Yii::t('app', 'Bills powers'))?>
<?=$form->field($model, 'dataArray[parties]')->checkboxList(Parties::getList())->label(Yii::t('app', 'Parties powers'))?>

<?php $form->end() ?>

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
        'id': 'bill-dataarray-bills',
        'name': 'Bill[dataArray][bills]',
        'container': '.field-bill-dataarray-bills',
        'input': '#bill-dataarray-bills',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-parties',
        'name': 'Bill[dataArray][parties]',
        'container': '.field-bill-dataarray-parties',
        'input': '#bill-dataarray-parties',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
            
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
        
    function onPostChange() {
        
        var postId = parseInt($('#bill-dataarray-postid').val());
        get_json('post/constitution', {
            postId: postId,
            types: '<?= ConstitutionArticleType::POWERS ?>:<?=Powers::BILLS?>,<?= ConstitutionArticleType::POWERS ?>:<?=Powers::PARTIES?>'
        }, function(data) {
            
            var bills, parties;
            for (var i = 0; i < data.result.length; i++) {
                var subType = parseInt(data.result[i].subType);
                switch (subType) {
                    case <?= Powers::BILLS ?>:
                        bills = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        break;
                    case <?= Powers::PARTIES ?>:
                        parties = data.result[i].value ? parseInt(data.result[i].value) : 0;
                        break;
                }
            }
            
            $('#bill-dataarray-bills input').prop('checked', false);
            if (bills & <?= Bills::VOTE ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::VOTE ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::CREATE ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::CREATE ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::ACCEPT ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::ACCEPT ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::VETO ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::VETO ?>]').prop('checked', true);;
            }
            if (bills & <?= Bills::DISCUSS ?>) {
                $('#bill-dataarray-bills input[value=<?= Bills::DISCUSS ?>]').prop('checked', true);;
            }
            
            $('#bill-dataarray-parties input').prop('checked', false);
            if (parties & <?= Parties::ACCEPT ?>) {
                $('#bill-dataarray-parties input[value=<?= Parties::ACCEPT ?>]').prop('checked', true);;
            }
            if (parties & <?= Parties::REVOKE ?>) {
                $('#bill-dataarray-parties input[value=<?= Parties::REVOKE ?>]').prop('checked', true);;
            }
            
        });
    }
        
    $('#bill-dataarray-postid').change(onPostChange);
    $(function(){
        onPostChange();
    });
    
</script>
