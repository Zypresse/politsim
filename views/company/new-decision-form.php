<?php

use yii\widgets\ActiveForm,
    yii\helpers\Url,
    app\models\economics\CompanyDecisionProto;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */

$form = new ActiveForm();

?>
<?php $form->begin([
    'options' => [
        'id' => 'new-decision-form',
    ],
    'action' => Url::to(['company/new-decision']),
    'enableClientValidation' => true,
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['company/new-decision-form', 'id' => $company->id, 'utr' => $shareholder->getUtr(), 'protoId' => $model->protoId])
]) ?>

<?=$form->field($model, 'protoId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'companyId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>
<?=$form->field($model, 'initiatorId', ['labelOptions' => ['class' => 'hide']])->hiddenInput()?>

<?=$this->render(CompanyDecisionProto::getViewByType($model->protoId), [
    'form' => $form,
    'model' => $model,
    'company' => $company,
    'shareholder' => $shareholder,
])?>

<?php $form->end() ?>

<script type="text/javascript">
    <?php foreach($this->js as $js): ?>
        <?=implode(PHP_EOL, $js)?>
    <?php endforeach ?>    
        
    $form = $('#new-decision-form');
            
    $form.on('submit', function() {
        if ($form.yiiActiveForm('data').validated) {
            json_request('company/new-decision', $form.serializeObject(), false, false, false, 'POST');
        }
        return false;
    });
    
</script>
<?=$this->render(CompanyDecisionProto::getViewByType($model->protoId).'_js')?>