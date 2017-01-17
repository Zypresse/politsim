<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
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
    'validationUrl' => Url::to(['work/new-bill-form', 'postId' => $post->id, 'protoId' => BillProto::CHANGE_FLAG_STATE])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'stateId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'userId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'postId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$form->field($model, 'dataArray[flag]')->textInput()->label(Yii::t('app', 'State flag'))?>
<div class="callout callout-info">
    <h4><i class="fa fa-exclamation-circle"></i> <?=Yii::t('app', 'Use direct link to image (ends with .jpg or .png)!')?></h4>
    <p><?=Yii::t('app', 'Please use safe and stable image hostings like <a href="https://imgur.com" target="_blank" >Imgur.com</a>')?></p>
</div>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-bill-form');
    
    $form.yiiActiveForm('add', {
        'id': 'bill-dataarray-flag',
        'name': 'Bill[dataArray][flag]',
        'container': '.field-bill-dataarray-flag',
        'input': '#bill-dataarray-flag',
        'error': '.help-block',
        'enableAjaxValidation': true
    });
        
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('work/new-bill', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
</script>
