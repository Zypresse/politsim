<?php

use yii\helpers\Html;

/* @var $this yii\base\View */
/* @var $model app\models\economics\CompanyDecision */
/* @var $shareholder app\models\economics\TaxPayer */
/* @var $company app\models\economics\Company */
/* @var $form yii\widgets\ActiveForm */

?>
<?=$form->field($model, 'dataArray[userId]', ['labelOptions' => ['class' => 'hide']])->hiddenInput()->label(Yii::t('app', 'User'))?>
<div class="form-group field-companydecision-dataarray-userid">
    <label class="control-label" for="search-user-name"><?=Yii::t('app', 'User')?></label>
    <?=Html::input('text', 'search-user-name', '', ['id' => 'search-user-name', 'class' => 'form-control', 'placeholder' => Yii::t('app', 'Input user name')])?>
    <div class="help-block"></div>
</div>
<br>
<div class="callout callout-info">
    <h4><i class="fa fa-exclamation-circle"></i> <?=Yii::t('app', 'Be careful!')?></h4>
    <p><?=Yii::t('app', 'This user will be setted as director and gets full access to this company!')?></p>
</div>
